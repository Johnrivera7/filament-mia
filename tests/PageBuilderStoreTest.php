<?php

namespace JohnRivera7\FilamentMia\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use JohnRivera7\FilamentMia\PageBuilder\Models\MiaPage;
use JohnRivera7\FilamentMia\PageBuilder\PageContent;
use JohnRivera7\FilamentMia\PageBuilder\PagePayload;
use PHPUnit\Framework\Attributes\Test;

/**
 * What the builder writes and what a visitor is served.
 *
 * The migration is published rather than loaded, so it is run here the way an
 * application runs it after publishing: by requiring the stub and calling it.
 * That also makes the stub itself part of the suite — a migration nobody can
 * run is worse than no migration at all.
 */
class PageBuilderStoreTest extends TestCase
{
    use RefreshDatabase;

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('cache.default', 'array');
    }

    protected function defineDatabaseMigrations(): void
    {
        $migration = require __DIR__ . '/../database/migrations/create_mia_pages_table.php.stub';

        $migration->up();
    }

    protected function content(): PageContent
    {
        return app(PageContent::class);
    }

    #[Test]
    public function the_published_migration_creates_the_table_it_promises(): void
    {
        $page = MiaPage::forKey();

        $this->assertSame('home', $page->key);
        $this->assertNull($page->published_at);
        $this->assertSame('mia_pages', $page->getTable());
    }

    #[Test]
    public function the_row_is_created_once_and_then_reused(): void
    {
        $first = MiaPage::forKey();
        $second = MiaPage::forKey();

        $this->assertSame($first->id, $second->id);
        $this->assertSame(1, MiaPage::query()->count());
    }

    #[Test]
    public function publishing_copies_the_draft_over_what_visitors_see(): void
    {
        $page = MiaPage::forKey();

        $page->forceFill(['draft' => ['blocks' => [['type' => 'hero', 'data' => []]]]])->save();

        $this->assertSame([], $this->content()->published()->blocks());

        $page->publish();

        $this->assertCount(1, $this->content()->published()->blocks());
        $this->assertNotNull($page->fresh()->published_at);
    }

    #[Test]
    public function a_draft_that_has_never_been_published_counts_as_a_change(): void
    {
        $page = MiaPage::forKey();

        $this->assertFalse($page->hasUnpublishedChanges());

        $page->forceFill(['draft' => ['blocks' => [['type' => 'hero', 'data' => []]]]])->save();

        $this->assertTrue($page->hasUnpublishedChanges());
    }

    #[Test]
    public function publishing_settles_the_unpublished_flag(): void
    {
        $page = MiaPage::forKey();
        $page->forceFill(['draft' => ['blocks' => []]])->save();
        $page->publish();

        $this->assertFalse($page->fresh()->hasUnpublishedChanges());

        $page->forceFill(['draft' => ['blocks' => [['type' => 'footer', 'data' => []]]]])->save();

        $this->assertTrue($page->fresh()->hasUnpublishedChanges());
    }

    /**
     * The whole point of caching the published payload is that a visit costs
     * no query, which is only safe if a write can be relied on to drop it.
     */
    #[Test]
    public function a_write_drops_the_cache_the_public_page_reads(): void
    {
        $page = MiaPage::forKey();
        $page->forceFill(['draft' => ['blocks' => [['type' => 'hero', 'data' => []]]]])->save();
        $page->publish();

        $this->assertCount(1, $this->content()->published()->blocks());
        $this->assertTrue(Cache::has(PageContent::cacheKey('home')));

        $page->forceFill(['draft' => ['blocks' => []]])->save();
        $page->publish();

        $this->assertSame([], $this->content()->published()->blocks());
    }

    #[Test]
    public function deleting_the_row_drops_the_cache_too(): void
    {
        $page = MiaPage::forKey();
        $page->forceFill(['draft' => ['blocks' => [['type' => 'hero', 'data' => []]]]])->save();
        $page->publish();

        $this->content()->published();
        $page->delete();

        $this->assertFalse(Cache::has(PageContent::cacheKey('home')));
    }

    #[Test]
    public function the_draft_is_read_straight_from_the_row(): void
    {
        MiaPage::forKey()
            ->forceFill(['draft' => ['blocks' => [['type' => 'faq', 'data' => []]]]])
            ->save();

        $this->assertCount(1, $this->content()->draft()->blocks());
    }

    #[Test]
    public function a_hidden_block_stays_in_storage_and_off_the_page(): void
    {
        $payload = PagePayload::fromArray(['blocks' => [
            ['type' => 'hero', 'data' => ['visible' => true]],
            ['type' => 'pricing', 'data' => ['visible' => false, 'heading' => 'Kept']],
            ['type' => 'footer', 'data' => []],
        ]]);

        $types = array_column($payload->blocks(), 'type');

        $this->assertSame(['hero', 'footer'], $types);
    }

    #[Test]
    public function the_order_the_builder_left_them_in_is_the_order_they_render(): void
    {
        $payload = PagePayload::fromArray(['blocks' => [
            ['type' => 'navigation', 'data' => []],
            ['type' => 'hero', 'data' => []],
            ['type' => 'features', 'data' => []],
        ]]);

        $this->assertSame(['navigation', 'hero', 'features'], array_column($payload->blocks(), 'type'));
    }

    #[Test]
    public function a_payload_hand_edited_into_nonsense_renders_nothing_rather_than_failing(): void
    {
        $payload = PagePayload::fromArray(['blocks' => [
            'not an array',
            ['data' => ['heading' => 'no type']],
            ['type' => 42, 'data' => []],
            ['type' => 'hero', 'data' => 'not an array'],
        ]]);

        $blocks = $payload->blocks();

        $this->assertCount(1, $blocks);
        $this->assertSame('hero', $blocks[0]['type']);
        $this->assertSame([], $blocks[0]['data']);
    }

    #[Test]
    public function an_empty_page_says_so(): void
    {
        $this->assertTrue(PagePayload::fromArray(null)->isEmpty());
        $this->assertTrue(PagePayload::fromArray(['blocks' => 'nonsense'])->isEmpty());
        $this->assertFalse(PagePayload::fromArray(['blocks' => [['type' => 'hero', 'data' => []]]])->isEmpty());
    }
}
