<?php

namespace App\Http\Requests\AllRole;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanUpdateRequest extends FormRequest
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
    protected function prepareForValidation()
    {
        $this->merge([
            'tanggal_pengajuan' => is_string($this->tanggal_pengajuan)
                ? explode(',', $this->tanggal_pengajuan)
                : $this->tanggal_pengajuan
        ]);
    }

    public function rules(): array
    {
        return [
            'kode_pengajuan' => 'required|string|exists:bookings,kode_pengajuan',
            'lab_id' => 'required|array',
            'lab_id.*' => 'exists:laboratorium_unpams,id',
            'tanggal_pengajuan' => 'required|array|min:1',
            'tanggal_pengajuan.*' => 'date|after_or_equal:today',
            'jam_mulai' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    if ($this->input('tanggal_pengajuan')) {
                        foreach ($this->input('tanggal_pengajuan') as $tanggal) {
                            if ($tanggal === date('Y-m-d') && $value < date('H:i')) {
                                $fail('Jam mulai tidak boleh kurang dari waktu saat ini untuk tanggal hari ini.');
                            }
                        }
                    }
                },
            ],
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keperluan' => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_pengajuan.required' => 'Kode pengajuan harus diisi.',
            'kode_pengajuan.string' => 'Kode pengajuan harus berupa teks.',
            'kode_pengajuan.exists' => 'Kode pengajuan tidak valid.',

            'lab_id.required' => 'Pilih minimal satu ruangan lab.',
            'lab_id.array' => 'Format ruangan lab tidak valid.',
            'lab_id.*.exists' => 'Ruangan lab yang dipilih tidak ditemukan.',

            'tanggal_pengajuan.required' => 'Pilih minimal satu tanggal.',
            'tanggal_pengajuan.array' => 'Format tanggal tidak valid.',
            'tanggal_pengajuan.min' => 'Pilih minimal satu tanggal.',
            'tanggal_pengajuan.*.date' => 'Tanggal harus berupa format tanggal yang valid.',
            'tanggal_pengajuan.*.after_or_equal' => 'Tanggal tidak boleh kurang dari hari ini.',

            'jam_mulai.required' => 'Jam mulai harus diisi.',
            'jam_mulai.date_format' => 'Format jam mulai harus dalam format HH:MM.',

            'jam_selesai.required' => 'Jam selesai harus diisi.',
            'jam_selesai.date_format' => 'Format jam selesai harus dalam format HH:MM.',
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',

            'keperluan.required' => 'Keperluan harus diisi.',
            'keperluan.string' => 'Keperluan harus berupa teks.',
            'keperluan.max' => 'Keperluan tidak boleh lebih dari 1000 karakter.',
        ];
    }

}
