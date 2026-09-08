/*
 * Screenshots of every sign-in composition, from the package's own preview
 * panel.
 *
 * Start the panel first:
 *
 *     php vendor/bin/testbench serve --host=127.0.0.1 --port=8321
 *     node bin/shots.mjs
 *
 * The composition is switched by writing the settings record the theme reads,
 * which is the same path the appearance page saves through — so what is
 * captured here is a panel configured exactly as a user would configure it.
 *
 * Every screen is captured in both colour modes, at desktop and phone widths,
 * and with a validation error on screen, because a composition that comes
 * apart when a message appears is the whole risk of having five of them.
 */

import { execFileSync } from 'node:child_process'
import { copyFileSync, mkdirSync, readdirSync } from 'node:fs'
import { join } from 'node:path'
import { chromium } from 'playwright'

const BASE = process.env.MIA_BASE ?? 'http://127.0.0.1:8321'
/*
 * The full set is verification material and stays out of the repository. Only
 * the frames the README shows are copied up into `art/`, so a reader of the
 * repository is not paging through forty near-identical images.
 */
const OUT = 'art/verification'
const PUBLISHED = 'art'

const README_FRAMES = [
    'login-card-light-desktop.jpg',
    'login-card-light-desktop-error.jpg',
    'login-split-light-desktop.jpg',
    'login-split-dark-desktop.jpg',
    'login-split-light-mobile.jpg',
    'login-bleed-light-desktop.jpg',
    'login-editorial-light-desktop.jpg',
    'login-portal-light-desktop.jpg',
    'login-two-step-split-dark.jpg',
]

/*
 * The panel serves the copy of the stylesheet under its public directory, not
 * the one `npm run build` writes. Republishing first is what keeps a capture
 * from quietly showing the previous build.
 */
execFileSync('php', ['vendor/bin/testbench', 'filament:assets'], {
    stdio: 'inherit',
})

const LAYOUTS = ['card', 'split', 'bleed', 'editorial', 'portal']

const VIEWPORTS = {
    desktop: { width: 1440, height: 900 },
    mobile: { width: 390, height: 844 },
}

const MODES = ['light', 'dark']

const EMAIL = 'valeria@mia.test'
const PASSWORD = 'password'

const TAGLINE = 'Client work, kept in one place.'

function artisan(code) {
    execFileSync('php', ['vendor/bin/testbench', 'tinker', '--execute', code], {
        stdio: 'ignore',
    })
}

function setLayout(layout) {
    artisan(
        `app(\\JohnRivera7\\FilamentMia\\Settings\\Contracts\\SettingsRepository::class)` +
            `->put('preview', \\JohnRivera7\\FilamentMia\\Settings\\ThemeSettings::defaults()` +
            `->with(['login_layout' => '${layout}', 'login_tagline' => '${TAGLINE}'])->toArray());`,
    )
}

function setTwoStep(enabled) {
    artisan(
        `$u = \\Workbench\\App\\Models\\User::first();` +
            `$u->app_authentication_secret = ${enabled ? 'app(\\Filament\\Auth\\MultiFactor\\App\\AppAuthentication::class)->generateSecret()' : 'null'};` +
            `$u->save();`,
    )
}

async function context(browser, viewport, mode) {
    const created = await browser.newContext({
        viewport: VIEWPORTS[viewport],
        deviceScaleFactor: 2,
        colorScheme: mode,
    })

    await created.addInitScript(
        ([mode]) => localStorage.setItem('theme', mode),
        [mode],
    )

    return created
}

async function settle(page) {
    await page.evaluate(() => document.fonts.ready)
    await page.waitForTimeout(700)
}

async function shoot(ctx, path, file, { error = false, signIn = false } = {}) {
    const page = await ctx.newPage()

    await page.goto(`${BASE}${path}`, { waitUntil: 'networkidle' })

    if (error || signIn) {
        // Filament throttles failed sign-ins, and this captures more than five
        // of them in a row.
        artisan('cache()->clear();')

        await page.fill('[wire\\:model="data.email"]', EMAIL)
        await page.fill(
            '[wire\\:model="data.password"]',
            error ? 'not-the-password' : PASSWORD,
        )
        await page.locator('button[type=submit]').click()

        await page.waitForSelector(
            error ? '.fi-fo-field-wrp-error-message' : '.fi-one-time-code-input-ctn',
            { timeout: 15000 },
        )
    }

    await settle(page)

    await page.screenshot({ path: `${OUT}/${file}.png` })
    await page.close()
}

mkdirSync(OUT, { recursive: true })

const browser = await chromium.launch()

setTwoStep(false)

for (const layout of LAYOUTS) {
    setLayout(layout)

    for (const viewport of Object.keys(VIEWPORTS)) {
        for (const mode of MODES) {
            const ctx = await context(browser, viewport, mode)

            const stem = `login-${layout}-${mode}-${viewport}`

            await shoot(ctx, '/admin/login', stem)
            await shoot(ctx, '/admin/login', `${stem}-error`, { error: true })

            await ctx.close()
        }
    }
}

/*
 * The rest of the flow. Captured on one composition rather than all five: the
 * point is that these screens are styled at all, and the compositions have
 * already been compared above.
 */
setLayout('split')

for (const mode of MODES) {
    const ctx = await context(browser, 'desktop', mode)

    await shoot(ctx, '/admin/register', `login-register-${mode}`)
    await shoot(
        ctx,
        '/admin/password-reset/request',
        `login-recover-${mode}`,
    )

    await ctx.close()
}

setTwoStep(true)

for (const layout of ['split', 'bleed']) {
    setLayout(layout)

    for (const mode of MODES) {
        const ctx = await context(browser, 'desktop', mode)

        await shoot(ctx, '/admin/login', `login-two-step-${layout}-${mode}`, {
            signIn: true,
        })

        await ctx.close()
    }
}

setTwoStep(false)
setLayout('card')

await browser.close()

/*
 * Playwright writes PNG at two device pixels per CSS pixel, which is right for
 * looking at closely and far too heavy to keep. These are photographs of
 * gradients, so JPEG costs nothing visible and about a fifteenth of the size.
 */
execFileSync(
    'python3',
    [
        'bin/optimize.py',
        ...readdirSync(OUT)
            // Only what this run just captured. Passing the JPEGs from a
            // previous run back in asks the optimiser to convert a file to
            // itself, which fails on the second pass.
            .filter((f) => f.startsWith('login-') && f.endsWith('.png'))
            .map((f) => join(OUT, f)),
    ],
    { stdio: 'inherit' },
)

for (const frame of README_FRAMES) {
    copyFileSync(join(OUT, frame), join(PUBLISHED, frame))
}

console.log(`done, ${README_FRAMES.length} frames published to ${PUBLISHED}/`)
