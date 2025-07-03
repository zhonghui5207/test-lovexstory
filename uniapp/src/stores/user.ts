import { getUserCenter } from '@/api/user'
import { TOKEN_KEY, SHARE_CODE } from '@/enums/cacheEnums'
import cache from '@/utils/cache'
import { defineStore } from 'pinia'
import { postIndexInvite } from '@/api/distribution'
interface UserSate {
    userInfo: Record<string, any>
    token: string | null
    temToken: string | null
}
export const useUserStore = defineStore({
    id: 'userStore',
    state: (): UserSate => ({
        userInfo: {},
        token: cache.get(TOKEN_KEY) || null,
        temToken: null
    }),
    getters: {
        isLogin: (state) => !!state.token
    },
    actions: {
        async getUser() {
            const data = await getUserCenter({
                token: this.token || this.temToken
            })
            this.userInfo = data
            this.bindDistribution()
        },
        login(token: string) {
            this.token = token
            cache.set(TOKEN_KEY, token)
        },
        logout() {
            this.token = ''
            this.userInfo = {}
            cache.remove(TOKEN_KEY)
        },
        //分销下级绑定
        async bindDistribution() {
            const code = cache.get(SHARE_CODE) || ''
            if (code == this.userInfo.code) {
                return
            }
            try {
                if (this.isLogin && code) {
                    await postIndexInvite({
                        code: code
                    })
                    cache.remove(SHARE_CODE)
                }
            } catch (error) {
                cache.remove(SHARE_CODE)
                console.log('绑定失败', error)
            }
        }
    }
})
