/*
 * The README's screenshots of a working panel.
 *
 * The sign-in compositions come from the package's own preview panel
 * (`bin/shots.mjs`); a panel with data in it does not, so these are taken from
 * the demo application, which runs on invented records and installs the theme
 * from a path repository.
 *
 *     MIA_DEMO=http://mia-demo.test node bin/demo-shots.mjs
 *
 * Nothing here is captured from a real system. Frames land in `art/` as
 * `demo-*`, which is the only naming `.gitignore` allows an image to have.
 */

import { execFileSync } from 'node:child_process'
import { mkdirSync, readdirSync } from 'node:fs'
import { join } from 'node:path'
import { chromium } from 'playwright'

const BASE = process.env.MIA_DEMO ?? 'http://mia-demo.test'
const OUT = 'art'

const EMAIL = 'valeria@mia.test'
const PASSWORD = 'password'

const DESKTOP = { width: 1440, height: 900 }
const PHONE = { width: 390, height: 844 }

mkdirSync(OUT, { recursive: true })

const browser = await chromium.launch()

/*
 * Signed in once, for every context that follows. Filament throttles repeated
 * sign-ins from one address, so a script that logs in per colour mode gets
 * quietly handed the sign-in screen halfway through.
 */
async function signIn() {
    const ctx = await browser.newContext({ viewport: DESKTOP })
    const page = await ctx.newPage()

    await page.goto(`${BASE}/app/login`, { waitUntil: 'networkidle' })
    await page.fill('[wire\\:model="data.email"]', EMAIL)
    await page.fill('[wire\\:model="data.password"]', PASSWORD)
    await page.locator('button[type=submit]').click()

    // Not a URL pattern: `/app/login` matches most patterns for `/app` too,
    // which is how an unauthenticated run once produced a set of screenshots
    // of the sign-in screen.
    await page.waitForSelector('.fi-topbar', { timeout: 20000 })

    const state = await ctx.storageState()
    await ctx.close()

    return state
}

const STATE = await signIn()

async function session(viewport, mode) {
    const ctx = await browser.newContext({
        viewport,
        deviceScaleFactor: 2,
        colorScheme: mode,
        storageState: STATE,
    })

    await ctx.addInitScript(
        ([mode]) => localStorage.setItem('theme', mode),
        [mode],
    )

    const page = await ctx.newPage()

    return { ctx, page }
}

async function settle(page) {
    await page.evaluate(() => document.fonts.ready)
    // Charts are drawn after mount, and the widgets load over Livewire.
    await page.waitForTimeout(1600)
}

async function shoot(page, path, file) {
    await page.goto(`${BASE}${path}`, { waitUntil: 'networkidle' })

    if (page.url().includes('/login')) {
        throw new Error(`Not signed in when capturing ${file}`)
    }

    await settle(page)
    await page.screenshot({ path: `${OUT}/${file}.png` })
}

/*
 * The chart tooltip, cropped to the widget that carries it.
 *
 * Chart.js paints the tooltip onto the canvas on mouse move, so it has to be
 * provoked with a real cursor. The bar chart is the target because a bar is a
 * wide hit area, where a line chart only registers within a few pixels of a
 * point.
 */
async function shootChartTooltip(page, file) {
    await page.goto(`${BASE}/app/analitica`, { waitUntil: 'networkidle' })
    await settle(page)

    const widget = page.locator('.fi-wi-chart').nth(2)
    const box = await widget.locator('canvas').boundingBox()

    await page.mouse.move(box.x + box.width * 0.4, box.y + box.height * 0.18)
    await page.waitForTimeout(600)

    await widget.screenshot({ path: `${OUT}/${file}.png` })
}

/*
 * The sign-in section of the appearance page, cropped to itself: the five
 * compositions with their descriptions and the live preview underneath.
 */
async function shootAppearanceLogin(page, file) {
    await page.goto(`${BASE}/app/theme-customizer`, { waitUntil: 'networkidle' })
    await settle(page)

    const section = page
        .locator('.fi-mia-login-preview')
        .locator('xpath=ancestor::*[contains(@class,"fi-sc-section")][1]')

    await section.scrollIntoViewIfNeeded()

    // The topbar is sticky, so it lands across the top of a cropped section.
    await page.addStyleTag({ content: '.fi-topbar { display: none !important }' })
    await page.waitForTimeout(400)
    await section.screenshot({ path: `${OUT}/${file}.png` })
}

for (const mode of ['light', 'dark']) {
    const { ctx, page } = await session(DESKTOP, mode)

    await shoot(page, '/app', `demo-panel-${mode}`)
    await shoot(page, '/app/proyectos', `demo-table-${mode}`)

    /*
     * One mode each, for the three below: a frame nothing links to is weight
     * in the repository and nothing else. Charts are shown dark, where the
     * greys they used to inherit were most obvious; the tooltip light, where
     * its warm chip reads against the page.
     */
    if (mode === 'dark') {
        await shoot(page, '/app/analitica', 'demo-charts-dark')
    }

    if (mode === 'light') {
        await shoot(page, '/app/theme-customizer', 'demo-appearance-light')
        await shoot(page, '/app/proyectos/create', 'demo-form-light')
        await shootAppearanceLogin(page, 'demo-appearance-login')
        await shootChartTooltip(page, 'demo-chart-tooltip-light')
    }

    await ctx.close()
}

for (const mode of ['light']) {
    const { ctx, page } = await session(PHONE, mode)

    await shoot(page, '/app', `demo-panel-${mode}-mobile`)
    await shoot(page, '/app/proyectos', `demo-table-${mode}-mobile`)

    await ctx.close()
}

await browser.close()

execFileSync(
    'python3',
    [
        'bin/optimize.py',
        ...readdirSync(OUT)
            .filter((f) => f.startsWith('demo-') && f.endsWith('.png'))
            .map((f) => join(OUT, f)),
    ],
    { stdio: 'inherit' },
)

console.log('done')
