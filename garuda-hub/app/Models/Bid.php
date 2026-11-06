<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bid extends Model
{
    // 'is_winner' sengaja tidak mass-assignable; hanya diubah lewat proses pilih pemenang.
    protected $fillable = ['project_id', 'developer_id', 'harga', 'durasi', 'proposal'];

    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'durasi' => 'integer',
            'is_winner' => 'boolean',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'developer_id');
    }
}
