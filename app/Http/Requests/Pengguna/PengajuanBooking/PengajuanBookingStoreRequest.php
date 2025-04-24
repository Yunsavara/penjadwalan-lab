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
            'tanggal_mulai' => ['required', 'date', 'before_or_equal:tanggal_selesai'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'laboratorium' => ['required', 'array', 'min:1'],
            'laboratorium.*' => ['exists:laboratorium_unpams,id'],
            'keperluan_pengajuan_booking' => ['required', 'string', 'max:255'],
            'modeJam' => ['required', 'in:manual,otomatis'],

            // Kalau mode otomatis aktif, wajib isi jam
            // 'jam_otomatis' => ['required_if:modeJam,otomatis', 'array'],
            // 'jam_otomatis.*' => ['integer', 'between:7,20'], // jam dari 07:00 sampai 20:59

            // Booking per tanggal & lab (optional karena dibikin via JS)
            'booking' => ['nullable', 'array'],
            'booking.*' => ['array'],
            'booking.*.*' => ['array'],
            'booking.*.*.*' => ['integer', 'between:7,20'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.before_or_equal' => 'Tanggal mulai harus sebelum atau sama dengan tanggal selesai.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',

            'laboratorium.required' => 'Minimal satu laboratorium harus dipilih.',
            'laboratorium.*.exists' => 'Laboratorium yang dipilih tidak valid.',

            'keperluan_pengajuan_booking.required' => 'Keperluan booking wajib diisi.',
            'keperluan_pengajuan_booking.max' => 'Keperluan booking maksimal 255 karakter.',

            'modeJam.required' => 'Silakan pilih mode pemilihan jam.',
            'modeJam.in' => 'Mode jam tidak valid.',

            // 'jam_otomatis.required_if' => 'Jam otomatis wajib dipilih jika mode otomatis digunakan.',
            // 'jam_otomatis.*.between' => 'Jam harus berada antara 07:00 - 20:59.',

            'booking.*.*.*.between' => 'Jam booking harus berada antara 07:00 - 20:59.'
        ];
    }

}
