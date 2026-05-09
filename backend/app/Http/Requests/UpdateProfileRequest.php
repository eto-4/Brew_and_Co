<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'   => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email' . Auth::id()],
        ];
    }
}