/**
 * Resolve a stored media value to a displayable URL.
 *
 * Handles three cases:
 *  - Full URLs (http/https) -> returned as-is
 *  - Spaces uploads (paths beginning with "uploads/") -> DigitalOcean Spaces URL
 *  - Legacy local files (e.g. "products/x.jpg", "profiles/y.jpg") -> /storage/...
 *
 * The Spaces base URL is exposed via a <meta name="spaces-url"> tag.
 */
export function mediaUrl(path?: string | null): string {
    if (!path) return ''

    if (/^https?:\/\//i.test(path)) return path

    const clean = path.replace(/^\/+/, '')

    if (clean.startsWith('uploads/')) {
        const base =
            document
                .querySelector('meta[name="spaces-url"]')
                ?.getAttribute('content') ?? ''
        return base ? `${base}/${clean}` : `/${clean}`
    }

    return `/storage/${clean}`
}
