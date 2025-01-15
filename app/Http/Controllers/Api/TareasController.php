<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\tareas;

class TareasController extends Controller
{
    /**
     * Mostrar una lista de las tareas.
     */
    public function index()
    {
        return tareas::all();
    }

    /**
     * Almacenar una nueva tarea.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tarea' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'idlugar' => 'nullable|exists:lugares,id',
            'idmomento' => 'nullable|exists:momentodia,id',
            'idtipo' => 'nullable|exists:tipos,id',
            'idestado' => 'nullable|exists:estados,id',
        ]);

        return tareas::create($validated);
    }

    /**
     * Mostrar una tarea específica.
     */
    public function show($id)
    {
        return tareas::findOrFail($id);
    }

    /**
     * Actualizar una tarea existente.
     */
    public function update(Request $request, $id)
    {
        $tarea = tareas::findOrFail($id);

        $validated = $request->validate([
            'tarea' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'sometimes|date',
            'idlugar' => 'nullable|exists:lugares,id',
            'idmomento' => 'nullable|exists:momentodia,id',
            'idtipo' => 'nullable|exists:tipos,id',
            'idestado' => 'nullable|exists:estados,id',
        ]);

        $tarea->update($validated);

        return $tarea;
    }

    /**
     * Eliminar una tarea.
     */
    public function destroy($id)
    {
        $tarea = tareas::findOrFail($id);
        $tarea->delete();

        return response()->json(['message' => 'Tarea eliminada correctamente.']);
    }
}
