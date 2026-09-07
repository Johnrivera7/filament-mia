<?php

namespace JohnRivera7\FilamentMia\Tests;

use Illuminate\Filesystem\Filesystem;
use JohnRivera7\FilamentMia\Enums\Density;
use JohnRivera7\FilamentMia\Enums\Roundness;
use JohnRivera7\FilamentMia\Exceptions\InvalidThemeOption;
use JohnRivera7\FilamentMia\Settings\Contracts\SettingsRepository;
use JohnRivera7\FilamentMia\Settings\FileSettingsRepository;
use JohnRivera7\FilamentMia\Settings\ThemeSettings;
use PHPUnit\Framework\Attributes\Test;

class SettingsTest extends TestCase
{
    protected string $directory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = sys_get_temp_dir() . '/filament-mia-tests-' . bin2hex(random_bytes(6));
    }

    protected function tearDown(): void
    {
        (new Filesystem)->deleteDirectory($this->directory);

        parent::tearDown();
    }

    protected function repository(): FileSettingsRepository
    {
        return new FileSettingsRepository(new Filesystem, $this->directory);
    }

    #[Test]
    public function it_survives_a_round_trip_through_the_repository(): void
    {
        $settings = ThemeSettings::defaults();

        $repository = $this->repository();
        $repository->put('admin', $settings->toArray());

        $this->assertSame(
            $settings->toArray(),
            ThemeSettings::fromArray($this->repository()->get('admin'))->toArray(),
        );
    }

    #[Test]
    public function it_returns_an_empty_record_for_a_panel_that_was_never_customised(): void
    {
        $this->assertSame([], $this->repository()->get('never-touched'));
    }

    #[Test]
    public function it_forgets_a_record(): void
    {
        $repository = $this->repository();
        $repository->put('admin', ThemeSettings::defaults()->toArray());
        $repository->forget('admin');

        $this->assertSame([], $repository->get('admin'));
    }

    /**
     * Colours are normalised on the way in, so a value typed in the interface
     * and the same value written in the config file store identically.
     */
    #[Test]
    public function it_normalises_colours(): void
    {
        $this->assertSame('#c9a227', ThemeSettings::fromArray(['accent_color' => '#C9A227'])->accentColor);
        $this->assertSame(
            ThemeSettings::defaults()->accentColor,
            ThemeSettings::fromArray(ThemeSettings::defaults()->toArray())->accentColor,
        );
    }

    #[Test]
    public function it_keeps_two_panels_apart(): void
    {
        $repository = $this->repository();

        $repository->put('admin', ['accent_color' => '#C9A227']);
        $repository->put('staff', ['accent_color' => '#7C8F5F']);

        $this->assertSame('#C9A227', $repository->get('admin')['accent_color']);
        $this->assertSame('#7C8F5F', $repository->get('staff')['accent_color']);
    }

    #[Test]
    public function it_does_not_let_a_panel_id_escape_its_directory(): void
    {
        $repository = $this->repository();
        $repository->put('../../etc/passwd', ['accent_color' => '#C9A227']);

        $written = (new Filesystem)->files($this->directory);

        $this->assertCount(1, $written);
        $this->assertStringStartsWith($this->directory . '/', $written[0]->getPathname());
        $this->assertStringNotContainsString('..', $written[0]->getFilename());
    }

    #[Test]
    public function it_falls_back_to_defaults_rather_than_throwing_on_a_corrupt_record(): void
    {
        $files = new Filesystem;
        $files->ensureDirectoryExists($this->directory);

        $repository = $this->repository();
        $repository->put('admin', ['accent_color' => '#C9A227']);

        // Re-read from a fresh instance so the memoised value is not returned.
        $path = $files->files($this->directory)[0]->getPathname();
        $files->put($path, '{ not json');

        $this->assertSame([], $this->repository()->get('admin'));
    }

    #[Test]
    public function it_fills_in_settings_a_stored_record_predates(): void
    {
        // A record written before `elevation` existed must still load, taking
        // the current default for anything it does not mention.
        $settings = ThemeSettings::fromArray(['accent_color' => '#C9A227']);

        $this->assertSame('#c9a227', $settings->accentColor);
        $this->assertSame(ThemeSettings::defaults()->elevation, $settings->elevation);
        $this->assertSame(ThemeSettings::defaults()->sansFont, $settings->sansFont);
    }

    #[Test]
    public function it_reads_enums_and_bounds_elevation(): void
    {
        $settings = ThemeSettings::fromArray([
            'roundness' => 'sharp',
            'density' => 'compact',
            'elevation' => 9.0,
        ]);

        $this->assertSame(Roundness::Sharp, $settings->roundness);
        $this->assertSame(Density::Compact, $settings->density);
        $this->assertSame(2.0, $settings->elevation);
    }

    #[Test]
    public function it_treats_a_blank_neutral_as_the_curated_ramp(): void
    {
        $this->assertNull(ThemeSettings::fromArray(['neutral_color' => ''])->neutralColor);
        $this->assertNull(ThemeSettings::fromArray(['neutral_color' => null])->neutralColor);
    }

    #[Test]
    public function it_rejects_a_colour_it_cannot_parse(): void
    {
        $this->expectException(InvalidThemeOption::class);

        ThemeSettings::fromArray(['accent_color' => 'burnt sienna']);
    }

    #[Test]
    public function it_binds_a_repository_by_default(): void
    {
        $this->assertInstanceOf(
            FileSettingsRepository::class,
            app(SettingsRepository::class),
        );
    }
}
