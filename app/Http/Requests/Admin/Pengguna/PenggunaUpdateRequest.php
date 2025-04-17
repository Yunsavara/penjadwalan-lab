<?php

namespace App\Http\Requests\Admin\Pengguna;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PenggunaUpdateRequest extends FormRequest
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
            'nama_pengguna_update' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/',
            'email_pengguna_update' => 'required|email|max:150|unique:users,email,' . Crypt::decryptString($this->id_pengguna_update),
            'lokasi_id_update' => 'required|integer|exists:lokasis,id',
            'peran_id_update' => 'required|integer|exists:roles,id'
        ];
    }

    public function messages(): array
    {
        return [
            'nama_pengguna_update.required' => 'Nama pengguna wajib diisi.',
            'nama_pengguna_update.string' => 'Nama pengguna harus berupa teks.',
            'nama_pengguna_update.max' => 'Nama pengguna maksimal 100 karakter.',
            'nama_pengguna_update.regex' => 'Nama pengguna hanya boleh huruf dan spasi.',

            'email_pengguna_update.required' => 'Email wajib diisi.',
            'email_pengguna_update.email' => 'Format email tidak valid.',
            'email_pengguna_update.max' => 'Email maksimal 150 karakter.',
            'email_pengguna_update.unique' => 'Email sudah digunakan oleh pengguna lain.',

            'lokasi_id_update.required' => 'Lokasi wajib dipilih.',
            'lokasi_id_update.integer' => 'ID lokasi tidak valid.',
            'lokasi_id_update.exists' => 'Lokasi tidak ditemukan.',

            'peran_id_update.required' => 'Peran pengguna wajib dipilih.',
            'peran_id_update.integer' => 'ID peran tidak valid.',
            'peran_id_update.exists' => 'Peran tidak ditemukan.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()
            ->route('admin.pengguna')
            ->withErrors($validator)
            ->withInput()
            ->with('form', 'editPengguna') // modal identifier
        );
    }
}
