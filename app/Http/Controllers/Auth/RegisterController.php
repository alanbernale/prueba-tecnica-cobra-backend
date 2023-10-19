<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // Obtener los datos de entrada validados
        $validated = $request->validated();

        // Se crea un nuevo usuario con los datos de entrada
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Se lanza un evento de nuevo usuario registrado
        event(new Registered($user));

        $token = $user->createToken($validated['device_name'])->plainTextToken;

        // Se retorna el token de acceso etiquetado con el nombre del dispositivo
        return response()->json([
            'token' => $token,
        ], Response::HTTP_OK);
    }
}
