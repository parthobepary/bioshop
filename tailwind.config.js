import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import animate from 'tailwindcss-animate';

/*
 * BioShop design tokens.
 *
 * Palette: warm off-white paper, near-black ink, one blue accent.
 * The legacy indigo / purple / violet / fuchsia scales are deliberately remapped
 * onto the accent ramp, and slate / gray onto the warm neutral ramp, so pages
 * written against the old theme pick up the new one without class churn.
 */

const ink = {
    50: '#FAF9F7',
    100: '#F5F3EF',
    200: '#E9E5DE',
    300: '#D8D3CA',
    400: '#A8A39A',
    500: '#807B72',
    600: '#605C55',
    700: '#494640',
    800: '#2E2C29',
    900: '#1A1A19',
    950: '#0F0F0E',
};

const accent = {
    50: '#EFF4FF',
    100: '#DCE6FE',
    200: '#C0D2FD',
    300: '#94B4FB',
    400: '#608DF7',
    500: '#3B6BF0',
    600: '#2563EB',
    700: '#1D4FD0',
    800: '#1E42A8',
    900: '#1E3B85',
    950: '#172551',
};

/*
 * BioShop's own brand colour, used on the marketing site. The dashboard stays
 * on the seller's shop colour, so these two never compete on the same screen.
 */
const brand = {
    50: '#F1F0FE',
    100: '#E5E3FD',
    200: '#CFCBFC',
    300: '#AEA6F9',
    400: '#8B7DF5',
    500: '#6F5AF0',
    600: '#5B3DE5',
    700: '#4B2DC7',
    800: '#3E27A2',
    900: '#342382',
    950: '#1F1355',
};

/* Per-topic accents for feature cards, steps and avatars. */
const pop = {
    violet: '#6F5AF0',
    sky: '#0EA5E9',
    emerald: '#10B981',
    whatsapp: '#25D366',
    amber: '#F59E0B',
    rose: '#F43F5E',
    coral: '#FF6B6B',
    teal: '#14B8A6',
};

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: ['class'],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{vue,js,ts,jsx,tsx}',
    ],

    theme: {
        container: {
            center: true,
            padding: '1.5rem',
            screens: {
                '2xl': '1200px',
            },
        },
        extend: {
            colors: {
                // shadcn-vue tokens
                border: 'hsl(var(--border))',
                input: 'hsl(var(--input))',
                ring: 'hsl(var(--ring))',
                background: 'hsl(var(--background))',
                foreground: 'hsl(var(--foreground))',
                secondary: {
                    DEFAULT: 'hsl(var(--secondary))',
                    foreground: 'hsl(var(--secondary-foreground))',
                },
                destructive: {
                    DEFAULT: 'hsl(var(--destructive))',
                    foreground: 'hsl(var(--destructive-foreground))',
                },
                muted: {
                    DEFAULT: 'hsl(var(--muted))',
                    foreground: 'hsl(var(--muted-foreground))',
                },
                accent: {
                    DEFAULT: 'hsl(var(--accent))',
                    foreground: 'hsl(var(--accent-foreground))',
                    ...accent,
                },
                popover: {
                    DEFAULT: 'hsl(var(--popover))',
                    foreground: 'hsl(var(--popover-foreground))',
                },
                card: {
                    DEFAULT: 'hsl(var(--card))',
                    foreground: 'hsl(var(--card-foreground))',
                },

                // Surfaces
                paper: {
                    DEFAULT: '#FDFCFA',
                    subtle: '#F7F5F1',
                    deep: '#F0EDE7',
                },
                line: {
                    DEFAULT: '#E5E1DA',
                    strong: '#D8D3CA',
                },

                // Brand
                ink,
                brand,
                pop,
                primary: {
                    DEFAULT: 'hsl(var(--primary))',
                    foreground: 'hsl(var(--primary-foreground))',
                    ...accent,
                },

                // Legacy scales, remapped onto the new system
                indigo: accent,
                violet: accent,
                purple: accent,
                fuchsia: accent,
                slate: ink,
                gray: ink,
                zinc: ink,
                neutral: ink,
                stone: ink,

                // Status
                success: {
                    50: '#ECFDF5',
                    100: '#D1FAE5',
                    400: '#34D399',
                    500: '#10B981',
                    600: '#059669',
                },
                warning: {
                    50: '#FFFBEB',
                    100: '#FEF3C7',
                    400: '#FBBF24',
                    500: '#F59E0B',
                    600: '#D97706',
                },
                error: {
                    50: '#FEF2F2',
                    100: '#FEE2E2',
                    400: '#F87171',
                    500: '#EF4444',
                    600: '#DC2626',
                },
                info: {
                    50: '#EFF4FF',
                    100: '#DCE6FE',
                    400: '#608DF7',
                    500: '#3B6BF0',
                    600: '#2563EB',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Plus Jakarta Sans', 'Inter', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
                bangla: ['Hind Siliguri', 'Noto Sans Bengali', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                lg: 'var(--radius)',
                md: 'calc(var(--radius) - 2px)',
                sm: 'calc(var(--radius) - 4px)',
                xl: '14px',
                '2xl': '18px',
                '3xl': '24px',
            },
            boxShadow: {
                // Flat by design: hairline borders carry elevation, not blur.
                xs: '0 1px 2px rgba(26, 26, 25, 0.04)',
                sm: '0 1px 2px rgba(26, 26, 25, 0.05)',
                DEFAULT: '0 1px 3px rgba(26, 26, 25, 0.06)',
                md: '0 2px 6px rgba(26, 26, 25, 0.06)',
                lg: '0 4px 14px rgba(26, 26, 25, 0.07)',
                xl: '0 10px 28px rgba(26, 26, 25, 0.08)',
                '2xl': '0 18px 48px rgba(26, 26, 25, 0.10)',
                card: '0 1px 2px rgba(26, 26, 25, 0.04)',
                'card-hover': '0 6px 20px rgba(26, 26, 25, 0.08)',
                primary: '0 4px 14px rgba(37, 99, 235, 0.18)',
                success: '0 4px 14px rgba(16, 185, 129, 0.18)',
                glow: '0 0 0 4px rgba(37, 99, 235, 0.10)',
            },
            maxWidth: {
                prose: '68ch',
            },
            keyframes: {
                'accordion-down': {
                    from: { height: 0 },
                    to: { height: 'var(--radix-accordion-content-height)' },
                },
                'accordion-up': {
                    from: { height: 'var(--radix-accordion-content-height)' },
                    to: { height: 0 },
                },
                blob: {
                    '0%': { transform: 'translate(0px, 0px) scale(1)' },
                    '33%': { transform: 'translate(20px, -30px) scale(1.05)' },
                    '66%': { transform: 'translate(-14px, 14px) scale(0.95)' },
                    '100%': { transform: 'translate(0px, 0px) scale(1)' },
                },
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                'slide-up': {
                    '0%': { opacity: '0', transform: 'translateY(12px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'slide-down': {
                    '0%': { opacity: '0', transform: 'translateY(-8px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'scale-in': {
                    '0%': { opacity: '0', transform: 'scale(0.97)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                'bounce-soft': {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-6px)' },
                },
                marquee: {
                    from: { transform: 'translateX(0)' },
                    to: { transform: 'translateX(-50%)' },
                },
            },
            animation: {
                'accordion-down': 'accordion-down 0.2s ease-out',
                'accordion-up': 'accordion-up 0.2s ease-out',
                blob: 'blob 12s infinite',
                'fade-in': 'fade-in 0.4s ease-out',
                'slide-up': 'slide-up 0.4s ease-out',
                'slide-down': 'slide-down 0.25s ease-out',
                'scale-in': 'scale-in 0.25s ease-out',
                'bounce-soft': 'bounce-soft 2s infinite',
                marquee: 'marquee 32s linear infinite',
            },
        },
    },

    plugins: [forms, animate],
};
