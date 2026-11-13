<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class IndonesianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute tidak valid.',
            'email' => 'Format email tidak valid.',
            'unique' => ':attribute sudah terdaftar.',
            'in' => ':attribute tidak valid.',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'date' => ':attribute harus tanggal yang valid.',
            'after_or_equal' => ':attribute tidak boleh sebelum hari ini.',
            'gte.numeric' => ':attribute harus lebih besar atau sama dengan budget minimum.',
            'max.string' => ':attribute maksimal :max karakter.',
            'min.string' => ':attribute minimal :min karakter.',
            'max.numeric' => ':attribute maksimal :max.',
            'min.numeric' => ':attribute minimal :min.',
        ];
    }
}
