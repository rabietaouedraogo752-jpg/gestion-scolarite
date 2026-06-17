<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\UfrInstitut;
use App\Models\Universite;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Support\Facades\Storage;
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

        return view('gestion.creer_departement', compact('prefill'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:ufr_instituts,code',
            'nom' => 'required|string|max:255',
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

        UfrInstitut::create([
            'universite_id' => $universite->id,
            'code' => $data['code'],
            'nom' => $data['nom'],
        ]);

        return redirect()
            ->route('gestion.liste_departement')
            ->with('success', 'Département créé avec succès.');
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

        $departement->update([
            'universite_id' => $universite->id,
            'code' => $data['code'],
            'nom' => $data['nom'],
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
}
