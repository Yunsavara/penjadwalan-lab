<?php

namespace App\Http\Requests\Admin\LaboratoriumUnpam;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class LaboratoriumUnpamStoreRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:15',
                Rule::unique('laboratorium_unpams')->where(function ($query) {
                    return $query->where('lokasi', request()->lokasi);
                })
            ],
            'jenislab_id' => 'required|string|exists:jenislabs,id',
            'lokasi' => 'required|string|in:pusat,viktor,serang',
            'kapasitas' => 'required|integer',
            'status' => 'required|string|in:tersedia,tidak tersedia'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama laboratorium wajib diisi.',
            'name.string' => 'Nama laboratorium harus berupa teks.',
            'name.max' => 'Nama laboratorium tidak boleh lebih dari 15 karakter.',
            'name.unique' => 'Nama laboratorium sudah ada di lokasi yang dipilih.',

            'jenislab_id.required' => 'Jenis lab wajib dipilih.',
            'jenislab_id.string' => 'Jenis lab harus berupa teks.',
            'jenislab_id.exists' => 'Jenis lab yang dipilih tidak valid.',

            'lokasi.required' => 'Lokasi laboratorium wajib diisi.',
            'lokasi.string' => 'Lokasi harus berupa teks.',
            'lokasi.in' => 'Lokasi yang dipilih tidak valid. Pilih antara: pusat, viktor, atau serang.',

            'kapasitas.required' => 'Kapasitas laboratorium wajib diisi.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka.',

            'status.required' => 'Status laboratorium wajib diisi.',
            'status.string' => 'Status harus berupa teks.',
            'status.in' => 'Status yang dipilih tidak valid. Pilih antara: tersedia atau tidak tersedia.',
        ];
    }

    // 'name' => [
    //         'required',
    //         'string',
    //         'max:15',
    //         Rule::unique('laboratoria')->where(function ($query) {
    //             return $query->where('lokasi', request()->lokasi);
    //         })->ignore($this->laboratorium)
    //     ],

}
