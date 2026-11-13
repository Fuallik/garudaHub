<?php

namespace App\Http\Requests;

class StoreBidRequest extends IndonesianRequest
{
    public function rules(): array
    {
        return [
            'harga' => ['required', 'integer', 'min:1', 'max:999999999999'],
            'durasi' => ['required', 'integer', 'min:1', 'max:3650'],
            'proposal' => ['required', 'string', 'max:5000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'harga' => 'Harga',
            'durasi' => 'Durasi',
            'proposal' => 'Proposal',
        ];
    }
}
