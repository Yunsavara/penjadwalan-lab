<?php

namespace App\Http\Requests\Pengguna\Booking;

use Illuminate\Foundation\Http\FormRequest;

class BookingStoreRequest extends FormRequest
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
            'lokasiId' => 'required|exists:lokasis,id',
            'laboratoriumId' => 'required|exists:laboratorium_unpams,id',
            'modeTanggal' => 'required|in:multi,range',
        ];
    }

    public function messages(): array
    {
        return [
            'lokasiId.required' => 'Lokasi harus dipilih.',
            'lokasiId.exists' => 'Lokasi yang dipilih tidak valid.',

            'laboratoriumId.required' => 'Laboratorium harus dipilih.',
            'laboratoriumId.exists' => 'Laboratorium yang dipilih tidak valid.',

            'modeTanggal.required' => 'Mode tanggal harus dipilih.',
            'modeTanggal.in' => 'Mode tanggal harus berupa "multi" atau "range".',
        ];
    }

}
