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
- An optional language switcher in the user menu, below the light/dark switch,
  enabled with `localeSwitcher()` or the `locales` config key. Off by default.
  - One item per language, labelled with the language's own name, so the choice
    stays readable to someone who cannot read the language currently on screen.
    Added through `Panel::userMenuItems()`, so no Blade view is overridden.
  - The choice is stored in a long-lived `filament_mia_locale` cookie and
    applied by middleware registered on that panel alone, persistent so
    Livewire requests resolve the same locale as the page that issued them. It
    survives a reload, a navigation, an expired session and a sign-out, which
    means the sign-in screen also comes back in the chosen language.
  - It sets Laravel's locale, so Filament's own copy and the application's
    follow, not just the theme's appearance page. Off by default because an
    application that already decides the language should keep deciding it, and
    because a panel whose own resources are untranslated would end up half in
    each language.
  - The stored value is validated against the languages that panel offers, so a
    cookie written by another panel is ignored.
- `Project status` and `Where this is going` sections in the README, stating
  the maturity of the `0.x` series and the direction of the project.


- Five compositions for the screens shown before sign-in, chosen with
  `loginLayout()` or from the appearance page: `card`, `split`, `bleed`,
  `editorial` and `portal`. They differ in staging rather than identity — where
  the form sits and what occupies the rest of the viewport — and the choice
  covers the whole authentication flow, including registration, password
  recovery and the multi-factor challenge. `card` is the default and needs no
  configuration.
- `loginTagline()`, a line of copy for the brand stage carried by the `split`
  and `editorial` compositions. Collapsed to a single line and capped at 120
  characters.
- A live preview of the sign-in composition on the appearance page. It renders
  the real simple layout under the compiled stylesheet rather than a diagram of
  it, and redraws client-side as the choice changes.
- Each of the five presets now names a composition, so applying one is still a
  complete set of design decisions.
- `PanelsRenderHook::SIMPLE_LAYOUT_START` is now used to emit the composition
  marker and the brand stage. Still no published or overridden Blade views.
- A preview panel bundled with the package (`php vendor/bin/testbench serve`),
  which serves sign-in, registration, password recovery and a multi-factor
  challenge against one invented account. Every screenshot in the README is
  taken from it.
- `bin/contrast-login.mjs`, which measures contrast on the sign-in screens from
  rendered pixels rather than from the palette — necessary because two
  compositions put text over a gradient and one puts it over frosted glass. 48
  pairs across five compositions and both colour modes, all clearing WCAG AA.

- Charts now follow the theme. Filament's chart view renders a set of empty
  elements whose computed colour the component reads and hands to Chart.js; the
  theme claims them, which puts the grid, the axes, the legend and the tooltip
  in the warm palette instead of Filament's greys. Series already followed the
  palette, so the two elements that carry each widget's own colour are left
  alone.
  - The numbers Chart.js reads — line tension, bar radius, legend swatch and
    tooltip radius — are derived from `roundness()` rather than fixed, so a
    chart follows the corner treatment of the panel around it, including a
    change saved from the appearance page.
  - `--mia-chart-line-tension`, `--mia-chart-bar-radius`,
    `--mia-chart-tooltip-radius` and `--mia-chart-legend-swatch-radius` are
    published alongside the rest.

- Preset names and descriptions on the appearance page are translatable, and
  ship in Spanish alongside the rest of the page. A preset registered by an
  application keeps the text it was given.
- Screenshots of a working panel in the README: dashboard, lists, a form,
  charts, a filtered empty state and the appearance page, in both colour modes
  and on a phone.
- A worked example inside the bundled preview panel, so the package
  photographs itself: two resources with populated lists and forms, a
  dashboard of stats, two charts and a table widget, and a seeder that runs
  from a fixed number so the same records come back on every build.
  `bin/panel-shots.mjs` captures the set. Development only — none of it ships
  with the package.
  - The panel is in English, which doubles as the check on the theme's own
    translation coverage: a string the theme fails to translate is visible in
    the frames rather than buried in a language file.
  - The projects list carries two empty states, one for a list with nothing in
    it and one for a list narrowed to nothing by a search or filter, the second
    offering a way back rather than a way to start.
- `bin/responsive-shots.mjs`, which walks a phone in both orientations, a
  tablet, a narrow desktop and a wide one, collapses and expands the sidebar at
  each, and captures both states in both colour modes. It reports every element
  inside the sidebar whose box ends past the rail's edge, and the centre line
  each of the rail's targets sits on — more than one centre line means part of
  the rail is still being laid out for the expanded column. A media query sweep
  alone never reaches that state, because the sidebar's width changes without
  the viewport changing. Development only.
  - The bundled preview panel is now registered with
    `sidebarCollapsibleOnDesktop()`, so the rail can be looked at in the
    package itself rather than only in an application that happens to enable it.

### Changed

- The package now requires PHP 8.4. The badge, the requirements table and the
  CI matrix say so; nothing claims a version that is not tested.

- `--mia-ink-muted` is one step darker in light mode, `--gray-600` rather than
  `--gray-500`. Measured against the rendered page it sat at 4.1:1, which
  passes against the flat background the palette-level report assumes and
  misses AA once the warm gradients behind the page are painted. Secondary text
  throughout the theme is affected.
- Link actions on the sign-in screens are set a step darker for the same
  reason, and are now matched on `fi-text-color-600` as well, which Filament
  applies with more specificity than an element rule on the subheading.
- The background grain on the sign-in canvas is finer and fainter. At two
  device pixels per CSS pixel the previous grating read as a visible weave.


- Colours are normalised when a settings record is built, so a value typed in
  the appearance page and the same value written in the config file store
  identically.


- Reworked the README positioning and the `composer.json` description so both
  say what the theme is and who it is for, and recorded the plugin directory
  copy in the repository so the three stay consistent.

- The shipped stylesheet is compiled against Filament 5.8, the newest release
  in the supported range, rather than 5.7.8. Because `->theme()` replaces
  Filament's stylesheet outright, the file carries Filament's compiled core,
  and building it against the oldest supported release left panels on 5.8
  without two fixes to the collapsible sidebar and the modal window. Panels on
  5.7 are unaffected: the two rules they gain address markup they already ship.

### Fixed

- The collapsed sidebar was styled as a narrower version of the expanded
  column rather than as its own layout, so it ran two width criteria at once:
  the navigation reduced to icons while everything else in it kept being laid
  out at the sidebar's full width.
  - Icons sat left of the rail's centre. The theme padded the navigation on
    both sides while Filament zeroes the end side of it in the rail, and a
    column padded on one side only cannot centre what it holds. The user menu
    and the notification bell landed on a second centre line of their own,
    24px from the first, and overhung the rail's edge.
  - The expand control in the sidebar header, which is the only way back out of
    the rail on a panel with no topbar, was held inside 1.25rem gutters that
    left it less room than it needed.
  - Content an application adds through `SIDEBAR_START`, `SIDEBAR_NAV_START`,
    `SIDEBAR_NAV_END` or `SIDEBAR_FOOTER` was laid out at the expanded width
    inside the rail: text wrapped into a column one word wide and the remainder
    painted over the canvas, 50px past the rail's edge in the case that was
    reported. Those blocks are no longer shown in the rail, which is the answer
    Filament already gives the logo, the tenant menu and sidebar global search.
    `fi-mia-rail-only` and `fi-mia-rail-hidden` are there for supplying a
    compact form instead.
  - A reserved scrollbar gutter no longer applies in the rail, where it is a
    fifth of the width and lands on one side only.
  - The rule meant to drop the active item's accent mark in the rail never
    matched anything: it was written against `.fi-sidebar.fi-collapsed`, while
    `fi-collapsed` is the class Filament puts on a collapsed navigation *group*.
  - The expanded sidebar is pixel-identical to before at every width and in
    both colour modes; only the rail changed.

- On a phone, the `split` composition centred the form in the space under its
  brand banner, which opened a gap about as tall as the banner between the two.
  The form now starts below it.

- The configured font families were silently dropped whenever the stylesheet
  passed to `viteStylesheets()` imported Tailwind's theme layer. That layer
  redeclares `--font-sans` and `--font-serif` on `:root` without Filament's
  leading `var(--font-family)`, and it loads after the theme, so the panel fell
  back to a system sans and headings to Georgia. The theme now restates those
  tokens on the panel's own element and sets `font-family` on it directly,
  which the cascade cannot undo from `:root`.

### Removed

- The first set of screenshots and the 16:9 cover. Every image is now produced
  by a script in `bin/`, from the preview panel bundled with the package,
  running on invented records.

- The interim gallery taken from a separate demo application, replaced
  one-for-one by frames from the bundled preview panel. The images were in
  Spanish while the README is in English, and regenerating them needed a
  project that is not in this repository.

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
