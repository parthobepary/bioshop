# BioShop — UI Design System

> Modern, Professional, Attractive Design

---

## Design Philosophy

```
✗ Boring corporate blue
✗ Generic SaaS look
✗ Flat, lifeless colors

✓ Vibrant gradients
✓ Bold, confident colors
✓ Depth with shadows
✓ Micro-interactions
✓ Memorable brand
```

---

## Color Palette

### Primary Brand Colors

```css
/* Vibrant Purple-Blue Gradient - Main Brand */
--primary-500: #6366F1;      /* Indigo */
--primary-600: #4F46E5;      /* Deeper Indigo */
--primary-700: #4338CA;      /* Dark Indigo */

/* Gradient */
--gradient-primary: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
--gradient-hero: linear-gradient(135deg, #6366F1 0%, #8B5CF6 50%, #D946EF 100%);
```

### Accent Colors

```css
/* Success - Emerald */
--success-400: #34D399;
--success-500: #10B981;
--success-600: #059669;

/* Warning - Amber */
--warning-400: #FBBF24;
--warning-500: #F59E0B;

/* Error - Rose */
--error-400: #FB7185;
--error-500: #F43F5E;

/* Info - Cyan */
--info-400: #22D3EE;
--info-500: #06B6D4;
```

### Neutral Colors

```css
/* Slate - Modern Gray */
--slate-50:  #F8FAFC;
--slate-100: #F1F5F9;
--slate-200: #E2E8F0;
--slate-300: #CBD5E1;
--slate-400: #94A3B8;
--slate-500: #64748B;
--slate-600: #475569;
--slate-700: #334155;
--slate-800: #1E293B;
--slate-900: #0F172A;
--slate-950: #020617;
```

### Feature Colors (For Sections)

```css
/* Vibrant Feature Colors */
--coral:     #FF6B6B;    /* Products */
--teal:      #4ECDC4;    /* Analytics */
--gold:      #FFE66D;    /* Pricing */
--lavender:  #A78BFA;    /* Links */
--peach:     #FFDAB9;    /* Settings */
--mint:      #98FB98;    /* Success states */
```

---

## Gradients

### Background Gradients

```css
/* Hero Gradient - Eye-catching */
.gradient-hero {
    background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
}

/* Mesh Gradient - Modern */
.gradient-mesh {
    background:
        radial-gradient(at 40% 20%, #6366F1 0px, transparent 50%),
        radial-gradient(at 80% 0%, #8B5CF6 0px, transparent 50%),
        radial-gradient(at 0% 50%, #EC4899 0px, transparent 50%),
        radial-gradient(at 80% 50%, #06B6D4 0px, transparent 50%),
        radial-gradient(at 0% 100%, #6366F1 0px, transparent 50%);
    background-color: #F8FAFC;
}

/* Subtle Gradient - Sections */
.gradient-subtle {
    background: linear-gradient(180deg, #F8FAFC 0%, #EEF2FF 100%);
}

/* Card Gradient - Premium Feel */
.gradient-card {
    background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 100%);
}

/* Dark Gradient - Admin */
.gradient-dark {
    background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
}
```

### Button Gradients

```css
/* Primary Button */
.btn-primary {
    background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
    transform: translateY(-2px);
}

/* Success Button */
.btn-success {
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
}

/* Danger Button */
.btn-danger {
    background: linear-gradient(135deg, #F43F5E 0%, #E11D48 100%);
    box-shadow: 0 4px 15px rgba(244, 63, 94, 0.4);
}
```

---

## Typography

### Font Family

```css
/* Modern, Clean Font Stack */
--font-sans: 'Inter', 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
--font-display: 'Plus Jakarta Sans', 'Inter', sans-serif;  /* Headings */
--font-mono: 'JetBrains Mono', 'Fira Code', monospace;

/* Bangla Support */
--font-bangla: 'Hind Siliguri', 'Noto Sans Bengali', sans-serif;
```

### Font Sizes

```css
/* Fluid Typography */
--text-xs:   0.75rem;    /* 12px */
--text-sm:   0.875rem;   /* 14px */
--text-base: 1rem;       /* 16px */
--text-lg:   1.125rem;   /* 18px */
--text-xl:   1.25rem;    /* 20px */
--text-2xl:  1.5rem;     /* 24px */
--text-3xl:  1.875rem;   /* 30px */
--text-4xl:  2.25rem;    /* 36px */
--text-5xl:  3rem;       /* 48px */
--text-6xl:  3.75rem;    /* 60px */
--text-7xl:  4.5rem;     /* 72px */
```

### Heading Styles

```css
h1 {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: clamp(2.5rem, 5vw, 4.5rem);
    line-height: 1.1;
    letter-spacing: -0.02em;
    background: linear-gradient(135deg, #1E293B 0%, #475569 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

h2 {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: clamp(1.875rem, 4vw, 3rem);
    line-height: 1.2;
    letter-spacing: -0.01em;
}
```

---

## Shadows

### Elevation System

```css
/* Subtle */
--shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.05);

/* Small */
--shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);

/* Medium */
--shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);

/* Large */
--shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);

/* XL */
--shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);

/* 2XL */
--shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);

/* Colored Shadows */
--shadow-primary: 0 10px 40px -10px rgba(99, 102, 241, 0.5);
--shadow-success: 0 10px 40px -10px rgba(16, 185, 129, 0.5);
--shadow-glow: 0 0 40px rgba(99, 102, 241, 0.3);
```

### Card Shadows

```css
.card {
    background: white;
    border-radius: 16px;
    box-shadow:
        0 0 0 1px rgba(0, 0, 0, 0.03),
        0 2px 4px rgba(0, 0, 0, 0.05),
        0 12px 24px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-4px);
    box-shadow:
        0 0 0 1px rgba(0, 0, 0, 0.03),
        0 4px 8px rgba(0, 0, 0, 0.05),
        0 24px 48px rgba(0, 0, 0, 0.1);
}
```

---

## Border Radius

```css
--radius-sm:   6px;
--radius-md:   8px;
--radius-lg:   12px;
--radius-xl:   16px;
--radius-2xl:  24px;
--radius-3xl:  32px;
--radius-full: 9999px;
```

---

## Components

### Buttons

```html
<!-- Primary Button -->
<button class="
    px-6 py-3
    bg-gradient-to-r from-indigo-500 to-purple-500
    text-white font-semibold
    rounded-xl
    shadow-lg shadow-indigo-500/30
    hover:shadow-xl hover:shadow-indigo-500/40
    hover:-translate-y-0.5
    active:translate-y-0
    transition-all duration-200
">
    Get Started Free
</button>

<!-- Secondary Button -->
<button class="
    px-6 py-3
    bg-white
    text-slate-700 font-semibold
    rounded-xl
    border border-slate-200
    shadow-sm
    hover:bg-slate-50 hover:border-slate-300
    hover:shadow-md
    transition-all duration-200
">
    Learn More
</button>

<!-- Ghost Button -->
<button class="
    px-6 py-3
    text-indigo-600 font-semibold
    rounded-xl
    hover:bg-indigo-50
    transition-all duration-200
">
    View Demo
</button>

<!-- Icon Button -->
<button class="
    w-10 h-10
    flex items-center justify-center
    bg-slate-100
    text-slate-600
    rounded-full
    hover:bg-slate-200
    transition-all duration-200
">
    <svg>...</svg>
</button>
```

### Input Fields

```html
<!-- Modern Input -->
<div class="relative">
    <input type="text"
        class="
            w-full px-4 py-3
            bg-slate-50
            border-2 border-transparent
            rounded-xl
            text-slate-900
            placeholder:text-slate-400
            focus:bg-white
            focus:border-indigo-500
            focus:ring-4 focus:ring-indigo-500/10
            transition-all duration-200
        "
        placeholder="Enter your email"
    />
</div>

<!-- Input with Icon -->
<div class="relative">
    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
        <svg>...</svg>
    </div>
    <input type="text"
        class="
            w-full pl-12 pr-4 py-3
            bg-slate-50
            border-2 border-transparent
            rounded-xl
            focus:bg-white focus:border-indigo-500
            transition-all duration-200
        "
        placeholder="Search..."
    />
</div>

<!-- Floating Label -->
<div class="relative">
    <input type="text"
        id="name"
        class="peer w-full px-4 pt-6 pb-2 bg-slate-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-indigo-500"
        placeholder=" "
    />
    <label for="name"
        class="absolute left-4 top-4 text-slate-400 text-sm transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:text-base peer-focus:top-4 peer-focus:translate-y-0 peer-focus:text-sm peer-focus:text-indigo-600"
    >
        Your Name
    </label>
</div>
```

### Cards

```html
<!-- Feature Card -->
<div class="
    p-6
    bg-white
    rounded-2xl
    border border-slate-100
    shadow-lg shadow-slate-200/50
    hover:shadow-xl hover:shadow-slate-200/50
    hover:-translate-y-1
    transition-all duration-300
">
    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mb-4">
        <svg class="w-6 h-6 text-white">...</svg>
    </div>
    <h3 class="text-lg font-semibold text-slate-900 mb-2">Feature Title</h3>
    <p class="text-slate-600">Description text goes here explaining the feature.</p>
</div>

<!-- Pricing Card -->
<div class="
    p-8
    bg-white
    rounded-3xl
    border-2 border-slate-100
    shadow-xl
    relative
    overflow-hidden
">
    <!-- Popular Badge -->
    <div class="absolute top-0 right-0 bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-xs font-bold px-4 py-1 rounded-bl-xl">
        POPULAR
    </div>

    <h3 class="text-xl font-bold text-slate-900">Pro Plan</h3>
    <div class="mt-4 flex items-baseline">
        <span class="text-4xl font-bold text-slate-900">৳299</span>
        <span class="text-slate-500 ml-2">/month</span>
    </div>

    <ul class="mt-6 space-y-3">
        <li class="flex items-center text-slate-600">
            <svg class="w-5 h-5 text-emerald-500 mr-3">✓</svg>
            Unlimited Products
        </li>
        <!-- More features -->
    </ul>

    <button class="mt-8 w-full py-3 bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-semibold rounded-xl">
        Get Started
    </button>
</div>

<!-- Stats Card -->
<div class="
    p-6
    bg-gradient-to-br from-indigo-500 to-purple-600
    rounded-2xl
    text-white
    shadow-lg shadow-indigo-500/30
">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-indigo-100 text-sm">Total Revenue</p>
            <p class="text-3xl font-bold mt-1">৳45,230</p>
            <p class="text-emerald-300 text-sm mt-2">↑ 12% from last month</p>
        </div>
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6">...</svg>
        </div>
    </div>
</div>
```

### Product Card (Public Shop)

```html
<div class="
    group
    bg-white
    rounded-2xl
    overflow-hidden
    shadow-md
    hover:shadow-xl
    transition-all duration-300
">
    <!-- Image -->
    <div class="relative aspect-square overflow-hidden">
        <img src="product.jpg" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />

        <!-- Stock Badge -->
        <div class="absolute top-3 left-3">
            <span class="px-2 py-1 bg-emerald-500 text-white text-xs font-medium rounded-full">
                In Stock
            </span>
        </div>

        <!-- Quick View -->
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300">
            <button class="px-4 py-2 bg-white text-slate-900 font-medium rounded-lg">
                Quick View
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="p-4">
        <h3 class="font-semibold text-slate-900 truncate">Product Name</h3>
        <div class="flex items-center justify-between mt-2">
            <div class="flex items-center gap-2">
                <span class="text-lg font-bold text-indigo-600">৳450</span>
                <span class="text-sm text-slate-400 line-through">৳550</span>
            </div>
            <button class="w-10 h-10 bg-indigo-500 text-white rounded-full flex items-center justify-center hover:bg-indigo-600 transition-colors">
                <svg class="w-5 h-5">WhatsApp Icon</svg>
            </button>
        </div>
    </div>
</div>
```

---

## Landing Page Sections

### Hero Section

```html
<section class="relative min-h-screen flex items-center overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-indigo-50 to-purple-50"></div>

    <!-- Decorative Elements -->
    <div class="absolute top-20 left-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute top-40 right-10 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute bottom-20 left-1/2 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>

    <!-- Content -->
    <div class="relative max-w-7xl mx-auto px-4 py-20">
        <div class="text-center max-w-4xl mx-auto">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium mb-8">
                <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                New: AI Product Descriptions
            </div>

            <!-- Headline -->
            <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 mb-6 leading-tight">
                Your Products,
                <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                    One Beautiful Link
                </span>
            </h1>

            <!-- Subheadline -->
            <p class="text-xl text-slate-600 mb-10 max-w-2xl mx-auto">
                Create a stunning product showcase in minutes.
                Perfect for Facebook sellers, Instagram shops, and small businesses.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="
                    px-8 py-4
                    bg-gradient-to-r from-indigo-600 to-purple-600
                    text-white text-lg font-semibold
                    rounded-2xl
                    shadow-lg shadow-indigo-500/30
                    hover:shadow-xl hover:shadow-indigo-500/40
                    hover:-translate-y-1
                    transition-all duration-200
                ">
                    Start Free — No Credit Card
                </a>
                <a href="#demo" class="
                    px-8 py-4
                    bg-white
                    text-slate-700 text-lg font-semibold
                    rounded-2xl
                    border border-slate-200
                    shadow-md
                    hover:shadow-lg
                    hover:-translate-y-1
                    transition-all duration-200
                ">
                    See Demo
                </a>
            </div>

            <!-- Trust Badges -->
            <div class="mt-12 flex flex-wrap items-center justify-center gap-8 text-slate-400 text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500">✓</svg>
                    Free forever plan
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500">✓</svg>
                    Setup in 2 minutes
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500">✓</svg>
                    bKash & Nagad ready
                </div>
            </div>
        </div>

        <!-- Hero Image -->
        <div class="mt-16 relative">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-50 via-transparent to-transparent z-10"></div>
            <img src="/images/hero-mockup.png" class="w-full max-w-5xl mx-auto rounded-2xl shadow-2xl" />
        </div>
    </div>
</section>
```

### Features Section

```html
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wider">Features</span>
            <h2 class="mt-4 text-4xl font-bold text-slate-900">
                Everything you need to sell online
            </h2>
            <p class="mt-4 text-xl text-slate-600">
                No website needed. No coding required. Just your products and a link.
            </p>
        </div>

        <!-- Feature Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="group p-8 rounded-3xl bg-gradient-to-br from-indigo-50 to-purple-50 hover:from-indigo-100 hover:to-purple-100 transition-all duration-300">
                <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30 mb-6">
                    <svg class="w-7 h-7 text-white">📦</svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Product Showcase</h3>
                <p class="text-slate-600 leading-relaxed">
                    Display your products beautifully with photos, prices, and descriptions. Organize with categories.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="group p-8 rounded-3xl bg-gradient-to-br from-emerald-50 to-teal-50 hover:from-emerald-100 hover:to-teal-100 transition-all duration-300">
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 mb-6">
                    <svg class="w-7 h-7 text-white">💬</svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">WhatsApp Orders</h3>
                <p class="text-slate-600 leading-relaxed">
                    Customers tap to order via WhatsApp. Pre-filled message with product details.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="group p-8 rounded-3xl bg-gradient-to-br from-pink-50 to-rose-50 hover:from-pink-100 hover:to-rose-100 transition-all duration-300">
                <div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-rose-500 rounded-2xl flex items-center justify-center shadow-lg shadow-pink-500/30 mb-6">
                    <svg class="w-7 h-7 text-white">💳</svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">bKash & Nagad</h3>
                <p class="text-slate-600 leading-relaxed">
                    Show your payment info clearly. QR codes and account numbers ready to share.
                </p>
            </div>

            <!-- Feature 4 -->
            <div class="group p-8 rounded-3xl bg-gradient-to-br from-amber-50 to-orange-50 hover:from-amber-100 hover:to-orange-100 transition-all duration-300">
                <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30 mb-6">
                    <svg class="w-7 h-7 text-white">📊</svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Analytics</h3>
                <p class="text-slate-600 leading-relaxed">
                    See who's viewing your shop. Track popular products and customer interest.
                </p>
            </div>

            <!-- Feature 5 -->
            <div class="group p-8 rounded-3xl bg-gradient-to-br from-cyan-50 to-sky-50 hover:from-cyan-100 hover:to-sky-100 transition-all duration-300">
                <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-sky-500 rounded-2xl flex items-center justify-center shadow-lg shadow-cyan-500/30 mb-6">
                    <svg class="w-7 h-7 text-white">🔗</svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Social Links</h3>
                <p class="text-slate-600 leading-relaxed">
                    Add all your social media links. Facebook, Instagram, YouTube — all in one place.
                </p>
            </div>

            <!-- Feature 6 -->
            <div class="group p-8 rounded-3xl bg-gradient-to-br from-violet-50 to-purple-50 hover:from-violet-100 hover:to-purple-100 transition-all duration-300">
                <div class="w-14 h-14 bg-gradient-to-br from-violet-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg shadow-violet-500/30 mb-6">
                    <svg class="w-7 h-7 text-white">🎨</svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Custom Themes</h3>
                <p class="text-slate-600 leading-relaxed">
                    Match your brand with custom colors. Make your shop uniquely yours.
                </p>
            </div>
        </div>
    </div>
</section>
```

### Pricing Section

```html
<section class="py-24 bg-gradient-to-b from-slate-50 to-white">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wider">Pricing</span>
            <h2 class="mt-4 text-4xl font-bold text-slate-900">
                Simple, transparent pricing
            </h2>
            <p class="mt-4 text-xl text-slate-600">
                Start free. Upgrade when you need more.
            </p>
        </div>

        <!-- Pricing Cards -->
        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">

            <!-- Free Plan -->
            <div class="p-8 bg-white rounded-3xl border border-slate-200 shadow-lg">
                <h3 class="text-lg font-bold text-slate-900">Free</h3>
                <p class="text-slate-500 mt-1">For getting started</p>

                <div class="mt-6 flex items-baseline">
                    <span class="text-5xl font-bold text-slate-900">৳0</span>
                    <span class="text-slate-500 ml-2">/month</span>
                </div>

                <ul class="mt-8 space-y-4">
                    <li class="flex items-center text-slate-600">
                        <svg class="w-5 h-5 text-emerald-500 mr-3">✓</svg>
                        5 Products
                    </li>
                    <li class="flex items-center text-slate-600">
                        <svg class="w-5 h-5 text-emerald-500 mr-3">✓</svg>
                        5 Links
                    </li>
                    <li class="flex items-center text-slate-600">
                        <svg class="w-5 h-5 text-emerald-500 mr-3">✓</svg>
                        Basic Analytics
                    </li>
                    <li class="flex items-center text-slate-400">
                        <svg class="w-5 h-5 mr-3">✗</svg>
                        BioShop Branding
                    </li>
                </ul>

                <button class="mt-8 w-full py-3 border-2 border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 transition-colors">
                    Get Started
                </button>
            </div>

            <!-- Starter Plan - Popular -->
            <div class="p-8 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-3xl shadow-xl shadow-indigo-500/30 relative scale-105">
                <!-- Badge -->
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-gradient-to-r from-amber-400 to-orange-400 text-slate-900 text-sm font-bold rounded-full shadow-lg">
                    MOST POPULAR
                </div>

                <h3 class="text-lg font-bold text-white">Starter</h3>
                <p class="text-indigo-200 mt-1">For growing shops</p>

                <div class="mt-6 flex items-baseline">
                    <span class="text-5xl font-bold text-white">৳149</span>
                    <span class="text-indigo-200 ml-2">/month</span>
                </div>

                <ul class="mt-8 space-y-4">
                    <li class="flex items-center text-white">
                        <svg class="w-5 h-5 text-emerald-400 mr-3">✓</svg>
                        50 Products
                    </li>
                    <li class="flex items-center text-white">
                        <svg class="w-5 h-5 text-emerald-400 mr-3">✓</svg>
                        Unlimited Links
                    </li>
                    <li class="flex items-center text-white">
                        <svg class="w-5 h-5 text-emerald-400 mr-3">✓</svg>
                        Full Analytics
                    </li>
                    <li class="flex items-center text-white">
                        <svg class="w-5 h-5 text-emerald-400 mr-3">✓</svg>
                        No Branding
                    </li>
                </ul>

                <button class="mt-8 w-full py-3 bg-white text-indigo-600 font-semibold rounded-xl hover:bg-indigo-50 transition-colors shadow-lg">
                    Get Started
                </button>
            </div>

            <!-- Pro Plan -->
            <div class="p-8 bg-white rounded-3xl border border-slate-200 shadow-lg">
                <h3 class="text-lg font-bold text-slate-900">Pro</h3>
                <p class="text-slate-500 mt-1">For serious sellers</p>

                <div class="mt-6 flex items-baseline">
                    <span class="text-5xl font-bold text-slate-900">৳299</span>
                    <span class="text-slate-500 ml-2">/month</span>
                </div>

                <ul class="mt-8 space-y-4">
                    <li class="flex items-center text-slate-600">
                        <svg class="w-5 h-5 text-emerald-500 mr-3">✓</svg>
                        Unlimited Products
                    </li>
                    <li class="flex items-center text-slate-600">
                        <svg class="w-5 h-5 text-emerald-500 mr-3">✓</svg>
                        Unlimited Links
                    </li>
                    <li class="flex items-center text-slate-600">
                        <svg class="w-5 h-5 text-emerald-500 mr-3">✓</svg>
                        Advanced Analytics
                    </li>
                    <li class="flex items-center text-slate-600">
                        <svg class="w-5 h-5 text-emerald-500 mr-3">✓</svg>
                        Custom Domain
                    </li>
                </ul>

                <button class="mt-8 w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:shadow-lg transition-all">
                    Get Started
                </button>
            </div>
        </div>
    </div>
</section>
```

---

## Dashboard Design

### Seller Dashboard

```html
<!-- Sidebar -->
<aside class="fixed inset-y-0 left-0 w-64 bg-white border-r border-slate-200">
    <!-- Logo -->
    <div class="h-16 flex items-center px-6 border-b border-slate-100">
        <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
            BioShop
        </span>
    </div>

    <!-- Nav -->
    <nav class="p-4 space-y-1">
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-indigo-600 bg-indigo-50 rounded-xl font-medium">
            <svg class="w-5 h-5">📊</svg>
            Dashboard
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 rounded-xl">
            <svg class="w-5 h-5">🔗</svg>
            Links
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 rounded-xl">
            <svg class="w-5 h-5">📦</svg>
            Products
        </a>
        <!-- More items -->
    </nav>

    <!-- Plan Badge -->
    <div class="absolute bottom-4 left-4 right-4">
        <div class="p-4 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl text-white">
            <p class="text-sm text-indigo-200">Current Plan</p>
            <p class="font-bold">Free Plan</p>
            <button class="mt-3 w-full py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-medium transition-colors">
                Upgrade to Pro
            </button>
        </div>
    </div>
</aside>

<!-- Main Content -->
<main class="ml-64 p-8 bg-slate-50 min-h-screen">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
            <p class="text-slate-500">Welcome back, Fatima!</p>
        </div>
        <a href="#" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-colors">
            View My Shop →
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-4 gap-6 mb-8">
        <div class="p-6 bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Page Views</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">1,234</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600">👁</svg>
                </div>
            </div>
            <p class="text-emerald-600 text-sm mt-4">↑ 12% from last week</p>
        </div>

        <div class="p-6 bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Products</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">45</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600">📦</svg>
                </div>
            </div>
            <p class="text-slate-500 text-sm mt-4">3 out of stock</p>
        </div>

        <div class="p-6 bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">WhatsApp Clicks</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">89</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600">💬</svg>
                </div>
            </div>
            <p class="text-emerald-600 text-sm mt-4">↑ 8% from last week</p>
        </div>

        <div class="p-6 bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Link Clicks</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">156</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600">🔗</svg>
                </div>
            </div>
            <p class="text-emerald-600 text-sm mt-4">↑ 24% from last week</p>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="col-span-2 p-6 bg-white rounded-2xl shadow-sm border border-slate-100">
            <h3 class="font-semibold text-slate-900 mb-4">Views This Week</h3>
            <div class="h-64">
                <!-- Chart here -->
            </div>
        </div>

        <!-- Top Products -->
        <div class="p-6 bg-white rounded-2xl shadow-sm border border-slate-100">
            <h3 class="font-semibold text-slate-900 mb-4">Top Products</h3>
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <img src="product.jpg" class="w-12 h-12 rounded-lg object-cover" />
                    <div class="flex-1">
                        <p class="font-medium text-slate-900 truncate">Black Hijab</p>
                        <p class="text-sm text-slate-500">23 views</p>
                    </div>
                </div>
                <!-- More products -->
            </div>
        </div>
    </div>
</main>
```

### Admin Dashboard (Dark Theme)

```html
<aside class="fixed inset-y-0 left-0 w-64 bg-slate-900">
    <!-- Logo -->
    <div class="h-16 flex items-center px-6 border-b border-slate-800">
        <span class="text-xl font-bold text-white">BioShop Admin</span>
    </div>

    <!-- Nav -->
    <nav class="p-4 space-y-1">
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-white bg-indigo-600 rounded-xl font-medium">
            Dashboard
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl">
            Sellers
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl">
            Payments
        </a>
        <!-- More items -->
    </nav>
</aside>

<main class="ml-64 p-8 bg-slate-950 min-h-screen text-white">
    <!-- Admin content -->
</main>
```

---

## Public Shop Page

```html
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    <div class="max-w-lg mx-auto px-4 py-8">

        <!-- Profile Header -->
        <div class="text-center mb-8">
            <div class="relative inline-block">
                <img src="avatar.jpg" class="w-28 h-28 rounded-full border-4 border-white shadow-xl object-cover" />
                <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-emerald-500 rounded-full border-4 border-white flex items-center justify-center">
                    <svg class="w-4 h-4 text-white">✓</svg>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mt-4">Hijab House</h1>
            <p class="text-slate-600 mt-1">Premium hijabs & modest fashion</p>

            <!-- Social Icons -->
            <div class="flex items-center justify-center gap-3 mt-4">
                <a href="#" class="w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition-shadow">
                    <svg class="w-5 h-5 text-blue-600">FB</svg>
                </a>
                <a href="#" class="w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition-shadow">
                    <svg class="w-5 h-5 text-pink-600">IG</svg>
                </a>
            </div>
        </div>

        <!-- Links -->
        <div class="space-y-3 mb-8">
            <a href="#" class="block p-4 bg-white rounded-2xl shadow-md hover:shadow-lg transition-all hover:-translate-y-0.5 text-center font-medium text-slate-700 border border-slate-100">
                🛍️ New Arrivals
            </a>
            <a href="#" class="block p-4 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-lg text-center font-medium text-white">
                📦 Track Your Order
            </a>
        </div>

        <!-- Products -->
        <div class="mb-8">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Our Products</h2>
            <div class="grid grid-cols-2 gap-4">
                <!-- Product cards -->
            </div>
        </div>

        <!-- Payment -->
        <div class="p-6 bg-white rounded-2xl shadow-md mb-8">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Payment Methods</h2>
            <div class="space-y-3">
                <div class="flex items-center gap-4 p-3 bg-pink-50 rounded-xl">
                    <img src="bkash.png" class="w-10 h-10" />
                    <div>
                        <p class="font-medium text-slate-900">bKash</p>
                        <p class="text-slate-600">01712-345678</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-3 bg-orange-50 rounded-xl">
                    <img src="nagad.png" class="w-10 h-10" />
                    <div>
                        <p class="font-medium text-slate-900">Nagad</p>
                        <p class="text-slate-600">01812-345678</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- WhatsApp FAB -->
        <a href="#" class="fixed bottom-6 right-6 w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full shadow-xl shadow-green-500/30 flex items-center justify-center hover:shadow-2xl hover:scale-110 transition-all">
            <svg class="w-8 h-8 text-white">WA</svg>
        </a>

        <!-- Footer -->
        <p class="text-center text-sm text-slate-400">
            Made with <span class="text-pink-500">♥</span> on BioShop
        </p>
    </div>
</div>
```

---

## Animations

### Tailwind Config

```js
// tailwind.config.js
module.exports = {
    theme: {
        extend: {
            animation: {
                'blob': 'blob 7s infinite',
                'fade-in': 'fadeIn 0.5s ease-out',
                'slide-up': 'slideUp 0.5s ease-out',
                'slide-down': 'slideDown 0.3s ease-out',
                'scale-in': 'scaleIn 0.3s ease-out',
                'bounce-soft': 'bounceSoft 2s infinite',
            },
            keyframes: {
                blob: {
                    '0%': { transform: 'translate(0px, 0px) scale(1)' },
                    '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                    '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                    '100%': { transform: 'translate(0px, 0px) scale(1)' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideDown: {
                    '0%': { opacity: '0', transform: 'translateY(-10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                scaleIn: {
                    '0%': { opacity: '0', transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                bounceSoft: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
            },
        },
    },
}
```

---

## Color Theme Options for Sellers

```js
const themeColors = [
    { name: 'Indigo', primary: '#6366F1', bg: '#EEF2FF' },
    { name: 'Purple', primary: '#8B5CF6', bg: '#F5F3FF' },
    { name: 'Pink', primary: '#EC4899', bg: '#FDF2F8' },
    { name: 'Rose', primary: '#F43F5E', bg: '#FFF1F2' },
    { name: 'Orange', primary: '#F97316', bg: '#FFF7ED' },
    { name: 'Amber', primary: '#F59E0B', bg: '#FFFBEB' },
    { name: 'Emerald', primary: '#10B981', bg: '#ECFDF5' },
    { name: 'Teal', primary: '#14B8A6', bg: '#F0FDFA' },
    { name: 'Cyan', primary: '#06B6D4', bg: '#ECFEFF' },
    { name: 'Sky', primary: '#0EA5E9', bg: '#F0F9FF' },
    { name: 'Slate', primary: '#64748B', bg: '#F8FAFC' },
    { name: 'Black', primary: '#171717', bg: '#FAFAFA' },
];
```

---

## Summary

```
Design System:
✅ Vibrant gradient colors (Indigo → Purple)
✅ Modern shadows with color
✅ Rounded corners (2xl, 3xl)
✅ Smooth animations
✅ Professional typography
✅ Dark admin theme
✅ Colorful feature cards
✅ Premium pricing UI
✅ Beautiful public shop
✅ Mobile-first responsive
```

---

*This design system ensures BioShop looks modern, professional, and attractive.*
