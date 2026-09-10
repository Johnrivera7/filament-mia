/*
 * Screenshots of the panel in both languages, from the package's own preview
 * panel.
 *
 * Start the panel first:
 *
 *     php vendor/bin/testbench serve --host=127.0.0.1 --port=8321
 *     node bin/locale-shots.mjs
 *
 * Captures the sign-in screen and the appearance page in English and in
 * Spanish, and walks the switcher the way a user does — pick a language,
 * reload, navigate — so the frames double as proof the choice survives.
 */

import { execFileSync } from 'node:child_process'
import { copyFileSync, mkdirSync, readdirSync } from 'node:fs'
import { join } from 'node:path'
import { chromium } from 'playwright'

const BASE = process.env.MIA_BASE ?? 'http://127.0.0.1:8321'
/*
 * The full set is verification material and stays out of the repository. Only
 * the frames the README shows are copied up into `art/`.
 */
const OUT = 'art/verification'
const PUBLISHED = 'art'

const README_FRAMES = [
    'locale-menu-en.jpg',
    'locale-menu-es.jpg',
    'locale-login-es.jpg',
]

const EMAIL = 'john@mia.test'
const PASSWORD = 'password'

const VIEWPORT = { width: 1440, height: 900 }

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

async function settle() {
    await page.evaluate(() => document.fonts.ready)
    await page.waitForTimeout(700)
}

async function shoot(file) {
    await settle()
    await page.screenshot({ path: `${OUT}/${file}.png` })
}

await page.goto(`${BASE}/admin/login`, { waitUntil: 'networkidle' })
await shoot('locale-login-en')

await page.fill('[wire\\:model="data.email"]', EMAIL)
await page.fill('[wire\\:model="data.password"]', PASSWORD)
await page.locator('button[type=submit]').click()
await page.waitForSelector('.fi-topbar', { timeout: 15000 })

console.log('signed in at', page.url())

await page.goto(`${BASE}/admin/theme-customizer`, { waitUntil: 'networkidle' })
await shoot('locale-appearance-en')

// The switcher, opened the way a user opens it.
await page.locator('.fi-user-menu-trigger').click()
await page.waitForTimeout(400)
await shoot('locale-menu-en')

await page.locator('.fi-dropdown-list-item', { hasText: 'Español' }).click()
await page.waitForLoadState('networkidle')
await shoot('locale-appearance-es')

await page.locator('.fi-user-menu-trigger').click()
await page.waitForTimeout(400)
await shoot('locale-menu-es')

// Reload, then navigate: the choice has to survive both.
await page.reload({ waitUntil: 'networkidle' })
await shoot('locale-appearance-es-reloaded')

await page.goto(`${BASE}/admin`, { waitUntil: 'networkidle' })
await shoot('locale-dashboard-es')

const heading = await page.locator('h1').first().textContent()
console.log('heading after reload and navigation:', heading.trim())

/*
 * Everything but the language choice is thrown away, which is the whole
 * argument for keeping it in a cookie: a visitor who was signed out an hour
 * ago still meets the sign-in screen in the language they picked.
 */
const kept = (await ctx.cookies()).filter(
    (cookie) => cookie.name === 'filament_mia_locale',
)

await ctx.clearCookies()
await ctx.addCookies(kept)

await page.goto(`${BASE}/admin/login`, { waitUntil: 'networkidle' })
await shoot('locale-login-es')

console.log('sign-in heading after losing the session:', (
    await page.locator('h1').first().textContent()
).trim())

// The same menu in the warm dark palette, where a badly coloured active state
// would show.
const dark = await browser.newContext({
    viewport: VIEWPORT,
    deviceScaleFactor: 2,
    colorScheme: 'dark',
})

await dark.addInitScript(() => localStorage.setItem('theme', 'dark'))
await dark.addCookies(kept)

const darkPage = await dark.newPage()

await darkPage.goto(`${BASE}/admin/login`, { waitUntil: 'networkidle' })
await darkPage.fill('[wire\\:model="data.email"]', EMAIL)
await darkPage.fill('[wire\\:model="data.password"]', PASSWORD)
await darkPage.locator('button[type=submit]').click()
await darkPage.waitForSelector('.fi-topbar', { timeout: 15000 })

await darkPage.locator('.fi-user-menu-trigger').click()
await darkPage.waitForTimeout(400)
await darkPage.evaluate(() => document.fonts.ready)
await darkPage.waitForTimeout(700)
await darkPage.screenshot({ path: `${OUT}/locale-menu-es-dark.png` })

await browser.close()

execFileSync(
    'python3',
    [
        'bin/optimize.py',
        ...readdirSync(OUT)
            .filter((f) => f.startsWith('locale-') && f.endsWith('.png'))
            .map((f) => join(OUT, f)),
    ],
    { stdio: 'inherit' },
)

for (const frame of README_FRAMES) {
    copyFileSync(join(OUT, frame), join(PUBLISHED, frame))
}
