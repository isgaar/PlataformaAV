<?php

namespace App\Http\Controllers;

use App\Models\Practice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class PracticeController extends Controller
{
    public function index()
    {
        $practices = Practice::all();
        return view('dashboard', compact('practices'));
    }

    public function create()
    {
        return view('practices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'source_practice' => 'required|file|mimes:zip',
            'source_reference_image' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        $zipFile = $request->file('source_practice');
        $zipFileName = pathinfo($zipFile->getClientOriginalName(), PATHINFO_FILENAME);
        $imgPath = $request->file('source_reference_image')->store('imgs', 'practices');

        // Guardar ZIP (por si quieres mantener respaldo)
        $zipPath = $zipFile->storeAs('drive', $zipFile->hashName(), 'practices');
        $fullZipPath = Storage::disk('practices')->path($zipPath);

        // Extraer en public/practices/{nombre}
        $publicPath = public_path("practices/{$zipFileName}");
        if (!is_dir($publicPath)) {
            mkdir($publicPath, 0775, true);
        }

        $zip = new ZipArchive;
        if ($zip->open($fullZipPath) === true) {
            $zip->extractTo($publicPath);
            $zip->close();
        } else {
            return back()->with('error', 'No se pudo extraer el archivo ZIP.');
        }

        Practice::create([
            'name' => $request->name,
            'description' => $request->description,
            'source_practice' => "practices/{$zipFileName}", // ruta pública
            'source_reference_image' => $imgPath,
        ]);

        return redirect()->route('dashboard')->with('success', 'Práctica creada y extraída correctamente.');
    }

    public function play(Practice $practice)
    {
        return view('practices.play', compact('practice'));
    }

    public function grade(Request $request, Practice $practice)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:100',
        ]);

        // Aquí guardarías la calificación en la tabla pivote, o algún modelo PracticeResult
        // Ejemplo simple:
        $user = auth()->user();
        $user->practices()->attach($practice->id, ['score' => $request->score]);

        return redirect()->route('practices.index')->with('success', 'Práctica calificada.');
    }

    /*public function download(Practice $practice)
    {
        return Storage::disk('practices')->download($practice->source_practice);
    }
    */
    public function show(Practice $practice)
    {
        return view('practices.show', compact('practice'));
    }

    public function edit(Practice $practice)
    {
        return view('practices.edit', compact('practice'));
    }

    public function update(Request $request, Practice $practice)
    {
        // implementación opcional
    }

    public function destroy(Practice $practice)
    {
        Storage::disk('practices')->delete($practice->source_practice);
        Storage::disk('practices')->delete($practice->source_reference_image);
        $practice->delete();

        return redirect()->route('practices.index')->with('success', 'Práctica eliminada.');
    }
}
