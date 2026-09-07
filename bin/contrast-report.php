<?php

/**
 * Prints the theme's OKLCH ramps and their WCAG contrast ratios.
 *
 * Uses Filament's own colour maths, so the numbers match what the browser is
 * served at runtime. Run from an application that has Filament installed:
 *
 *   php bin/contrast-report.php /path/to/app/vendor/autoload.php
 */

use Filament\Support\Colors\Color;
use JohnRivera7\FilamentMia\Support\Palette;

require $argv[1] ?? __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/Support/Palette.php';

$brand = [
    'canvas cream' => '#FAF2E7',
    'warm cream' => '#FAE6CC',
    'pale champagne' => '#F8E3C7',
    'champagne' => '#EFC189',
    'gold leaf' => '#DFB375',
    'honey brass' => '#D9A14E',
    'deep gold' => '#B88447',
    'dusty rose' => '#E4A987',
    'terracotta' => '#CF8253',
];

echo "=== Brand colours sampled from the avatar ===\n";

foreach ($brand as $name => $hex) {
    printf("%-18s %s  %s\n", $name, $hex, Color::convertToOklch($hex));
}

$accent = $argv[2] ?? '#D9A14E';
$secondary = $argv[3] ?? '#E4A987';

$ramps = [
    'gray (warm neutral)' => Palette::neutral(),
    'primary (accent)' => Palette::from($accent),
    'secondary (rose)' => Palette::from($secondary),
    'danger' => Palette::from('#C1614F'),
    'info' => Palette::from('#8B9FB0'),
    'success' => Palette::from('#8A9A6B'),
    'warning' => Palette::from('#D9A441'),
];

foreach ($ramps as $name => $ramp) {
    echo "\n=== {$name} ===\n";

    foreach ($ramp as $shade => $value) {
        printf("%4d  %-28s %s\n", $shade, $value, Color::convertToHex($value));
    }
}

$gray = $ramps['gray (warm neutral)'];
$primary = $ramps['primary (accent)'];

// The surface the theme paints where Filament would paint pure white.
$ivory = Color::convertToHex('oklch(0.988 0.005 80)');

$pairs = [
    // Light mode
    'LIGHT body text on canvas' => [$gray[950], $gray[50]],
    'LIGHT body text on surface' => [$gray[950], $ivory],
    'LIGHT muted text on canvas' => [$gray[500], $gray[50]],
    'LIGHT muted text on surface' => [$gray[500], $ivory],
    'LIGHT link/accent text on surface' => [$primary[600], $ivory],
    'LIGHT white text on accent button' => ['#ffffff', $primary[600]],

    // Dark mode
    'DARK body text on card' => [$ivory, $gray[900]],
    'DARK body text on canvas' => [$ivory, $gray[950]],
    'DARK muted text on card' => [$gray[400], $gray[900]],
    'DARK muted text on canvas' => [$gray[400], $gray[950]],
    'DARK link/accent text on card' => [$primary[400], $gray[900]],
];

/*
 * WCAG 1.4.11 applies to user interface components, so input borders and focus
 * indicators are held to 3:1. Container dividers are decorative and are
 * reported separately: this theme keeps them deliberately faint, which is a
 * design decision rather than a conformance failure.
 */
$nonText = [
    'LIGHT input border on surface' => [$gray[500], $ivory],
    'LIGHT focus ring on surface' => [$primary[600], $ivory],
    'LIGHT focus ring on canvas' => [$primary[600], $gray[50]],
    'DARK input border on card' => [$gray[400], $gray[900]],
    'DARK focus ring on card' => [$primary[400], $gray[900]],
    'DARK focus ring on canvas' => [$primary[400], $gray[950]],
];

$decorative = [
    'LIGHT divider on canvas' => [$gray[300], $gray[50]],
    'LIGHT divider on surface' => [$gray[300], $ivory],
    'DARK divider on card' => [$gray[700], $gray[900]],
];

echo "\n=== WCAG 2.1 contrast ratios ===\n\n";

$failures = 0;

$report = function (array $set, float $threshold, string $heading) use (&$failures): void {
    echo "{$heading}\n";

    foreach ($set as $label => [$fg, $bg]) {
        $ratio = Color::calculateContrastRatio($fg, $bg);
        $passes = $ratio >= $threshold;
        $failures += $passes ? 0 : 1;

        printf("  %-36s %6.2f:1  %s\n", $label, $ratio, $passes ? 'PASS' : 'FAIL');
    }

    echo "\n";
};

$report($pairs, Color::WCAG_AA_TEXT, 'Text — WCAG AA requires 4.5:1');
$report($nonText, Color::WCAG_AA_NON_TEXT, 'UI components — WCAG AA requires 3.0:1');

echo "Decorative dividers — no WCAG requirement, listed for reference\n";

foreach ($decorative as $label => [$fg, $bg]) {
    printf("  %-36s %6.2f:1\n", $label, Color::calculateContrastRatio($fg, $bg));
}

echo "\n";
echo $failures === 0
    ? "All text and UI-component pairs meet WCAG AA.\n"
    : "{$failures} pair(s) below WCAG AA.\n";

exit($failures === 0 ? 0 : 1);
