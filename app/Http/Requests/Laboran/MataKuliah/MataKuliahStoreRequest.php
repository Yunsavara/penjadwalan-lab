<?php

namespace App\Http\Requests\Laboran\MataKuliah;

use Illuminate\Foundation\Http\FormRequest;

class MataKuliahStoreRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'dosen' => 'required|array',
            'dosen.*' => 'exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama mata kuliah wajib diisi.',
            'name.string' => 'Nama mata kuliah harus berupa teks.',
            'name.max' => 'Nama mata kuliah tidak boleh lebih dari 100 karakter.',

            'dosen.required' => 'Harap pilih setidaknya satu dosen.',
            'dosen.array' => 'Format dosen tidak valid.',
            'dosen.*.exists' => 'Dosen yang dipilih tidak valid.',
        ];
    }
}
