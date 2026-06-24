<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpWord\PhpWord;
use App\Imports\EtudiantsImport;

class EtudiantController extends Controller
{
    public function create(Request $request)
    {
        $prefill = $request->only([
            'name',
            'email',
            'matricule',
            'genre',
            'filiere',
            'niveau',
            'annee_academique',
            'date_naissance',
            'lieu_naissance',
        ]);

        // Correction : Récupérer toutes les filières pour alimenter le select de la vue de création
        $filieres = Filiere::orderBy('nom_filiere')->get();

        return view('gestion.creer_etudiant', compact('prefill', 'filieres'));
    }

    public function index(Request $request)
    {
        $filiereId = $request->query('filiere_id');
        $niveauId = $request->query('niveau_id');

        $etudiants = Etudiant::with('user', 'filiere', 'niveau')
            ->when($filiereId, function ($query) use ($filiereId) {
                $query->where('filiere_id', $filiereId);
            })
            ->when($niveauId, function ($query) use ($niveauId) {
                $query->where('niveau_id', $niveauId);
            })
            ->orderBy('id', 'desc')
            ->get();

        $filieres = Filiere::orderBy('nom_filiere')->get();
        $niveaux = Niveau::orderBy('code_niveau')->get();

        return view('gestion.liste_etudiant', compact('etudiants', 'filieres', 'niveaux', 'filiereId', 'niveauId'));
    }

    public function edit(Etudiant $etudiant)
    {
        $filieres = Filiere::orderBy('nom_filiere')->get();
        return view('gestion.editer_etudiant', compact('etudiant', 'filieres'));
    }

    public function update(Request $request, Etudiant $etudiant)
    {
        // Correction de la validation : filiere attend désormais un ID qui existe
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,'.$etudiant->user_id,
            'matricule'        => 'required|string|max:50|unique:etudiants,matricule,'.$etudiant->id,
            'date_naissance'   => 'required|date',
            'lieu_naissance'   => 'required|string|max:150',
            'genre'            => 'required|in:M,F',
            'filiere'          => 'required|exists:filieres,id', // <-- ID vérifié ici
            'niveau'           => 'required|string|max:255',
            'annee_academique' => ['required', 'regex:/^\d{4}-\d{4}$/'],
        ]);

        $user = $etudiant->user;
        if ($user) {
            $user->update([
                'name'  => $data['name'],
                'email' => $data['email'],
            ]);
        }

        // Plus besoin de faire un firstOrCreate pour la filière puisqu'on reçoit un ID existant
        $niveau = Niveau::updateOrCreate(
            ['code_niveau' => $data['niveau']],
            ['intitule'    => $data['niveau']]
        );

        [$anneeDebut, $anneeFin] = explode('-', $data['annee_academique']);

        $etudiant->update([
            'nom_prenom'     => $data['name'],
            'matricule'      => $data['matricule'],
            'date_naissance' => $data['date_naissance'],
            'lieu_naissance' => $data['lieu_naissance'],
            'genre'          => $data['genre'],
            'filiere_id'     => $data['filiere'], // <-- Enregistrement direct de l'ID reçu
            'niveau_id'      => $niveau->id,
            'annee_debut'    => $anneeDebut.'-09-01',
            'annee_fin'      => $anneeFin.'-08-31',
        ]);

        return redirect()->route('gestion.liste_etudiant')->with('success', 'Étudiant mis à jour avec succès.');
    }

    public function store(Request $request)
    {
        // Correction de la validation : filiere attend un ID existant
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'matricule'        => 'required|string|max:50',
            'genre'            => 'required|in:M,F',
            'filiere'          => 'required|exists:filieres,id', // <-- ID vérifié ici
            'niveau'           => 'required|string',
            'annee_academique' => 'required|string',
            'date_naissance'   => 'required|date',
            'lieu_naissance'   => 'required|string',
        ]);

        $password = Str::random(8);
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($password),
        ]);

        $niveau = Niveau::updateOrCreate(
            ['code_niveau' => $data['niveau']],
            ['intitule'    => $data['niveau']]
        );

        $annees = explode('-', $data['annee_academique']);
        $anneeDebut = ($annees[0] ?? date('Y')) . '-09-01';
        $anneeFin   = ($annees[1] ?? (date('Y') + 1)) . '-08-31';

        Etudiant::create([
            'user_id'        => $user->id,
            'nom_prenom'     => $data['name'],
            'matricule'      => $data['matricule'],
            'genre'          => $data['genre'],
            'date_naissance' => $data['date_naissance'],
            'lieu_naissance' => $data['lieu_naissance'],
            'filiere_id'     => $data['filiere'], // On associe directement l'ID
            'niveau_id'      => $niveau->id,
            'annee_debut'    => $anneeDebut,
            'annee_fin'      => $anneeFin,
        ]);

        return redirect()->route('gestion.liste_etudiant')->with('success', "Étudiant créé ! Mot de passe : " . $password);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls,ods,pdf,docx,doc,uml|max:10240',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());

        if (in_array($ext, ['xlsx', 'xls', 'csv', 'ods'])) {
            $import = new EtudiantsImport();
            Excel::import($import, $file);
            $created = $import->getCreatedCredentials();
            if (!empty($created)) {
                return back()->with('success', 'Import terminé.')->with('import_credentials', $created);
            }
            return back()->with('success', 'Import Excel traité: enregistrements créés/mis à jour.');
        }

        $filename = time().'_'.preg_replace('/[^A-Za-z0-9\\-_.]/', '_', $file->getClientOriginalName());
        $path = $file->storeAs('imports/etudiants', $filename);

        return back()->with('success', 'Fichier enregistré (pas de parsing automatique pour ce type): '.$path);
    }

    public function exportExcel()
    {
        return Excel::download(new \App\Exports\EtudiantsExport, 'etudiants_'.date('Y-m-d').'.xlsx');
    }

    public function exportPdf()
    {
        $etudiants = Etudiant::with('user', 'filiere', 'niveau')->orderBy('id', 'desc')->get();
        $pdf = PDF::loadView('gestion.exports.etudiants_pdf', compact('etudiants'));
        return $pdf->download('etudiants_'.date('Y-m-d').'.pdf');
    }

    public function exportWord()
    {
        $etudiants = Etudiant::with('user', 'filiere', 'niveau')->orderBy('id', 'desc')->get();
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        
        $section->addTitle('Liste des Étudiants', 1);
        $section->addTextBreak();
        
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        
        $headerCells = $table->addRow()->getCells();
        $headerCells[0]->addText('Nom', ['bold' => true]);
        $headerCells[1]->addText('INE', ['bold' => true]);
        $headerCells[2]->addText('Email', ['bold' => true]);
        $headerCells[3]->addText('Filière', ['bold' => true]);
        $headerCells[4]->addText('Niveau', ['bold' => true]);
        $headerCells[5]->addText('Année', ['bold' => true]);
        
        foreach ($etudiants as $et) {
            $cells = $table->addRow()->getCells();
            $cells[0]->addText($et->user->name ?? '—');
            $cells[1]->addText($et->matricule ?? '—');
            $cells[2]->addText($et->user->email ?? '—');
            $cells[3]->addText($et->filiere->nom_filiere ?? '—');
            $cells[4]->addText($et->niveau->code_niveau ?? '—');
            $cells[5]->addText($et->annee_debut ? date('Y', strtotime($et->annee_debut)).'-'.date('Y', strtotime($et->annee_fin)) : '—');
        }
        
        $filename = 'etudiants_'.date('Y-m-d').'.docx';
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        
        return response()->streamDownload(function () use ($objWriter) {
            $objWriter->save('php://output');
        }, $filename);
    }

    public function exportHtml()
    {
        $etudiants = Etudiant::with('user', 'filiere', 'niveau')->orderBy('id', 'desc')->get();
        return response()
            ->view('gestion.exports.etudiants_html', compact('etudiants'))
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="etudiants_'.date('Y-m-d').'.html"');
    }
}