<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RenderOnlineController extends Controller
{
    public function index()
    {
        return view('renderonline'); // Renderiza la vista renderonline.blade.php
    }

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

            // Obtener el archivo
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();

            // Verificar la extensión del archivo
            if ($file->getClientOriginalExtension() !== 'pdb') {
                return response()->json(['error' => 'El archivo debe tener extensión .pdb.'], 400);
            }

            // Guardar el archivo en la carpeta "uploads"
            $path = $file->storeAs('uploads', $filename, 'public');

            // Leer el contenido del archivo
            $fileContents = Storage::disk('public')->get("uploads/$filename");

            // Validar si el archivo tiene contenido válido
            if (!$this->validatePDBContent($fileContents)) {
                Storage::disk('public')->delete("uploads/$filename"); // Elimina archivo inválido
                return response()->json(['error' => 'El archivo no contiene datos PDB válidos.'], 400);
            }

            // Extraer información del archivo PDB
            $pdbData = $this->parsePDB($fileContents);

            // Programar la eliminación automática del archivo después de 10 segundos
            $this->scheduleFileDeletion($filename);

            // Retornar la información del archivo
            return response()->json([
                'filename' => $filename,
                'path' => asset("storage/uploads/$filename"),
                'pdb_content' => $fileContents,
                'pdb_data' => $pdbData,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()], 500);
        }
    }

    private function validatePDBContent($content)
    {
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            if (preg_match('/^(ATOM|HETATM|HEADER|TITLE|COMPND|SOURCE|AUTHOR)/', trim($line))) {
                return true;
            }
        }
        return false;
    }

    private function parsePDB($content)
    {
        $atoms = 0;
        $chains = [];
        $residues = [];
        $secondaryStructures = [];
        $connections = [];

        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            if (preg_match('/^(ATOM|HETATM)\s+\d+\s+(\S+)\s+(\S{3})\s+(\S)/', trim($line), $matches)) {
                $atoms++;
                $residue = $matches[3];
                $chain = $matches[4];

                if (!in_array($chain, $chains)) {
                    $chains[] = $chain;
                }

                if (!in_array($residue, $residues)) {
                    $residues[] = $residue;
                }
            }

            if (preg_match('/^HELIX\s+\d+\s+\S+\s+(\S)\s+\d+\s+\S+\s+(\S)\s+\d+/', trim($line), $matches)) {
                $secondaryStructures[] = [
                    'type' => 'helix',
                    'chain' => $matches[1],
                    'start' => $matches[2],
                    'end' => $matches[3],
                ];
            }

            if (preg_match('/^SHEET\s+\d+\s+\S+\s+(\S)\s+\d+\s+\S+\s+(\S)\s+\d+/', trim($line), $matches)) {
                $secondaryStructures[] = [
                    'type' => 'sheet',
                    'chain' => $matches[1],
                    'start' => $matches[2],
                    'end' => $matches[3],
                ];
            }

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
