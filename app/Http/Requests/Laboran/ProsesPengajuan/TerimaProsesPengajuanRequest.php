<?php

namespace App\Http\Requests\Laboran\ProsesPengajuan;

use Illuminate\Foundation\Http\FormRequest;

class TerimaProsesPengajuanRequest extends FormRequest
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
            'mode_terima' => 'required|in:otomatis,tidak',
            'alasan_penolakan_otomatis' => 'nullable|required_if:mode_terima,otomatis|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'mode_terima.required' => 'Mode terima harus dipilih.',
            'mode_terima.in' => 'Mode terima tidak valid. Pilih antara Otomatis atau Tidak.',

            'alasan_penolakan_otomatis.required_if' => 'Alasan penolakan harus diisi jika memilih mode Otomatis.',
            'alasan_penolakan_otomatis.string' => 'Alasan penolakan harus berupa teks.',
            'alasan_penolakan_otomatis.max' => 'Alasan penolakan tidak boleh lebih dari 255 karakter.',
        ];
    }
}
