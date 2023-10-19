<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Constructor del controlador
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['logout']);
    }

    public function login(LoginRequest $request)
    {
        // Obtener los datos de entrada validados
        $validated = $request->validated();

        // Buscar el usuario relacionado con el email proporcionado
        $user = User::where('email', $validated['email'])->first();

        // Si no existe usuario con el email proporcionado o la contraseña es incorrecta se lanza un error de validación
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($validated['device_name'])->plainTextToken;

        // Se retorna el token de acceso etiquetado con el nombre del dispositivo
        return response()->json([
            'token' => $token,
        ], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        // Se elimina el token de acceso de la solicitud
        $request->user()->currentAccessToken()->delete();

        // Se retorna una respuesta en formato JSON
        return response()->json([
            'token' => 'The token was removed.'
        ], Response::HTTP_OK);
    }
}
