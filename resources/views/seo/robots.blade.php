User-agent: *
Allow: /

# Disallow admin and dashboard areas
Disallow: /dashboard
Disallow: /admin
Disallow: /profile
Disallow: /setup
Disallow: /api

# Disallow auth pages from indexing
Disallow: /login
Disallow: /register
Disallow: /forgot-password
Disallow: /reset-password

# Allow static assets
Allow: /build/
Allow: /storage/

# Sitemap location
Sitemap: {{ url('/sitemap.xml') }}
