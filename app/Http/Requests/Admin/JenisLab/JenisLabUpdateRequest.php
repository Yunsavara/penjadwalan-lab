<?php

namespace App\Http\Requests\Admin\JenisLab;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/|unique:jenislabs,name,' . $this->jenislab->slug . ',slug',
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

}
