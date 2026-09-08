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
 * the warm dark ramp is `TokenSheet::darkTokens()`, and the pre-compiled
 * stylesheet is what `resources/dist/mia.css` is for.
 */

import { execFileSync } from 'node:child_process'
import { existsSync, mkdirSync, readFileSync, writeFileSync } from 'node:fs'
import { chromium } from 'playwright'

const DEMO = process.env.MIA_DEMO ?? 'http://mia-demo.test'
const EMAIL = 'valeria@mia.test'
const PASSWORD = 'password'
const REUSE = process.env.MIA_REUSE === '1'

const ART = 'art'
const SHOTS = 'art/listing-candidates'

mkdirSync(SHOTS, { recursive: true })

/* The panel is captured well above its final size: every placement on the
 * canvas is then a reduction, which is what keeps the type crisp. */
const PANEL = { width: 1560, height: 950 }

/** Full-panel screens, and the section of the appearance page shown as a
 * floating detail. A whole page reduced to a third of its width is unreadable;
 * one section of it still is. */
const PAGES = [
    ['workspace', '/app'],
    ['board', '/app/tablero'],
    ['appearance', '/app/theme-customizer'],
]

/** Sections lifted out of the appearance page, by their heading. */
const DETAILS = [
    ['detail-presets', 'Presets'],
    ['detail-colour', 'Colour'],
]

const shotPath = (name) => `${SHOTS}/panel-${name}.png`

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

    for (const [name, path] of PAGES) {
        await page.goto(`${DEMO}${path}`, { waitUntil: 'networkidle' })

        // Widgets arrive deferred; the charts animate in. Give both a beat,
        // then settle the page at the top so the crop is predictable.
        await page.waitForTimeout(2500)
        await page.evaluate(() => window.scrollTo(0, 0))
        await page.waitForTimeout(400)

        await page.screenshot({ path: shotPath(name) })
    }

    // Still on the appearance page from the loop above.
    for (const [name, heading] of DETAILS) {
        const section = page
            .locator('section')
            .filter({ has: page.getByRole('heading', { name: heading, exact: true }) })
            .first()

        await section.scrollIntoViewIfNeeded()
        await page.waitForTimeout(500)
        await section.screenshot({ path: shotPath(name) })
    }

    await browser.close()
}

if (!REUSE || !PAGES.concat(DETAILS).every(([name]) => existsSync(shotPath(name)))) {
    await capture()
}

/* ------------------------------------------------------------------ *
 * The composition.
 * ------------------------------------------------------------------ */

const inline = (file) => `data:image/png;base64,${readFileSync(file).toString('base64')}`

const asset = (name) => inline(shotPath(name))
const avatar = inline(`${ART}/mia-avatar-512.png`)

/**
 * The sheet is authored once at 1600x900 and multiplied up, so the cover and
 * the thumbnail are the same drawing at two sizes rather than two drawings.
 *
 * `tight` pulls the frame in for the thumbnail: at 1280 wide the lede and the
 * fourth pill are noise, and the panel wants to be larger, not smaller.
 *
 * @param {{ scale: number, tight: boolean }} spec
 */
const canvas = ({ scale, tight }) => {
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
      grid-template-columns: ${px(tight ? 560 : 600)} 1fr;
      gap: ${px(tight ? 36 : 48)};
      padding: ${px(tight ? 62 : 76)};
      align-items: center;
  }

  .word { display: flex; flex-direction: column; align-items: flex-start; }

  .mark {
      display: flex;
      align-items: center;
      gap: ${px(13)};
      margin-bottom: ${px(tight ? 26 : 30)};
  }

  .mark img {
      width: ${px(tight ? 60 : 58)};
      height: ${px(tight ? 60 : 58)};
      border-radius: 50%;
      border: ${px(1)} solid rgba(176, 137, 90, .40);
  }

  .mark span {
      font-family: Fraunces, Georgia, serif;
      font-weight: 600;
      font-size: ${px(tight ? 40 : 38)};
      letter-spacing: ${px(-0.5)};
      color: #2E2620;
  }

  h1 {
      font-family: Fraunces, Georgia, serif;
      font-weight: 700;
      font-size: ${px(tight ? 62 : 56)};
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
      gap: ${px(11)};
      margin-top: ${px(tight ? 30 : 30)};
  }

  li {
      font-size: ${px(tight ? 19 : 17)};
      font-weight: 600;
      color: #6A5233;
      background: rgba(255, 252, 246, .86);
      border: ${px(1)} solid rgba(176, 137, 90, .32);
      border-radius: ${px(999)};
      padding: ${px(tight ? 12 : 11)} ${px(tight ? 22 : 20)};
      white-space: nowrap;
      /* Wide, soft and tinted rather than black, like every other lifted
       * surface in the theme. */
      box-shadow: 0 ${px(10)} ${px(28)} -${px(14)} rgba(120, 88, 52, .30);
  }

  code {
      margin-top: ${px(tight ? 30 : 28)};
      font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
      font-size: ${px(tight ? 18 : 16)};
      color: #7A6248;
      letter-spacing: ${px(-0.2)};
  }

  code b { font-weight: 600; color: #4A3B2C; }

  .stage { position: relative; height: 100%; }

  .shot {
      position: absolute;
      border-radius: ${px(18)};
      border: ${px(1)} solid rgba(176, 137, 90, .34);
      box-shadow:
          0 ${px(44)} ${px(96)} -${px(32)} rgba(97, 70, 40, .42),
          0 ${px(8)} ${px(24)} -${px(12)} rgba(97, 70, 40, .20);
      overflow: hidden;
      background: #FFFDF9;
  }

  .shot img { display: block; width: 100%; }

  /* The panel runs off the right edge on purpose: it reads as an object the
   * frame is too small to hold, not as a shrunken screenshot. The sidebar and
   * the first column of content stay inside the frame. */
  .shot.back {
      top: ${px(tight ? 6 : 22)};
      left: ${px(tight ? 34 : 46)};
      width: ${px(tight ? 1080 : 1010)};
  }

  .shot.front {
      bottom: ${px(tight ? 8 : 18)};
      left: ${px(tight ? -46 : -34)};
      width: ${px(tight ? 700 : 660)};
  }
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
        <li>Dark mode that stays warm</li>
        ${tight ? '' : '<li>Pre-compiled — no build step</li>'}
      </ul>
      <code>composer require <b>johnrivera7/filament-mia-theme</b></code>
    </div>
    <div class="stage">
      <div class="shot back"><img src="${asset('workspace')}" alt=""></div>
      <div class="shot front"><img src="${asset('detail-presets')}" alt=""></div>
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
 *
 * @param {string} name
 * @param {{ scale: number, tight: boolean, width: number, height: number }} spec
 */
const render = async (name, spec) => {
    const shot = await chromium.launch()

    const context = await shot.newContext({
        viewport: { width: 2560, height: 1440 },
        deviceScaleFactor: 1,
    })

    const sheet = await context.newPage()

    writeFileSync(`${SHOTS}/${name}.html`, canvas(spec))
    await sheet.goto(`file://${process.cwd()}/${SHOTS}/${name}.html`, {
        waitUntil: 'networkidle',
    })
    await sheet.evaluate(() => document.fonts.ready)
    await sheet.waitForTimeout(500)

    await sheet.screenshot({ path: `${SHOTS}/${name}.png` })
    await shot.close()

    execFileSync('python3', [
        'bin/listing-encode.py',
        `${SHOTS}/${name}.png`,
        `${ART}/${name}.jpg`,
        String(spec.width),
        String(spec.height),
    ])
}

await render('listing-cover', { scale: 1.6, tight: false, width: 2560, height: 1440 })
await render('listing-thumbnail', { scale: 1.6, tight: true, width: 1280, height: 720 })

console.log('cover and thumbnail written to art/')
