<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el rol seleccionado (por defecto: student)
        $role = $request->get('role', 'student');

        // Filtrar los usuarios por el rol seleccionado (útil para el maestro)
        $users = User::with(['roles', 'school', 'grade', 'group', 'turno'])
            ->whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            })
            ->paginate(10);

        // Obtener todas las prácticas (actividades) de la base de datos
        $practices = Activity::all();

        // Por defecto, ningún usuario ha hecho prácticas
        $donePractices = [];

        // Si el usuario autenticado es estudiante, obtener prácticas que ha completado
        if (Auth::check() && Auth::user()->hasRole('student')) {
            $donePractices = Auth::user()
                ->activities()
                ->wherePivot('done', true)
                ->pluck('activities.id')
                ->toArray();
        }

        // Si el rol actual es "teacher", simula progreso para mostrar en el dashboard
        if ($role === 'teacher') {
            foreach ($users as $user) {
                $user->done_practices = $user->activities()
                    ->wherePivot('done', true)
                    ->pluck('activities.id')
                    ->toArray();
            }
        }

        return view('dashboard', compact('users', 'role', 'practices', 'donePractices'));
    }
}
