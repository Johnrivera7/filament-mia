<?php

namespace JohnRivera7\FilamentMia\PageBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use JohnRivera7\FilamentMia\PageBuilder\PageContent;

/**
 * One page built with the block builder.
 *
 * Two payloads of the same shape live side by side: `draft` is what the
 * builder writes on every save, `published` is what visitors are served.
 * Publishing is therefore a copy, and the preview never needs a second source
 * of truth.
 *
 * Each payload is `['blocks' => [...]]`.
 *
 * @property string $key
 * @property array<string, mixed>|null $draft
 * @property array<string, mixed>|null $published
 * @property Carbon|null $published_at
 */
class MiaPage extends Model
{
    public const HOME = 'home';

    protected $table = 'mia_pages';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'draft',
        'published',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'draft' => 'array',
            'published' => 'array',
            'published_at' => 'datetime',
        ];
    }

    /**
     * The row for a page, created empty on first use so neither the builder
     * nor the public page has to special-case a missing record.
     */
    public static function forKey(string $key = self::HOME): self
    {
        return static::query()->firstOrCreate(['key' => $key], [
            'draft' => null,
            'published' => null,
        ]);
    }

    /**
     * Copy the draft over the published payload.
     *
     * @return array<string, mixed> the payload now being served
     */
    public function publish(): array
    {
        $payload = $this->draft ?? [];

        $this->forceFill([
            'published' => $payload,
            'published_at' => now(),
        ])->save();

        return $payload;
    }

    public function hasUnpublishedChanges(): bool
    {
        if ($this->published_at === null) {
            return ($this->draft ?? []) !== [];
        }

        return json_encode($this->draft) !== json_encode($this->published);
    }

    /**
     * The public page reads its blocks from a cache, so any write to this row
     * has to drop it. Doing it on the model rather than in each caller means a
     * later save from a command, a seeder or a test cannot forget.
     */
    protected static function booted(): void
    {
        $forget = function (self $page): void {
            app(PageContent::class)->forget($page->key);
        };

        static::saved($forget);
        static::deleted($forget);
    }
}
