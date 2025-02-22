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
    public function rules(): array
    {
        return [
            'user_id' => 'required|in:' . auth()->user()->id, // harus sesuai dengan user yang login
            'lab_id' => 'required|exists:laboratorium_unpams,id',
            'tanggal_pengajuan' => 'required|array',
            'tanggal_pengajuan.*' => 'date|after_or_equal:today',

            'jam_mulai' => 'required|array',
            'jam_mulai.*' => 'date_format:H:i',

            'jam_selesai' => 'required|array',
            'jam_selesai.*' => 'date_format:H:i|after:jam_mulai.*',

            'keperluan' => 'required|string'
        ];
    }


    public function messages(): array
    {
        return [
            'user_id.required' => 'User tidak valid.',
            'user_id.in' => 'Anda hanya bisa mengajukan untuk akun Anda sendiri.',

            'lab_id.required' => 'Ruang lab harus dipilih.',
            'lab_id.exists' => 'Ruang lab yang dipilih tidak valid.',

            'tanggal_pengajuan.required' => 'Minimal satu tanggal harus dipilih.',
            'tanggal_pengajuan.array' => 'Format tanggal pengajuan tidak valid.',
            'tanggal_pengajuan.*.date' => 'Format tanggal tidak valid.',
            'tanggal_pengajuan.*.after_or_equal' => 'Tanggal tidak boleh kurang dari hari ini.',

            'jam_mulai.required' => 'Jam mulai harus diisi.',
            'jam_mulai.array' => 'Format jam mulai tidak valid.',
            'jam_mulai.*.date_format' => 'Jam mulai harus dalam format HH:MM.',

            'jam_selesai.required' => 'Jam selesai harus diisi.',
            'jam_selesai.array' => 'Format jam selesai tidak valid.',
            'jam_selesai.*.date_format' => 'Jam selesai harus dalam format HH:MM.',
            'jam_selesai.*.after' => 'Jam selesai harus lebih besar dari jam mulai.',

            'keperluan.required' => 'Keperluan harus diisi.',
            'keperluan.string' => 'Keperluan harus berupa teks.'
        ];
    }


}
