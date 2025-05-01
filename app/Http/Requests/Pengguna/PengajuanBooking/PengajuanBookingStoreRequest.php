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
            'lokasi' => ['required', 'exists:lokasis,id'],

            'laboratorium' => ['required', 'array'],
            'laboratorium.*' => ['required', 'integer', 'exists:laboratorium_unpams,id'],

            'tanggalRange' => ['required', 'array', 'size:2'],
            'tanggalRange.0' => ['required', 'date', 'before_or_equal:tanggalRange.1'],
            'tanggalRange.1' => ['required', 'date', 'after_or_equal:tanggalRange.0'],

            'keperluan_pengajuan_booking' => ['required', 'string'],

            'hari_operasional' => ['required', 'array'],
            'hari_operasional.*' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],

            'sesi' => ['required', 'array'],
            'sesi.*' => ['required', 'array'],
            'sesi.*.*' => ['required', 'string'],

            'sesi_tanggal' => ['required', 'array'],
            'sesi_tanggal.*' => ['required', 'array'],
            'sesi_tanggal.*.*' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'lokasi.required' => 'Lokasi wajib dipilih.',
            'lokasi.exists' => 'Lokasi tidak valid.',

            'laboratorium.required' => 'Minimal satu laboratorium harus dipilih.',
            'laboratorium.array' => 'Format laboratorium tidak valid.',
            'laboratorium.*.required' => 'ID laboratorium tidak boleh kosong.',
            'laboratorium.*.integer' => 'ID laboratorium harus berupa angka.',
            'laboratorium.*.exists' => 'Laboratorium yang dipilih tidak ditemukan.',

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
            'hari_operasional.array' => 'Format hari operasional tidak valid.',
            'hari_operasional.*.required' => 'Hari operasional tidak boleh kosong.',
            'hari_operasional.*.in' => 'Hari operasional tidak valid.',

            'sesi.required' => 'Sesi per hari harus diisi.',
            'sesi.array' => 'Format sesi tidak valid.',
            'sesi.*.required' => 'Sesi untuk setiap hari harus diisi.',
            'sesi.*.array' => 'Format sesi per hari tidak valid.',
            'sesi.*.*.required' => 'Jam sesi harus diisi.',
            'sesi.*.*.string' => 'Format jam sesi tidak valid.',

            'sesi_tanggal.required' => 'Sesi per tanggal harus diisi.',
            'sesi_tanggal.array' => 'Format sesi per tanggal tidak valid.',
            'sesi_tanggal.*.required' => 'Sesi pada setiap tanggal harus diisi.',
            'sesi_tanggal.*.array' => 'Format sesi per tanggal tidak valid.',
            'sesi_tanggal.*.*.required' => 'Jam sesi pada tanggal harus diisi.',
            'sesi_tanggal.*.*.string' => 'Format jam sesi pada tanggal tidak valid.',
        ];
    }

    
    public function toPivotData(): array
    {
        $pivotData = [];

        foreach ($this->laboratorium as $labId) {
            foreach ($this->sesi_tanggal as $tanggal => $jamList) {
                foreach ($jamList as $jam) {
                    [$mulai, $selesai] = explode(' - ', $jam);
                    $pivotData[] = [
                        'laboratorium_id' => $labId,
                        'tanggal' => $tanggal,
                        'jam_mulai' => $mulai,
                        'jam_selesai' => $selesai,
                    ];
                }
            }
        }

        return $pivotData;
    }

}
