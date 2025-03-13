<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\School;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Turno;

class UserController extends Controller
{
    // Método para listar usuarios con sus roles y relaciones
    public function index(Request $request)
    {
        $search = $request->get('search', ''); // Obtener valor de búsqueda

        $users = User::with(['roles', 'school', 'grade', 'group', 'turno']) // Cargar relaciones
            ->where('name', 'like', "%$search%")
            ->orWhere('last_name', 'like', "%$search%")
            ->orWhere('second_last_name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->paginate(10);  

        return view('admin.users.index', compact('users', 'search'));
    }

    // Método para mostrar el formulario de creación
    public function create()
    {
        $roles = Role::all();
        $schools = School::all();
        $grades = Grade::all();
        $groups = Group::all();
        $turnos = Turno::all();
        return view('admin.users.create', compact('roles', 'schools', 'grades', 'groups', 'turnos'));
    }

    // Método para almacenar un nuevo usuario
    public function store(Request $request)
    {
        // Validar datos
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'school_id' => 'required|exists:schools,id',
            'grade_id' => 'required|exists:grades,id',
            'group_id' => 'required|exists:groups,id',
            'turno_id' => 'required|exists:turnos,id',
        ]);

        // Crear usuario
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'second_last_name' => $request->second_last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'school_id' => $request->school_id,
            'grade_id' => $request->grade_id,
            'group_id' => $request->group_id,
            'turno_id' => $request->turno_id,
        ]);

        // Asignar roles si se seleccionan
        if ($request->has('roles')) {
            $user->assignRole($request->roles);
        }

        return redirect()->route('users.index')->with('status', 'Usuario creado correctamente');
    }

    // Método para mostrar formulario de edición
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $schools = School::all();
        $grades = Grade::all();
        $groups = Group::all();
        $turnos = Turno::all();
        return view('admin.users.edit', compact('user', 'roles', 'schools', 'grades', 'groups', 'turnos'));
    }

    // Método para actualizar usuario
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validar datos
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'school_id' => 'required|exists:schools,id',
            'grade_id' => 'required|exists:grades,id',
            'group_id' => 'required|exists:groups,id',
            'turno_id' => 'required|exists:turnos,id',
        ]);

        // Actualizar usuario
        $user->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'second_last_name' => $request->second_last_name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'school_id' => $request->school_id,
            'grade_id' => $request->grade_id,
            'group_id' => $request->group_id,
            'turno_id' => $request->turno_id,
        ]);

        // Actualizar roles
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('users.index')->with('status', 'Usuario actualizado correctamente');
    }

    // Método para mostrar detalles del usuario
    public function show($id)
    {
        $user = User::with(['school', 'grade', 'group', 'turno', 'roles'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    // Método para eliminar usuario
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('status', 'Usuario eliminado correctamente');
    }
}
