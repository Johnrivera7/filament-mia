# Changelog

All notable changes to `johnrivera7/filament-mia` are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- An appearance page inside the panel, off by default. Edits accent, secondary
  and status colours, the interface and heading families from a checked list of
  Bunny Fonts families, roundness, density and elevation, with five presets
  including the theme as shipped, a light and dark preview switch, and a
  specimen of the components the settings affect most.
  - The preview is not an approximation: every control writes a custom property
    the compiled stylesheet already reads, and one code path paints both the
    preview and the saved panel.
  - `customizer()`, `customizerAuthorization()` and `customizerNavigation()` on
    the plugin, and a `customizer` block in the config file.
  - Settings are stored per panel, shared by everyone who uses it, as JSON under
    `storage/app/filament-mia/`. No migration is needed. Bind the
    `SettingsRepository` contract to store them anywhere else, including per
    user.
  - A saved record takes precedence over both the config file and the fluent
    API, and applies whether or not the page is enabled. The page's reset action
    discards it. A record that has been hand-edited into an invalid state is
    ignored rather than thrown, so a bad value cannot lock anyone out of the
    page that would fix it.
- Spanish translations for the appearance page, alongside the English ones.
  Publish or override them with the `filament-mia-translations` tag.
- `Project status` and `Where this is going` sections in the README, stating
  the maturity of the `0.x` series and the direction of the project.

### Changed

- Colours are normalised when a settings record is built, so a value typed in
  the appearance page and the same value written in the config file store
  identically.

### Changed

- Reworked the README positioning and the `composer.json` description so both
  say what the theme is and who it is for, and recorded the plugin directory
  copy in the repository so the three stay consistent.

### Removed

- Every screenshot, and the 16:9 cover, along with their history. They were
  captured against a real application. Images are being retaken against a demo
  application running on invented data, and `.gitignore` now blocks the
  filenames screenshots tend to get so this cannot recur.

## [0.1.0] - 2026-09-07

First release: a warm editorial theme for Filament, shipped pre-compiled.

### Added

#### Design

- Warm neutral ramp built in OKLCH, giving a cream and ivory light mode and an
  espresso and taupe dark mode from the same source, so the two modes read as
  one design rather than an inversion.
- Soft honey gold accent with a desaturated dusty rose secondary, and status
  colours warmed to sit inside the palette instead of cutting across it.
- Editorial type hierarchy: a high-contrast display serif for page, modal,
  brand and empty-state headings, a geometric humanist sans for content, and
  small tracked caps for column headings, group labels and stat captions.
  Families are served from [Bunny Fonts](https://fonts.bunny.net), which sets
  no cookies and logs no IP addresses.
- Sidebar and topbar rebuilt as part of the page: the sidebar sits directly on
  the canvas behind a hairline, the topbar is a translucent warm veil, and the
  active navigation item is marked with an accent rule rather than a filled
  pill.
- Form controls redrawn as flat fields with hairline borders in place of the
  framework's ring and shadow, with the accent appearing only on focus.
- Tables set as a printed index: caps column headings, no vertical rules,
  tabular figures with slashed zeroes, and row hover drawn as an inset shadow.
- Badges reduced to a tinted wash with small tracked caps, with no ring.
- Wide, diffuse shadows tinted with the neutral rather than black, and
  generous corner radii throughout.
- Illustrated empty states, drawn as a CSS mask over an accent gradient so the
  illustration follows whatever palette is configured and needs no published
  image assets.
- Shimmering skeletons for deferred sections, plus a reusable `fi-skeleton`
  block, animated with `translateX` so the effect is composited rather than
  repainted each frame.
- Authentication screens on a washed canvas with a serif heading and accent
  rule.
- Slow, gentle motion on a decelerating curve, replacing the framework's 75ms
  transitions.

#### Configuration

All options are available fluently on the plugin and as defaults in a
publishable config file; the fluent call wins. Colours are emitted as OKLCH
custom properties and non-colour settings as `--mia-*` properties scoped to
`.fi-panel-{id}`, so everything below is configurable at runtime, without
recompiling, and can differ between panels in one application.

- `accentColor()`, `secondaryColor()`, `neutralColor()` and `statusColors()`.
  A colour is expanded into an eleven-shade ramp that preserves its hue *and*
  its saturation character, so an understated colour stays understated.
- `font()`, `monoFont()` and `serifHeadings()`.
- `roundness()` — `sharp`, `subtle`, `soft` or `round`.
- `density()` — `compact`, `comfortable` or `spacious`.
- `elevation()` — `0.0` to `2.0`, where `0` is completely flat.
- `motion()`, independent of `prefers-reduced-motion`.
- `darkMode()` and `sidebarWidth()`.
- `viteStylesheets()`, for loading the application's own compiled Tailwind
  utilities after the theme. A pre-compiled theme cannot contain utility
  classes from the consuming application's Blade views, since those files do
  not exist when the theme is built, and `Panel::viteTheme()` cannot be used
  for it because Filament gives it unconditional precedence over `theme()`.
- Invalid colours, font families and enum values throw `InvalidThemeOption` at
  boot, because Filament converts colours without validating them and an
  unparseable value would otherwise yield a black palette and no error.
- The `--mia-*` custom properties are documented as public surface, so
  application components can match the theme or adjust what the options do not
  cover, along with a `fi-skeleton` class for loading states in your own views.

#### Accessibility

- Every text pair clears the 4.5:1 WCAG AA asks of body copy, and every
  control clears the 3:1 WCAG 1.4.11 asks of user interface components, in
  both colour modes. The ratios are asserted in the test suite, so a change to
  the ramps that broke them fails the build.
- Two-layer focus ring, legible on light surfaces, dark surfaces and coloured
  buttons alike.
- Row actions rest at reduced opacity rather than being hidden until hover,
  which would remove them from keyboard navigation and put them out of reach
  on touch.
- `prefers-reduced-motion` is honoured throughout. Durations collapse to
  `0.01ms` rather than to zero, so `transitionend` still fires and Alpine
  transitions continue to resolve.

#### Packaging

- Ships pre-compiled: `resources/dist/mia.css` is committed and marked as
  generated, so installing needs no Node, Tailwind or build step.
- Registered as a `Theme` asset from the service provider's `packageBooted()`,
  so `php artisan filament:assets` can publish it from the console, where no
  panel exists yet.
- No Filament Blade view is published or overridden, so framework upgrades
  cannot silently revert to a stale copy of a template.
- Tested on PHP 8.2 through 8.5 and Filament v5.7, on both the lowest and the
  highest resolvable dependencies. The suite runs with `error_reporting=-1`
  and fails on any deprecation, notice or warning originating in the package.

[Unreleased]: https://github.com/Johnrivera7/filament-mia/compare/v0.1.0...HEAD
[0.1.0]: https://github.com/Johnrivera7/filament-mia/releases/tag/v0.1.0
