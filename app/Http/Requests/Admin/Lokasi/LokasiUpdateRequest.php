<?php

namespace App\Http\Requests\Admin\Lokasi;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LokasiUpdateRequest extends FormRequest
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
            'nama_lokasi_update' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/|unique:lokasis,nama_lokasi,' . Crypt::decryptString($this->id_lokasi_update) . ',id',
            'deskripsi_lokasi_update' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lokasi_update.required' => 'Nama lokasi wajib diisi.',
            'nama_lokasi_update.string' => 'Nama lokasi harus berupa teks.',
            'nama_lokasi_update.max' => 'Nama lokasi tidak boleh lebih dari 100 karakter.',
            'nama_lokasi_update.regex' => 'Nama lokasi hanya boleh mengandung huruf dan spasi.',
            'nama_lokasi_update.unique' => 'Nama lokasi sudah digunakan.',

            'deskripsi_lokasi_update.required' => 'Deskripsi lokasi wajib diisi.',
            'deskripsi_lokasi_update.string' => 'Deskripsi lokasi harus berupa teks.',
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
                 ->with('form', 'editLokasi') // modal identifier
         );
     }
}
