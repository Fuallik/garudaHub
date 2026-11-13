<?php

namespace App\Http\Requests;

class ProjectRequest extends IndonesianRequest
{
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'kategori' => ['required', 'string', 'max:50'],
            'budget_min' => ['required', 'integer', 'min:0', 'max:999999999999'],
            'budget_max' => ['required', 'integer', 'gte:budget_min', 'max:999999999999'],
            'batas_lelang' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function attributes(): array
    {
        return [
            'judul' => 'Judul',
            'deskripsi' => 'Deskripsi',
            'kategori' => 'Kategori',
            'budget_min' => 'Budget minimum',
            'budget_max' => 'Budget maksimum',
            'batas_lelang' => 'Batas lelang',
        ];
    }
}
