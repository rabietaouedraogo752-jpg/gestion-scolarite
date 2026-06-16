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
use PhpOffice\PhpWord\Shared\Inches;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EtudiantController extends Controller
{
    public function create()
    {
        return view('gestion.creer_etudiant');
    }

    public function index()
    {
        $etudiants = Etudiant::with('user', 'filiere', 'niveau')->orderBy('id', 'desc')->get();
        return view('gestion.liste_etudiant', compact('etudiants'));
    }

    public function edit(Etudiant $etudiant)
    {
        return view('gestion.editer_etudiant', compact('etudiant'));
    }

    public function update(Request $request, Etudiant $etudiant)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$etudiant->user_id,
            'matricule' => 'required|string|max:50|unique:etudiants,matricule,'.$etudiant->id,
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:150',
            'genre' => 'required|in:M,F',
            'filiere' => 'required|string|max:255',
            'niveau' => 'required|string|max:255',
            'annee_academique' => ['required', 'regex:/^\d{4}-\d{4}$/'],
        ]);

        $user = $etudiant->user;
        if ($user) {
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);
        }

        $filere = Filiere::firstOrCreate([
            'nom_filiere' => $data['filiere'],
        ]);

        $niveau = Niveau::updateOrCreate(
            ['code_niveau' => $data['niveau']],
            ['intitule' => $data['niveau']]
        );

        [$anneeDebut, $anneeFin] = explode('-', $data['annee_academique']);

        $etudiant->update([
            'matricule' => $data['matricule'],
            'date_naissance' => $data['date_naissance'],
            'lieu_naissance' => $data['lieu_naissance'],
            'genre' => $data['genre'],
            'filiere_id' => $filere->id,
            'niveau_id' => $niveau->id,
            'annee_debut' => $anneeDebut.'-09-01',
            'annee_fin' => $anneeFin.'-08-31',
        ]);

        return redirect()->route('gestion.liste_etudiant')->with('success', 'Étudiant mis à jour avec succès.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'matricule' => 'required|string|max:50|unique:etudiants,matricule',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:150',
            'genre' => 'required|in:M,F',
            'filiere' => 'required|string|max:255',
            'niveau' => 'required|string|max:255',
            'annee_academique' => ['required', 'regex:/^\d{4}-\d{4}$/'],
        ]);

        // generate a username and a random password, then create user
        $username = strtolower(str_replace(' ', '.', $data['name'])) . '.' . Str::random(3);
        $plainPassword = Str::random(10);
        $user = User::create([
            'name' => $data['name'],
            'username' => $username,
            'email' => $data['email'],
            'password' => Hash::make($plainPassword),
            'role' => 'etudiant',
        ]);

        // create or find filiere by name
        $filere = null;
        if (!empty($data['filiere'])) {
            $filere = Filiere::firstOrCreate([
                'nom_filiere' => $data['filiere'],
            ]);
        }

        // create or update niveau by code
        $niveau = Niveau::updateOrCreate(
            ['code_niveau' => $data['niveau']],
            ['intitule' => $data['niveau']]
        );

        // create etudiant
        [$anneeDebut, $anneeFin] = explode('-', $data['annee_academique']);
        Etudiant::create([
            'user_id' => $user->id,
            'matricule' => $data['matricule'],
            'date_naissance' => $data['date_naissance'],
            'lieu_naissance' => $data['lieu_naissance'],
            'genre' => $data['genre'],
            'filiere_id' => $filere ? $filere->id : null,
            'niveau_id' => $niveau->id,
            'annee_debut' => $anneeDebut.'-09-01',
            'annee_fin' => $anneeFin.'-08-31',
            'generated_password' => $plainPassword,
        ]);

        // return to list with credentials shown once
        $credentials = ['username' => $user->username, 'password' => $plainPassword];
        return redirect()->route('gestion.liste_etudiant')->with('success', 'Étudiant créé avec succès.')->with('credentials', $credentials);
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
        
        // Titre
        $section->addTitle('Liste des Étudiants', 1);
        $section->addTextBreak();
        
        // Tableau
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        
        // En-têtes
        $headerCells = $table->addRow()->getCells();
        $headerCells[0]->addText('Nom', ['bold' => true]);
        $headerCells[1]->addText('INE', ['bold' => true]);
        $headerCells[2]->addText('Email', ['bold' => true]);
        $headerCells[3]->addText('Filière', ['bold' => true]);
        $headerCells[4]->addText('Niveau', ['bold' => true]);
        $headerCells[5]->addText('Année', ['bold' => true]);
        
        // Données
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
