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
             'lokasi_pengajuan_booking' => 'required|exists:lokasis,id',
             'laboratorium_pengajuan_booking' => 'required|array',
             'laboratorium_pengajuan_booking.*' => 'exists:laboratorium_unpams,id',
             'mode_tanggal' => 'required|in:multi,range',
             'tanggal_multi' => 'required_if:mode_tanggal,multi',
             'tanggal_range' => 'required_if:mode_tanggal,range',
             'hari_operasional' => 'required_if:mode_tanggal,range|array',
             'jam' => 'required|array',
             'keperluan_pengajuan_booking' => 'required|string|max:255',
         ];
     }
     
     public function messages()
     {
         return [
             'lokasi_pengajuan_booking.required' => 'Lokasi harus dipilih.',
             'laboratorium_pengajuan_booking.required' => 'Laboratorium harus dipilih.',
             'tanggal_multi.required_if' => 'Tanggal harus dipilih untuk mode manual.',
             'tanggal_range.required_if' => 'Rentang tanggal harus dipilih untuk mode rentang.',
             'hari_operasional.required_if' => 'Minimal satu hari operasional harus dicentang.',
             'jam.required' => 'Sesi jam harus dipilih minimal satu.',
             'keperluan_pengajuan_booking.required' => 'Keperluan tidak boleh kosong.',
         ];
     }
     
}
