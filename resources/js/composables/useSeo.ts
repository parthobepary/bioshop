import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

interface SeoMeta {
    title?: string
    description?: string
    image?: string
    url?: string
    type?: 'website' | 'article' | 'profile'
}

export function useSeo() {
    const page = usePage()

    const appName = computed(() => 'BioShop')
    const appUrl = computed(() => window.location.origin)

    const generateTitle = (title?: string) => {
        if (!title) return appName.value
        return `${title} - ${appName.value}`
    }

    const defaultDescription = 'Create your beautiful link-in-bio shop page. Showcase products, accept payments, and connect with customers via WhatsApp AI.'

    const generateMetaTags = (meta: SeoMeta) => {
        const title = meta.title || appName.value
        const description = meta.description || defaultDescription
        const image = meta.image || `${appUrl.value}/images/og-default.png`
        const url = meta.url || window.location.href
        const type = meta.type || 'website'

        return {
            title,
            description,
            ogTitle: title,
            ogDescription: description,
            ogImage: image,
            ogUrl: url,
            ogType: type,
            twitterCard: 'summary_large_image',
            twitterTitle: title,
            twitterDescription: description,
            twitterImage: image,
        }
    }

    return {
        appName,
        appUrl,
        generateTitle,
        generateMetaTags,
        defaultDescription,
    }
}
