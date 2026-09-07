{{--
    The preview stylesheet is written into `document.head` rather than into
    this template. A `<style>` rendered here would be inside the Livewire
    component, and every re-render would discard it — which is precisely what
    happens on each keystroke that updates the preview.
--}}
<x-filament-panels::page>
    <div
        x-data="{
            styleId: 'mia-preview',

            apply(css, fonts) {
                let style = document.getElementById(this.styleId)

                if (! style) {
                    style = document.createElement('style')
                    style.id = this.styleId
                    document.head.appendChild(style)
                }

                style.textContent = css

                fonts.forEach((href) => this.loadFont(href))
            },

            loadFont(href) {
                if (document.querySelector(`link[href='${href}']`)) {
                    return
                }

                const link = document.createElement('link')
                link.rel = 'stylesheet'
                link.href = href
                document.head.appendChild(link)
            },

            setMode(mode) {
                window.dispatchEvent(
                    new CustomEvent('theme-changed', { detail: mode }),
                )
            },
        }"
        x-on:mia-preview.window="apply($event.detail.css, $event.detail.fonts)"
        class="fi-mia-customizer"
    >
        <div class="fi-mia-customizer-modes">
            <span class="fi-mia-customizer-modes-label">
                {{ __('filament-mia::customizer.mode.label') }}
            </span>

            @if (filament()->hasDarkMode())
                <x-filament::button
                    color="gray"
                    size="sm"
                    outlined
                    x-on:click="setMode('light')"
                >
                    {{ __('filament-mia::customizer.mode.light') }}
                </x-filament::button>

                <x-filament::button
                    color="gray"
                    size="sm"
                    outlined
                    x-on:click="setMode('dark')"
                >
                    {{ __('filament-mia::customizer.mode.dark') }}
                </x-filament::button>

                <x-filament::button
                    color="gray"
                    size="sm"
                    outlined
                    x-on:click="setMode('system')"
                >
                    {{ __('filament-mia::customizer.mode.system') }}
                </x-filament::button>
            @else
                <p class="fi-mia-customizer-modes-note">
                    {{ __('filament-mia::customizer.mode.disabled') }}
                </p>
            @endif
        </div>

        {{ $this->form }}

        {{--
            A specimen of the components the settings affect most, so the
            effect of a change is visible without leaving the page.
        --}}
        <x-filament::section
            :heading="__('filament-mia::customizer.specimen.heading')"
            :description="__('filament-mia::customizer.specimen.description')"
        >
            <div class="fi-mia-specimen">
                <h3 class="fi-mia-specimen-heading">
                    {{ __('filament-mia::customizer.specimen.sample_heading') }}
                </h3>

                <p class="fi-mia-specimen-body">
                    {{ __('filament-mia::customizer.specimen.sample_body') }}
                </p>

                <div class="fi-mia-specimen-row">
                    <x-filament::button>
                        {{ __('filament-mia::customizer.specimen.primary_action') }}
                    </x-filament::button>

                    <x-filament::button color="gray" outlined>
                        {{ __('filament-mia::customizer.specimen.secondary_action') }}
                    </x-filament::button>

                    <x-filament::badge color="success">
                        {{ __('filament-mia::customizer.specimen.badge_success') }}
                    </x-filament::badge>

                    <x-filament::badge color="warning">
                        {{ __('filament-mia::customizer.specimen.badge_warning') }}
                    </x-filament::badge>

                    <x-filament::badge color="danger">
                        {{ __('filament-mia::customizer.specimen.badge_danger') }}
                    </x-filament::badge>

                    <x-filament::badge color="info">
                        {{ __('filament-mia::customizer.specimen.badge_info') }}
                    </x-filament::badge>
                </div>

                <div class="fi-mia-specimen-figures">
                    <span>1,284.50</span>
                    <span>0.075</span>
                    <span>10,900</span>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
