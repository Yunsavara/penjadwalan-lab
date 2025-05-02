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
    
     public function rules()
     {
         return [
             'lokasi_pengajuan_booking' => ['required', 'exists:lokasis,id'],
             'laboratorium_pengajuan_booking' => ['required', 'array', 'min:1'],
             'laboratorium_pengajuan_booking.*' => ['exists:laboratorium_unpams,id'],
             'tanggal_pengajuan_booking' => ['required', 'regex:/^\d{4}-\d{2}-\d{2} - \d{4}-\d{2}-\d{2}$/'],
             'hari_operasional' => ['required', 'array', 'min:1'],
             'hari_operasional.*' => ['string'],
             'jam_operasional' => ['required', 'array'],
             'jam_operasional.*' => ['array', 'min:1'],
             'keperluan_pengajuan_booking' => ['required', 'string', 'min:10'],
         ];
     }
     
     public function messages()
     {
         return [
             'lokasi_pengajuan_booking.required' => 'Lokasi wajib dipilih.',
             'lokasi_pengajuan_booking.exists' => 'Lokasi yang dipilih tidak valid.',
     
             'laboratorium_pengajuan_booking.required' => 'Minimal satu laboratorium harus dipilih.',
             'laboratorium_pengajuan_booking.array' => 'Format laboratorium tidak valid.',
             'laboratorium_pengajuan_booking.min' => 'Minimal satu laboratorium harus dipilih.',
             'laboratorium_pengajuan_booking.*.exists' => 'Laboratorium yang dipilih tidak valid.',
     
             'tanggal_pengajuan_booking.required' => 'Tanggal pengajuan wajib diisi.',
             'tanggal_pengajuan_booking.regex' => 'Format tanggal rentang tidak valid. Gunakan format YYYY-MM-DD - YYYY-MM-DD.',
     
             'hari_operasional.required' => 'Pilih minimal satu hari operasional.',
             'hari_operasional.array' => 'Format hari operasional tidak valid.',
             'hari_operasional.min' => 'Pilih minimal satu hari operasional.',
             'hari_operasional.*.string' => 'Hari operasional tidak valid.',
     
             'jam_operasional.required' => 'Jam operasional wajib dipilih.',
             'jam_operasional.array' => 'Format jam operasional tidak valid.',
             'jam_operasional.*.array' => 'Format jam operasional tidak valid.',
             'jam_operasional.*.min' => 'Setiap hari operasional harus memiliki minimal satu jam.',
     
             'keperluan_pengajuan_booking.required' => 'Keperluan wajib diisi.',
             'keperluan_pengajuan_booking.string' => 'Format keperluan tidak valid.',
             'keperluan_pengajuan_booking.min' => 'Keperluan minimal 10 karakter.',
         ];
     }
     
}
