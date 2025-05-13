<?php

namespace App\Http\Requests\Pengguna\PengajuanBooking;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
        // Mode multi: pastikan tanggal_multi array dan bersih dari spasi
        if ($this->mode_tanggal == 'multi') {
            if (is_string($this->tanggal_multi)) {
                $this->tanggal_multi = explode(',', $this->tanggal_multi);
            }

            if (is_array($this->tanggal_multi)) {
                $this->merge([
                    'tanggal_multi' => array_map('trim', $this->tanggal_multi),
                ]);
            }
        }

        // Mode range: pecah tanggal_range menjadi start dan end
        if ($this->mode_tanggal == 'range' && is_string($this->tanggal_range)) {
            $dates = explode(' - ', $this->tanggal_range);
            $this->merge([
                'tanggal_range' => [
                    'start' => trim($dates[0] ?? ''),
                    'end' => trim($dates[1] ?? ''),
                ],
            ]);
        }
    }

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
            'lokasi_pengajuan_booking.required' => 'Lokasi kampus wajib dipilih.',
            'lokasi_pengajuan_booking.exists' => 'Lokasi kampus tidak valid.',

            'laboratorium_pengajuan_booking.required' => 'Laboratorium wajib dipilih.',
            'laboratorium_pengajuan_booking.array' => 'Format laboratorium tidak valid.',
            'laboratorium_pengajuan_booking.*.exists' => 'Salah satu laboratorium yang dipilih tidak ditemukan.',

            'mode_tanggal.required' => 'Mode tanggal wajib dipilih.',
            'mode_tanggal.in' => 'Mode tanggal harus bernilai multi atau range.',

            'tanggal_multi.required_if' => 'Tanggal wajib diisi jika mode tanggal adalah multi.',
            'tanggal_range.required_if' => 'Rentang tanggal wajib diisi jika mode tanggal adalah range.',

            'hari_operasional.required_if' => 'Hari operasional wajib dipilih saat menggunakan mode range.',
            'hari_operasional.array' => 'Format hari operasional tidak valid.',

            'jam.required' => 'Jam booking wajib diisi.',
            'jam.array' => 'Format jam booking tidak valid.',

            'keperluan_pengajuan_booking.required' => 'Keperluan booking wajib diisi.',
            'keperluan_pengajuan_booking.string' => 'Keperluan booking harus berupa teks.',
            'keperluan_pengajuan_booking.max' => 'Keperluan booking tidak boleh lebih dari 255 karakter.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('pesan', 'Terdapat Kesalahan :');

        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
        );
    }

}
