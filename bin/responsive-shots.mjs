/*
 * The shell at four widths, in both of the sidebar's states.
 *
 * The sidebar is the one part of the layout whose width changes without the
 * viewport changing, so it is the one place where a rule written for the
 * expanded column keeps applying to a rail that cannot hold it. A media query
 * sweep alone never reaches that state: it has to be collapsed on purpose at
 * every width.
 *
 * As well as the frames, this reports every element inside the sidebar whose
 * box ends past the rail's edge. Spill is the failure a screenshot hides most
 * easily — content painted over the canvas reads as part of the page until you
 * notice that it moves when the sidebar does.
 *
 * Start a panel first, then:
 *
 *     php vendor/bin/testbench serve --host=127.0.0.1 --port=8321
 *     node bin/responsive-shots.mjs
 *
 *     MIA_BASE=http://mia-demo.test MIA_TAG=after node bin/responsive-shots.mjs
 */

import { mkdirSync, writeFileSync } from 'node:fs'
import { chromium } from 'playwright'

const BASE = process.env.MIA_BASE ?? 'http://127.0.0.1:8321'
const OUT = process.env.MIA_OUT ?? 'art/verification/responsive'
const TAG = process.env.MIA_TAG ?? 'after'

const EMAIL = process.env.MIA_EMAIL ?? 'john@mia.test'
const PASSWORD = process.env.MIA_PASSWORD ?? 'password'

/*
 * A phone, a tablet in portrait, a window just past the breakpoint where the
 * sidebar stops being a drawer, and a wide desktop. The narrow desktop is the
 * important one: it is the first width at which the rail exists at all, and
 * the width at which the content column has least room to give.
 *
 * The phone in landscape is here for the other axis. A drawer 380px tall has
 * to hold the whole navigation plus whatever is pinned under it, and that is
 * where sidebar content ends up below the fold with no way to scroll to it.
 */
const VIEWPORTS = {
    phone: { width: 390, height: 844 },
    'phone-landscape': { width: 740, height: 380 },
    tablet: { width: 820, height: 1024 },
    'desktop-narrow': { width: 1120, height: 820 },
    'desktop-wide': { width: 1680, height: 1000 },
}

mkdirSync(OUT, { recursive: true })

const browser = await chromium.launch()

/*
 * Signed in once and replayed, because Filament throttles the sign-in form
 * after a handful of attempts a minute and this script opens a context per
 * width and per colour scheme.
 */
const signedIn = await (async () => {
    const ctx = await browser.newContext({
        viewport: VIEWPORTS['desktop-wide'],
    })
    const page = await ctx.newPage()

    await page.goto(`${BASE}/admin/login`, { waitUntil: 'networkidle' })
    await page.fill('[wire\\:model="data.email"]', EMAIL)
    await page.fill('[wire\\:model="data.password"]', PASSWORD)
    await page.locator('button[type=submit]').click()
    await page.waitForSelector('.fi-main', { timeout: 20000 })

    const state = await ctx.storageState()
    await ctx.close()

    return state
})()

/*
 * Every descendant of the sidebar, measured against the rail it sits in.
 *
 * `spill` is how far past the rail's inline edge an element's box reaches.
 * Zero-size boxes are skipped: a hidden label still has a position, and
 * counting it would bury the elements that are actually painting outside.
 */
async function measure(page) {
    return await page.evaluate(() => {
        const sidebar = document.querySelector('.fi-main-sidebar')
        const rail = sidebar.getBoundingClientRect()
        const spilling = []

        for (const el of sidebar.querySelectorAll('*')) {
            const box = el.getBoundingClientRect()

            if (box.width < 1 || box.height < 1) {
                continue
            }

            const spill = Math.round(box.right - rail.right)

            if (spill > 1) {
                spilling.push({
                    cls: (el.getAttribute('class') ?? el.tagName).slice(0, 70),
                    spill,
                })
            }
        }

        /*
         * The horizontal centre of every target in the rail. A rail whose
         * targets do not share one centre line is the symptom that some of its
         * content is still being laid out for the expanded column.
         */
        const centres = [
            ...sidebar.querySelectorAll(
                '.fi-sidebar-item-btn > .fi-icon, .fi-sidebar-group-dropdown-trigger-btn, .fi-sidebar-footer .fi-dropdown-trigger, .fi-sidebar-database-notifications-btn',
            ),
        ]
            .map((el) => el.getBoundingClientRect())
            /* A hidden target still has a position, and counting it would
               report a centre line that nothing is actually drawn on. */
            .filter((box) => box.width > 0)
            .map((box) => Math.round((box.left + box.right) / 2))

        /*
         * The other axis. A drawer shorter than its own navigation is fine as
         * long as the navigation is the part that scrolls: what is not fine is
         * the sidebar itself growing past the viewport, which puts whatever is
         * pinned under the navigation below the fold with nothing to scroll.
         */
        const nav = sidebar.querySelector('.fi-sidebar-nav')
        const navOverflow = nav.scrollHeight - nav.clientHeight

        return {
            rail: Math.round(rail.width),
            railCentre: Math.round(rail.left + rail.width / 2),
            /* Anything above zero is content laid out wider than the rail. */
            spillWidth: sidebar.scrollWidth - sidebar.clientWidth,
            /* Anything above zero is sidebar content below the fold. */
            belowFold: Math.max(0, sidebar.scrollHeight - sidebar.clientHeight),
            navOverflow: Math.max(0, navOverflow),
            navScrolls:
                navOverflow < 1 ||
                ['auto', 'scroll'].includes(getComputedStyle(nav).overflowY),
            offCanvas: Math.round(rail.left) < 0,
            /* One entry means every target shares a centre line. */
            centres: [...new Set(centres)],
            spilling: spilling.slice(0, 10),
        }
    })
}

async function setSidebar(page, open) {
    await page.evaluate(
        (open) =>
            open
                ? window.Alpine.store('sidebar').open()
                : window.Alpine.store('sidebar').close(),
        open,
    )

    /* Long enough for the theme's slow collapse easing to finish. */
    await page.waitForTimeout(900)
}

const report = {}

for (const [name, viewport] of Object.entries(VIEWPORTS)) {
    for (const mode of ['light', 'dark']) {
        const ctx = await browser.newContext({
            viewport,
            deviceScaleFactor: 2,
            colorScheme: mode,
            storageState: signedIn,
        })

        await ctx.addInitScript(
            ([mode]) => localStorage.setItem('theme', mode),
            [mode],
        )

        const page = await ctx.newPage()

        await page.goto(`${BASE}/admin`, { waitUntil: 'networkidle' })
        await page.evaluate(() => document.fonts.ready)
        await page.waitForTimeout(600)

        for (const open of [true, false]) {
            await setSidebar(page, open)

            const state = open ? 'expanded' : 'collapsed'

            /* Geometry does not change with the colour scheme. */
            if (mode === 'light') {
                report[`${name}/${state}`] = await measure(page)
            }

            await page.screenshot({
                path: `${OUT}/${TAG}-${name}-${state}-${mode}.png`,
            })
        }

        await ctx.close()
    }
}

/*
 * Collapsing with reduced motion asked for. The theme puts back the width
 * transition Filament removes on desktop, so this is where a jump would show:
 * the frame is taken straight after the click rather than after settling.
 */
{
    const ctx = await browser.newContext({
        viewport: VIEWPORTS['desktop-narrow'],
        deviceScaleFactor: 2,
        reducedMotion: 'reduce',
        storageState: signedIn,
    })
    const page = await ctx.newPage()

    await page.goto(`${BASE}/admin`, { waitUntil: 'networkidle' })
    await page.evaluate(() => document.fonts.ready)
    await page.waitForTimeout(600)
    await page.evaluate(() => window.Alpine.store('sidebar').close())
    await page.waitForTimeout(60)
    await page.screenshot({
        path: `${OUT}/${TAG}-desktop-narrow-collapsing-reduced-motion.png`,
    })

    /*
     * Measured after the frame rather than with it: reduced motion collapses
     * the durations to a hundredth of a millisecond, so what matters is that
     * the rail has already reached its final geometry by the time the frame
     * above is taken, and comparing the two tells us whether it had.
     */
    await page.waitForTimeout(900)

    report['desktop-narrow/reduced-motion'] = await measure(page)

    await ctx.close()
}

await browser.close()

writeFileSync(`${OUT}/${TAG}-report.json`, JSON.stringify(report, null, 4))

console.log(JSON.stringify(report, null, 4))
