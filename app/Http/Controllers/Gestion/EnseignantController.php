<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Support\Facades\Storage;
use App\Imports\EnseignantsImport;

class EnseignantController extends Controller
{
    private function enseignantsQuery(?string $grade = null)
    {
        return Enseignant::with('user')
            ->when($grade, function ($query) use ($grade) {
                $query->where('grade', $grade);
            })
            ->orderBy('id', 'desc');
    }

    public function create(Request $request)
    {
        $prefill = $request->only([
            'name',
            'email',
            'matricule_fonctionnaire',
            'domaine_enseignement',
            'grade',
            'telephone',
        ]);

        return view('gestion.creer_enseignant', compact('prefill'));
    }

    public function edit(Enseignant $enseignant)
    {
        return view('gestion.editer_enseignant', compact('enseignant'));
    }

    public function index(Request $request)
    {
        $grade = $request->query('grade');

        $enseignants = $this->enseignantsQuery($grade)->get();

        $grades = Enseignant::query()
            ->whereNotNull('grade')
            ->distinct()
            ->orderBy('grade')
            ->pluck('grade');

        return view('gestion.liste_enseignant', compact('enseignants', 'grades', 'grade'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'matricule_fonctionnaire' => 'nullable|string|max:100|unique:enseignants,matricule_fonctionnaire',
            'domaine_enseignement' => 'nullable|string|max:150',
            'grade' => 'required|in:MA,MC,PT,Vacataire',
            'telephone' => 'nullable|string|max:20',
        ]);

        $username = strtolower(str_replace(' ', '.', $data['name'])) . '.' . Str::random(3);
        $plainPassword = Str::random(10);

        $user = User::create([
            'name' => $data['name'],
            'username' => $username,
            'email' => $data['email'],
            'password' => Hash::make($plainPassword),
            'role' => 'enseignant',
        ]);

        Enseignant::create([
            'user_id' => $user->id,
            'matricule_fonctionnaire' => $data['matricule_fonctionnaire'] ?? null,
            'domaine_enseignement' => $data['domaine_enseignement'] ?? null,
            'grade' => $data['grade'],
            'telephone' => $data['telephone'] ?? null,
            'generated_password' => $plainPassword,
        ]);

        $credentials = ['username' => $username, 'password' => $plainPassword];

        return redirect()
            ->route('gestion.liste_enseignant')
            ->with('success', 'Enseignant créé avec succès.')
            ->with('credentials', $credentials);
    }

    public function update(Request $request, Enseignant $enseignant)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$enseignant->user_id,
            'matricule_fonctionnaire' => 'nullable|string|max:100|unique:enseignants,matricule_fonctionnaire,'.$enseignant->id,
            'domaine_enseignement' => 'nullable|string|max:150',
            'grade' => 'required|in:MA,MC,PT,Vacataire',
            'telephone' => 'nullable|string|max:20',
        ]);

        if ($enseignant->user) {
            $enseignant->user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);
        }

        $enseignant->update([
            'matricule_fonctionnaire' => $data['matricule_fonctionnaire'] ?? null,
            'domaine_enseignement' => $data['domaine_enseignement'] ?? null,
            'grade' => $data['grade'],
            'telephone' => $data['telephone'] ?? null,
        ]);

        return redirect()
            ->route('gestion.liste_enseignant')
            ->with('success', 'Enseignant mis à jour avec succès.');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new \App\Exports\EnseignantsExport($request->query('grade')), 'enseignants_'.date('Y-m-d').'.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $enseignants = $this->enseignantsQuery($request->query('grade'))->get();
        $pdf = PDF::loadView('gestion.exports.enseignants_pdf', compact('enseignants'));

        return $pdf->download('enseignants_'.date('Y-m-d').'.pdf');
    }

    public function exportWord(Request $request)
    {
        $enseignants = $this->enseignantsQuery($request->query('grade'))->get();
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addTitle('Liste des Enseignants', 1);
        $section->addTextBreak();

        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        $table->addRow();
        $table->addCell()->addText('Nom', ['bold' => true]);
        $table->addCell()->addText('Email', ['bold' => true]);
        $table->addCell()->addText('Nom d\'utilisateur', ['bold' => true]);
        $table->addCell()->addText('Matricule', ['bold' => true]);
        $table->addCell()->addText('Grade', ['bold' => true]);
        $table->addCell()->addText('Téléphone', ['bold' => true]);

        foreach ($enseignants as $enseignant) {
            $table->addRow();
            $table->addCell()->addText($enseignant->user->name ?? '—');
            $table->addCell()->addText($enseignant->user->email ?? '—');
            $table->addCell()->addText($enseignant->user->username ?? '—');
            $table->addCell()->addText($enseignant->matricule_fonctionnaire ?? '—');
            $table->addCell()->addText($enseignant->grade ?? '—');
            $table->addCell()->addText($enseignant->telephone ?? '—');
        }

        return response()->streamDownload(function () use ($phpWord) {
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save('php://output');
        }, 'enseignants_'.date('Y-m-d').'.docx');
    }

    public function exportHtml(Request $request)
    {
        $enseignants = $this->enseignantsQuery($request->query('grade'))->get();

        return response()
            ->view('gestion.exports.enseignants_html', compact('enseignants'))
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="enseignants_'.date('Y-m-d').'.html"');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls,ods,pdf,docx,doc,uml|max:10240',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());

        if (in_array($ext, ['xlsx', 'xls', 'csv', 'ods'])) {
            $import = new EnseignantsImport();
            Excel::import($import, $file);
            $created = $import->getCreatedCredentials();
            if (!empty($created)) {
                return back()->with('success', 'Import terminé.')->with('import_credentials', $created);
            }
            return back()->with('success', 'Import Excel traité: enregistrements créés/mis à jour.');
        }

        $filename = time().'_'.preg_replace('/[^A-Za-z0-9\\-_.]/', '_', $file->getClientOriginalName());
        $path = $file->storeAs('imports/enseignants', $filename);

        return back()->with('success', 'Fichier enregistré (pas de parsing automatique pour ce type): '.$path);
    }
}
