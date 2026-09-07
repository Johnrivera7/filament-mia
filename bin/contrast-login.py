"""
Pixel side of `bin/contrast-login.mjs`.

Takes the manifest that script writes, averages each background sample and
reports the WCAG 2.1 contrast ratio against the text colour that was measured
on the page.

The averaging matters for these screens: a gradient behind a line of text has
no single value, and the blur behind frosted glass has whatever the field
underneath it contributed. An average is the honest summary of a backdrop that
varies, and the variation across one line of text is small enough that it does
not hide a failure at either end.
"""

import json
import sys

from PIL import Image

AA_NORMAL = 4.5
AA_LARGE = 3.0

# Text that is 24px or heavier-and-18.66px counts as large under 1.4.3. The
# headings and the stage type are both well past it, so they are held to 3:1;
# everything else to 4.5:1.
LARGE = {'heading', 'stage name'}


def channel(value: float) -> float:
    value /= 255

    return value / 12.92 if value <= 0.04045 else ((value + 0.055) / 1.055) ** 2.4


def luminance(rgb: tuple[float, float, float]) -> float:
    r, g, b = (channel(c) for c in rgb)

    return 0.2126 * r + 0.7152 * g + 0.0722 * b


def ratio(a: tuple[float, float, float], b: tuple[float, float, float]) -> float:
    first, second = sorted((luminance(a), luminance(b)), reverse=True)

    return (first + 0.05) / (second + 0.05)


def parse(colour: list[float]) -> tuple[float, float, float]:
    return tuple(colour[:3])


def average(path: str) -> tuple[float, float, float]:
    image = Image.open(path).convert('RGB')
    pixels = list(image.getdata())

    return tuple(sum(c) / len(pixels) for c in zip(*pixels))


def main(manifest: str) -> int:
    samples = json.load(open(manifest))

    rows = []
    failures = 0

    for sample in samples:
        text = parse(sample['colour'])
        background = average(sample['file'])
        measured = ratio(text, background)

        required = AA_LARGE if sample['name'] in LARGE else AA_NORMAL
        passed = measured >= required

        failures += not passed

        rows.append(
            (
                sample['layout'],
                sample['mode'],
                sample['name'],
                f'{measured:.2f}:1',
                f'{required:.1f}:1',
                'pass' if passed else 'FAIL',
            )
        )

    widths = [max(len(str(row[i])) for row in rows) for i in range(6)]

    for row in rows:
        print('  '.join(str(cell).ljust(widths[i]) for i, cell in enumerate(row)))

    print()
    print(f'{len(rows)} pairs measured, {failures} below AA')

    return 1 if failures else 0


if __name__ == '__main__':
    sys.exit(main(sys.argv[1]))
