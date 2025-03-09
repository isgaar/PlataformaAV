<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;  // Si usas roles con Spatie

class UserController extends Controller
{
    // Método para listar usuarios con sus roles
    public function index(Request $request)
    {
        $search = $request->get('search', ''); // Obtener valor de búsqueda, por defecto vacío

        // Realizamos la búsqueda si se proporciona un término
        $users = User::with('roles') // Trae los usuarios con sus roles
            ->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->paginate(10);  // Paginación de 10 usuarios por página

        return view('admin.users.index', compact('users', 'search')); // Pasamos usuarios y la búsqueda
    }

    // Método para mostrar el formulario de crear usuario
    public function create()
    {
        $roles = Role::all(); // Obtenemos todos los roles disponibles
        return view('admin.users.create', compact('roles'));
    }

    public function show($id)
    {
        // Obtener el usuario por ID
        $user = User::with('roles')->findOrFail($id);

        // Retornar la vista con los detalles del usuario
        return view('admin.users.show', compact('user'));
    }

    // Método para almacenar el nuevo usuario en la base de datos
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Crear un nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Asignar roles al usuario
        if ($request->has('roles')) {
            $user->assignRole($request->roles);
        }

        return redirect()->route('users.index')->with('status', 'Usuario creado correctamente');
    }

    // Método para mostrar el formulario de edición de un usuario
    public function edit($id)
    {
        $user = User::findOrFail($id); // Obtener usuario por ID
        $roles = Role::all(); // Obtener todos los roles disponibles
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // Método para actualizar los datos de un usuario
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id); // Obtener el usuario por ID

        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed', // La contraseña es opcional al editar
        ]);

        // Actualizar los datos del usuario
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password, // Solo actualizar si hay nueva contraseña
        ]);

        // Actualizar los roles
        if ($request->has('roles')) {
            $user->syncRoles($request->roles); // Sincronizar roles
        }

        return redirect()->route('users.index')->with('status', 'Usuario actualizado correctamente');
    }

    // Método para eliminar un usuario
    public function destroy($id)
    {
        $user = User::findOrFail($id); // Obtener el usuario por ID
        $user->delete(); // Eliminar el usuario

        return redirect()->route('users.index')->with('status', 'Usuario eliminado correctamente');
    }
}
