<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\School;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Turno;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el rol seleccionado (por defecto: student)
        $role = $request->get('role', 'student');

        // Filtrar los usuarios por el rol seleccionado
        $users = User::with(['roles', 'school', 'grade', 'group', 'turno'])
            ->whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            })
            ->paginate(10);

        // Agregar prácticas para mostrar en la vista
        $practices = [
            [
                'id' => 'PA17',
                'image' => 'PA17.png',
                'title' => 'Práctica PA17',
                'description' => 'Partículas<br>Átomos<br>Moléculas'
            ],
            [
                'id' => 'PA18',
                'image' => 'PA18.png',
                'title' => 'Práctica PA18',
                'description' => 'Mezclas:<br>Homogéneas<br>Heterogéneas'
            ],
            [
                'id' => 'PA22',
                'image' => 'PA22.png',
                'title' => 'Práctica PA22',
                'description' => 'Enlaces:<br>Iónico<br>Molecular'
            ],
            [
                'id' => 'PA24',
                'image' => 'PA24.png',
                'title' => 'Práctica PA24',
                'description' => 'Compuestos:<br>Molecular<br>Iónico'
            ],
        ];

        // Simular prácticas realizadas para el usuario autenticado (por ejemplo, estudiante)
        if (auth()->check()) {
            $user = auth()->user();
            $user->done_practices = collect(['PA17', 'PA22']); // Puedes variar esto según usuario
        }


        // Si el rol es "teacher", generamos datos falsos de prácticas realizadas para cada estudiante
        if ($role === 'teacher') {
            foreach ($users as $user) {
                // Simular prácticas realizadas de forma aleatoria
                $done = collect($practices)
                    ->filter(fn($p) => rand(0, 1)) // 50% de probabilidad
                    ->pluck('id')
                    ->toArray();

                // Agregar atributo dinámico al modelo
                $user->done_practices = $done;
            }
        }

        return view('dashboard', compact('users', 'role', 'practices'));
    }
}
