/*
 * Measured contrast for the sign-in compositions.
 *
 * The colour report in `bin/contrast-report.php` works from the palette, which
 * is enough for flat surfaces. These screens are not flat: two of them put
 * text over a gradient and one puts it over frosted glass, and a ratio
 * calculated against a nominal background would not be a measurement of
 * anything.
 *
 * So this reads pixels. For every piece of text on the screen it takes the
 * computed colour, hides the glyphs, photographs the box they occupied, and
 * averages what is behind them. The ratio is then WCAG 2.1 1.4.3 against the
 * real backdrop, including whatever the blur picked up.
 *
 *     php vendor/bin/testbench serve --host=127.0.0.1 --port=8321
 *     node bin/contrast-login.mjs
 */

import { execFileSync } from 'node:child_process'
import { mkdtempSync, writeFileSync } from 'node:fs'
import { tmpdir } from 'node:os'
import { join } from 'node:path'
import { chromium } from 'playwright'

const BASE = process.env.MIA_BASE ?? 'http://127.0.0.1:8321'

const LAYOUTS = ['card', 'split', 'bleed', 'editorial', 'portal']

const TARGETS = [
    ['heading', '.fi-simple-header-heading'],
    ['subheading', '.fi-simple-header-subheading'],
    ['registration link', '.fi-simple-header-subheading a'],
    ['field label', '.fi-fo-field-wrp-label'],
    ['recovery link', '.fi-fo-field-wrp-hint a'],
    ['remember me', '.fi-fo-checkbox .fi-fo-field-wrp-label'],
    ['submit', '.fi-form-actions .fi-btn-label'],
    ['error message', '.fi-fo-field-wrp-error-message'],
    ['stage name', '.fi-mia-login-stage-name'],
    ['stage tagline', '.fi-mia-login-stage-tagline'],
]

const dir = mkdtempSync(join(tmpdir(), 'mia-contrast-'))

const browser = await chromium.launch()
const samples = []

for (const layout of LAYOUTS) {
    execFileSync(
        'php',
        [
            'vendor/bin/testbench',
            'tinker',
            '--execute',
            `app(\\JohnRivera7\\FilamentMia\\Settings\\Contracts\\SettingsRepository::class)` +
                `->put('preview', \\JohnRivera7\\FilamentMia\\Settings\\ThemeSettings::defaults()` +
                `->with(['login_layout' => '${layout}', 'login_tagline' => 'Client work, kept in one place.'])->toArray());`,
        ],
        { stdio: 'ignore' },
    )

    for (const mode of ['light', 'dark']) {
        const context = await browser.newContext({
            viewport: { width: 1440, height: 900 },
            colorScheme: mode,
        })

        await context.addInitScript(
            ([mode]) => localStorage.setItem('theme', mode),
            [mode],
        )

        const page = await context.newPage()
        await page.goto(`${BASE}/admin/login`, { waitUntil: 'networkidle' })

        // Put a validation error on screen, so its message is measured too.
        execFileSync('php', ['vendor/bin/testbench', 'tinker', '--execute', 'cache()->clear();'], {
            stdio: 'ignore',
        })
        await page.fill('[wire\\:model="data.email"]', 'john@mia.test')
        await page.fill('[wire\\:model="data.password"]', 'not-the-password')
        await page.locator('button[type=submit]').click()
        await page.waitForSelector('.fi-fo-field-wrp-error-message')
        await page.evaluate(() => document.fonts.ready)
        await page.waitForTimeout(600)

        for (const [name, selector] of TARGETS) {
            const target = page.locator(selector).first()

            if ((await target.count()) === 0 || !(await target.isVisible())) {
                continue
            }

            /*
             * Read back through a canvas rather than parsing the string.
             * Computed colours on this theme serialise as `oklch()`, because
             * that is how the palette is emitted, and a naive parse of those
             * three numbers as if they were RGB channels quietly produces
             * plausible-looking nonsense.
             */
            const colour = await target.evaluate((node) => {
                const canvas = document.createElement('canvas')
                canvas.width = canvas.height = 1

                const ctx = canvas.getContext('2d')
                ctx.fillStyle = getComputedStyle(node).color
                ctx.fillRect(0, 0, 1, 1)

                return [...ctx.getImageData(0, 0, 1, 1).data].slice(0, 3)
            })

            const box = await target.boundingBox()

            if (!box || box.width < 2 || box.height < 2) {
                continue
            }

            // Hide the glyphs without moving anything, then photograph the
            // box they were in. What is left is exactly what they sat on.
            await target.evaluate((node) => {
                node.style.color = 'transparent'
                node.style.textDecorationColor = 'transparent'
            })

            const file = join(
                dir,
                `${layout}-${mode}-${name.replace(/\s+/g, '-')}.png`,
            )

            await page.screenshot({ path: file, clip: box })

            await target.evaluate((node) => {
                node.style.removeProperty('color')
                node.style.removeProperty('text-decoration-color')
            })

            samples.push({ layout, mode, name, colour, file })
        }

        await context.close()
    }
}

await browser.close()

const manifest = join(dir, 'samples.json')
writeFileSync(manifest, JSON.stringify(samples))

try {
    execFileSync('python3', ['bin/contrast-login.py', manifest], {
        stdio: 'inherit',
    })
} catch {
    process.exitCode = 1
}
