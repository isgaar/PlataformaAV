<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $search = strtolower(trim($request->get('search', '')));

        $schools = School::whereRaw("LOWER(name) LIKE ?", ["%{$search}%"])
            ->orWhereRaw("LOWER(address) LIKE ?", ["%{$search}%"])
            ->paginate(10);

        return view('admin.schools.index', compact('schools', 'search'));
    }

    public function create()
    {
        return view('admin.schools.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255'
        ]);

        try {
            $school = School::create($validated);

            Session::flash('status', 'Escuela creada correctamente.');
            Session::flash('status_type', 'success');
            return redirect()->route('schools.index');
        } catch (\Exception $e) {
            Log::error('Error al crear escuela: ' . $e->getMessage());
            Session::flash('status', 'Error al crear escuela.');
            Session::flash('status_type', 'error');
            return back();
        }
    }

    public function edit($id)
    {
        $school = School::findOrFail($id);
        return view('admin.schools.edit', compact('school'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255'
        ]);

        try {
            $school = School::findOrFail($id);
            $school->update($validated);

            Session::flash('status', 'Escuela actualizada correctamente.');
            Session::flash('status_type', 'success');
            return redirect()->route('schools.index');
        } catch (\Exception $e) {
            Log::error('Error al actualizar escuela: ' . $e->getMessage());
            Session::flash('status', 'Error al actualizar escuela.');
            Session::flash('status_type', 'error');
            return back();
        }
    }

    public function show($id)
    {
        $school = School::with('users')->findOrFail($id);
        return view('admin.schools.show', compact('school'));
    }

    public function delete($id)
    {
        $school = School::findOrFail($id);
        return view('admin.schools.delete', compact('school'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $school = School::findOrFail($id);
            $school->delete();
            DB::commit();

            Session::flash('status', 'Escuela eliminada correctamente.');
            Session::flash('status_type', 'success');
            return redirect()->route('schools.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar escuela: ' . $e->getMessage());
            Session::flash('status', 'Error al eliminar escuela.');
            Session::flash('status_type', 'error');
            return back();
        }
    }
}
