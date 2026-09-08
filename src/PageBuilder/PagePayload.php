<?php

namespace JohnRivera7\FilamentMia\PageBuilder;

/**
 * One version of a page — its blocks, resolved and ready to render.
 *
 * Views only ever receive this, never the raw stored array, so the shape of
 * what is stored can change without every Blade partial having to know.
 */
final class PagePayload
{
    /** @param  array<string, mixed>  $raw */
    private function __construct(private readonly array $raw) {}

    /** @param  array<string, mixed>|null  $raw */
    public static function fromArray(?array $raw): self
    {
        return new self($raw ?? []);
    }

    /**
     * The blocks in the order the builder left them, with the hidden ones
     * dropped.
     *
     * A block switched off is kept in storage — turning it back on has to
     * bring its content with it — but never reaches a view.
     *
     * @return list<array{type: string, data: array<string, mixed>}>
     */
    public function blocks(): array
    {
        $blocks = $this->raw['blocks'] ?? [];

        if (! is_array($blocks)) {
            return [];
        }

        $visible = [];

        foreach ($blocks as $block) {
            if (! is_array($block) || ! is_string($block['type'] ?? null)) {
                continue;
            }

            $data = is_array($block['data'] ?? null) ? $block['data'] : [];

            if (($data['visible'] ?? true) === false) {
                continue;
            }

            $visible[] = ['type' => $block['type'], 'data' => $data];
        }

        return $visible;
    }

    public function isEmpty(): bool
    {
        return $this->blocks() === [];
    }
}
