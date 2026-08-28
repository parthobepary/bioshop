export function useToast() {
    const toast = {
        success: (message: string, title?: string) => {
            if ((window as any).$toast) {
                (window as any).$toast.success(message, title)
            }
        },
        error: (message: string, title?: string) => {
            if ((window as any).$toast) {
                (window as any).$toast.error(message, title)
            }
        },
        warning: (message: string, title?: string) => {
            if ((window as any).$toast) {
                (window as any).$toast.warning(message, title)
            }
        },
        info: (message: string, title?: string) => {
            if ((window as any).$toast) {
                (window as any).$toast.info(message, title)
            }
        },
    }

    return { toast }
}
