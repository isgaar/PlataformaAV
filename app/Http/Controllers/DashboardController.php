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

        return view('dashboard', compact('users', 'role'));
    }
}

