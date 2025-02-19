<?php

namespace App\Http\Requests\Admin\Semester;

use Illuminate\Foundation\Http\FormRequest;

class SemesterUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:20',
            'tanggal_mulai' => 'required|date|after_or_equal:today|before_or_equal:tanggal_akhir',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,tidak aktif,selesai'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 20 karakter.',

            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai harus berupa format tanggal yang valid.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini.',
            'tanggal_mulai.before_or_equal' => 'Tanggal mulai tidak boleh lebih dari tanggal selesai.',

            'tanggal_akhir.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_akhir.date' => 'Tanggal selesai harus berupa format tanggal yang valid.',
            'tanggal_akhir.after_or_equal' => 'Tanggal selesai tidak boleh kurang dari tanggal mulai.',

            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus antara aktif, tidak aktif, dan selesai.'
        ];
    }
}
