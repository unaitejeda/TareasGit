<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\tareas;
use App\Models\estados;

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
    ]);

    $validated['user_id'] = $request->user()->id;
    $validated['idestado'] = Estados::where('nombre', 'programada')->value('id'); // Establecer estado "programada"

    return Tareas::create($validated);
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
    public function getTareasGenerales()
    {
        try {
            // Obtiene todas las tareas generales de la base de datos
            $tareas = tareas::all();  // Ajusta según sea necesario
            return response()->json($tareas);
        } catch (\Exception $e) {
            // Maneja errores si algo falla
            return response()->json(['message' => 'Error al obtener las tareas'], 500);
        }
    }

    public function getTareasUsuario(Request $request)
    {
        try {
            // Obtener las tareas del usuario autenticado con la relación de usuario
            $tareas = Tareas::where('user_id', $request->user()->id)
                ->with(['estado', 'user'])
                ->get();

    
            // Verificar si hay tareas
            if ($tareas->isEmpty()) {
                return response()->json(['message' => 'No se encontraron tareas para este usuario.'], 404);
            }
    
            return response()->json($tareas);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener las tareas'], 500);
        }
    }
    
    public function finalizarTarea($id)
{
    try {
        $tarea = Tareas::findOrFail($id);

        if ($tarea->idestado != Estados::where('nombre', 'programada')->value('id')) {
            return response()->json(['message' => 'La tarea no está en estado programada.'], 400);
        }

        $tarea->idestado = Estados::where('nombre', 'finalizada')->value('id');
        $tarea->save();

        return response()->json(['message' => 'Tarea finalizada correctamente.']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error al finalizar la tarea.'], 500);
    }
}


}
