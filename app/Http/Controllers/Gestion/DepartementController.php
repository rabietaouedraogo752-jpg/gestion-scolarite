<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\UfrInstitut;
use App\Models\Universite;
use App\Models\User; // <-- Ajouté pour créer les identifiants
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash; // <-- Ajouté pour hacher le mot de passe
use Illuminate\Support\Str; // <-- Ajouté pour générer le mot de passe
use App\Imports\DepartementsImport;

class DepartementController extends Controller
{
    private function departementsQuery(?string $universiteId = null)
    {
        return UfrInstitut::with('universite')
            ->when($universiteId, function ($query) use ($universiteId) {
                $query->where('universite_id', $universiteId);
            })
            ->orderBy('id', 'desc');
    }

    public function index(Request $request)
    {
        $universiteId = $request->query('universite_id');
        $departements = $this->departementsQuery($universiteId)->get();
        $universites = Universite::orderBy('nom_universite')->get();

        return view('gestion.liste_departement', compact('departements', 'universites', 'universiteId'));
    }

   public function create(Request $request)
    {
        $prefill = $request->only([
            'code',
            'nom',
            'code_univ',
            'nom_universite',
            'ville',
        ]);

        // AJOUT : Récupérer tous les départements existants pour la liste déroulante
        $departements = \App\Models\UfrInstitut::orderBy('nom')->get();

        // MODIFICATION : On ajoute 'departements' dans le compact()
        return view('gestion.creer_departement', compact('prefill', 'departements'));
    }
    public function store(Request $request)
{
    // 1. Validation des données du formulaire
    $request->validate([
        'departement_id' => 'required|exists:ufr_instituts,id', // Remplacez 'ufr_instituts' par le nom réel de votre table si différent
        'name'           => 'required|string|max:255',
        'email'          => 'required|email',
    ]);

    // 2. Récupérer le département sélectionné dans la liste déroulante
    $departement = \App\Models\UfrInstitut::findOrFail($request->departement_id);

    // 3. Sauvegarder le nom du chef dans la colonne lue par votre tableau ('chef_nom')
    $departement->chef_nom = $request->name;
    
    // Si votre table possède une colonne pour l'émail du chef (optionnel) :
    // $departement->chef_email = $request->email;
    
    $departement->save();

    // 4. Redirection vers la liste des départements avec un message de succès
    return redirect()->route('gestion.liste_departement')
                     ->with('success', "Le chef de département a été assigné avec succès à la structure : {$departement->nom} !");
}
    public function edit(UfrInstitut $departement)
    {
        return view('gestion.editer_departement', compact('departement'));
    }

    public function update(Request $request, UfrInstitut $departement)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:ufr_instituts,code,'.$departement->id,
            'nom' => 'required|string|max:255',
            'chef_nom' => 'nullable|string|max:255',
            'code_univ' => 'required|string|max:50',
            'nom_universite' => 'required|string|max:255',
            'ville' => 'required|string|max:100',
        ]);

        $universite = Universite::updateOrCreate(
            ['code_univ' => $data['code_univ']],
            [
                'nom_universite' => $data['nom_universite'],
                'ville' => $data['ville'],
            ]
        );

        // Mise à jour propre de l'enregistrement de l'UFR / Institut
        $departement->update([
            'universite_id' => $universite->id,
            'code' => $data['code'],
            'nom' => $data['nom'],
            'chef_nom' => $data['chef_nom'], // Enregistre le nouveau chef s'il change
        ]);

        return redirect()
            ->route('gestion.liste_departement')
            ->with('success', 'Département mis à jour avec succès.');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new \App\Exports\DepartementsExport($request->query('universite_id')), 'departements_'.date('Y-m-d').'.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $departements = $this->departementsQuery($request->query('universite_id'))->get();
        $pdf = PDF::loadView('gestion.exports.departements_pdf', compact('departements'));

        return $pdf->download('departements_'.date('Y-m-d').'.pdf');
    }

    public function exportWord(Request $request)
    {
        $departements = $this->departementsQuery($request->query('universite_id'))->get();
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addTitle('Liste des Départements', 1);
        $section->addTextBreak();

        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        $table->addRow();
        $table->addCell()->addText('Code', ['bold' => true]);
        $table->addCell()->addText('Nom', ['bold' => true]);
        $table->addCell()->addText('Université', ['bold' => true]);
        $table->addCell()->addText('Ville', ['bold' => true]);

        foreach ($departements as $departement) {
            $table->addRow();
            $table->addCell()->addText($departement->code ?? '—');
            $table->addCell()->addText($departement->nom ?? '—');
            $table->addCell()->addText($departement->universite->nom_universite ?? '—');
            $table->addCell()->addText($departement->universite->ville ?? '—');
        }

        return response()->streamDownload(function () use ($phpWord) {
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save('php://output');
        }, 'departements_'.date('Y-m-d').'.docx');
    }

    public function exportHtml(Request $request)
    {
        $departements = $this->departementsQuery($request->query('universite_id'))->get();

        return response()
            ->view('gestion.exports.departements_html', compact('departements'))
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="departements_'.date('Y-m-d').'.html"');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls,ods,pdf,docx,doc,uml|max:10240',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());

        if (in_array($ext, ['xlsx', 'xls', 'csv', 'ods'])) {
            Excel::import(new DepartementsImport, $file);
            return back()->with('success', 'Import Excel traité: enregistrements créés/mis à jour.');
        }

        $filename = time().'_'.preg_replace('/[^A-Za-z0-9\\-_.]/', '_', $file->getClientOriginalName());
        $path = $file->storeAs('imports/departements', $filename);

        return back()->with('success', 'Fichier enregistré (pas de parsing automatique pour ce type): '.$path);
    }
    
    public function listeFiliere(Request $request)
    {
        // 1. Récupération de tous les départements pour remplir le filtre de recherche
        $departements = \App\Models\UfrInstitut::all();

        // 2. On prépare la requête pour charger les filières ET leur département lié (via la clé ufr_id)
        $query = \App\Models\Filiere::with('departement');

        // 3. Gestion du filtre : si l'utilisateur choisit un département spécifique
        if ($request->has('departement_id') && $request->departement_id != '') {
            $query->where('ufr_id', $request->departement_id);
        }

        // 4. On exécute la requête pour obtenir la liste complète
        $filieres = $query->get();

        // 5. On envoie les variables exactes attendues par la vue HTML
        return view('gestion.liste_filiere', compact('filieres', 'departements'));
    }

    public function destroyFiliere($id)
    {
        $filiere = \App\Models\Filiere::findOrFail($id);
        $filiere->delete();

        return redirect()->back()->with('success', 'La filière a été supprimée avec succès !');
    }
public function storeDepartementFiliere(Request $request)
{
    // 1. Validation des données du formulaire
    $request->validate([
        'departement_id' => 'required|exists:ufr_instituts,id', // Vérifie que le département sélectionné existe
        'name'           => 'required|string|max:255',
        'email'          => 'required|email',
    ]);

    // 2. On récupère le département sélectionné dans la liste déroulante
    $departement = \App\Models\UfrInstitut::findOrFail($request->departement_id);

    // 3. On attribue le nom saisi au champ chef_nom qui est lu par votre tableau
    $departement->chef_nom = $request->name;
    
    // Si votre table possède un champ pour l'email du chef, décommentez la ligne suivante :
    // $departement->chef_email = $request->email;

    // 4. On sauvegarde les modifications
    $departement->save();

    // 5. Redirection vers la liste des départements avec le message de succès !
    return redirect()->route('gestion.liste_departement')
                     ->with('success', "Le chef de département " . $request->name . " a bien été assigné avec succès !");
}
    
}