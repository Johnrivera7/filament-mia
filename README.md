<div align="center">

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/mia-avatar-512.png" alt="Mía" width="180" />

# Mía

### A warm editorial theme for Filament

Cream, champagne and espresso, with a high-contrast serif for headings.<br />
An admin panel that reads like a printed page rather than a dashboard.

[![Latest version on Packagist](https://img.shields.io/packagist/v/johnrivera7/filament-mia.svg?style=flat-square&color=D9A14E&labelColor=3C3227)](https://packagist.org/packages/johnrivera7/filament-mia)
[![Total downloads](https://img.shields.io/packagist/dt/johnrivera7/filament-mia.svg?style=flat-square&color=D9A14E&labelColor=3C3227)](https://packagist.org/packages/johnrivera7/filament-mia)
[![Tests](https://img.shields.io/github/actions/workflow/status/Johnrivera7/filament-mia/tests.yml?branch=main&style=flat-square&label=tests&labelColor=3C3227&color=8A9A6B)](https://github.com/Johnrivera7/filament-mia/actions/workflows/tests.yml)
[![License](https://img.shields.io/packagist/l/johnrivera7/filament-mia.svg?style=flat-square&color=D9A14E&labelColor=3C3227)](LICENSE.md)

[![PHP](https://img.shields.io/badge/PHP-8.2%20%E2%80%93%208.4-777BB4?style=flat-square&labelColor=3C3227)](https://www.php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11%20%C2%B7%2012%20%C2%B7%2013-FF2D20?style=flat-square&labelColor=3C3227)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-v5.7%2B-F59E0B?style=flat-square&labelColor=3C3227)](https://filamentphp.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-v4.3-06B6D4?style=flat-square&labelColor=3C3227)](https://tailwindcss.com)

</div>

---

> **Status: in development.** The package scaffolding, configuration API and
> brand are in place. The compiled stylesheet and the screenshots below are
> being finished — see [Roadmap](#roadmap).

## What this is

Most admin themes are a palette swap. Mía is an attempt at a different
*register*: warm, quiet and typographic, where a panel feels considered rather
than utilitarian.

Three things carry it:

**A real editorial voice.** Headings are set in a high-contrast display serif
and content in a geometric humanist sans. Structural labels are small, in caps
and widely tracked. The result is a genuine typographic hierarchy instead of
one weight repeated at three sizes.

**A dark mode that is actually warm.** Not a cool grey inversion — espresso,
taupe and deep umber, built from the same neutral ramp as the light mode, so
the two modes read as one design rather than two.

**Restraint as a feature.** Hairline borders at very low contrast, wide diffuse
shadows tinted with the neutral instead of black, generous corner radii and
slow, gentle motion. Nothing announces itself.

Everything is verified against WCAG AA in both modes, and reported — see
[Accessibility](#accessibility).

## Design direction

| | |
|---|---|
| **Light mode** | Cream and ivory canvases, surfaces barely separated from the page, warm hairline borders |
| **Dark mode** | Espresso and dark taupe, warm rather than technical |
| **Accent** | A soft honey gold, drawn from the brand illustration |
| **Secondary** | A desaturated dusty rose |
| **Type** | High-contrast serif headings over a geometric humanist sans |
| **Shape** | Generous radii, wide low-opacity shadows with a warm tint, ample spacing |
| **Motion** | Slow, delicate easing; discreet hover and focus states; `prefers-reduced-motion` honoured throughout |

## Screenshots

<!-- SCREENSHOTS: replaced with real captures once the stylesheet is final. -->

### Light mode

_Desktop — dashboard_
<!-- ![Mía light mode, desktop dashboard](https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-light-desktop.png) -->

_Desktop — table listing_
<!-- ![Mía light mode, table listing](https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-light-table.png) -->

_Desktop — form_
<!-- ![Mía light mode, form](https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-light-form.png) -->

_Login_
<!-- ![Mía light mode, login](https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-light-login.png) -->

### Dark mode

_Desktop — dashboard_
<!-- ![Mía dark mode, desktop dashboard](https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-dark-desktop.png) -->

_Desktop — table listing_
<!-- ![Mía dark mode, table listing](https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-dark-table.png) -->

_Desktop — form_
<!-- ![Mía dark mode, form](https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-dark-form.png) -->

_Login_
<!-- ![Mía dark mode, login](https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-dark-login.png) -->

### Responsive

_Tablet_
<!-- ![Mía on tablet](https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-tablet.png) -->

_Mobile — navigation_
<!-- ![Mía on mobile, navigation](https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-mobile-nav.png) -->

_Mobile — table_
<!-- ![Mía on mobile, table](https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-mobile-table.png) -->

## Requirements

| | |
|---|---|
| PHP | 8.2 or later |
| Laravel | 11.28, 12 or 13 |
| Filament | 5.7 or later |

The stylesheet ships pre-compiled against Tailwind CSS v4.3. You do **not**
need Tailwind, Node or a build step to use the theme.

## Installation

```bash
composer require johnrivera7/filament-mia
```

Publish the compiled stylesheet to `public/`:

```bash
php artisan filament:assets
```

Register the plugin on your panel:

```php
use JohnRivera7\FilamentMia\MiaTheme;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(MiaTheme::make());
}
```

Add the published asset directory to your `.gitignore`:

```
/public/css/johnrivera7
```

## Configuration

Every option can be set fluently on the plugin, or in a published config file.

```php
->plugin(
    MiaTheme::make()
        ->accentColor('#D9A14E')
        ->secondaryColor('#E4A987')
        ->font('Jost', 'Cormorant Garamond')
        ->roundness('soft')
        ->density('comfortable')
        ->darkMode(true)
)
```

A full option reference is being written up — see [Roadmap](#roadmap).

## Accessibility

Contrast ratios for the default palette are computed with Filament's own colour
maths and published in this README, in both modes. Full table to follow.

## Overridden Filament views

The theme resolves as much as possible with CSS and render hooks. Any Blade
view it does publish is listed here, together with the Filament version it was
copied from, so upgrades stay auditable.

_None yet._

## Troubleshooting

### The theme does not appear at all

If your panel calls `->viteTheme(...)`, remove it. Filament gives `viteTheme`
unconditional precedence over `theme`, regardless of the order in the chain, so
the theme is silently ignored with no error or warning.

## Roadmap

- [x] Package scaffolding and configuration API
- [x] Warm neutral and accent colour ramps, WCAG AA verified
- [ ] Compiled stylesheet
- [ ] Illustrated empty states
- [ ] Loading and skeleton states
- [ ] Screenshots, light and dark, desktop and mobile
- [ ] Full option reference

## Credits

- [John Rivera](https://github.com/Johnrivera7)

## License

The MIT License (MIT). See [LICENSE.md](LICENSE.md).
