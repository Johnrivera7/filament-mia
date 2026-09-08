"""Reduce a rendered sheet to its listing size and encode it as JPEG.

    python3 bin/listing-encode.py in.png out.jpg 2560 1440

The plugin directory takes JPEG under about 500 KB, so quality steps down
until the file fits rather than being guessed at once.
"""

import sys

from PIL import Image

BUDGET = 500 * 1024

source, target, width, height = sys.argv[1], sys.argv[2], int(sys.argv[3]), int(sys.argv[4])

image = Image.open(source).convert("RGB")

if image.size != (width, height):
    image = image.resize((width, height), Image.LANCZOS)

for quality in (92, 88, 84, 80, 76, 72):
    image.save(target, quality=quality, optimize=True, progressive=True, subsampling=0)

    with open(target, "rb") as handle:
        size = len(handle.read())

    if size <= BUDGET:
        break

print(f"{target} {width}x{height} {size / 1024:.0f} KB q{quality}")
