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
            // 'alasan' => ['required', 'string', 'max:255'],
            'slot' => 'required|array',
        ];
    }


    public function messages(): array
    {
        return [
            // 'alasan.required' => 'Alasan booking wajib diisi.',
            'slot.required' => 'Anda harus memilih minimal satu slot booking.',
        ];
    }

}
