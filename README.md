<div align="center">

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/mia-avatar-512.png" alt="" width="132" />

# Mía

### A warm editorial theme for Filament

Cream and champagne in the light, espresso in the dark, with a high-contrast<br />
serif for headings. An admin panel that reads like a printed page.

[![License](https://img.shields.io/badge/license-MIT-D9A14E?style=flat-square&labelColor=3C3227)](LICENSE.md)
[![PHP](https://img.shields.io/badge/PHP-8.2%20%E2%80%93%208.4-777BB4?style=flat-square&labelColor=3C3227)](https://www.php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11%20%C2%B7%2012%20%C2%B7%2013-FF2D20?style=flat-square&labelColor=3C3227)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-v5.7%2B-F59E0B?style=flat-square&labelColor=3C3227)](https://filamentphp.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-v4.3-06B6D4?style=flat-square&labelColor=3C3227)](https://tailwindcss.com)
[![WCAG](https://img.shields.io/badge/contrast-WCAG%20AA-8A9A6B?style=flat-square&labelColor=3C3227)](#accessibility)

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/cover.jpg" alt="Mía in light and dark mode" width="100%" />

</div>

## What this is

Most admin themes are a palette swap. Mía is an attempt at a different
*register*: warm, quiet and typographic, where a panel feels considered rather
than utilitarian. Three things carry it.

**A real editorial voice.** Headings are set in a high-contrast display serif
and content in a geometric humanist sans. Column headings, group labels and
stat captions are small, in caps and widely tracked. The result is a genuine
typographic hierarchy instead of one weight repeated at three sizes.

**A dark mode that is actually warm.** Not a cool grey inversion — espresso,
taupe and deep umber, built from the same neutral ramp as the light mode, so
the two read as one design rather than two.

**Restraint as a feature.** Hairline borders at very low contrast, wide diffuse
shadows tinted with the neutral rather than black, generous radii, and motion
slow enough to feel deliberate. Empty states carry an illustration drawn for
the theme instead of a stock outline icon.

It ships pre-compiled. There is no Node, Tailwind or build step to install.

## Screenshots

### Light

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-light-table.png" alt="A table listing in light mode" width="100%" />

<details open>
<summary><b>More light mode</b></summary>
<br />

|  |  |
|---|---|
| <img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-light-login.png" alt="The sign-in screen in light mode" /> | <img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-light-form.png" alt="A form in light mode" /> |
| **Sign in** — a washed canvas, serif heading and accent rule | **Forms** — flat fields with hairline borders, no rings |
| <img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-light-empty.png" alt="An illustrated empty state in light mode" /> | <img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-light-detail.png" alt="A detail page in light mode" /> |
| **Empty states** — an illustration drawn for the theme | **Detail pages** — caps labels over tabular figures |

</details>

### Dark

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-dark-table.png" alt="A table listing in dark mode" width="100%" />

<details open>
<summary><b>More dark mode</b></summary>
<br />

|  |  |
|---|---|
| <img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-dark-login.png" alt="The sign-in screen in dark mode" /> | <img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-dark-form.png" alt="A form in dark mode" /> |
| **Sign in** | **Forms** |
| <img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-dark-empty.png" alt="An illustrated empty state in dark mode" /> | <img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-dark-detail.png" alt="A detail page in dark mode" /> |
| **Empty states** | **Detail pages** |

</details>

### Tablet and mobile

<div align="center">
<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-light-tablet.png" alt="Mía on a tablet" width="40%" />
<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-light-mobile-nav.png" alt="The navigation drawer on mobile" width="19%" />
<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-light-mobile.png" alt="A table on mobile" width="19%" />
</div>

## Requirements

| | |
|---|---|
| PHP | 8.2 or later |
| Laravel | 11.28, 12 or 13 |
| Filament | 5.7 or later |

The stylesheet is compiled with Tailwind CSS v4.3 and committed to the
repository. Tailwind is a development dependency of this package only — your
application never needs it.

## Installation

```bash
composer require johnrivera7/filament-mia
```

<details>
<summary>Not on Packagist yet — installing from the repository</summary>
<br />

Until the package is submitted to Packagist, add it as a VCS repository first:

```bash
composer config repositories.filament-mia vcs https://github.com/Johnrivera7/filament-mia
composer require johnrivera7/filament-mia
```

</details>

Publish the compiled stylesheet into `public/`:

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

Then add the published directory to your `.gitignore`, since it is build output
that `filament:assets` regenerates:

```gitignore
/public/css/johnrivera7
```

> **If your panel calls `->viteTheme(...)`, remove it.** Filament gives
> `viteTheme` unconditional precedence over `theme`, so the theme is silently
> ignored while it is there. See [Troubleshooting](#troubleshooting).

## Configuration

Every option is available fluently on the plugin, and as a default in a config
file. The fluent call wins.

```php
->plugin(
    MiaTheme::make()
        ->accentColor('#C9A227')
        ->secondaryColor('#E8C4C0')
        ->font('Jost', 'Cormorant Garamond')
        ->roundness('soft')
        ->density('comfortable')
        ->elevation(0.75)
        ->motion()
        ->darkMode(),
)
```

To set project-wide defaults instead:

```bash
php artisan vendor:publish --tag=filament-mia-config
```

### Options

| Method | Default | Accepts |
|---|---|---|
| `accentColor()` | `#D9A14E` | Hex, `rgb()`, an `r, g, b` triplet or `oklch()` |
| `secondaryColor()` | `#E4A987` | as above |
| `neutralColor()` | the theme's warm ramp | as above, or `null` to keep it |
| `statusColors()` | warmed defaults | Named arguments: `danger:` `info:` `success:` `warning:` |
| `font()` | `Jost`, `Cormorant Garamond` | Sans, then optional serif — family names as spelled on [Bunny Fonts](https://fonts.bunny.net) |
| `monoFont()` | the system stack | A family name, or `null` |
| `serifHeadings()` | `true` | `bool` — `false` keeps headings in the sans |
| `roundness()` | `soft` | `sharp` `subtle` `soft` `round` |
| `density()` | `comfortable` | `compact` `comfortable` `spacious` |
| `elevation()` | `1.0` | `0.0`–`2.0`; `0` is completely flat |
| `motion()` | `true` | `bool` — independent of `prefers-reduced-motion` |
| `darkMode()` | `true` | `bool` |
| `sidebarWidth()` | `17rem` | Any CSS length |
| `viteStylesheets()` | none | See [below](#tailwind-utilities-in-your-own-views) |

Colours are resolved per request and emitted as OKLCH custom properties, and
non-colour settings as `--mia-*` properties scoped to `.fi-panel-{id}`. Nothing
here requires a rebuild, and two panels in one application can be configured
differently.

The panel below is the same page, and the same compiled stylesheet, as the
light mode screenshot above — reconfigured with nothing but a fluent call:

```php
MiaTheme::make()
    ->accentColor('#7C6A9C')
    ->roundness('sharp')
    ->density('compact')
    ->elevation(0.0)
    ->serifHeadings(false)
```

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/screenshot-light-variant.png" alt="The same panel reconfigured with a violet accent, sharp corners, compact density and no shadows" width="100%" />

A colour you pass is expanded into an eleven-shade ramp that preserves its hue
*and* its saturation character, so an understated colour stays understated
rather than being pushed to full chroma.

Invalid input throws `InvalidThemeOption` at boot. This is deliberate:
Filament converts colours without validating them, so an unparseable value
would otherwise produce a black palette and no error at all.

### Tailwind utilities in your own views

A pre-compiled theme can only contain the utility classes Filament itself uses.
It cannot know about classes in *your* Blade views, because those files do not
exist when the theme is built. If you write Tailwind utilities in your own
Filament views, compile them yourself and hand the entrypoint over:

```php
->plugin(
    MiaTheme::make()->viteStylesheets('resources/css/filament/admin/utilities.css'),
)
```

```css
/* resources/css/filament/admin/utilities.css */
@import 'tailwindcss/theme.css' layer(theme);
@import 'tailwindcss/utilities.css' layer(utilities);

@source '../../../../app/Filament';
@source '../../../../resources/views/filament';
```

Only the theme and utility layers are imported: pulling in the whole of
`tailwindcss` would re-apply Preflight on top of the theme's base layer. The
stylesheet is emitted after the theme and alongside it — do not use
`Panel::viteTheme()` for this, as it would replace the theme outright.

## Accessibility

Contrast is computed with Filament's own colour maths and checked in the test
suite, so a change to the ramps that broke accessibility fails the build.

| Pair | Light | Dark |
|---|---|---|
| Body text on the page | 18.97:1 | 19.58:1 |
| Body text on a card | 19.58:1 | 17.03:1 |
| Muted text on a card | 4.79:1 | 7.29:1 |
| Link and accent text on a card | 4.88:1 | 9.07:1 |
| Button label on the accent | 5.04:1 | 5.04:1 |
| Input border on a card | 4.79:1 | 7.29:1 |
| Focus ring on a card | 4.88:1 | 9.07:1 |

Text pairs clear the 4.5:1 that WCAG AA asks of body copy, and controls clear
the 3:1 that WCAG 1.4.11 asks of user interface components. Decorative
hairlines are deliberately below that: they carry no information, and WCAG
1.4.11 explicitly exempts elements that do not.

Beyond contrast:

- **Focus is always visible**, drawn as two layers — an inner ring in the
  surface colour and an outer ring in the accent — so it stays legible on light
  surfaces, dark surfaces and coloured buttons alike.
- **Row actions are never hidden until hover.** They rest at reduced opacity
  instead, because hiding them removes them from keyboard navigation and puts
  them out of reach on touch entirely.
- **`prefers-reduced-motion` is honoured** for every animation and transition
  the theme adds. Durations collapse to `0.01ms` rather than to zero, so
  `transitionend` still fires and Alpine's transitions continue to resolve.
- **Numbers use tabular figures** with slashed zeroes, so numeric columns line
  up and `0` and `O` stay distinguishable.

## Overridden Filament views

**None.** The theme is implemented entirely in CSS and one render hook
(`PanelsRenderHook::STYLES_AFTER`, used to emit the runtime custom properties).
No Blade view is published or replaced, so Filament upgrades cannot silently
revert to an old copy of a framework template.

## Troubleshooting

### The theme has no effect at all

Check for `->viteTheme(...)` on the panel and remove it. Filament resolves the
active theme by returning `viteTheme` first if it is set, regardless of where
either call appears in the chain, so it overrides `->theme()` with no error or
warning. A plugin theme and `viteTheme` cannot coexist.

If you need your own Tailwind utilities as well, use
[`viteStylesheets()`](#tailwind-utilities-in-your-own-views), which loads them
alongside the theme rather than instead of it.

### Some of my own components lost their styling

Your views are using Tailwind utilities that the pre-compiled theme does not
contain. See [Tailwind utilities in your own
views](#tailwind-utilities-in-your-own-views).

### The stylesheet does not update after upgrading

`php artisan filament:assets` copies the file into `public/`, and Filament
serves it with a version query string. Re-run it after every `composer update`,
and add it to your deployment script:

```bash
php artisan filament:assets
```

### Headings are not in the serif

Confirm the family is available on [Bunny Fonts](https://fonts.bunny.net) and
spelled as it is there. `->font('Jost', 'Fraunces')` takes plain family names,
without quotes or CSS fallbacks, and rejects anything else at boot. Pass
`->serifHeadings(false)` to keep headings in the sans deliberately.

## Development

```bash
composer install
npm install

npm run build     # compile resources/dist/mia.css
npm run dev       # rebuild on change
composer test     # run the test suite
composer lint     # apply the code style
```

`resources/dist/mia.css` is committed so the package installs without a build
step, and is marked as generated in `.gitattributes` to keep it out of diffs.
Rebuild and commit it in a separate commit whenever the source CSS changes.

## Credits

- [John Rivera](https://github.com/Johnrivera7)

## License

The MIT License (MIT). See [LICENSE.md](LICENSE.md).
