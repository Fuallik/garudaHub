<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'client_id', 'judul', 'deskripsi', 'kategori',
        'budget_min', 'budget_max', 'batas_lelang', 'status',
    ];

    protected function casts(): array
    {
        return [
            'budget_min' => 'integer',
            'budget_max' => 'integer',
            'batas_lelang' => 'date',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    /** Cari berdasarkan judul atau kategori; wildcard LIKE di-escape. */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = mb_substr(trim((string) $term), 0, 100);

        if ($term === '') {
            return $query;
        }

        $like = '%'.addcslashes($term, '\\%_').'%';

        return $query->where(function (Builder $q) use ($like) {
            $q->where('judul', 'like', $like)->orWhere('kategori', 'like', $like);
        });
    }

    /** Lelang terbuka: status 'terbuka' dan batas lelang belum lewat. */
    public function isOpen(): bool
    {
        return $this->status === 'terbuka' && $this->batas_lelang->greaterThanOrEqualTo(today());
    }

    public function budgetRange(): string
    {
        return 'Rp '.number_format($this->budget_min, 0, ',', '.')
            .' - Rp '.number_format($this->budget_max, 0, ',', '.');
    }
}
