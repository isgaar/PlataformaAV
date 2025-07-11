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

        // Filtrar los usuarios por el rol seleccionado
        $users = User::with(['roles', 'school', 'grade', 'group', 'turno'])
            ->whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            })
            ->paginate(10);

        // Obtener todas las prácticas
        $practices = Activity::all();

        // Por defecto, ningún usuario ha hecho prácticas
        $donePractices = [];

        // Si el usuario autenticado es estudiante
        if (Auth::check() && Auth::user()->hasRole('student')) {
            $donePractices = Auth::user()
                ->activities()
                ->wherePivot('done', true)
                ->pluck('activities.id')
                ->toArray();
        }

        // Siempre cargar progreso de todos los usuarios listados
        foreach ($users as $user) {
            $user->done_practices = $user->activities()
                ->wherePivot('done', true)
                ->pluck('activities.id')
                ->toArray();
        }

        return view('dashboard', compact('users', 'role', 'practices', 'donePractices'));
    }
}
