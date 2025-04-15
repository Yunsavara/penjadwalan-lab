<?php

namespace App\Http\Requests\Laboran\JenisLab;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class JenisLabUpdateRequest extends FormRequest
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
            'name_jenis_lab_update' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/|unique:jenislabs,name_jenis_lab,' . $this->slug_jenis_lab_update . ',slug_jenis_lab',
            'description_jenis_lab_update' => 'nullable|string'
        ];
    }

    // JenisLab->slug_jenis_lab_update

    public function messages(): array
    {
        return [
            'name_jenis_lab_update.required' => 'Nama Jenis Lab Harus Terisi',
            'name_jenis_lab_update.string' => 'Nama Jenis Lab harus berupa string',
            'name_jenis_lab_update.max' => 'Nama Jenis Lab tidak boleh melebihi 100 Kata',
            'name_jenis_lab_update.regex' => 'Nama Jenis Lab tidak boleh berupa simbol',
            'name_jenis_lab_update.unique' => 'Nama Jenis Lab sudah terpakai',

            'description_jenis_lab_update.string' => 'Deskripsi harus berupa string'
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
                ->with('form', 'editJenisLab') // modal identifier
        );
    }
}
