<?php

namespace App\Http\Requests\Laboran\LaboratoriumUnpam;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LaboratoriumUnpamUpdateRequest extends FormRequest
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
            'name_laboratorium_update' => [
                'required',
                'string',
                'max:15',
                Rule::unique('laboratorium_unpams', 'name_laboratorium')->where(function ($query) {
                    return $query->where('lokasi_id', $this->lokasi_id_update);
                })->ignore(Crypt::decryptString($this->id_laboratorium_update))
            ],
            'jenislab_id_update' => 'required|string|exists:jenislabs,id',
            'lokasi_id_update' => 'required|string|exists:lokasis,id',
            'kapasitas_laboratorium_update' => 'required|integer',
            'status_laboratorium_update' => 'required|string|in:tersedia,tidak tersedia'
        ];
    }

    public function messages(): array
    {
        return [
            'name_laboratorium_update.required' => 'Nama laboratorium wajib diisi.',
            'name_laboratorium_update.string' => 'Nama laboratorium harus berupa teks.',
            'name_laboratorium_update.max' => 'Nama laboratorium tidak boleh lebih dari 15 karakter.',
            'name_laboratorium_update.unique' => 'Nama laboratorium sudah ada di lokasi yang dipilih.',

            'jenislab_id_update.required' => 'Jenis lab wajib dipilih.',
            'jenislab_id_update.string' => 'Jenis lab harus berupa teks.',
            'jenislab_id_update.exists' => 'Jenis lab yang dipilih tidak valid.',

            'lokasi_laboratorium_update.required' => 'Lokasi laboratorium wajib diisi.',
            'lokasi_laboratorium_update.string' => 'Lokasi harus berupa teks.',
            'lokasi_laboratorium_update.exists' => 'Lokasi yang dipilih tidak valid.',

            'kapasitas_laboratorium_update.required' => 'Kapasitas laboratorium wajib diisi.',
            'kapasitas_laboratorium_update.integer' => 'Kapasitas harus berupa angka.',

            'status_laboratorium_update.required' => 'Status laboratorium wajib diisi.',
            'status_laboratorium_update.string' => 'Status harus berupa teks.',
            'status_laboratorium_update.in' => 'Status yang dipilih tidak valid. Pilih antara: tersedia atau tidak tersedia.',
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
                ->with('form', 'editLaboratorium') // modal identifier
        );
    }
}
