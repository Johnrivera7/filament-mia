@php
    use JohnRivera7\FilamentMia\Pages\PageBuilder;

    $icons = [
        'mobile' => 'heroicon-m-device-phone-mobile',
        'tablet' => 'heroicon-m-device-tablet',
        'desktop' => 'heroicon-m-computer-desktop',
    ];
@endphp

<x-filament-panels::page>
    <div class="fi-mia-builder">
        <form wire:submit="save" class="fi-mia-builder-form">
            {{ $this->form }}

            <div class="fi-mia-builder-save">
                <x-filament::button type="submit" icon="heroicon-m-check">
                    {{ __('filament-mia::page-builder.actions.save') }}
                </x-filament::button>

                @if ($this->hasUnpublishedChanges)
                    <span class="fi-mia-builder-hint">
                        {{ __('filament-mia::page-builder.unpublished_hint') }}
                    </span>
                @endif
            </div>
        </form>

        {{--
            The preview pane. An iframe of the draft route, so what is on
            screen is the real page in a real viewport — including the
            responsive behaviour a scaled-down component preview would get
            wrong.
        --}}
        <aside
            class="fi-mia-builder-preview"
            x-data="{
                reload() {
                    const frame = $refs.frame;

                    if (! frame) {
                        return;
                    }

                    let position = 0;

                    try {
                        position = frame.contentWindow.scrollY;
                    } catch (error) {
                        /* Nothing to restore. */
                    }

                    frame.addEventListener('load', () => {
                        try {
                            frame.contentWindow.scrollTo(0, position);
                        } catch (error) {
                            /* Nothing to restore. */
                        }
                    }, { once: true });

                    frame.contentWindow.location.reload();
                },
            }"
            x-on:mia-refresh-page-preview.window="reload()"
        >
            <x-filament::section>
                <x-slot name="heading">{{ __('filament-mia::page-builder.preview.heading') }}</x-slot>

                <x-slot name="description">
                    {{ __('filament-mia::page-builder.preview.description') }}
                </x-slot>

                <div class="fi-mia-builder-toolbar">
                    <div class="fi-mia-builder-widths">
                        @foreach ($icons as $key => $icon)
                            <x-filament::icon-button
                                :icon="$icon"
                                :label="__('filament-mia::page-builder.preview.widths.' . $key)"
                                :color="$this->previewWidth === $key ? 'primary' : 'gray'"
                                wire:click="setPreviewWidth('{{ $key }}')"
                                tag="button"
                            />
                        @endforeach
                    </div>

                    <div class="fi-mia-builder-widths">
                        <x-filament::button
                            size="xs"
                            color="gray"
                            icon="heroicon-m-arrow-path"
                            x-on:click="reload()"
                            tag="button"
                            type="button"
                        >
                            {{ __('filament-mia::page-builder.preview.refresh') }}
                        </x-filament::button>

                        <x-filament::button
                            size="xs"
                            :color="$this->livePreview ? 'primary' : 'gray'"
                            :icon="$this->livePreview ? 'heroicon-m-bolt' : 'heroicon-m-bolt-slash'"
                            wire:click="toggleLivePreview"
                            tag="button"
                            type="button"
                        >
                            {{ __('filament-mia::page-builder.preview.live') }}
                        </x-filament::button>
                    </div>
                </div>

                @if ($this->livePreview)
                    <p class="fi-mia-builder-hint">
                        {{ __('filament-mia::page-builder.preview.live_hint') }}
                    </p>
                @endif

                {{--
                    The frame renders the draft at a real viewport width and
                    scales whatever the pane cannot fit. Scaling rather than
                    shrinking the viewport is the difference between a preview
                    of the desktop layout and a preview of the narrow one.
                --}}
                <div
                    class="fi-mia-builder-frame"
                    style="--mia-preview-target: {{ $this->previewViewportWidth() }}px"
                    x-data="{
                        fit() {
                            const target = parseFloat(
                                getComputedStyle($el).getPropertyValue('--mia-preview-target'),
                            );

                            if (! target) {
                                return;
                            }

                            const scale = Math.min(1, $el.clientWidth / target);

                            $el.style.setProperty('--mia-preview-scale', scale);

                            // The inner viewport is made taller by the same
                            // factor it is shrunk by, so the scaled result
                            // fills the pane exactly instead of leaving a gap.
                            $el.style.setProperty(
                                '--mia-preview-height',
                                `${$el.clientHeight / scale}px`,
                            );
                        },
                    }"
                    x-init="
                        fit();
                        new ResizeObserver(() => fit()).observe($el);
                    "
                    x-on:mia-refit-page-preview.window="$nextTick(() => fit())"
                >
                    <div class="fi-mia-builder-viewport">
                        <iframe
                            x-ref="frame"
                            src="{{ $this->previewUrl() }}"
                            title="{{ __('filament-mia::page-builder.preview.heading') }}"
                            loading="lazy"
                        ></iframe>
                    </div>
                </div>

                <p class="fi-mia-builder-hint">
                    {{ __('filament-mia::page-builder.preview.rendered_at', [
                        'width' => $this->previewViewportWidth(),
                    ]) }}
                </p>
            </x-filament::section>
        </aside>
    </div>
</x-filament-panels::page>
