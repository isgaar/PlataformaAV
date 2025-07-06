<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'done' => 'required|boolean',
            'session' => 'required|string'
        ]);

        $user = Auth::user();

        // Guardar el resultado en la tabla pivote
        $user->activities()->syncWithoutDetaching([
            $validated['activity_id'] => [
                'done' => $validated['done'],
                'session' => $validated['session'],
            ]
        ]);

        return response()->json([
            'message' => 'Resultado guardado correctamente',
            'status' => true
        ]);
    }
}
