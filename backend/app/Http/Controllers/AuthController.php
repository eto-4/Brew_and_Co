<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador responsable de l'autenticació d'usuaris.
 *
 * Gestiona el registre, inici de sessió i tancament de sessió
 * mitjançant tokens d'autenticació (Sanctum).
 */
class AuthController extends Controller
{
    /**
     * Registra un nou usuari al sistema i genera un token d'accés.
     *
     * @param \App\Http\Requests\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Autentica un usuari i genera un token d'accés.
     *
     * Verifica les credencials proporcionades i retorna l'usuari autenticat
     * amb el seu token si són correctes.
     *
     * @param \App\Http\Requests\LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credencials incorrectes.',
            ], 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }

    /**
     * Tanca la sessió de l'usuari eliminant el token actual.
     *
     * Invalida el token d'accés actiu de l'usuari autenticat.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sessió tancada correctament.',
        ]);
    }
}