<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\tareas;
use App\Models\estados;

class TareasController extends Controller
{
    public function index()
    {
        return tareas::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tarea' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'idlugar' => 'nullable|exists:lugares,id',
            'idmomento' => 'nullable|exists:momentodia,id',
            'idtipo' => 'nullable|exists:tipos,id',
            'ubicacion' => 'nullable|string|max:255'
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['idestado'] = Estados::where('estado', 'programada')->value('id');

        return Tareas::create($validated);
    }


    public function show($id)
    {
        return tareas::findOrFail($id);
    }

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
        ]);

        $tarea->update($validated);

        return response()->json(['message' => 'Tarea actualizada con éxito.', 'tarea' => $tarea]);
    }


    public function destroy($id)
    {
        $tarea = tareas::findOrFail($id);
        $tarea->delete();

        return response()->json(['message' => 'Tarea eliminada correctamente.']);
    }
    public function getTareasGenerales()
    {
        try {
            // Obtiene todas las tareas con la ubicación incluida
            $tareas = Tareas::with(['estado', 'user'])->get(['id', 'tarea', 'descripcion', 'fecha', 'ubicacion', 'idestado', 'user_id']);

            return response()->json([
                'success' => true,
                'message' => 'Tareas generales obtenidas con éxito',
                'data' => $tareas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las tareas',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getTareasUsuario(Request $request)
    {
        try {
            $tareas = Tareas::where('user_id', $request->user()->id)
                ->with(['estado', 'user'])
                ->get();


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

            if ($tarea->idestado != Estados::where('estado', 'programada')->value('id')) {
                return response()->json(['message' => 'La tarea no está en estado programada.'], 400);
            }

            $tarea->idestado = Estados::where('estado', 'finalizada')->value('id');
            $tarea->save();

            return response()->json(['message' => 'Tarea finalizada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al finalizar la tarea.'], 500);
        }
    }


    public function asignarTarea(Request $request)
{
    $validated = $request->validate([
        'tarea' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'fecha' => 'required|date',
        'idlugar' => 'nullable|exists:lugares,id',
        'idmomento' => 'nullable|exists:momentodia,id',
        'idtipo' => 'nullable|exists:tipos,id',
        'ubicacion' => 'nullable|string|max:255',
        'user_id' => 'required|exists:users,id'
    ]);

    $validated['idestado'] = Estados::where('estado', 'programada')->value('id');

    return Tareas::create($validated);
}
}
