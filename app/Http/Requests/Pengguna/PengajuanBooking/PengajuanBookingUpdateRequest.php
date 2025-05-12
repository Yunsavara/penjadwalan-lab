<?php

namespace App\Http\Requests\Pengguna\PengajuanBooking;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanBookingUpdateRequest extends FormRequest
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

     public function prepareForValidation()
    {
        // Pastikan tanggal_multi adalah array
        if ($this->mode_tanggal == 'multi') {
            // Jika $this->tanggal_multi adalah string, pisahkan berdasarkan koma atau spasi
            if (is_string($this->tanggal_multi)) {
                $this->tanggal_multi = explode(',', $this->tanggal_multi);
            }

            // Menghapus spasi ekstra pada setiap elemen array
            $this->merge([
                'tanggal_multi' => array_map('trim', $this->tanggal_multi),
            ]);
        }

        if ($this->mode_tanggal == 'range') {
            $dates = explode(' - ', $this->tanggal_range);
            $this->merge([
                'tanggal_range' => [
                    'start' => trim($dates[0] ?? null),
                    'end' => trim($dates[1] ?? null),
                ]
            ]);
        }
    }    

    public function rules()
    {
        return [
            'lokasi_pengajuan_booking' => 'required|exists:lokasis,id',
            'laboratorium_pengajuan_booking' => 'required|array|min:1',
            'laboratorium_pengajuan_booking.*' => 'exists:laboratorium_unpams,id',
            'mode_tanggal' => 'required|in:multi,range',
            'tanggal_multi' => 'required_if:mode_tanggal,multi',
            'tanggal_range' => 'required_if:mode_tanggal,range',
            'keperluan_pengajuan_booking' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'lokasi_pengajuan_booking.required' => 'Lokasi pengajuan harus dipilih.',
            'lokasi_pengajuan_booking.exists' => 'Lokasi yang dipilih tidak valid.',
            'laboratorium_pengajuan_booking.required' => 'Setidaknya satu laboratorium harus dipilih.',
            'laboratorium_pengajuan_booking.array' => 'Laboratorium harus dalam bentuk array.',
            'laboratorium_pengajuan_booking.*.exists' => 'Laboratorium yang dipilih tidak valid.',
            'mode_tanggal.required' => 'Mode tanggal harus dipilih.',
            'mode_tanggal.in' => 'Mode tanggal harus berupa "multi" atau "range".',
            'tanggal_multi.required_if' => 'Tanggal manual harus diisi ketika mode tanggal "manual" dipilih.',
            'tanggal_range.required_if' => 'Tanggal rentang harus diisi ketika mode tanggal "rentang" dipilih.',
            'keperluan_pengajuan_booking.required' => 'Keperluan pengajuan harus diisi.',
            'keperluan_pengajuan_booking.string' => 'Keperluan pengajuan harus berupa teks.',
            'keperluan_pengajuan_booking.max' => 'Keperluan pengajuan tidak boleh lebih dari 255 karakter.',
        ];
    }

}
