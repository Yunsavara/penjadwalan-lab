<?php

namespace App\Http\Requests\Admin\Peran;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PeranStoreRequest extends FormRequest
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
            'nama_peran_store' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/|unique:roles,nama_peran',
            'prioritas_peran_store' => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'nama_peran_store.required' => 'Nama peran wajib diisi.',
            'nama_peran_store.string' => 'Nama peran harus berupa teks.',
            'nama_peran_store.max' => 'Nama peran maksimal 100 karakter.',
            'nama_peran_store.regex' => 'Nama peran hanya boleh mengandung huruf dan spasi.',
            'nama_peran_store.unique' => 'Nama peran sudah digunakan.',

            'prioritas_peran_store.required' => 'Prioritas peran wajib diisi.',
            'prioritas_peran_store.integer' => 'Prioritas peran harus berupa angka.',
            'prioritas_peran_store.min' => 'Prioritas peran minimal bernilai 1.',
        ];
    }

    // Kalau ada gagal input, supaya modal nya tetap tampil
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()
                ->route('admin.pengguna')
                ->withErrors($validator)
                ->withInput()
                ->with('form', 'createPeran') // modal identifier
        );
    }

}
