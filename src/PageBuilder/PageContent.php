<?php

namespace JohnRivera7\FilamentMia\PageBuilder;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Database\QueryException;
use JohnRivera7\FilamentMia\PageBuilder\Models\MiaPage;

/**
 * Reads a built page, published or draft.
 *
 * The published version is cached indefinitely and invalidated by
 * {@see MiaPage} on every write, so a visit costs no query at all once the
 * cache is warm. The draft deliberately is not cached: it exists to be looked
 * at immediately after an edit.
 *
 * The array is cached rather than the resolved {@see PagePayload} so the value
 * stays a plain, portable structure — a serialised object graph would tie
 * every cached page to the exact class shape that wrote it.
 *
 * Bound as a singleton, which is what makes {@see forget()} more than half a
 * job: a model event resolving a fresh instance would clear the store but
 * leave the array the current request is rendering from untouched.
 */
class PageContent
{
    /** @var array<string, array<string, mixed>> */
    private array $memoised = [];

    public function __construct(private readonly Cache $cache) {}

    public function published(string $key = MiaPage::HOME): PagePayload
    {
        $this->memoised[$key] ??= $this->cache->rememberForever(
            self::cacheKey($key),
            fn (): array => $this->read($key),
        );

        return PagePayload::fromArray($this->memoised[$key]);
    }

    public function draft(string $key = MiaPage::HOME): PagePayload
    {
        return PagePayload::fromArray(MiaPage::forKey($key)->draft);
    }

    public function forget(string $key = MiaPage::HOME): void
    {
        unset($this->memoised[$key]);

        $this->cache->forget(self::cacheKey($key));
    }

    public static function cacheKey(string $key): string
    {
        return "filament-mia:page:{$key}:v1";
    }

    /**
     * An empty page rather than an exception when the table is not there.
     *
     * The migration is published, not shipped, so the honest failure mode for
     * an application that switched the builder on and has not run it yet is a
     * page saying there is nothing to show — not a 500 on the site's front
     * door.
     *
     * @return array<string, mixed>
     */
    private function read(string $key): array
    {
        try {
            return MiaPage::query()->where('key', $key)->first()?->published ?? [];
        } catch (QueryException) {
            return [];
        }
    }
}
