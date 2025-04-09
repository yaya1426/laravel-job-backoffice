<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        // First check if user exists and get their credentials
        $user = User::withTrashed()->where('email', $this->email)->first();

        // If user doesn't exist at all
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Check if user is archived BEFORE checking password
        if ($user->deleted_at !== null) {
            throw ValidationException::withMessages([
                'email' => 'This account has been archived. Please contact support for assistance.',
            ]);
        }

        // If user exists and is not archived, verify password
        if (!Hash::check($this->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // If we get here, password is correct and user is not archived
        Auth::login($user);
    }
}
