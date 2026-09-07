"""
Turn the raw captures from `bin/shots.mjs` into images fit for a README.

Playwright writes PNG at two device pixels per CSS pixel, which is right for
looking at closely and wrong for a repository: a single desktop frame lands at
over a megabyte, and a full set of them at fifty.

These are photographs of gradients, so PNG has nothing to work with and JPEG
has everything. Resized to a width a README will never exceed and encoded at a
quality where the type stays crisp, a frame comes in around a fifteenth of the
size.

    python3 bin/optimize.py art/login-*.png
"""

import os
import sys

from PIL import Image

WIDTH = 1600
QUALITY = 88


def optimize(path: str) -> tuple[int, int]:
    before = os.path.getsize(path)

    image = Image.open(path).convert('RGB')

    if image.width > WIDTH:
        height = round(image.height * WIDTH / image.width)
        image = image.resize((WIDTH, height), Image.LANCZOS)

    target = path.rsplit('.', 1)[0] + '.jpg'
    image.save(target, 'JPEG', quality=QUALITY, optimize=True, progressive=True)

    os.remove(path)

    return before, os.path.getsize(target)


def main(paths: list[str]) -> int:
    before = after = 0

    for path in paths:
        was, is_now = optimize(path)
        before += was
        after += is_now

    print(f'{len(paths)} images, {before / 1e6:.1f} MB -> {after / 1e6:.1f} MB')

    return 0


if __name__ == '__main__':
    sys.exit(main(sys.argv[1:]))
