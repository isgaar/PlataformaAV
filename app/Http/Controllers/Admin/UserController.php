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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Importa Log

class UserController extends Controller
{
    // Método para listar usuarios con paginación y búsqueda
    public function index(Request $request)
    {
        $search = strtolower(trim($request->get('search', '')));

        $users = User::with(['roles', 'school', 'grade', 'group', 'turno'])
            ->whereRaw("LOWER(name) LIKE ?", ["%{$search}%"])
            ->orWhereRaw("LOWER(last_name) LIKE ?", ["%{$search}%"])
            ->orWhereRaw("LOWER(second_last_name) LIKE ?", ["%{$search}%"])
            ->orWhereRaw("LOWER(email) LIKE ?", ["%{$search}%"])
            ->orWhereHas('roles', function ($query) use ($search) {
                $query->whereRaw("LOWER(name) LIKE ?", ["%{$search}%"]);
            })
            ->orWhereHas('school', function ($query) use ($search) {
                $query->whereRaw("LOWER(name) LIKE ?", ["%{$search}%"]);
            })
            ->orWhereHas('grade', function ($query) use ($search) {
                $query->whereRaw("LOWER(name) LIKE ?", ["%{$search}%"]);
            })
            ->orWhereHas('group', function ($query) use ($search) {
                $query->whereRaw("LOWER(name) LIKE ?", ["%{$search}%"]);
            })
            ->orWhereHas('turno', function ($query) use ($search) {
                $query->whereRaw("LOWER(nombre) LIKE ?", ["%{$search}%"]);
            })
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


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            Log::info('Intentando guardar usuario...');

            $user = new User();
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->second_last_name = $request->second_last_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->school_id = $request->school_id;
            $user->grade_id = $request->grade_id;
            $user->group_id = $request->group_id;
            $user->turno_id = $request->turno_id;

            if ($user->save()) {
                Log::info('Usuario guardado con éxito. ID: ' . $user->id);
            } else {
                Log::error('Error al guardar usuario.');
                Session::flash('status', 'No se pudo guardar el usuario.');
                Session::flash('status_type', 'error');
                return back();
            }

            if ($request->has('roles')) {
                $role = Role::find($request->roles); // Busca el rol por ID
                if ($role) {
                    $user->assignRole($role->name); // Asigna el rol por nombre
                    Log::info('Rol asignado correctamente: ' . $role->name);
                } else {
                    Log::error('El rol con ID ' . $request->roles . ' no existe.');
                }
            }

            DB::commit();

            // Mensaje de éxito con Session::flash()
            Session::flash('status', "Se ha agregado correctamente el usuario");
            Session::flash('status_type', 'success');

            return redirect(route('users.index'))->with('token');
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            Log::error('Error de base de datos: ' . $ex->getMessage());

            Session::flash('status', $ex->getMessage());
            Session::flash('status_type', 'error-Query');
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error general: ' . $e->getMessage());

            Session::flash('status', $e->getMessage());
            Session::flash('status_type', 'error');
            return back();
        }
    }






    // Método para mostrar el formulario de edición
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


    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            // Validación de datos
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'second_last_name' => 'nullable|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|',
                'school_id' => 'nullable|exists:schools,id',
                'grade_id' => 'nullable|exists:grades,id',
                'group_id' => 'nullable|exists:groups,id',
                'turno_id' => 'nullable|exists:turnos,id',
                'roles' => 'required|exists:roles,id',
            ]);

            // Obtener el nuevo rol desde la base de datos
            $role = Role::find($request->roles);

            if (!$role) {
                DB::rollBack();
                Log::error("❌ Error: El rol con ID {$request->roles} no existe.");
                return back()->withErrors(["El rol seleccionado no es válido."]);
            }

            $isAdmin = strtolower($role->name) === "admin";

            // 🔥 **Actualizar el campo `role` en la tabla `users` directamente**
            $user->update([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'second_last_name' => $request->second_last_name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
                'role' => $role->name, // 💡 Asegurar que el campo `role` en `users` se actualiza
                'school_id' => $isAdmin ? null : $request->school_id,
                'grade_id' => $isAdmin ? null : $request->grade_id,
                'group_id' => $isAdmin ? null : $request->group_id,
                'turno_id' => $isAdmin ? null : $request->turno_id,
            ]);

            // 🔥 **Eliminar todos los roles previos antes de asignar el nuevo**
            $user->syncRoles([$role->name]);

            // 🔎 **Verificar después de la actualización**
            Log::info("✅ Usuario actualizado con ID: {$user->id}");
            Log::info("Nuevo campo `role` en `users`: " . $user->role);
            Log::info("Roles asignados en Spatie: " . json_encode($user->getRoleNames()));

            DB::commit();

            Session::flash('status', "Usuario actualizado correctamente");
            Session::flash('status_type', 'success');

            return redirect(route('users.index'));
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            Log::error('❌ Error de base de datos: ' . $ex->getMessage());

            Session::flash('status', $ex->getMessage());
            Session::flash('status_type', 'error-Query');

            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error general: ' . $e->getMessage());

            Session::flash('status', $e->getMessage());
            Session::flash('status_type', 'error');

            return back();
        }
    }

    // Método para mostrar detalles del usuario
    public function show($id)
    {
        $user = User::with(['school', 'grade', 'group', 'turno', 'roles'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    // Método para mostrar el formulario de eliminación
    public function delete($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.delete', ['user' => $user]);
    }

    // Método para eliminar usuario
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->delete();

            DB::commit();
            return redirect()->route('users.index')->with('status', 'Usuario eliminado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }
}
