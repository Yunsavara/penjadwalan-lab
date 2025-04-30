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
            'booking' => 'required|array',
            'alasan' => 'required|string',
        ];
    }


    public function messages(): array
    {
        return [
        ];
    }

    public function prepareForValidation()
    {
        $booking = $this->input('booking', []);

        foreach ($booking as $tanggal => $labs) {
            foreach ($labs as $labId => $slotJsonArray) {
                foreach ($slotJsonArray as $index => $slotJson) {
                    $booking[$tanggal][$labId][$index] = json_decode($slotJson, true);
                }
            }
        }

        $this->merge([
            'booking' => $booking,
        ]);
    }

}
