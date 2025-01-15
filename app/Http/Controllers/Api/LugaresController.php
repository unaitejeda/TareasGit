<?php

namespace App\Http\Controllers\Api;

use App\Models\lugares;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class LugaresController extends Controller
{
    public function index()
    {
        return lugares::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lugar' => 'required|string|max:255',
        ]);

        return lugares::create($validated);
    }

    public function show($id)
    {
        return lugares::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $lugar = lugares::findOrFail($id);

        $validated = $request->validate([
            'lugar' => 'required|string|max:255',
        ]);

        $lugar->update($validated);

        return $lugar;
    }

    public function destroy($id)
    {
        $lugar = lugares::findOrFail($id);
        $lugar->delete();

        return response()->json(['message' => 'Lugar eliminado correctamente.']);
    }
}
