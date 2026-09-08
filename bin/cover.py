"""
Turn a raw cover capture into the two images the plugin directory asks for.

The directory wants 16:9 at 2560x1440 or better for the listing's main image,
and optionally a second one at 1280x720 for the grid of plugins. The second is
only worth uploading if it is a different picture: a whole panel shrunk to a
thumbnail is a grey rectangle, so it is cropped to the part that carries the
theme's voice rather than scaled down from the same frame.

Captured wider than either target and reduced here, because downscaling is what
makes text look drawn rather than rendered.

    python3 bin/cover.py art/verification/cover.png
"""

import os
import sys

from PIL import Image

COVER = (2560, 1440)
THUMB = (1280, 720)
QUALITY = 92

# The crop, as a fraction of the capture: the sidebar's lower half, the page
# heading in the display serif and the first stat cards. Left of the charts on
# purpose — a chart at thumbnail size is a squiggle, while type and a warm
# surface still read.
CROP = (0.0, 0.0, 0.62, 0.55)


def save(image: Image.Image, target: str, size: tuple[int, int]) -> int:
    image = image.resize(size, Image.LANCZOS)
    image.save(target, 'JPEG', quality=QUALITY, optimize=True, progressive=True)

    return os.path.getsize(target)


def main(paths: list[str]) -> int:
    if len(paths) != 1:
        print('usage: python3 bin/cover.py <capture.png>')

        return 1

    source = Image.open(paths[0]).convert('RGB')

    cover = save(source, 'art/listing-cover.jpg', COVER)

    left, top, right, bottom = CROP
    crop = source.crop((
        round(source.width * left),
        round(source.height * top),
        round(source.width * right),
        round(source.height * bottom),
    ))

    thumb = save(crop, 'art/listing-thumbnail.jpg', THUMB)

    print(f'cover {cover / 1e6:.2f} MB, thumbnail {thumb / 1e6:.2f} MB')

    return 0


if __name__ == '__main__':
    sys.exit(main(sys.argv[1:]))
