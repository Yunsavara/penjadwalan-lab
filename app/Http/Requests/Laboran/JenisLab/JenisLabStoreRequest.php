<?php

namespace App\Http\Requests\Laboran\JenisLab;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class JenisLabStoreRequest extends FormRequest
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
            'name' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/|unique:jenislabs,name',
            'description' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama Jenis Lab Harus Terisi',
            'name.string' => 'Nama Jenis Lab harus berupa string',
            'name.max' => 'Nama Jenis Lab tidak boleh melebihi 100 Kata',
            'name.regex' => 'Nama Jenis Lab tidak boleh berupa simbol',
            'name.unique' => 'Nama Jenis Lab sudah terpakai',

            'description.string' => 'Deskripsi harus berupa string'
        ];
    }

    // Kalau ada gagal input, supaya modal nya tetap tampil
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()
                ->route('laboran.laboratorium')
                ->withErrors($validator)
                ->withInput()
                ->with('form', 'createJenisLab') // modal identifier
        );
    }

}
