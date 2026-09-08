/*
 * The two images the Filament plugin directory asks for.
 *
 *     php vendor/bin/testbench workbench:build
 *     php vendor/bin/testbench serve --host=127.0.0.1 --port=8321
 *     node bin/cover-shots.mjs
 *
 * Light mode, as the form requires, and captured wider than either target so
 * the reduction in `bin/cover.py` does the sharpening.
 */

import { execFileSync } from 'node:child_process'
import { mkdirSync } from 'node:fs'
import { chromium } from 'playwright'

const BASE = process.env.MIA_BASE ?? 'http://127.0.0.1:8321'
const OUT = 'art/verification'

const EMAIL = 'valeria@mia.test'
const PASSWORD = 'password'

// 16:9 at a width where the dashboard still reads as a laptop screen rather
// than a wall. Two device pixels each, so the capture lands at 3200x1800 and
// every target is a reduction.
const VIEWPORT = { width: 1600, height: 900 }

mkdirSync(OUT, { recursive: true })

execFileSync('php', ['vendor/bin/testbench', 'filament:assets'], {
    stdio: 'inherit',
})

const browser = await chromium.launch()

const ctx = await browser.newContext({
    viewport: VIEWPORT,
    deviceScaleFactor: 2,
    colorScheme: 'light',
})

await ctx.addInitScript(() => localStorage.setItem('theme', 'light'))

const page = await ctx.newPage()

await page.goto(`${BASE}/admin/login`, { waitUntil: 'networkidle' })
await page.fill('[wire\\:model="data.email"]', EMAIL)
await page.fill('[wire\\:model="data.password"]', PASSWORD)
await page.locator('button[type=submit]').click()
await page.waitForSelector('.fi-topbar', { timeout: 15000 })

await page.goto(`${BASE}/admin`, { waitUntil: 'networkidle' })

// Nothing hovered and nothing focused: a listing image with a button lit up
// looks like a screenshot taken mid-click.
await page.mouse.move(VIEWPORT.width - 4, VIEWPORT.height - 4)
await page.evaluate(() => document.activeElement?.blur())
await page.evaluate(() => document.fonts.ready)
await page.waitForTimeout(1200)

await page.screenshot({ path: `${OUT}/cover.png` })

await browser.close()

execFileSync('python3', ['bin/cover.py', `${OUT}/cover.png`], {
    stdio: 'inherit',
})
