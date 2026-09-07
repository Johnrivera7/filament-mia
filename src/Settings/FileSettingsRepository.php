<?php

namespace JohnRivera7\FilamentMia\Settings;

use Illuminate\Filesystem\Filesystem;
use JohnRivera7\FilamentMia\Settings\Contracts\SettingsRepository;
use JsonException;

/**
 * Stores the customiser's settings as JSON under the application's storage
 * directory, one file per panel.
 *
 * The default because it needs no migration and no table: the theme installs
 * into an existing project and starts working, which a schema change would
 * not allow. The trade-off is that it is local disk. On several application
 * servers, or on a platform with an ephemeral filesystem, bind a repository
 * backed by your database or shared cache instead — see the README.
 *
 * Reads are memoised for the lifetime of the request. The theme reads settings
 * on every panel boot, and re-reading the same file on each of those calls
 * would be wasted work.
 */
class FileSettingsRepository implements SettingsRepository
{
    /** @var array<string, array<string, mixed>> */
    protected array $memoised = [];

    public function __construct(
        protected Filesystem $files,
        protected string $directory,
    ) {}

    public function get(string $panelId): array
    {
        if (array_key_exists($panelId, $this->memoised)) {
            return $this->memoised[$panelId];
        }

        $path = $this->path($panelId);

        if (! $this->files->exists($path)) {
            return $this->memoised[$panelId] = [];
        }

        try {
            $decoded = json_decode($this->files->get($path), associative: true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            // A corrupt file must not take the panel down. Falling back to the
            // configured defaults renders a working panel that simply ignores
            // the customisation, which is recoverable; throwing here would
            // lock the administrator out of the page that could fix it.
            return $this->memoised[$panelId] = [];
        }

        return $this->memoised[$panelId] = is_array($decoded) ? $decoded : [];
    }

    public function put(string $panelId, array $settings): void
    {
        $this->files->ensureDirectoryExists($this->directory);

        // Write to a sibling and rename, so a reader during a concurrent write
        // sees either the old file or the new one, never a half-written one.
        $path = $this->path($panelId);
        $temporary = $path . '.' . bin2hex(random_bytes(6)) . '.tmp';

        $this->files->put($temporary, json_encode(
            $settings,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
        ) . PHP_EOL);

        $this->files->move($temporary, $path);

        $this->memoised[$panelId] = $settings;
    }

    public function forget(string $panelId): void
    {
        $this->files->delete($this->path($panelId));

        $this->memoised[$panelId] = [];
    }

    protected function path(string $panelId): string
    {
        // Panel ids reach the filesystem, so reduce them to a safe token
        // rather than trusting them. Two panels whose ids differ only by
        // stripped characters would collide, which a hash suffix prevents.
        $safe = preg_replace('/[^a-zA-Z0-9_-]/', '', $panelId);

        return $this->directory . '/' . $safe . '-' . substr(sha1($panelId), 0, 8) . '.json';
    }
}
