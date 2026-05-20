<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile(): JsonResponse
    {
        return response()->json(Auth::user());
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = Auth::user();
        $user->update($request->validated());

        return response()->json($user);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $userPassword = Auth::user();
        $userPassword->update([
            'password' => $request->password,
        ]);

        return response()->json(['message' => 'Contrasenya actualitzada correctament.']);
    }
}