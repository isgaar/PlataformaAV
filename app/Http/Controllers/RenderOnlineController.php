<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RenderOnlineController extends Controller
{
    // Método para renderizar la vista con el visor
    public function index()
    {
        return view('renderonline'); // Renderiza la vista renderonline.blade.php
    }

    // Método para manejar la carga de archivos PDB
    public function upload(Request $request)
    {
        try {
            // Validar el archivo PDB
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:pdb,txt|max:20480', // Acepta hasta 20MB
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            // Obtener el archivo cargado
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();

            // Verificar que la extensión del archivo sea ".pdb"
            if ($file->getClientOriginalExtension() !== 'pdb') {
                return response()->json(['error' => 'El archivo debe tener extensión .pdb.'], 400);
            }

            // Guardar el archivo en la carpeta "uploads"
            $path = $file->storeAs('uploads', $filename, 'public');

            // Leer el contenido del archivo PDB
            $fileContents = Storage::disk('public')->get("uploads/$filename");

            // Validar que el archivo contenga datos PDB válidos
            if (!$this->validatePDBContent($fileContents)) {
                Storage::disk('public')->delete("uploads/$filename"); // Elimina archivo inválido
                return response()->json(['error' => 'El archivo no contiene datos PDB válidos.'], 400);
            }

            // Analizar el contenido del archivo PDB para extraer información
            $pdbData = $this->parsePDB($fileContents);

            // Programar la eliminación automática del archivo después de 5 segundos
            $this->scheduleFileDeletion($filename);

            // Retornar la información del archivo y el contenido para renderizarlo
            return response()->json([
                'filename' => $filename,
                'path' => asset("storage/uploads/$filename"),
                'pdb_content' => $fileContents,
                'pdb_data' => $pdbData, // Información analizada del archivo PDB
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()], 500);
        }
    }

    // Función para validar que el archivo contenga datos PDB válidos
    private function validatePDBContent($content)
    {
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            if (preg_match('/^(ATOM|HETATM|HEADER|TITLE|COMPND|SOURCE|AUTHOR)/', trim($line))) {
                return true; // Archivo válido
            }
        }
        return false; // No contiene estructura PDB reconocida
    }

    // Función para analizar el archivo PDB
    private function parsePDB($content)
{
    $atoms = 0;
    $chains = [];
    $residues = [];
    $secondaryStructures = []; // Para hélices y láminas
    $connections = []; // Para enlaces entre átomos

    $lines = explode("\n", $content);
    foreach ($lines as $line) {
        // Extraer información de átomos
        if (preg_match('/^(ATOM|HETATM)\s+\d+\s+\S+\s+(\S{3})\s+(\S)/', trim($line), $matches)) {
            $atoms++;
            $residue = $matches[2];
            $chain = $matches[3];

            if (!in_array($chain, $chains)) {
                $chains[] = $chain;
            }

            if (!in_array($residue, $residues)) {
                $residues[] = $residue;
            }
        }

        // Extraer información de estructuras secundarias (hélices)
        if (preg_match('/^HELIX\s+\d+\s+\S+\s+(\S)\s+\d+\s+\S+\s+(\S)\s+\d+/', trim($line), $matches)) {
            $secondaryStructures[] = [
                'type' => 'helix',
                'chain' => $matches[1],
                'start' => $matches[2],
                'end' => $matches[3],
            ];
        }

        // Extraer información de estructuras secundarias (láminas)
        if (preg_match('/^SHEET\s+\d+\s+\S+\s+(\S)\s+\d+\s+\S+\s+(\S)\s+\d+/', trim($line), $matches)) {
            $secondaryStructures[] = [
                'type' => 'sheet',
                'chain' => $matches[1],
                'start' => $matches[2],
                'end' => $matches[3],
            ];
        }

        // Extraer información de enlaces (CONECT)
        if (preg_match('/^CONECT\s+(\d+)\s+(\d+)/', trim($line), $matches)) {
            $connections[] = [
                'atom1' => $matches[1],
                'atom2' => $matches[2],
            ];
        }
    }

    return [
        'atoms' => $atoms,
        'chains' => $chains,
        'residues' => $residues,
        'secondary_structures' => $secondaryStructures,
        'connections' => $connections,
        'size' => strlen($content),
    ];
}

    // Función para programar la eliminación del archivo después de 5 segundos
    private function scheduleFileDeletion($filename)
    {
        $filePath = "uploads/$filename";
        dispatch(function () use ($filePath) {
            sleep(10);
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
                Log::info("Archivo eliminado: " . $filePath);
            }
        })->afterResponse();
    }
}
