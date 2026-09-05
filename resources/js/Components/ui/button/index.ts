import { cva, type VariantProps } from 'class-variance-authority'

export { default as Button } from './Button.vue'

export const buttonVariants = cva(
    'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0',
    {
        variants: {
            variant: {
                default: 'bg-ink-900 text-paper hover:bg-ink-800',
                destructive: 'bg-error-600 text-white hover:bg-error-500',
                outline:
                    'border border-line bg-white text-ink-800 hover:border-ink-300 hover:bg-paper-subtle',
                secondary: 'bg-paper-deep text-ink-900 hover:bg-ink-200',
                ghost: 'text-ink-600 hover:bg-paper-deep hover:text-ink-900',
                link: 'text-accent-600 underline-offset-4 hover:underline',
                success: 'bg-success-600 text-white hover:bg-success-500',
            },
            size: {
                default: 'h-10 px-4',
                sm: 'h-8 px-3 text-xs',
                lg: 'h-11 px-5 text-[15px]',
                xl: 'h-12 px-6 text-base',
                icon: 'h-9 w-9',
            },
        },
        defaultVariants: {
            variant: 'default',
            size: 'default',
        },
    }
)

export type ButtonVariants = VariantProps<typeof buttonVariants>
