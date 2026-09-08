<div align="center">

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/mia-avatar-512.png" alt="" width="132" />

# Mía

### A warm editorial theme for Filament v5

For panels that are part of the product rather than an afterthought behind a<br />
login. Cream and champagne in the light, espresso in the dark, serif headings.

[![Status](https://img.shields.io/badge/status-v0.x%20%C2%B7%20active%20development-D9A14E?style=flat-square&labelColor=3C3227)](#project-status)
[![License](https://img.shields.io/badge/license-MIT-D9A14E?style=flat-square&labelColor=3C3227)](LICENSE.md)
[![PHP](https://img.shields.io/badge/PHP-8.4%20%E2%80%93%208.5-777BB4?style=flat-square&labelColor=3C3227)](https://www.php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11%20%C2%B7%2012%20%C2%B7%2013-FF2D20?style=flat-square&labelColor=3C3227)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-v5.7%2B-F59E0B?style=flat-square&labelColor=3C3227)](https://filamentphp.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-v4.3-06B6D4?style=flat-square&labelColor=3C3227)](https://tailwindcss.com)
[![WCAG](https://img.shields.io/badge/contrast-WCAG%20AA-8A9A6B?style=flat-square&labelColor=3C3227)](#accessibility)
[![Languages](https://img.shields.io/badge/languages-en%20%C2%B7%20es-8B9FB0?style=flat-square&labelColor=3C3227)](#languages)

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

Every image on this page comes from the [preview panel](#looking-at-it-locally)
bundled with the package, on invented records seeded from a fixed number. Clone
the repository and two commands reproduce the lot — including this page's
frames, which is the point: a gallery that needs a separate application to
regenerate stops matching the code it advertises.

### Before signing in

The sign-in screen is the only part of a panel a visitor sees without an
account, so the theme ships five stagings of it. They differ in composition,
not in identity — [pick one](#sign-in-compositions) in a line of configuration
or from the appearance page.

<table>
<tr>
<td width="50%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-card-light-desktop.jpg" alt="The centred card composition in light mode" /></td>
<td width="50%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-split-light-desktop.jpg" alt="The split stage composition in light mode" /></td>
</tr>
<tr>
<td><b>Centred card</b><br />A single card on the canvas, over two soft pools of warm light.</td>
<td><b>Split stage</b><br />Two columns, one of them brand territory in a deep warm field.</td>
</tr>
<tr>
<td><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-bleed-light-desktop.jpg" alt="The full bleed composition in light mode" /></td>
<td><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-editorial-light-desktop.jpg" alt="The editorial composition in light mode" /></td>
</tr>
<tr>
<td><b>Full bleed</b><br />A warm field to every edge, the panel laid out across the screen on frosted glass.</td>
<td><b>Editorial</b><br />Asymmetric and print-like, with the facing side left as air.</td>
</tr>
<tr>
<td><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-portal-light-desktop.jpg" alt="The portal composition in light mode" /></td>
<td><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-split-dark-desktop.jpg" alt="The split stage composition in dark mode" /></td>
</tr>
<tr>
<td><b>Portal</b><br />A narrow, tall column with a brand medallion and no card edge.</td>
<td><b>Dark mode</b><br />The same composition in the warm dark palette.</td>
</tr>
</table>

Every composition holds its shape in the states that actually happen — a
validation error, a phone, a two-step challenge:

<table>
<tr>
<td width="25%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-split-light-mobile.jpg" alt="The split stage composition on a phone, with the brand column folded into a banner" /></td>
<td width="37%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-card-light-desktop-error.jpg" alt="The centred card composition showing a validation error" /></td>
<td width="38%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-two-step-split-dark.jpg" alt="The two-step challenge in the split stage composition, dark mode" /></td>
</tr>
<tr>
<td><b>Folded</b><br />The brand column becomes a banner.</td>
<td><b>Wrong credentials</b><br />The message takes a line; nothing reflows.</td>
<td><b>Two steps</b><br />The challenge keeps the staging.</td>
</tr>
</table>

### Inside the panel

<table>
<tr>
<td width="50%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-dashboard-light.jpg" alt="A dashboard in light mode: cream canvas, serif headings, stat cards and charts on hairline-bordered surfaces" /></td>
<td width="50%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-dashboard-dark.jpg" alt="The same dashboard in dark mode, in warm espresso and umber rather than cool grey" /></td>
</tr>
<tr>
<td><b>Dashboard</b><br />Widgets on barely differentiated surfaces, stat figures set in the display serif.</td>
<td><b>The same, dark</b><br />Espresso and umber, built from the same neutral ramp as the light mode.</td>
</tr>
<tr>
<td><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-table-light.jpg" alt="A table of projects: tracked caps in the column headings, tabular figures, tinted badges" /></td>
<td><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-table-dark.jpg" alt="The same table in dark mode" /></td>
</tr>
<tr>
<td><b>Tables</b><br />Column headings in small tracked caps, tabular figures, badges as tinted washes rather than pale pills.</td>
<td><b>Density is a setting</b><br />Row height and padding follow <code>density()</code>, so the same table can be airy or tight.</td>
</tr>
<tr>
<td><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-form-light.jpg" alt="An edit form with sections, selects, a date picker and hairline-bordered inputs" /></td>
<td><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-charts-dark.jpg" alt="Two chart widgets in dark mode, with grid lines, axes and tick labels in the theme's warm palette" /></td>
</tr>
<tr>
<td><b>Forms</b><br />Inputs carry a hairline and a soft halo on focus, in place of Filament's ring.</td>
<td><b>Charts</b><br />Grid, axes and legend take the palette too, not just the series. <a href="#charts">How that works</a>.</td>
</tr>
</table>

<table>
<tr>
<td width="34%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-chart-tooltip-light.jpg" alt="A chart tooltip: a dark warm chip with rounded corners and a small legend swatch" /></td>
<td width="33%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-dashboard-light-mobile.jpg" alt="The dashboard on a phone, with the sidebar collapsed and the widgets stacked" /></td>
<td width="33%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-table-light-mobile.jpg" alt="The projects table on a phone, scrolling horizontally" /></td>
</tr>
<tr>
<td><b>Chart tooltip</b><br />The same warm chip as every other tooltip, with corners from <code>roundness()</code>.</td>
<td><b>On a phone</b><br />The sidebar collapses; the layout keeps its air.</td>
<td><b>Tables on a phone</b><br />Horizontal scroll, with the header treatment intact.</td>
</tr>
</table>

An empty state is a screen most panels meet and few design. This one is a list
emptied by a search rather than by having nothing in it, which is the version
that needs a way back rather than a way to start:

<div align="center">
<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-table-empty-light.jpg" alt="A projects table with a search that matches nothing: an illustrated mark in a warm halo, a heading reading Nothing matches, and an action to show everything again" width="860" />
</div>

### The appearance page

Colour, type, roundness, density and elevation, edited in the panel with a live
preview. The sign-in composition is chosen here too, and previewed as the real
layout rather than a diagram of one:

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-appearance-login.jpg" alt="The sign-in section of the appearance page, with the five compositions and a live preview of the selected one" width="100%" />

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-appearance-light.jpg" alt="The appearance page: colour, typography, shape and depth controls beside a specimen of the components they affect" width="100%" />

## Project status

Mía is a young project in active development, currently in the `0.x` series.

What that means in practice. The theme is complete and usable: light and dark
mode are both finished, the configuration API is stable enough to build on, the
stylesheet ships pre-compiled and the suite runs against PHP 8.4 and 8.5.
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
| PHP | 8.4 or 8.5 |
| Laravel | 11.28, 12 or 13 |
| Filament | 5.7 or later |

The constraint is `^8.4`. Both versions in that range are tested rather than
assumed: every push runs the suite against 8.4 and 8.5, on the oldest and the
newest resolvable dependencies, with `error_reporting=-1` so a deprecation,
notice or warning raised in the package fails the build. Deprecations from
inside Laravel or Filament are ignored, since they say nothing about this
package.

A live panel render was also exercised under PHP 8.5.8 under the same error
reporting, which the suite alone does not cover.

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
| [`loginLayout()`](#loginlayout) | `card` | `login.layout` |
| [`loginTagline()`](#logintagline) | `null` | `login.tagline` |
| [`customizer()`](#customizer) | `false` | `customizer.enabled` |
| [`customizerAuthorization()`](#customizerauthorization) | `null` — anyone who can reach the panel | — |
| [`customizerNavigation()`](#customizernavigation) | ungrouped | `customizer.navigation_group` `customizer.navigation_sort` |
| [`localeSwitcher()`](#localeswitcher) | `false` — no switcher | `locales` |

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

Only the configured families are downloaded. Filament links its own Inter
stylesheet on every page and there is no supported way for a theme to withdraw
it, so that request remains — but nothing in the theme references Inter, so the
browser fetches no Inter binary. On a page set to the defaults that is three
files: two weights of Jost and one of Cormorant Garamond.

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

#### `loginLayout()`

```php
public function loginLayout(LoginLayout|string $layout): static
```

Which of the five [sign-in compositions](#sign-in-compositions) the panel uses.
Accepts a case of `JohnRivera7\FilamentMia\Enums\LoginLayout` or its string
value: `card`, `split`, `bleed`, `editorial`, `portal`. Anything else throws
`InvalidThemeOption` at boot.

The setting covers the whole authentication flow — sign-in, registration,
password reset and the multi-factor challenge — so a panel does not change
shape halfway through logging in.

```php
MiaTheme::make()->loginLayout('split')
```

#### `loginTagline()`

```php
public function loginTagline(?string $tagline): static
```

A line of copy for the brand stage. Only the `split` and `editorial`
compositions have somewhere to put it; the others ignore it. Whitespace is
collapsed to a single line and the value is capped at 120 characters, because
it shares its space with type set at display size.

```php
MiaTheme::make()->loginLayout('split')->loginTagline('Client work, kept in one place.')
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

#### `localeSwitcher()`

```php
public function localeSwitcher(array|bool $locales = ['en', 'es']): static
```

Offers a language switcher in the user menu and applies the choice to the
panel. Off by default. See [Languages](#languages) for what it changes and when
to leave it off.

```php
MiaTheme::make()->localeSwitcher()                          // English, Español
MiaTheme::make()->localeSwitcher(['en', 'es', 'pt_BR'])
MiaTheme::make()->localeSwitcher(['en' => 'English (US)', 'es'])
```

## The collapsed sidebar

A panel registered with `sidebarCollapsibleOnDesktop()` has a second
navigation layout, not a narrower version of the first one. At the collapsed
width there is room for one centred target per row and for nothing else, so
the question each piece of sidebar content has to answer is not whether it
fits but whether it is a target.

The theme answers it the same way for everything in the rail:

- **Navigation** reduces to icons, all on the rail's centre line — including
  the expand control in the header, the user menu and the notification bell,
  which are laid out from the same centre rather than from their own.
- **A navigation group with an icon** keeps its items, in the dropdown
  Filament opens beside the rail.
- **Anything an application adds** through `SIDEBAR_START`,
  `SIDEBAR_NAV_START`, `SIDEBAR_NAV_END` or `SIDEBAR_FOOTER` is not shown.

The last one is the deliberate part. A credit meter, a workspace switcher or a
search field has a label, a figure and a control in it, and none of the three
survive being squeezed into the rail: they wrap into a column one word wide
and paint the remainder over the canvas. It is also the answer Filament
already gives its own non-target chrome — the logo, the tenant menu and
sidebar global search all disappear when the sidebar closes — so the rail ends
up with one criterion instead of two.

Nothing becomes unreachable. The expand control is in the topbar, or in the
sidebar header itself when the panel has no topbar, and the block is back one
click later.

### Giving a block a rail form

Where a compact form does make sense, supply one. The theme reads two classes:

| Class | In the rail | Expanded |
|---|---|---|
| `fi-mia-rail-only` | Shown, centred and clipped to the rail | Hidden |
| `fi-mia-rail-hidden` | Hidden | Shown |

```blade
{{-- Both live in the same SIDEBAR_FOOTER hook. --}}
<div class="px-4 pb-4 pt-2">
    {{-- The full meter: label, figures, progress bar. --}}
</div>

<div class="fi-mia-rail-only">
    <span class="mia-kicker">64%</span>
</div>
```

The full block needs no class of its own if it is what the render hook
returns, since it is hidden by the rule above. `fi-mia-rail-hidden` is for the
case where it sits nested inside a wrapper that has to stay.

Both classes are plain CSS in the theme's stylesheet, so they work in a
pre-compiled theme without a build step of your own.

`bin/responsive-shots.mjs` is what checks this. It walks a phone in both
orientations, a tablet, a narrow desktop and a wide one, collapses and expands
the sidebar at each, and reports every element inside the sidebar whose box
ends past the rail's edge along with the centre line each of its targets sits
on — a rail with more than one centre line is the symptom that some of its
content is still being laid out for the expanded column.

## Sign-in compositions

The sign-in screen is the only part of a panel a visitor sees without an
account, so the theme ships five stagings of it rather than one. They differ in
composition — where the form sits and what occupies the rest of the viewport —
not in identity. Palette, type pairing and shape treatment are the same across
all five.

| Value | Composition |
|---|---|
| `card` | A single card centred on the canvas, over two soft pools of warm light. The quietest of the five, and the default. |
| `split` | Two columns. One is brand territory — a deep warm field carrying the logo, the panel name and an optional line of copy — and the other holds the form, uncarded, on the cream canvas. |
| `bleed` | A warm field running to every edge. The panel is laid out *across* the screen on frosted glass: brand and heading in one half, fields in the other, divided by a hairline. |
| `editorial` | Asymmetric and print-like. The form is anchored to one side with no card around it, and the facing side is left as air with the panel name at display size. |
| `portal` | A narrow, tall column, centred, with a brand medallion above the heading and no card edge at all. The canvas grades vertically. |

Choose one when registering the plugin:

```php
->plugin(
    MiaTheme::make()
        ->loginLayout('split')
        ->loginTagline('Client work, kept in one place.'),
)
```

Or from the [appearance page](#the-appearance-page), which previews the choice
before it is saved. The preview is the real layout rather than a diagram of
one: it renders the same markup with the same stylesheet, so what is on screen
is what visitors will get.

The choice applies to the whole authentication flow. Registration, password
recovery and the multi-factor challenge all take the same staging, so a panel
does not change shape between entering a password and confirming a code.

**Nothing needs configuring.** With no settings at all the panel gets the
`card` composition, which is already a long way from Filament's default box.

### What happens on a phone

The two-column compositions are the ones that break on narrow screens, so each
states what it does rather than leaving it to the grid:

- `split` folds the brand column into a short banner above the form, keeping
  the logo, the panel name and the accent rule, and dropping the tagline and
  the botanical mark — both need width to read as anything but clutter.
- `editorial` drops the facing side entirely. It is ornament, and stacking it
  under the form would only add scroll.
- `bleed` becomes a single column, and the glass panel returns to a card.
- `card` and `portal` are single-column already.

The carded compositions keep their corner radius at phone widths, with a small
margin to show it against, where Filament runs the card edge to edge.

### Accessibility of the compositions

Contrast on these screens is measured from rendered pixels rather than
calculated from the palette. Two of the compositions put text over a gradient
and one puts it over frosted glass, and a ratio computed against a nominal
background would not be a measurement of anything.

`bin/contrast-login.mjs` walks all five in both colour modes, with a validation
error on screen, and for every piece of text takes the computed colour, hides
the glyphs, photographs the box they occupied and averages what is behind them.
48 pairs, all clearing WCAG AA — headings against the 3:1 that 1.4.3 allows
large text, everything else against 4.5:1.

That measurement is also what caught the two failures it now guards against:
muted text and link actions both sat around 4.1:1 on the cream once the warm
gradients behind the page were actually painted, while passing comfortably
against the flat background the palette-level report assumes.

Entry motion is a single fade and rise, disabled under `prefers-reduced-motion`
along with everything else the theme animates. The backgrounds are static
gradients — nothing animates continuously.

## The appearance page

An optional page inside the panel for editing the theme and saving the result,
for the case where whoever decides how the panel looks is not the person who
deploys it.

It edits accent, secondary and status colours; the interface and heading
families; roundness, density and elevation; the [sign-in
composition](#sign-in-compositions) and its line of copy; and it carries five
presets — Mía, Atelier, Botanica, Papier and Plain. A specimen of the
components the settings affect most sits below the form.

The sign-in composition is the one setting whose result is not visible from the
page, since it changes a screen only people who are not signed in ever see, so
it gets a live preview of its own. That preview renders the real simple layout
with the real marker element, under the compiled stylesheet — not a wireframe
of it — and it redraws as the choice changes without waiting for a round trip.

The font list is short on purpose. The theme sets headings at sizes and
tracking that most families do not survive, so the page offers ten of each,
all confirmed to be served by Bunny Fonts:

| | |
|---|---|
| Interface | Jost, Outfit, Manrope, Nunito Sans, DM Sans, Figtree, Plus Jakarta Sans, Urbanist, Karla, Work Sans |
| Headings | Cormorant Garamond, Playfair Display, Fraunces, EB Garamond, Lora, Crimson Pro, Bodoni Moda, DM Serif Display, Spectral, Newsreader |

This list constrains the page only. [`font()`](#font) accepts any family Bunny
Fonts serves.

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
| `--mia-chart-line-tension` | Curvature of a chart line, set by `roundness()` |
| `--mia-chart-bar-radius` | Bar corner radius in pixels, set by `roundness()` |
| `--mia-chart-tooltip-radius` | Chart tooltip corner radius in pixels, set by `roundness()` |
| `--mia-chart-legend-swatch-radius` | Legend swatch corner radius in pixels, set by `roundness()` |

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

### Charts

A chart is painted onto a bare canvas, so no stylesheet reaches it directly.
Filament bridges that with a set of empty elements whose computed colour the
chart component reads and hands to Chart.js, reapplying it when the colour mode
changes. The theme claims them, which puts the grid, the axes, the legend and
the tooltip in its own palette instead of Filament's greys — the difference is
most obvious in dark mode.

Two of those elements are deliberately left alone: `-bg-color` and
`-border-color` carry the colour of each individual widget, so a theme that
overrode them would paint every series in a panel the same.

The numbers Chart.js reads are derived from `roundness()` rather than fixed, so
bars, line curvature, legend swatches and the chart tooltip follow the corner
treatment of everything around them — including a change made from the
appearance page, with no rebuild.

| `roundness()` | Line tension | Bar radius | Tooltip radius |
|---|---|---|---|
| `sharp` | `0` | `0` | `4` |
| `subtle` | `0.2` | `2` | `8` |
| `soft` | `0.35` | `4` | `12` |
| `round` | `0.45` | `8` | `18` |

Override any of it per widget in the usual way, from `getOptions()`, which
takes precedence over the properties the theme sets.

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

## Languages

The theme ships English and Spanish, and it can put a language switcher in the
user menu, below the light/dark switch.

<table>
<tr>
<td width="50%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/locale-menu-en.jpg" alt="The user menu open, showing the light/dark switch above English and Español, with English marked as current" /></td>
<td width="50%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/locale-menu-es.jpg" alt="The same panel after choosing Español, with the page, the navigation and Filament's own menu items in Spanish" /></td>
</tr>
<tr>
<td><b>The switcher</b><br />One item per language, in the language's own name, next to the light/dark switch.</td>
<td><b>Chosen</b><br />The whole panel follows, including Filament's own copy.</td>
</tr>
</table>

### What is translated

The theme itself only labels one surface: the [appearance
page](#the-appearance-page), including the names and descriptions of the
presets. That is fully translated into both languages, and the two files are
checked for key parity in the test suite. Nothing else in the theme carries
copy: the sign-in
compositions render your brand name and your own tagline, and the switcher
labels languages with their own name, which is not translated by design.

Everything else in a panel comes from elsewhere, and the switcher changes it
too:

- **Filament's own copy** — headings, buttons, table and form messages — ships
  in more than sixty languages, Spanish among them.
- **Your resources, pages and fields** are yours to translate. A switcher over
  untranslated copy leaves a panel half in one language, which reads worse than
  one language throughout. Check this before turning it on.

### Turning the switcher on

```php
->plugin(
    MiaTheme::make()->localeSwitcher(['en', 'es']),
)
```

Codes must match the directories in your `lang` folder. Languages are labelled
with their own name; pass a label to override one:

```php
MiaTheme::make()->localeSwitcher(['en' => 'English (US)', 'es', 'pt_BR'])
```

It is a list rather than a button that cycles, so the name of every option
stays on screen — which is the point when the visitor cannot read the language
the interface is currently in — and so that adding a third language changes
nothing about how it works.

The choice is stored in a long-lived cookie, `filament_mia_locale`, written by
Laravel's cookie jar like any other. That is where the light/dark choice lives
too, and for the same reason: it belongs to the browser, not to the sign-in. It
survives a reload, a new page, an expired session and a sign-out, so a visitor
who chose Spanish yesterday meets the sign-in screen in Spanish today.

One limit worth stating: the switcher lives in the user menu, which does not
exist before signing in. A first-time visitor gets the application's default
language on the sign-in screen. Panels that need the language chosen from the
sign-in screen itself should set the locale from the URL or the request, which
is the application's job rather than the theme's.

<div align="center">
<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/locale-login-es.jpg" alt="The sign-in screen in Spanish, with Filament's own labels translated, after the session was discarded" width="720" />
</div>

### Adding a language

Nothing has to be contributed upstream. Laravel's package translations are
overridable per application, so a fourth or a fortieth language is a folder in
your own project:

```
lang/vendor/filament-mia/fr/customizer.php
```

Copy `vendor/johnrivera7/filament-mia/resources/lang/en/customizer.php` as a
starting point, or publish both bundled languages first:

```bash
php artisan vendor:publish --tag=filament-mia-translations
```

Then offer it:

```php
MiaTheme::make()->localeSwitcher(['en', 'es', 'fr'])
```

You will also want Filament's own translations for that language, which are
published with `php artisan vendor:publish --tag=filament-translations`.

### Turning the switcher off

It is off until you ask for it, and `localeSwitcher(false)` turns it off again
— useful for disabling it on one panel while a config-file default enables it
everywhere else.

Off is the default on purpose. Setting the locale is not a visual decision: it
changes Filament's copy, your application's copy, and anything else reading
`app()->getLocale()` for the length of the request. Plenty of applications
already decide the language from the user record, the subdomain or an
`Accept-Language` header, and installing a theme should not quietly take that
over.

When you do turn it on, this is what the theme does and does not touch:

- The locale is applied by middleware **registered on that panel only**. Every
  other route in your application, and every panel where the switcher is off,
  is untouched.
- The middleware is added *after* the ones your panel provider registers, so
  inside that panel the visitor's choice wins over an earlier `setLocale()`.
  That is the point of enabling it. If your own locale logic must win instead,
  leave the switcher off, or register your middleware on the panel after the
  plugin.
- Nothing is applied until a visitor picks a language. Without the cookie the
  theme never calls `setLocale()` at all, so an untouched panel behaves exactly
  as it did before.
- The cookie is validated against the languages that panel offers, so a value
  another panel wrote is ignored rather than trusted.

## Accessibility

Contrast is computed with Filament's own colour maths and checked in the test
suite, so a change to the ramps that broke accessibility fails the build.

| Pair | Light | Dark |
|---|---|---|
| Body text on the page | 18.97:1 | 19.58:1 |
| Body text on a card | 19.58:1 | 17.03:1 |
| Muted text on a card | 6.97:1 | 7.29:1 |
| Link and accent text on a card | 4.88:1 | 9.07:1 |
| Button label on the accent | 5.04:1 | 5.04:1 |
| Input border on a card | 4.79:1 | 7.29:1 |
| Focus ring on a card | 4.88:1 | 9.07:1 |

Text pairs clear the 4.5:1 that WCAG AA asks of body copy, and controls clear
the 3:1 that WCAG 1.4.11 asks of user interface components. The sign-in screens
are measured separately, from rendered pixels, because their backgrounds are
gradients; see [Accessibility of the
compositions](#accessibility-of-the-compositions). Decorative
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

**None.** The theme is implemented in CSS and two render hooks:

- `PanelsRenderHook::STYLES_AFTER` emits the runtime custom properties.
- `PanelsRenderHook::SIMPLE_LAYOUT_START` emits the marker element that selects
  a [sign-in composition](#sign-in-compositions), and the brand stage for the
  two compositions that use one.

No Blade view is published or replaced, so Filament upgrades cannot silently
revert to an old copy of a framework template. The simple layout in particular
is one of the files most likely to change between releases, and a published
copy of it would stop tracking upstream without saying so.

The [language switcher](#languages) is the same story from the other side: it
appears in the user menu through `Panel::userMenuItems()`, Filament's own
extension point for that menu, rather than by publishing the menu's view.

## Troubleshooting

### The theme has no effect at all

Check for `->viteTheme(...)` on the panel and remove it. Filament resolves the
active theme by returning `viteTheme` first if it is set, regardless of where
either call appears in the chain, so it overrides `->theme()` with no error or
warning. A plugin theme and `viteTheme` cannot coexist.

If you need your own Tailwind utilities as well, use
[`viteStylesheets()`](#tailwind-utilities-in-your-own-views), which loads them
alongside the theme rather than instead of it.

### The panel is not using the fonts I configured

If the interface falls back to a system sans, or headings render in Georgia
rather than the configured serif, an application stylesheet is redeclaring
Tailwind's font tokens on `:root`.

It happens when the file passed to [`viteStylesheets()`](#vitestylesheets)
imports `tailwindcss/theme.css`, which is the normal way to give Tailwind the
tokens it needs to compile utilities. That file declares `--font-sans` and
`--font-serif` without Filament's leading `var(--font-family)`, and because
your stylesheet loads after the theme it wins.

The theme now restates those tokens on the panel's own `<body>` and sets
`font-family` there explicitly, so this resolves itself on upgrade. Nothing to
change in your stylesheet.

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

### Looking at it locally

The package carries its own panel, so no application is needed to see the
theme:

```bash
php vendor/bin/testbench workbench:build
php vendor/bin/testbench serve
```

That serves a panel at `/admin` with sign-in, registration, password recovery
and a multi-factor challenge, against one invented account
(`valeria@mia.test` / `password`). Behind it is a small worked example — a
projects list, a client list, a dashboard of stats and charts, and the
appearance page — seeded from a fixed number, so the same records come back in
the same order on every build. Every screenshot in this README is taken from
it:

```bash
node bin/shots.mjs             # all five compositions, both modes, both widths
node bin/panel-shots.mjs       # the panel interior: dashboard, lists, form, charts
node bin/locale-shots.mjs      # the language switcher, and the choice surviving
node bin/contrast-login.mjs    # measured contrast for the same set
php bin/contrast-report.php    # palette-level contrast
```

The example panel is deliberately in English, which is also how the theme's
translation coverage gets checked: anything the theme itself labels shows up in
those frames, so a string left in another language is visible in the picture
rather than buried in a language file.

Three conventions keep the repository honest:

- **`resources/dist/mia.css` is rebuilt and committed on its own.** It is
  committed so the package installs without a build step, and marked as
  generated in `.gitattributes` so it stays out of diffs. Rebuild it with
  `npm run build` and commit it in a change of its own, never mixed with the
  source CSS that produced it. Because `->theme()` replaces Filament's
  stylesheet outright, that file contains Filament's own compiled core, and is
  built against the newest release in the supported range — currently Filament
  5.8. CI rebuilds it on every push and fails if the result differs from what
  is committed, which is also how a Filament release that changes the core
  stylesheet announces itself.
- **A change to the public surface updates the README and the changelog in the
  same commit.** Any new or altered option, chainable method, published custom
  property, overridden view, requirement or command belongs in the diff that
  introduces it. This README is the product page, and documenting afterwards
  reliably leaves options undocumented and examples that no longer match the
  code.
- **Every image comes from the bundled preview panel or the demo
  application.** Both run on invented data. `.gitignore` blocks the filenames
  screenshots tend to get by default, because an image of a real system can
  carry personal data and a blob pushed to a public repository stays reachable
  by SHA long after the file is deleted.

## Where this is going

Mía starts as a theme. The direction is a design system for Filament: the
stylesheet is the first layer, not the whole of it.

Concretely, what is in and what is not.

**Today.** A pre-compiled stylesheet, a configuration API for colour,
typography, roundness, density and elevation, warm light and dark modes, five
sign-in compositions, illustrated empty states, loading states, measured
contrast, an in-panel appearance page that edits and persists all of it, and
English and Spanish with an optional switcher.

**Next.** The rest of the panel rephotographed from the bundled preview panel.
Presets shipped as named palettes beyond the five the appearance page carries.
More bundled languages, taken from what people actually ask for.

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
