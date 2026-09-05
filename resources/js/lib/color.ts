/**
 * Helpers for driving UI from a seller's chosen shop colour.
 *
 * The dashboard mirrors the same colour the public shop page uses, so the
 * settings panel previews the real thing rather than a generic accent.
 */

const INK = { r: 26, g: 26, b: 25 }

/** Parse #rgb / #rrggbb. Falls back to ink for half-typed or invalid values. */
export function hexToRgb(hex?: string | null): { r: number; g: number; b: number } {
    if (!hex) return INK

    const value = hex.trim().replace(/^#/, '')
    const full = value.length === 3 ? value.replace(/(.)/g, '$1$1') : value

    if (!/^[0-9a-f]{6}$/i.test(full)) return INK

    const int = parseInt(full, 16)

    return {
        r: (int >> 16) & 255,
        g: (int >> 8) & 255,
        b: int & 255,
    }
}

/** The same colour at a given alpha, for tints and rings. */
export function alpha(hex: string | null | undefined, value: number): string {
    const { r, g, b } = hexToRgb(hex)

    return `rgba(${r}, ${g}, ${b}, ${value})`
}

/** Relative luminance, 0 (black) to 1 (white). */
export function luminance(hex?: string | null): number {
    const { r, g, b } = hexToRgb(hex)

    const channel = (c: number) => {
        const s = c / 255
        return s <= 0.03928 ? s / 12.92 : ((s + 0.055) / 1.055) ** 2.4
    }

    return 0.2126 * channel(r) + 0.7152 * channel(g) + 0.0722 * channel(b)
}

/** WCAG contrast ratio between two relative luminances. */
function contrast(a: number, b: number): number {
    return (Math.max(a, b) + 0.05) / (Math.min(a, b) + 0.05)
}

/**
 * Text colour that stays legible on top of the given fill.
 *
 * Compares the actual contrast of ink and white against the fill rather than
 * guessing from a lightness threshold — a mid-tone like amber reads as "dark
 * enough" for white text otherwise, and white on amber fails badly.
 */
export function readableOn(hex?: string | null): string {
    const fill = luminance(hex)

    return contrast(fill, luminance('#1A1A19')) >= contrast(fill, 1) ? '#1A1A19' : '#FFFFFF'
}

const toHex = ({ r, g, b }: { r: number; g: number; b: number }) =>
    `#${[r, g, b].map((c) => Math.round(c).toString(16).padStart(2, '0')).join('')}`

/**
 * A version of the shop colour dark enough to read as text on the paper
 * background. Light picks — amber, yellow, lime — fail badly at their raw
 * value, so darken toward black (which holds the hue) until 4.5:1 is met.
 */
export function readableTextOn(hex?: string | null, background = '#FDFCFA'): string {
    const bg = luminance(background)
    let { r, g, b } = hexToRgb(hex)

    for (let step = 0; step < 24; step += 1) {
        const current = toHex({ r, g, b })
        if (contrast(luminance(current), bg) >= 4.5) return current

        r *= 0.9
        g *= 0.9
        b *= 0.9
    }

    return '#1A1A19'
}

/**
 * CSS custom properties for a shop colour, to spread onto a wrapper element.
 * Children then style themselves with the `accent-*` classes in app.css.
 *
 *  --shop         the raw colour, for fills
 *  --shop-text    a darkened variant, for text on a light background
 *  --shop-on      text colour to use on top of a --shop fill
 *  --shop-on-*    translucent surfaces derived from --shop-on
 */
export function shopVars(hex?: string | null): Record<string, string> {
    const on = readableOn(hex)
    const onRgb = hexToRgb(on)
    const onAlpha = (value: number) => `rgba(${onRgb.r}, ${onRgb.g}, ${onRgb.b}, ${value})`

    return {
        '--shop': hex || '#1A1A19',
        '--shop-text': readableTextOn(hex),
        '--shop-tint': alpha(hex, 0.1),
        '--shop-soft': alpha(hex, 0.16),
        '--shop-line': alpha(hex, 0.32),
        '--shop-ring': alpha(hex, 0.18),
        '--shop-on': on,
        '--shop-on-muted': onAlpha(0.72),
        '--shop-on-soft': onAlpha(0.14),
        '--shop-on-line': onAlpha(0.26),
    }
}
