<?php

namespace App\Http\Requests\Admin\Lokasi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LokasiStoreRequest extends FormRequest
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
            'nama_lokasi_store' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/|unique:lokasis,nama_lokasi',
            'deskripsi_lokasi_store' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lokasi_store.required' => 'Nama lokasi wajib diisi.',
            'nama_lokasi_store.string' => 'Nama lokasi harus berupa teks.',
            'nama_lokasi_store.max' => 'Nama lokasi maksimal 100 karakter.',
            'nama_lokasi_store.regex' => 'Nama lokasi hanya boleh berisi huruf dan spasi.',
            'nama_lokasi_store.unique' => 'Nama lokasi sudah digunakan.',

            'deskripsi_lokasi_store.required' => 'Deskripsi lokasi wajib diisi.',
            'deskripsi_lokasi_store.string' => 'Deskripsi lokasi harus berupa teks.',
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
                 ->with('form', 'createLokasi') // modal identifier
         );
     }
}
