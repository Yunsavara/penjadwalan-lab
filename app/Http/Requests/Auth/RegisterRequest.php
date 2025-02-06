<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'nama_lengkap' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/',
            'user_email' => 'required|email|max:150',
            'password' => 'required|min:8|max:16|confirmed'
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.string' => 'Nama lengkap harus berupa teks.',
            'nama_lengkap.max' => 'Nama lengkap tidak boleh lebih dari 100 karakter.',
            'nama_lengkap.regex' => 'Nama lengkap hanya boleh mengandung huruf dan spasi.',

            'user_email.required' => 'Email wajib diisi.',
            'user_email.email' => 'Email tidak valid.',
            'user_email.max' => 'Email tidak boleh lebih dari 150 karakter.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus terdiri dari 8 karakter.',
            'password.max' => 'Password tidak boleh lebih dari 16 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password yang dimasukkan.',
        ];
    }

}
