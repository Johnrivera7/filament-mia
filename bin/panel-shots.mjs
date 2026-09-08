/*
 * Screenshots of the panel interior, from the package's own preview panel.
 *
 * Start the panel first:
 *
 *     php vendor/bin/testbench workbench:build
 *     php vendor/bin/testbench serve --host=127.0.0.1 --port=8321
 *     node bin/panel-shots.mjs
 *
 * The panel is in English and its records are seeded from a fixed number, so
 * a capture taken today is comparable with one taken next month, and any label
 * left untranslated shows up in the frame.
 */

import { execFileSync } from 'node:child_process'
import { copyFileSync, mkdirSync, readdirSync } from 'node:fs'
import { join } from 'node:path'
import { chromium } from 'playwright'

const BASE = process.env.MIA_BASE ?? 'http://127.0.0.1:8321'
const OUT = 'art/verification'
const PUBLISHED = 'art'

const README_FRAMES = [
    'panel-dashboard-light.jpg',
    'panel-dashboard-dark.jpg',
    'panel-dashboard-light-mobile.jpg',
    'panel-table-light.jpg',
    'panel-table-dark.jpg',
    'panel-table-light-mobile.jpg',
    'panel-table-empty-light.jpg',
    'panel-form-light.jpg',
    'panel-charts-dark.jpg',
    'panel-chart-tooltip-light.jpg',
    'panel-appearance-light.jpg',
    'panel-appearance-login.jpg',
]

const EMAIL = 'valeria@mia.test'
const PASSWORD = 'password'

const VIEWPORTS = {
    desktop: { width: 1440, height: 900 },
    mobile: { width: 390, height: 844 },
}

mkdirSync(OUT, { recursive: true })

/*
 * The panel serves the copy of the stylesheet under its public directory, not
 * the one `npm run build` writes. Republishing first is what keeps a capture
 * from quietly showing the previous build.
 */
execFileSync('php', ['vendor/bin/testbench', 'filament:assets'], {
    stdio: 'inherit',
})

const browser = await chromium.launch()

/*
 * Signed in once, then replayed into every context.
 *
 * Filament throttles the sign-in form after a handful of attempts a minute,
 * and this script opens five contexts: signing in from each one puts the run
 * over the limit and the later frames come back as the sign-in screen.
 */
const signedIn = await (async () => {
    const ctx = await browser.newContext({ viewport: VIEWPORTS.desktop })
    const page = await ctx.newPage()

    await page.goto(`${BASE}/admin/login`, { waitUntil: 'networkidle' })
    await page.fill('[wire\\:model="data.email"]', EMAIL)
    await page.fill('[wire\\:model="data.password"]', PASSWORD)
    await page.locator('button[type=submit]').click()
    await page.waitForSelector('.fi-topbar', { timeout: 15000 })

    const state = await ctx.storageState()
    await ctx.close()

    return state
})()

async function session(viewport, mode) {
    const ctx = await browser.newContext({
        viewport: VIEWPORTS[viewport],
        deviceScaleFactor: 2,
        colorScheme: mode,
        storageState: signedIn,
    })

    await ctx.addInitScript(
        ([mode]) => localStorage.setItem('theme', mode),
        [mode],
    )

    return await ctx.newPage()
}

async function settle(page) {
    await page.evaluate(() => document.fonts.ready)
    await page.waitForTimeout(900)
}

async function shoot(page, path, file, { fullPage = false } = {}) {
    await page.goto(`${BASE}${path}`, { waitUntil: 'networkidle' })

    if (page.url().includes('/login')) {
        throw new Error(`Not signed in when capturing ${file}`)
    }

    await settle(page)
    await page.screenshot({ path: `${OUT}/${file}.png`, fullPage })
}

for (const [viewport, modes] of [
    ['desktop', ['light', 'dark']],
    ['mobile', ['light']],
]) {
    for (const mode of modes) {
        const page = await session(viewport, mode)
        const suffix = viewport === 'mobile' ? `${mode}-mobile` : mode

        // The dashboard whole, because the widgets below the fold are half of
        // what a dashboard looks like. The lists stay viewport-height: a full
        // page of them is eighteen rows of the same row.
        await shoot(page, '/admin', `panel-dashboard-${suffix}`, {
            fullPage: true,
        })
        await shoot(page, '/admin/projects', `panel-table-${suffix}`)

        if (viewport === 'desktop' && mode === 'light') {
            await shoot(page, '/admin/projects/1/edit', 'panel-form-light')
            await shoot(page, '/admin/theme-customizer', 'panel-appearance-light')
        }

        if (viewport === 'desktop' && mode === 'dark') {
            await shoot(page, '/admin', 'panel-charts-dark')
        }

        await page.context().close()
    }
}

// A list emptied by a search rather than by having nothing in it, which is the
// empty state most panels never write and the one a visitor actually meets.
{
    const page = await session('desktop', 'light')

    await page.goto(`${BASE}/admin/projects`, { waitUntil: 'networkidle' })
    await page.fill('.fi-ta-search-field input', 'annual audit')
    await page.waitForSelector('.fi-ta-empty-state', { timeout: 15000 })
    await settle(page)

    await page.screenshot({ path: `${OUT}/panel-table-empty-light.png` })
    await page.context().close()
}

// The sign-in section of the appearance page, cropped to itself: the five
// compositions with a live preview of the selected one.
{
    const page = await session('desktop', 'light')

    await page.goto(`${BASE}/admin/theme-customizer`, { waitUntil: 'networkidle' })
    await settle(page)

    // Hidden before anything is measured: the topbar is sticky, and taking it
    // out after scrolling shifts the page up by its height, which is how the
    // crop ends up carrying a strip of the card above.
    await page.addStyleTag({ content: '.fi-topbar { display: none !important }' })
    await page.waitForTimeout(300)

    /*
     * Clipped out of a full-page capture rather than screenshotting the
     * element. An element capture scrolls to it first, and a page with a
     * sticky header composites what was on screen before the scroll into the
     * top of the frame.
     */
    const box = await page.evaluate(() => {
        const section = document
            .querySelector('.fi-mia-login-preview')
            .closest('.fi-sc-section')
        const rect = section.getBoundingClientRect()

        return {
            x: rect.x + window.scrollX,
            y: rect.y + window.scrollY,
            width: rect.width,
            height: rect.height,
        }
    })

    await page.screenshot({
        path: `${OUT}/panel-appearance-login.png`,
        fullPage: true,
        clip: box,
    })

    await page.context().close()
}

// The tooltip, which is the one chart element that only exists on hover and
// the one most likely to be left in Filament's greys by a theme.
{
    const page = await session('desktop', 'light')

    await page.goto(`${BASE}/admin`, { waitUntil: 'networkidle' })
    await settle(page)

    const widget = page.locator('.fi-wi-chart').nth(1)
    const canvas = await widget.locator('canvas').boundingBox()

    await page.mouse.move(
        canvas.x + canvas.width * 0.45,
        canvas.y + canvas.height * 0.55,
    )
    await page.waitForTimeout(700)

    await widget.screenshot({ path: `${OUT}/panel-chart-tooltip-light.png` })
    await page.context().close()
}

await browser.close()

execFileSync(
    'python3',
    [
        'bin/optimize.py',
        ...readdirSync(OUT)
            .filter((f) => f.startsWith('panel-') && f.endsWith('.png'))
            .map((f) => join(OUT, f)),
    ],
    { stdio: 'inherit' },
)

for (const frame of README_FRAMES) {
    copyFileSync(join(OUT, frame), join(PUBLISHED, frame))
}
