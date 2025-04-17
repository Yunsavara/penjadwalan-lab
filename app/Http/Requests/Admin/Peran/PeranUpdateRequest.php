<?php

namespace App\Http\Requests\Admin\Peran;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PeranUpdateRequest extends FormRequest
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
            'nama_peran_update' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/|unique:roles,nama_peran,' . Crypt::decryptString($this->id_peran_update) .' ,id',
            'prioritas_peran_update' => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'nama_peran_update.required' => 'Nama peran wajib diisi.',
            'nama_peran_update.string' => 'Nama peran harus berupa teks.',
            'nama_peran_update.max' => 'Nama peran maksimal 100 karakter.',
            'nama_peran_update.regex' => 'Nama peran hanya boleh huruf dan spasi.',
            'nama_peran_update.unique' => 'Nama peran sudah digunakan.',

            'prioritas_peran_update.required' => 'Prioritas peran wajib diisi.',
            'prioritas_peran_update.integer' => 'Prioritas peran harus berupa angka.',
            'prioritas_peran_update.min' => 'Prioritas peran minimal bernilai 1.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()
                ->route('admin.pengguna')
                ->withErrors($validator)
                ->withInput()
                ->with('form', 'editPeran') // modal identifier
        );
    }

}
