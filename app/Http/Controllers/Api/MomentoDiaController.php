<?php

namespace App\Http\Controllers\Api;

use App\Models\Momentodia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MomentoDiaController extends Controller
{
    /**
     * Mostrar una lista de momentos del día.
     */
    public function index()
    {
        try {
            $momentos = Momentodia::all(); // Obtén todos los registros
            return response()->json($momentos, 200); // Devuelve en formato JSON
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener momentos del día'], 500);
        }
    }

    /**
     * Almacenar un nuevo momento del día.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'momento' => 'required|string|max:255',
        ]);

        return Momentodia::create($validated);
    }

    /**
     * Mostrar un momento específico.
     */
    public function show($id)
    {
        return Momentodia::findOrFail($id);
    }

    /**
     * Actualizar un momento existente.
     */
    public function update(Request $request, $id)
    {
        $momento = Momentodia::findOrFail($id);

        $validated = $request->validate([
            'momento' => 'required|string|max:255',
        ]);

        $momento->update($validated);

        return $momento;
    }

    /**
     * Eliminar un momento del día.
     */
    public function destroy($id)
    {
        $momento = Momentodia::findOrFail($id);
        $momento->delete();

        return response()->json(['message' => 'Momento del día eliminado correctamente.']);
    }
}
