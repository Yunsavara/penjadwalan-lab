<?php

namespace App\Http\Requests\Pengguna\PengajuanBooking;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'lokasi' => ['required', 'exists:lokasis,id'],
            'laboratorium' => ['required', 'array'],
            'laboratorium.*' => ['string', 'distinct'],
            'tanggalRange' => ['required', 'array', 'size:2'],
            'tanggalRange.0' => ['required', 'date', 'before_or_equal:tanggalRange.1'],
            'tanggalRange.1' => ['required', 'date', 'after_or_equal:tanggalRange.0'],
            'keperluan_pengajuan_booking' => ['required', 'string'],
            'hari_operasional' => ['required', 'array'],
            'hari_operasional.*' => ['in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
            'sesi' => ['required', 'array'],
            'sesi.*' => ['array'],
            'sesi.*.*' => ['string'],
            'sesi_tanggal' => ['required', 'array'],
            'sesi_tanggal.*' => ['array'],
            'sesi_tanggal.*.*' => ['string'],
        ];
    }

    public function messages(): array
    {
        return [
            'lokasi.required' => 'Lokasi wajib dipilih.',
            'lokasi.exists' => 'Lokasi tidak valid.',
            'laboratorium.required' => 'Minimal satu laboratorium harus dipilih.',
            'laboratorium.array' => 'Format laboratorium tidak valid.',
            'tanggalRange.required' => 'Tanggal harus dipilih.',
            'tanggalRange.size' => 'Range tanggal harus terdiri dari 2 tanggal.',
            'tanggalRange.0.required' => 'Tanggal mulai harus diisi.',
            'tanggalRange.1.required' => 'Tanggal selesai harus diisi.',
            'tanggalRange.0.date' => 'Format tanggal mulai tidak valid.',
            'tanggalRange.1.date' => 'Format tanggal selesai tidak valid.',
            'tanggalRange.0.before_or_equal' => 'Tanggal mulai tidak boleh setelah tanggal selesai.',
            'tanggalRange.1.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'keperluan_pengajuan_booking.required' => 'Keperluan harus diisi.',
            'hari_operasional.required' => 'Pilih minimal satu hari operasional.',
            'hari_operasional.*.in' => 'Hari operasional tidak valid.',
            'sesi.required' => 'Sesi per hari harus diisi.',
            'sesi.*.array' => 'Format sesi per hari tidak valid.',
            'sesi_tanggal.required' => 'Sesi per tanggal harus diisi.',
            'sesi_tanggal.*.array' => 'Format sesi per tanggal tidak valid.',
        ];
    }

    // protected function prepareForValidation(): void
    // {
    //     // Parsing sesi per tanggal
    //     $parsedSesiTanggal = [];
    //     foreach ($this->input('sesi_tanggal', []) as $tanggal => $sesiList) {
    //         foreach ($sesiList as $sesi) {
    //             [$mulai, $selesai] = explode(' - ', $sesi);
    //             $parsedSesiTanggal[$tanggal][] = [
    //                 'jam_mulai' => trim($mulai),
    //                 'jam_selesai' => trim($selesai),
    //             ];
    //         }
    //     }

    //     // Parsing sesi per hari
    //     $parsedSesiPerHari = [];
    //     foreach ($this->input('sesi', []) as $hari => $sesiList) {
    //         foreach ($sesiList as $sesi) {
    //             [$mulai, $selesai] = explode(' - ', $sesi);
    //             $parsedSesiPerHari[$hari][] = [
    //                 'jam_mulai' => trim($mulai),
    //                 'jam_selesai' => trim($selesai),
    //             ];
    //         }
    //     }

    //     $this->merge([
    //         'sesi_tanggal' => $parsedSesiTanggal,
    //         'sesi' => $parsedSesiPerHari,
    //     ]);
    // }

    // protected function failedValidation(Validator $validator)
    // {
    //     throw new HttpResponseException(
    //         redirect()
    //             ->back()
    //             ->withErrors($validator)
    //             ->withInput()
    //             ->with([
    //                 'old_hari' => $this->input('hari_operasional', []),
    //                 'old_sesi' => $this->input('sesi', []),
    //                 'old_sesi_tanggal' => $this->input('sesi_tanggal', []),
    //                 'old_labs' => $this->input('laboratorium', []),
    //                 'old_range' => $this->input('tanggalRange', []),
    //             ])
    //     );
    // }

}
