<?php

namespace App\Http\Controllers\Api;

use App\Models\Estados;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EstadosController extends Controller
{
    /**
     * Mostrar una lista de estados.
     */
    public function index()
    {
        return Estados::all();
    }

    /**
     * Almacenar un nuevo estado.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'estado' => 'required|string|max:255',
        ]);

        return Estados::create($validated);
    }

    /**
     * Mostrar un estado específico.
     */
    public function show($id)
    {
        return Estados::findOrFail($id);
    }

    /**
     * Actualizar un estado existente.
     */
    public function update(Request $request, $id)
    {
        $estado = Estados::findOrFail($id);

        $validated = $request->validate([
            'estado' => 'required|string|max:255',
        ]);

        $estado->update($validated);

        return $estado;
    }

    /**
     * Eliminar un estado.
     */
    public function destroy($id)
    {
        $estado = Estados::findOrFail($id);
        $estado->delete();

        return response()->json(['message' => 'Estado eliminado correctamente.']);
    }
}
