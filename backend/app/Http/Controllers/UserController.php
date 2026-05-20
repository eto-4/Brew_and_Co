<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador responsable de la gestió del perfil d'usuari.
 *
 * Permet consultar i actualitzar les dades del perfil, així com
 * modificar la contrasenya de l'usuari autenticat.
 */
class UserController extends Controller
{
    /**
     * Retorna la informació de l'usuari autenticat.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(): JsonResponse
    {
        return response()->json(Auth::user());
    }

    /**
     * Actualitza les dades del perfil de l'usuari autenticat.
     *
     * Valida i actualitza el nom i l'email de l'usuari.
     *
     * @param \App\Http\Requests\UpdateProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = Auth::user();
        $user->update($request->validated());

        return response()->json($user);
    }

    /**
     * Actualitza la contrasenya de l'usuari autenticat.
     *
     * Desa la nova contrasenya després de validar-la i retorna
     * un missatge de confirmació.
     *
     * @param \App\Http\Requests\UpdatePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $userPassword = Auth::user();
        $userPassword->update([
            'password' => $request->password,
        ]);

        return response()->json(['message' => 'Contrasenya actualitzada correctament.']);
    }
}