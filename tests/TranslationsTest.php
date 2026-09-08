<?php

namespace JohnRivera7\FilamentMia\Tests;

use JohnRivera7\FilamentMia\Settings\Presets;

class TranslationsTest extends TestCase
{
    public function test_both_bundled_languages_carry_the_same_keys(): void
    {
        // A missing key does not fail at runtime: Laravel renders the key
        // itself, so a half-translated page looks like a typo rather than a
        // bug. The parity check is what makes it visible.
        $en = $this->flatten(require __DIR__ . '/../resources/lang/en/customizer.php');
        $es = $this->flatten(require __DIR__ . '/../resources/lang/es/customizer.php');

        $this->assertSame([], array_values(array_diff($en, $es)), 'Keys missing from the Spanish file.');
        $this->assertSame([], array_values(array_diff($es, $en)), 'Keys missing from the English file.');
    }

    public function test_the_preset_labels_come_from_the_language_files(): void
    {
        app()->setLocale('es');

        $descriptions = Presets::descriptions();

        // The preset names are proper nouns and stay as they are; their
        // descriptions are copy and have to travel.
        $this->assertStringContainsString('Oro miel', $descriptions['mia']);

        app()->setLocale('en');

        $this->assertStringContainsString('Honey gold', Presets::descriptions()['mia']);
    }

    /**
     * @param  array<string, mixed>  $translations
     * @return array<int, string>
     */
    protected function flatten(array $translations, string $prefix = ''): array
    {
        $keys = [];

        foreach ($translations as $key => $value) {
            $key = $prefix === '' ? (string) $key : "{$prefix}.{$key}";

            $keys = [
                ...$keys,
                ...(is_array($value) ? $this->flatten($value, $key) : [$key]),
            ];
        }

        sort($keys);

        return $keys;
    }
}
