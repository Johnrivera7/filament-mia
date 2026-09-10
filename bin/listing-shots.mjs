/*
 * The cover and thumbnail the Filament plugin directory asks for.
 *
 *     node bin/listing-shots.mjs
 *     MIA_REUSE=1 node bin/listing-shots.mjs   # skip the demo, recompose only
 *
 * The directory shows these in a grid beside every other theme, so a bare
 * screenshot loses: at that size the panel is a grey rectangle and the reader
 * has no idea what they are looking at. The listings that read well are
 * composed — a headline, a mark, a claim or two, and the panel shown as an
 * object rather than as the whole frame. So this captures the demo and then
 * lays it out, rather than cropping a browser window and hoping.
 *
 * Every claim printed on the image is checked in the source before it goes on:
 * five sign-in layouts is `LoginLayout`, the appearance page is a real page,
 * the warm dark ramp is `TokenSheet::darkTokens()`, the error and maintenance
 * screens are `resources/views/http`, and the pre-compiled stylesheet is what
 * `resources/dist/mia.css` is for.
 *
 * A "landing page builder" pill stood here for a while, with a note saying not
 * to publish until `pageBuilder()` reached main. Nothing in the package
 * answers to that name yet, and these two images exist to be published now, so
 * the slot went to the error and maintenance pages, which shipped. Put it back
 * when there is something to point at.
 *
 * This went through twenty-two covers and three thumbnails before a pair was
 * chosen, written side by side under `art/listing-candidates` so they could be
 * compared. Both the rejects and the folder are gone: the two compositions
 * below are the two that ship, they are written straight to `art/`, and a
 * script that also produces two dozen images nobody wants is one whose output
 * has to be sorted through before it can be used. The frames photographed on
 * the way are working files and land in `build/listing`, which is not
 * committed.
 */

import { execFileSync } from 'node:child_process'
import { existsSync, mkdirSync, readFileSync, writeFileSync } from 'node:fs'
import { chromium } from 'playwright'

const DEMO = process.env.MIA_DEMO ?? 'http://mia-demo.test'
const EMAIL = 'john@mia.test'
const PASSWORD = 'password'
const REUSE = process.env.MIA_REUSE === '1'


const ART = 'art'

/* The frames and the rendered sheets are working files: only the two encoded
 * JPEGs belong in `art/`. */
const FRAMES = 'build/listing'

mkdirSync(FRAMES, { recursive: true })

/* The panel is captured well above its final size: every placement on the
 * canvas is then a reduction, which is what keeps the type crisp. */
const PANEL = { width: 1560, height: 950 }

/**
 * The one page the scene is built on, with the viewport height that ends it on
 * a clean edge rather than through the middle of a widget.
 *
 * The board and the appearance page were photographed here too, for the
 * compositions that used them as a second plane. The chosen one does not, and
 * a capture nothing places is three seconds of the demo's time and a file to
 * ignore later.
 *
 * At 950 the dashboard stops just above the figures, which are the reason to
 * show the dashboard at all. 1080 ends in the gutter under the stat row.
 */
const PAGES = [['dashboard', '/app', 1080, 0]]

/**
 * Widgets lifted off the dashboard to float in front of it.
 *
 * A whole page reduced to a third of its width is unreadable, and so is a card
 * of body copy — the first attempt used the appearance page's preset list and
 * it went to grey mush in the grid. These two are shape and figure: a ring with
 * one percentage in it, and a year of activity as a field of squares. Both hold
 * their meaning all the way down to the directory's thumbnail.
 */
const DETAILS = [
    ['detail-targets', 'Targets for the quarter'],
    ['detail-pulse', 'Workspace pulse'],
]

const shotPath = (name) => `${FRAMES}/panel-${name}.png`

async function capture() {
    const browser = await chromium.launch()

    const ctx = await browser.newContext({
        viewport: PANEL,
        deviceScaleFactor: 2,
        colorScheme: 'light',
        reducedMotion: 'reduce',
    })

    await ctx.addInitScript(() => localStorage.setItem('theme', 'light'))

    const page = await ctx.newPage()

    await page.goto(`${DEMO}/app/login`, { waitUntil: 'networkidle' })

    // The whole sign-in page was photographed here on the way past, for the
    // compositions that placed it entire. The chosen one places the card, which
    // is cropped from a committed frame further down instead.

    // Filament renders the fields through Livewire, so they are addressed by
    // the component's own ids rather than by input type.
    await page.fill('#form\\.email', EMAIL)
    await page.fill('#form\\.password', PASSWORD)
    await page.click('button[type="submit"]')

    // `/app/login` matches any /app pattern, so waiting on one lets the script
    // sail past a failed sign-in and screenshot the login page three times.
    // Wait for the login segment to go away instead.
    await page.waitForURL((url) => !String(url).includes('/login'), { timeout: 30_000 })
    await page.waitForLoadState('networkidle')

    for (const [name, path, height, scroll] of PAGES) {
        await page.setViewportSize({ width: PANEL.width, height })
        await page.goto(`${DEMO}${path}`, { waitUntil: 'networkidle' })

        // Widgets arrive deferred; the charts animate in. Give both a beat,
        // then settle the page so the crop is predictable.
        await page.waitForTimeout(3000)
        await page.evaluate((y) => window.scrollTo(0, y), scroll)
        await page.waitForTimeout(600)

        await page.screenshot({ path: shotPath(name) })
    }

    await page.setViewportSize(PANEL)
    await page.goto(`${DEMO}/app`, { waitUntil: 'networkidle' })
    await page.waitForTimeout(3000)

    for (const [name, heading] of DETAILS) {
        // By heading rather than by position: the widgets were addressed by
        // index and the ring quietly became the stat row when the order
        // changed, which the script had no way to notice.
        const card = page
            .locator('.fi-section')
            .filter({ hasText: heading })
            .first()

        await card.scrollIntoViewIfNeeded()
        await page.waitForTimeout(600)
        await card.screenshot({ path: shotPath(name) })
    }

    await browser.close()
}

/* One of the pills claims five sign-in layouts; showing one of them turns that
 * from an assertion into something the reader can see. The demo serves the
 * bleed layout, whose warm gradient field is the same family as the canvas
 * behind it, so the screen joins the scene instead of sitting on it as a patch.
 *
 * It goes in cropped to the card with a margin of gradient left around it,
 * which is a compromise between the two ways of getting it wrong. Whole, the
 * screen is mostly empty field: at a size where the form is legible it takes a
 * third of the canvas, and at a size that fits it is a pale rectangle that
 * dissolves into the cream — cover-m through cover-r are that experiment, and
 * the sign-in reads as a smear across the dashboard in all of them. Trimmed to
 * the card alone it would have no edge either. Trimmed with the gradient
 * margin it keeps the tie to the canvas and gains a border, and cover-v is
 * that. The crop is taken from the committed 1280px capture rather than from
 * the live `signin` frame because the demo's card carries a demo-account block
 * that makes it half as wide again and leaves no room for the ring beside it.
 */
const LOGIN_CARD = [232, 176, 1049, 624]

function cropLogin() {
    execFileSync('python3', [
        '-c',
        'import sys; from PIL import Image; '
            + 'Image.open(sys.argv[1]).crop(tuple(int(n) for n in sys.argv[3:])).save(sys.argv[2])',
        `${ART}/login-bleed-light-desktop.jpg`,
        shotPath('login'),
        ...LOGIN_CARD.map(String),
    ])
}

const FRAME_NAMES = [...PAGES.map(([name]) => name), ...DETAILS.map(([name]) => name)]

if (!REUSE || !FRAME_NAMES.every((name) => existsSync(shotPath(name)))) {
    await capture()
}

if (!REUSE || !existsSync(shotPath('login'))) {
    cropLogin()
}

/* ------------------------------------------------------------------ *
 * The composition.
 * ------------------------------------------------------------------ */

const inline = (file) => `data:image/png;base64,${readFileSync(file).toString('base64')}`

const asset = (name) => inline(shotPath(name))
const avatar = inline(`${ART}/mia-avatar-512.png`)

/* ------------------------------------------------------------------ *
 * The scene.
 *
 * The screens are placed in one perspective rather than being pasted flat:
 * a shared vanishing point on `.stage` and a `translateZ` per screen, so they
 * read as three objects at three distances instead of three rectangles. The
 * angle is deliberately shallow — past about 14deg the panel's own type starts
 * to smear, and the point is to show a legible interface.
 * ------------------------------------------------------------------ */

/**
 * A screen in the scene.
 *
 * @typedef {object} Screen
 * @property {string} shot     key under `build/listing/panel-*.png`
 * @property {number} width    in the 1600x900 design space
 * @property {number} x        left offset within the stage
 * @property {number} y        top offset within the stage
 * @property {number} z        distance; negative is further away
 * @property {number} turn     rotateY, degrees
 * @property {number} tip      rotateX, degrees
 * @property {number} roll     in-plane rotate, degrees
 * @property {number} [lift]   shadow strength, 1 is the base
 */

/** @param {Screen} s */
const screenCss = (s, i, px) => `
  .shot-${i} {
      left: ${px(s.x)};
      top: ${px(s.y)};
      width: ${px(s.width)};
      transform:
          translateZ(${px(s.z)})
          rotateY(${s.turn}deg)
          rotateX(${s.tip}deg)
          rotate(${s.roll}deg);
      box-shadow:
          0 ${px(48 * (s.lift ?? 1))} ${px(110 * (s.lift ?? 1))} -${px(34)} rgba(97, 70, 40, ${0.34 * (s.lift ?? 1)}),
          0 ${px(10 * (s.lift ?? 1))} ${px(30)} -${px(14)} rgba(97, 70, 40, ${0.18 * (s.lift ?? 1)});
  }
`

/**
 * @param {object} spec
 * @param {number} spec.scale
 * @param {boolean} spec.tight
 * @param {Screen[]} spec.screens
 * @param {number} spec.perspective
 * @param {string} spec.origin
 */
const canvas = ({ scale, tight, screens, perspective, origin }) => {
    const px = (n) => `${n * scale}px`

    return `
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=fraunces:600,700|manrope:400,500,600" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  html, body {
      width: ${px(1600)};
      height: ${px(900)};
      overflow: hidden;
  }

  body {
      font-family: Manrope, system-ui, sans-serif;
      font-size: ${px(16)};
      color: #3B322A;
      /* The theme's own light surfaces: cream through champagne, warmed at
       * the top left where the mark sits. */
      background:
          radial-gradient(120% 90% at 8% 0%, #FDF6EC 0%, #FBF1E4 38%, #F6E9DA 68%, #F1E1CE 100%);
      position: relative;
  }

  /* A single hairline arc, the same gesture the theme uses for its borders. */
  body::after {
      content: '';
      position: absolute;
      right: ${px(-300)};
      top: ${px(-340)};
      width: ${px(980)};
      height: ${px(980)};
      border-radius: 50%;
      border: ${px(1)} solid rgba(176, 137, 90, .30);
      pointer-events: none;
  }

  .sheet {
      position: relative;
      z-index: 1;
      height: 100%;
      display: grid;
      grid-template-columns: ${px(tight ? 590 : 600)} 1fr;
      gap: ${px(tight ? 20 : 40)};
      padding: ${px(tight ? 66 : 76)};
      align-items: center;
  }

  .word { display: flex; flex-direction: column; align-items: flex-start; }

  .mark {
      display: flex;
      align-items: center;
      gap: ${px(13)};
      margin-bottom: ${px(tight ? 30 : 30)};
  }

  .mark img {
      width: ${px(tight ? 66 : 58)};
      height: ${px(tight ? 66 : 58)};
      border-radius: 50%;
      border: ${px(1)} solid rgba(176, 137, 90, .40);
  }

  .mark span {
      font-family: Fraunces, Georgia, serif;
      font-weight: 600;
      font-size: ${px(tight ? 44 : 38)};
      letter-spacing: ${px(-0.5)};
      color: #2E2620;
  }

  h1 {
      font-family: Fraunces, Georgia, serif;
      font-weight: 700;
      font-size: ${px(tight ? 66 : 56)};
      line-height: 1.06;
      letter-spacing: ${px(-1.2)};
      color: #2A231D;
  }

  h1 em { font-style: italic; color: #A9743A; }

  p.lede {
      margin-top: ${px(22)};
      font-size: ${px(21)};
      line-height: 1.5;
      color: #6B5A4A;
      max-width: ${px(520)};
  }

  ul {
      list-style: none;
      display: grid;
      grid-template-columns: auto auto;
      justify-content: start;
      gap: ${px(tight ? 13 : 11)};
      margin-top: ${px(30)};
  }

  li {
      font-size: ${px(tight ? 21 : 17)};
      font-weight: 600;
      color: #6A5233;
      background: rgba(255, 252, 246, .86);
      border: ${px(1)} solid rgba(176, 137, 90, .32);
      border-radius: ${px(999)};
      padding: ${px(tight ? 13 : 11)} ${px(tight ? 24 : 20)};
      white-space: nowrap;
      /* Wide, soft and tinted rather than black, like every other lifted
       * surface in the theme. */
      box-shadow: 0 ${px(10)} ${px(28)} -${px(14)} rgba(120, 88, 52, .30);
  }

  code {
      margin-top: ${px(tight ? 34 : 28)};
      font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
      font-size: ${px(tight ? 20 : 16)};
      color: #7A6248;
      letter-spacing: ${px(-0.2)};
  }

  code b { font-weight: 600; color: #4A3B2C; }

  /* One vanishing point for the whole scene. Without this each screen would
   * carry its own perspective and they would not read as one arrangement. */
  .stage {
      position: relative;
      height: 100%;
      perspective: ${px(perspective)};
      perspective-origin: ${origin};
      transform-style: preserve-3d;
  }

  .shot {
      position: absolute;
      border-radius: ${px(16)};
      border: ${px(1)} solid rgba(176, 137, 90, .34);
      overflow: hidden;
      background: #FFFDF9;
      transform-style: preserve-3d;
      will-change: transform;
  }

  .shot img { display: block; width: 100%; }

${screens.map((s, i) => screenCss(s, i, px)).join('')}
</style>
</head>
<body>
  <div class="sheet">
    <div class="word">
      <div class="mark">
        <img src="${avatar}" alt="">
        <span>Mía</span>
      </div>
      <h1>A Filament theme<br>that keeps a panel <em>calm</em>.</h1>
      ${tight ? '' : `<p class="lede">Warm cream surfaces, serif headings and hairline borders — a component layer rewritten by hand, not a repainted palette.</p>`}
      <ul>
        <li>5 sign-in layouts</li>
        <li>Appearance page in the panel</li>
        ${tight ? '' : '<li>Light and dark, both warm</li>'}
        ${tight ? '' : '<li>Error and maintenance pages</li>'}
        ${tight ? '' : '<li>Pre-compiled — no build step</li>'}
      </ul>
      <code>composer require <b>johnrivera7/filament-mia-theme</b></code>
    </div>
    <div class="stage">
${screens.map((s, i) => `      <div class="shot shot-${i}"><img src="${asset(s.shot)}" alt=""></div>`).join('\n')}
    </div>
  </div>
</body>
</html>
`
}

/**
 * Both images are drawn at 2560x1440 and the thumbnail is reduced from there:
 * laying out at 1280 wide gives fractional borders and mushy type, whereas a
 * Lanczos reduction of a 2x drawing keeps the hairlines.
 */
const render = async (name, spec, out = `${ART}/${name}.jpg`) => {
    const shot = await chromium.launch()

    const context = await shot.newContext({
        viewport: { width: 2560, height: 1440 },
        deviceScaleFactor: 1,
    })

    const sheet = await context.newPage()

    writeFileSync(`${FRAMES}/${name}.html`, canvas({ scale: 1.6, ...spec }))
    // file:// with inlined data URIs never settles to networkidle.
    await sheet.goto(`file://${process.cwd()}/${FRAMES}/${name}.html`, {
        waitUntil: 'load',
    })
    await sheet.evaluate(() => document.fonts.ready)
    await sheet.waitForTimeout(500)

    await sheet.screenshot({ path: `${FRAMES}/${name}.png` })
    await shot.close()

    console.log(
        execFileSync('python3', [
            'bin/listing-encode.py',
            `${FRAMES}/${name}.png`,
            out,
            String(spec.width),
            String(spec.height),
        ]).toString().trim(),
    )
}

const COVER = { width: 2560, height: 1440, tight: false }
const THUMB = { width: 1280, height: 720, tight: true }

/**
 * Where the canvas cuts the dashboard matters more than how much of it shows.
 * At 1120 wide the right edge landed 49px into the "Targets for the quarter"
 * card and sliced its heading mid-word, which reads as a mistake. Taking the
 * panel wider pushes the cut back into the assistant card's body, where an
 * interrupted line reads as a frame too small to hold the panel — the effect
 * the bleed is there for — and frees the ring to float in front instead of
 * appearing twice.
 *
 * @type {Record<string, object>}
 */
const VARIANTS = {
    /* The arrangement settled on: the dashboard turned away at the back, and
     * three foreground objects stepped down the frame from left to right
     * rather than stacked on the right.
     *
     * The order they arrived in is worth keeping, because each one moved for a
     * reason. The ring and the heatmap came first — they are the two cards that
     * are shape and one figure, so they hold their meaning all the way down to
     * the directory's grid. The heatmap sits right of the pills, where an
     * earlier placement was clipping the last word off "Pre-compiled — no build
     * step". The sign-in card came last and had to find a pocket that cost
     * neither of them, which is the gap between the ring and the heatmap.
     *
     * It is pulled fully inside the frame on purpose. The dashboard already
     * bleeds off the right edge; a second object cut by the same edge at a
     * different depth reads as an accident rather than as depth. Whole, the
     * card keeps its border and shadow all the way round, which is what makes
     * it a separate screen instead of a hole in the dashboard. */
    'listing-cover': {
        ...COVER,
        perspective: 2500,
        origin: '6% 46%',
        screens: [
            { shot: 'dashboard', width: 1400, x: 60, y: -80, z: -180, turn: 12, tip: 2, roll: -1 },
            { shot: 'login', width: 450, x: 400, y: 165, z: 60, turn: 8, tip: 0.5, roll: -1.5, lift: 1.2 },
            { shot: 'detail-pulse', width: 700, x: 20, y: 555, z: 200, turn: 7, tip: 0, roll: -1.6, lift: 1.3 },
            { shot: 'detail-targets', width: 215, x: 105, y: 60, z: 300, turn: 5, tip: 0, roll: -2, lift: 1.45 },
        ],
    },

    /* The same scene, at the size the directory actually lists it.
     *
     * The thumbnail is not the cover reduced. Halving 2560 to 1280 halves the
     * type with it, and the lede, three of the pills and the panel's own
     * navigation all fall below the size at which they say anything — a
     * paragraph of grey dashes reads as a mistake where nothing at all reads as
     * restraint. So `tight` takes them out and draws what is left larger: the
     * mark, the headline, two claims and the command.
     *
     * The scene loses an object for the same reason. The heatmap is the one
     * that goes: it is the widest of the three and it is the one whose meaning
     * is carried by the size of its squares, which at this scale are a texture.
     * The ring and the sign-in card keep the cover's diagonal between them —
     * ring high on the left, card stepped down to the right — so this reads as
     * the same picture seen closer rather than as a second idea. Both are drawn
     * larger than the cover has them, which is the point of dropping the third.
     */
    'listing-thumbnail': {
        ...THUMB,
        perspective: 2400,
        origin: '6% 48%',
        screens: [
            { shot: 'dashboard', width: 1300, x: 30, y: -60, z: -140, turn: 12, tip: 2, roll: -1 },
            { shot: 'login', width: 540, x: 235, y: 420, z: 180, turn: 8, tip: 0.5, roll: -1.6, lift: 1.3 },
            { shot: 'detail-targets', width: 260, x: 30, y: 90, z: 300, turn: 5, tip: 0, roll: -2, lift: 1.45 },
        ],
    },
}

/* Either image on its own, for when only one of them is being worked on:
 *
 *     node bin/listing-shots.mjs listing-thumbnail
 */
const only = process.argv.slice(2)

for (const [name, spec] of Object.entries(VARIANTS)) {
    if (only.length && !only.includes(name)) {
        continue
    }

    await render(name, spec)
}
