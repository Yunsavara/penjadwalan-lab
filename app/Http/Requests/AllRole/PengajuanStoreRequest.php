<?php

namespace App\Http\Requests\AllRole;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanStoreRequest extends FormRequest
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

    public function rules()
    {
        return [
            'keperluan' => 'required|string|max:1000',
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
            'lab_id' => 'required|array',
            'lab_id.*' => 'exists:laboratorium_unpams,id',
        ];
    }

    public function messages()
    {
        return [
            'keperluan.required' => 'Keperluan harus diisi.',
            'keperluan.string' => 'Keperluan harus berupa teks.',
            'keperluan.max' => 'Keperluan tidak boleh lebih dari 1000 karakter.',

            'tanggal_pengajuan.required' => 'Harus memilih minimal satu tanggal.',
            'tanggal_pengajuan.array' => 'Tanggal pengajuan harus dalam bentuk array.',
            'tanggal_pengajuan.min' => 'Harus memilih minimal satu tanggal.',
            'tanggal_pengajuan.*.date' => 'Format tanggal tidak valid.',
            'tanggal_pengajuan.*.after_or_equal' => 'Tanggal pengajuan tidak boleh di masa lalu.',

            'jam_mulai.required' => 'Jam mulai harus diisi.',
            'jam_mulai.date_format' => 'Format jam mulai tidak valid, gunakan format HH:MM.',

            'jam_selesai.required' => 'Jam selesai harus diisi.',
            'jam_selesai.date_format' => 'Format jam selesai tidak valid, gunakan format HH:MM.',
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',

            'lab_id.required' => 'Harus memilih minimal satu lab.',
            'lab_id.array' => 'Lab harus dalam bentuk array.',
            'lab_id.*.exists' => 'Lab yang dipilih tidak ditemukan di sistem.',
        ];
    }
}
