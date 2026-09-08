{{--
    The wrapper every block shares.

    The surface class is what makes the page's rhythm — cream, warm, espresso —
    editable: it re-points the theme's semantic colour slots, so the content
    inside adapts without knowing where it landed.

    A block type that is page furniture rather than a section says so here and
    builds its own element instead. That keeps one entry point for the loop in
    the layout, which does not have to know which blocks are special.

    Expected data:

      type    Block type, which names both the chrome below and the partial.
      data    The block's own stored content.
      main    Whether this block opens the page's main landmark.
--}}
@php
    use JohnRivera7\FilamentMia\PageBuilder\BlockCatalog;

    $chrome = [
        // The navigation bar is not a section: it has no surface, no vertical
        // rhythm, and its own sticky behaviour.
        'navigation' => ['tag' => 'header', 'section' => false, 'shell' => false],
        // The hero lays a decorative wash over its own bounds.
        'hero' => ['extra' => 'mia-page-section--clipped'],
        'metrics' => ['extra' => 'mia-page-section--tight'],
        'footer' => ['tag' => 'footer', 'extra' => 'mia-page-section--tight'],
    ][$type] ?? [];

    $tag = $chrome['tag'] ?? 'section';
    $isSection = $chrome['section'] ?? true;
    $hasShell = $chrome['shell'] ?? true;

    $surface = $data['surface'] ?? 'canvas';
    $surface = in_array($surface, BlockCatalog::SURFACES, true) ? $surface : 'canvas';

    $classes = array_filter([
        $isSection ? 'mia-page-section' : null,
        $isSection && $surface !== 'canvas' ? "mia-page-section--{$surface}" : null,
        $isSection ? ($chrome['extra'] ?? null) : null,
    ]);

    if ($type === 'navigation') {
        $classes[] = 'mia-page-nav';

        if ($data['sticky'] ?? true) {
            $classes[] = 'mia-page-nav--sticky';
        }
    }

    $anchor = filled($data['anchor'] ?? null) ? $data['anchor'] : null;
@endphp
<{{ $tag }} @if ($anchor) id="{{ $anchor }}" @endif @class($classes)>
    @if ($main ?? false)
        <span id="mia-page-content" class="mia-page-sr"></span>
    @endif

    @if ($hasShell)
        <div class="mia-page-shell">
            @include('filament-mia::page-builder.blocks.' . $type)
        </div>
    @else
        @include('filament-mia::page-builder.blocks.' . $type)
    @endif
</{{ $tag }}>
