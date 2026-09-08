<?php

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Workbench\Database\Factories\ProjectFactory;

#[Fillable([
    'client_id', 'owner_id', 'code', 'name', 'status', 'health', 'priority',
    'progress', 'budget', 'spent', 'starts_at', 'ends_at', 'delivered_at',
    'is_starred', 'brief',
])]
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at' => 'date',
            'delivered_at' => 'date',
            'is_starred' => 'boolean',
        ];
    }

    /** @return BelongsTo<Client, $this> */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /** @return BelongsTo<User, $this> */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /** @param  Builder<$this>  $query */
    public function scopeInFlight(Builder $query): Builder
    {
        return $query->whereNull('delivered_at')->where('status', '!=', 'on_hold');
    }

    /** Spend as a percentage of budget, which is what the meter draws. */
    public function budgetUsage(): int
    {
        return $this->budget > 0
            ? (int) round(($this->spent / $this->budget) * 100)
            : 0;
    }

    public function isOverdue(): bool
    {
        return $this->ends_at !== null
            && $this->delivered_at === null
            && $this->ends_at->isPast();
    }
}
