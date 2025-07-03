import { useAppStore } from '@/stores/app'
import { useUserStore } from '@/stores/user'

export function useStore() {
    const appStore = useAppStore()
    const userStore = useUserStore()

    return {
        appStore,
        userStore
    }
}
