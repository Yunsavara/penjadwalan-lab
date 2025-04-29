<?php

namespace App\Http\Requests\Pengguna\PengajuanBooking;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanBookingStoreRequest extends FormRequest
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
            'slots' => ['required', 'array', 'min:1'],
            'slots.*' => ['required', 'array', 'min:1'], // setiap tanggal harus array dan minimal 1 lab
            'slots.*.*' => ['required', 'array', 'min:1'], // setiap lab harus array dan minimal 1 jam
            'slots.*.*.*' => ['required', 'string'], // setiap jam harus string (contohnya "08:00-10:00")
        ];
    }

    public function messages(): array
    {
        return [
            'slots.required' => 'Slot booking harus diisi.',
            'slots.array' => 'Format slot booking harus berupa array.',
            'slots.min' => 'Minimal pilih satu tanggal booking.',

            'slots.*.required' => 'Tanggal booking harus diisi.',
            'slots.*.array' => 'Format data per tanggal harus berupa array.',
            'slots.*.min' => 'Minimal pilih satu laboratorium per tanggal.',

            'slots.*.*.required' => 'Laboratorium harus dipilih.',
            'slots.*.*.array' => 'Format data per laboratorium harus berupa array.',
            'slots.*.*.min' => 'Minimal pilih satu jam booking per laboratorium.',

            'slots.*.*.*.required' => 'Jam booking harus dipilih.',
            'slots.*.*.*.string' => 'Format jam booking tidak valid.',
        ];
    }
}
