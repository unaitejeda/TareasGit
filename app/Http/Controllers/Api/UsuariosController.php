<?php

namespace App\Http\Controllers\Api;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UsuariosController extends Controller
{
    /**
     * Registro de un nuevo usuario.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'sexo' => 'required|in:M,F,Otro',
            'mail' => 'required|email|unique:usuarios,mail',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $usuario = Usuarios::create([
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'sexo' => $validated['sexo'],
            'mail' => $validated['mail'],
            'password' => Hash::make($validated['password']),
            'cantidad_tareas' => 0,
        ]);

        return response()->json(['message' => 'Usuario registrado con éxito.', 'user' => $usuario], 201);
    }

    /**
     * Inicio de sesión de usuario.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'mail' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt(['email' => $credentials['mail'], 'password' => $credentials['password']])) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $token = $request->user()->createToken('authToken')->plainTextToken;

        return response()->json(['message' => 'Inicio de sesión exitoso.', 'token' => $token], 200);
    }

    /**
     * Cierre de sesión de usuario.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Cierre de sesión exitoso.'], 200);
    }

    /**
     * Obtener información del usuario autenticado.
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
