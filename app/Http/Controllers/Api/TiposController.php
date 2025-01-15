<?php

namespace App\Http\Controllers\Api;

use App\Models\tipos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TiposController extends Controller
{
    public function index()
    {
        return tipos::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:255',
        ]);

        return tipos::create($validated);
    }

    public function show($id)
    {
        return tipos::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $tipo = tipos::findOrFail($id);

        $validated = $request->validate([
            'tipo' => 'required|string|max:255',
        ]);

        $tipo->update($validated);

        return $tipo;
    }

    public function destroy($id)
    {
        $tipo = tipos::findOrFail($id);
        $tipo->delete();

        return response()->json(['message' => 'Tipo eliminado correctamente.']);
    }
}
