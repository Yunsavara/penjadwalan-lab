<?php

namespace App\Http\Requests\Admin\Pengguna;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PenggunaStoreRequest extends FormRequest
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
            'nama_pengguna_store' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/',
            'email_pengguna_store' => 'required|email|max:150|unique:users,email',
            'password_pengguna_store' => 'required|min:8|max:16',
            'password_konfirmasi_pengguna_store' => 'required|same:password_pengguna_store',
            'lokasi_id_store' => 'required|integer|exists:lokasis,id',
            'peran_id_store' => 'required|integer|exists:roles,id'
        ];
    }

    public function messages(): array
    {
        return [
            'nama_pengguna_store.required' => 'Nama pengguna wajib diisi.',
            'nama_pengguna_store.string' => 'Nama pengguna harus berupa teks.',
            'nama_pengguna_store.max' => 'Nama pengguna maksimal 100 karakter.',
            'nama_pengguna_store.regex' => 'Nama pengguna hanya boleh huruf dan spasi.',

            'email_pengguna_store.required' => 'Email wajib diisi.',
            'email_pengguna_store.email' => 'Format email tidak valid.',
            'email_pengguna_store.max' => 'Email maksimal 150 karakter.',
            'email_pengguna_store.unique' => 'Email sudah digunakan.',

            'password_pengguna_store.required' => 'Password wajib diisi.',
            'password_pengguna_store.min' => 'Password tidak boleh kurang dari 8 karakter.',
            'password_pengguna_store.max' => 'Password tidak boleh lebih dari 16 karakter.',

            'password_konfirmasi_pengguna_store.required' => 'Konfirmasi password wajib diisi.',
            'password_konfirmasi_pengguna_store.same' => 'Konfirmasi password harus sama dengan password.',

            'lokasi_id_store.required' => 'Lokasi wajib dipilih.',
            'lokasi_id_store.integer' => 'ID lokasi tidak valid.',
            'lokasi_id_store.exists' => 'Lokasi tidak ditemukan.',

            'peran_id_store.required' => 'Peran pengguna wajib dipilih.',
            'peran_id_store.integer' => 'ID peran tidak valid.',
            'peran_id_store.exists' => 'Peran tidak ditemukan.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()
            ->route('admin.pengguna')
            ->withErrors($validator)
            ->withInput()
            ->with('form', 'createPengguna') // modal identifier
        );
    }
}
