<?php

namespace App\Http\Requests\Laboran\LaboratoriumUnpam;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'name_laboratorium_store' => [
                'required',
                'string',
                'max:15',
                Rule::unique('laboratorium_unpams', 'name_laboratorium')->where(function ($query) {
                    return $query->where('lokasi_id', request()->lokasi_id_store);
                })
            ],
            'jenislab_id_store' => 'required|string|exists:jenislabs,id',
            'lokasi_id_store' => 'required|string|exists:lokasis,id',
            'kapasitas_laboratorium_store' => 'required|integer',
            'status_laboratorium_store' => 'required|string|in:tersedia,tidak tersedia'
        ];
    }

    public function messages(): array
    {
        return [
            'name_laboratorium_store.required' => 'Nama laboratorium wajib diisi.',
            'name_laboratorium_store.string' => 'Nama laboratorium harus berupa teks.',
            'name_laboratorium_store.max' => 'Nama laboratorium tidak boleh lebih dari 15 karakter.',
            'name_laboratorium_store.unique' => 'Nama laboratorium sudah ada di lokasi yang dipilih.',

            'jenislab_id_store.required' => 'Jenis lab wajib dipilih.',
            'jenislab_id_store.string' => 'Jenis lab harus berupa teks.',
            'jenislab_id_store.exists' => 'Jenis lab yang dipilih tidak valid.',

            'lokasi_id_store.required' => 'Lokasi laboratorium wajib diisi.',
            'lokasi_id_store.string' => 'Lokasi harus berupa teks.',
            'lokasi_id_store.exists' => 'Lokasi yang dipilih tidak valid.',

            'kapasitas_laboratorium_store.required' => 'Kapasitas laboratorium wajib diisi.',
            'kapasitas_laboratorium_store.integer' => 'Kapasitas harus berupa angka.',

            'status_laboratorium_store.required' => 'Status laboratorium wajib diisi.',
            'status_laboratorium_store.string' => 'Status harus berupa teks.',
            'status_laboratorium_store.in' => 'Status yang dipilih tidak valid. Pilih antara: tersedia atau tidak tersedia.',
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
                ->with('form', 'createLaboratorium') // modal identifier
        );
    }
}
