<div align="center">

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/mia-avatar-512.png" alt="" width="132" />

# Mía

### A warm editorial theme for Filament v5

For panels that are part of the product rather than an afterthought behind a<br />
login. Cream and champagne in the light, espresso in the dark, serif headings.

[![Status](https://img.shields.io/badge/status-v0.x%20%C2%B7%20active%20development-D9A14E?style=flat-square&labelColor=3C3227)](#project-status)
[![License](https://img.shields.io/badge/license-MIT-D9A14E?style=flat-square&labelColor=3C3227)](LICENSE.md)
[![PHP](https://img.shields.io/badge/PHP-8.2%20%E2%80%93%208.5-777BB4?style=flat-square&labelColor=3C3227)](https://www.php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11%20%C2%B7%2012%20%C2%B7%2013-FF2D20?style=flat-square&labelColor=3C3227)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-v5.7%2B-F59E0B?style=flat-square&labelColor=3C3227)](https://filamentphp.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-v4.3-06B6D4?style=flat-square&labelColor=3C3227)](https://tailwindcss.com)
[![WCAG](https://img.shields.io/badge/contrast-WCAG%20AA-8A9A6B?style=flat-square&labelColor=3C3227)](#accessibility)

</div>

## What this is

Mía is not a palette swap. Feeding an accent colour into Filament's defaults
changes the hue and leaves the rest of the design intact. Here every surface is
set by hand — type scale, spacing, hierarchy, borders, shadows, motion, focus
rings, empty states — and the stylesheet rewrites Filament's component layer
rather than tinting it. Three things carry the result.

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

_Being retaken from the bundled demo application. See [Roadmap](#where-this-is-going)._

## Project status

Mía is a young project in active development, currently in the `0.x` series.

What that means in practice. The theme is complete and usable: light and dark
mode are both finished, the configuration API is stable enough to build on, the
stylesheet ships pre-compiled and the suite runs against PHP 8.2 through 8.5.
What it does not mean is a frozen surface. Until `1.0` the option names, the
CSS custom properties and the set of restyled components may still change, and
minor releases can carry breaking changes. Each one is listed in the
[changelog](CHANGELOG.md).

Iteration is frequent and feedback shapes it. If a component looks wrong in
your panel, or a token you need is not exposed, open an issue — that is the
fastest way to influence what lands next.

## Requirements

| | |
|---|---|
| PHP | 8.2 – 8.5 |
| Laravel | 11.28, 12 or 13 |
| Filament | 5.7 or later |

PHP 8.5 is supported, not required. The constraint is `^8.2`, the lowest
version Filament v5 itself accepts, so the package installs on the PHP most
Laravel projects are still running. Note that Laravel 13 requires PHP 8.3 or
later independently of this package.

PHP 8.5 support is verified rather than assumed: the full test suite and a
live panel render were both exercised under PHP 8.5.8 with
`error_reporting=-1`, failing on any deprecation, notice or warning originating
in the package. Deprecations raised inside Laravel or Filament are ignored,
since they say nothing about this package.

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

### Options at a glance

| Method | Default | Config key |
|---|---|---|
| [`accentColor()`](#accentcolor) | `#D9A14E` | `colors.accent` |
| [`secondaryColor()`](#secondarycolor) | `#E4A987` | `colors.secondary` |
| [`neutralColor()`](#neutralcolor) | `null` — the theme's warm ramp | `colors.neutral` |
| [`statusColors()`](#statuscolors) | warmed defaults | `colors.danger` `colors.info` `colors.success` `colors.warning` |
| [`font()`](#font) | `Jost`, `Cormorant Garamond` | `fonts.sans` `fonts.serif` |
| [`monoFont()`](#monofont) | `null` — the system stack | `fonts.mono` |
| [`serifHeadings()`](#serifheadings) | `true` | `typography.serif_headings` |
| [`roundness()`](#roundness) | `soft` | `roundness` |
| [`density()`](#density) | `comfortable` | `density` |
| [`elevation()`](#elevation) | `1.0` | `elevation` |
| [`motion()`](#motion) | `true` | `motion` |
| [`darkMode()`](#darkmode) | `true` | `dark_mode` |
| [`sidebarWidth()`](#sidebarwidth) | `17rem` | `sidebar_width` |
| [`viteStylesheets()`](#vitestylesheets) | `[]` | `vite_stylesheets` `vite_build_directory` |
| [`customizer()`](#customizer) | `false` | `customizer.enabled` |
| [`customizerAuthorization()`](#customizerauthorization) | `null` — anyone who can reach the panel | — |
| [`customizerNavigation()`](#customizernavigation) | ungrouped | `customizer.navigation_group` `customizer.navigation_sort` |

### Reference

Everywhere a colour is accepted, the accepted formats are hex (`#RGB` or
`#RRGGBB`), `rgb(r, g, b)`, a bare `r, g, b` triplet, or `oklch(l c h)`.
Anything else throws `InvalidThemeOption` at boot.

#### `accentColor()`

```php
public function accentColor(string $color): static
```

The accent that carries buttons, links, focus rings and active states.

```php
MiaTheme::make()->accentColor('#C9A227')
```

#### `secondaryColor()`

```php
public function secondaryColor(string $color): static
```

A supporting colour, available to any component as `->color('secondary')`.

```php
MiaTheme::make()->secondaryColor('#E8C4C0')
```

#### `neutralColor()`

```php
public function neutralColor(?string $color): static
```

Page backgrounds, surfaces, borders and body copy are all built from the
neutral. `null` keeps the theme's curated warm ramp, which is tuned so light
mode reads as cream and dark mode as espresso — replace it only if you want a
different temperature throughout.

```php
MiaTheme::make()->neutralColor('#8A7D6D')
```

#### `statusColors()`

```php
public function statusColors(
    ?string $danger = null,
    ?string $info = null,
    ?string $success = null,
    ?string $warning = null,
): static
```

Status colours, warmed by default so they sit inside the palette instead of
cutting across it with stock blues and greens. Any argument left out keeps its
default. Use named arguments.

```php
MiaTheme::make()->statusColors(danger: '#C1614F', success: '#8A9A6B')
```

#### `font()`

```php
public function font(string $sans, ?string $serif = null): static
```

The interface sans, and optionally the display serif. Families are served from
[Bunny Fonts](https://fonts.bunny.net), which sets no cookies and logs no IP
addresses. Give plain family names as spelled there, without quotes or CSS
fallbacks.

```php
MiaTheme::make()->font('Outfit', 'Fraunces')
```

#### `monoFont()`

```php
public function monoFont(?string $family): static
```

An optional monospace family. `null` keeps the system stack.

```php
MiaTheme::make()->monoFont('JetBrains Mono')
```

#### `serifHeadings()`

```php
public function serifHeadings(bool $condition = true): static
```

Whether page, modal, brand and empty-state headings use the serif family.
`false` keeps the whole interface in the sans, for a quieter panel.

```php
MiaTheme::make()->serifHeadings(false)
```

#### `roundness()`

```php
public function roundness(Roundness|string $roundness): static
```

Accepts `sharp`, `subtle`, `soft` or `round`, or the matching
`Roundness` enum case. Applied over Tailwind's own `--radius-*` scale, so it
retunes every `rounded-*` utility already compiled into the stylesheet.

```php
use JohnRivera7\FilamentMia\Enums\Roundness;

MiaTheme::make()->roundness(Roundness::Sharp)
```

#### `density()`

```php
public function density(Density|string $density): static
```

Accepts `compact`, `comfortable` or `spacious`, or the matching `Density` enum
case. Scales padding, gaps and table row height together.

```php
MiaTheme::make()->density('compact')
```

#### `elevation()`

```php
public function elevation(float $scale): static
```

Multiplier for the shadow system, from `0.0` to `2.0`. Shadows stay wide,
diffuse and tinted with the neutral rather than black at any value. `0.0`
declares no shadow at all, for a completely flat interface.

```php
MiaTheme::make()->elevation(0.0)
```

#### `motion()`

```php
public function motion(bool $condition = true): static
```

Entry animations and hover micro-interactions. Independent of
`prefers-reduced-motion`, which the theme always honours regardless of this
setting.

```php
MiaTheme::make()->motion(false)
```

#### `darkMode()`

```php
public function darkMode(bool $condition = true): static
```

Whether the panel offers the light and dark switch.

```php
MiaTheme::make()->darkMode(false)
```

#### `sidebarWidth()`

```php
public function sidebarWidth(string $width): static
```

Any CSS length. The default `17rem` is narrower than Filament's `20rem`, which
crowds the content column on smaller laptops.

```php
MiaTheme::make()->sidebarWidth('19rem')
```

#### `viteStylesheets()`

```php
public function viteStylesheets(string|array $paths, ?string $buildDirectory = null): static
```

One or more of your application's own Vite entrypoints, loaded after the theme
and alongside it. See [Tailwind utilities in your own
views](#tailwind-utilities-in-your-own-views) for why this exists and what to
put in the file.

```php
MiaTheme::make()->viteStylesheets('resources/css/filament/admin/utilities.css')
```

#### `customizer()`

```php
public function customizer(bool $condition = true): static
```

Adds the [appearance page](#the-appearance-page) to the panel. Off by default.

```php
MiaTheme::make()->customizer()
```

#### `customizerAuthorization()`

```php
public function customizerAuthorization(Closure $callback): static
```

Decides who may open the appearance page. Without it, anyone who can reach the
panel can open it, and what they save applies to everyone. The callback runs on
every navigation build, so keep it cheap.

```php
MiaTheme::make()
    ->customizer()
    ->customizerAuthorization(fn (): bool => auth()->user()?->isAdmin() ?? false)
```

#### `customizerNavigation()`

```php
public function customizerNavigation(
    string|UnitEnum|null $group = null,
    ?int $sort = null,
    string|BackedEnum|null $icon = null,
): static
```

Where the page sits in the navigation. The icon defaults to a swatch.

```php
MiaTheme::make()
    ->customizer()
    ->customizerNavigation(group: 'Settings', sort: 90)
```

## The appearance page

An optional page inside the panel for editing the theme and saving the result,
for the case where whoever decides how the panel looks is not the person who
deploys it.

It edits accent, secondary and status colours; the interface and heading
families, from a checked list of Bunny Fonts families; roundness, density and
elevation; and it carries five presets, including the theme as shipped. A
specimen of the components the settings affect most sits below the form.

Turn it on with [`customizer()`](#customizer) and restrict it with
[`customizerAuthorization()`](#customizerauthorization).

```php
->plugin(
    MiaTheme::make()
        ->customizer()
        ->customizerAuthorization(fn (): bool => auth()->user()?->isAdmin() ?? false)
        ->customizerNavigation(group: 'Settings'),
)
```

### The preview is the result

Every control writes a custom property the compiled stylesheet already reads,
and the same code paints the preview and the saved panel, so what is on screen
before saving is what the panel becomes after. Nothing in the page can generate
a Tailwind class — that is the constraint that makes a pre-compiled theme
configurable at all.

### Where the settings are stored, and for whom

**Per panel, shared by everyone who uses it.** How a panel looks is a property
of the panel, in the same way its logo is; it is not a per-user preference. Two
panels in one application keep separate records.

The exception is light and dark mode, which Filament already stores per browser
and which the page only offers as a way to preview both.

Records are written as JSON under `storage/app/filament-mia/`, one file per
panel. That is the default because it needs no migration: the package installs
into an existing project and works. The trade-off is local disk, so on several
application servers, or on a platform with an ephemeral filesystem, bind your
own repository:

```php
use JohnRivera7\FilamentMia\Settings\Contracts\SettingsRepository;

$this->app->singleton(SettingsRepository::class, DatabaseSettingsRepository::class);
```

The contract is three methods — `get()`, `put()` and `forget()`, all keyed by
panel id — so storing settings per user, per tenant or in a shared cache is a
matter of implementing it.

### Precedence

**A saved record wins over both the config file and the fluent API.** It has to:
it is the most recent deliberate decision, and a panel that ignored what an
administrator just saved would be broken. The page's reset action discards the
record and returns the panel to your code.

A saved record applies whether or not the page is currently enabled, so turning
the page off freezes the appearance rather than reverting it. A record that has
been hand-edited into an invalid state is ignored rather than thrown, so a bad
value cannot lock you out of the page that would fix it.

### How the options reach the browser

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



### Custom properties

These are part of the theme's public surface. Read them to make your own
components match, or redeclare them on `.fi-body` to adjust something the
options do not cover. All are set for both colour modes.

| Property | What it holds |
|---|---|
| `--mia-canvas` | The page background |
| `--mia-surface` | Cards, tables, modals |
| `--mia-surface-sunken` | Recessed areas — modal footers, fieldsets |
| `--mia-surface-raised` | Floating surfaces — dropdowns, notifications |
| `--mia-ink` | Body copy and headings |
| `--mia-ink-muted` | Secondary and supporting text |
| `--mia-hairline` | Decorative borders and dividers |
| `--mia-hairline-strong` | Borders on interactive controls, which must clear 3:1 |
| `--mia-accent-wash` | The tinted background of active and selected states |
| `--mia-accent-line` | The accent at border strength |
| `--mia-radius-xs` … `--mia-radius-3xl` | The radius scale, set by `roundness()` |
| `--mia-density` | The spacing multiplier, set by `density()` |
| `--mia-row-height` | Minimum table row height |
| `--mia-section-gap` | Vertical rhythm between sections |
| `--mia-shadow-sm` … `--mia-shadow-xl` | The shadow scale, set by `elevation()` |
| `--mia-ease` | The theme's easing curve |
| `--mia-duration` | Standard transition duration |
| `--mia-duration-slow` | Slower transitions, for larger movements |
| `--mia-anim-duration` | Entry animations |
| `--mia-heading-font` | The heading family, set by `serifHeadings()` |
| `--mia-heading-weight` | Heading weight |
| `--mia-heading-tracking` | Heading letter-spacing |

Reference them with a fallback, as the theme's own stylesheet does, so your
component still renders if a property is ever renamed:

```css
.my-panel {
    background-color: var(--mia-surface);
    border: 1px solid var(--mia-hairline);
    border-radius: var(--mia-radius-2xl, 1.25rem);
    box-shadow: var(--mia-shadow-md);
    transition: box-shadow var(--mia-duration, 260ms) var(--mia-ease, ease);
}
```

### Skeleton placeholders

The theme also publishes one class, for loading states in your own views. It
carries the warm shimmer used by Filament's deferred sections, and stops
animating under `prefers-reduced-motion`:

```blade
<div class="fi-skeleton" style="height: 1rem; width: 60%"></div>
```

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

The test suite runs with `error_reporting=-1` and fails on any deprecation,
notice or warning raised by the package, with those from Laravel and Filament
ignored — a dependency's deprecation says nothing about this package.

One gap worth naming: the suite does not render the appearance page. Rendering
any Filament page under Orchestra Testbench currently fails inside Livewire's
validation support, for Filament's own shipped pages as much as for this one,
so a test there would report on the harness rather than on the package. The
page's wiring, storage, precedence and preview output are covered; the rendered
page is checked by hand against a running application.

Three conventions keep the repository honest:

- **`resources/dist/mia.css` is rebuilt and committed on its own.** It is
  committed so the package installs without a build step, and marked as
  generated in `.gitattributes` so it stays out of diffs. Rebuild it with
  `npm run build` and commit it in a change of its own, never mixed with the
  source CSS that produced it.
- **A change to the public surface updates the README and the changelog in the
  same commit.** Any new or altered option, chainable method, published custom
  property, overridden view, requirement or command belongs in the diff that
  introduces it. This README is the product page, and documenting afterwards
  reliably leaves options undocumented and examples that no longer match the
  code.
- **Every image comes from the demo application.** Captures are named `art/demo-*`
  and are produced against invented data. `.gitignore` blocks the other
  filenames screenshots tend to get, because an image of a real system can
  carry personal data and a blob pushed to a public repository stays reachable
  by SHA long after the file is deleted.

## Where this is going

Mía starts as a theme. The direction is a design system for Filament: the
stylesheet is the first layer, not the whole of it.

Concretely, what is in and what is not.

**Today.** A pre-compiled stylesheet, a configuration API for colour,
typography, roundness, density and elevation, warm light and dark modes,
illustrated empty states, loading states, measured contrast, and an in-panel
appearance page that edits and persists all of it.

**Next.** A demo application that doubles as the source of every screenshot.
Presets shipped as named palettes beyond the five the appearance page carries.

**Later, and deliberately vaguer because it is not built.** Blade components
that use the tokens directly, for building custom pages that match the panel.
Exporting a saved appearance back out as configuration, so a look tuned in one
environment can be committed. Coverage for the Filament plugins that carry
their own UI.

Dates are not promised. The `0.x` series is where this gets worked out in the
open; see [Project status](#project-status).

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

## Credits

- [John Rivera](https://github.com/Johnrivera7)

## License

The MIT License (MIT). See [LICENSE.md](LICENSE.md).
