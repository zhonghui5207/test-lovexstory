import { throttle } from './util'
import { useUserStore } from '@/stores/user'
import { useAppStore } from '@/stores/app'
import { apiSilentLogin } from '@/api/account'

// 小程序获取用户信息
export const getUserProfile = (): Promise<void> => {
    return new Promise((resolve, reject) => {
        uni.getUserProfile({
            desc: '获取用户信息，完善用户资料',
            success: (res: Event) => resolve(res)
        })
    })
}

export const getWxCode = (): Promise<void> => {
    return new Promise((resolve, reject) => {
        uni.login({
            desc: '获取用户信息，完善用户资料',
            success: (res: Event) => resolve(res.code)
        })
    })
}

async function mpLogin() {
    const userStore = useUserStore()
    const appStore = useAppStore()
    const { coerce_mobile, mnp_auto_wechat_auth } = appStore.config

    //#ifdef  MP-WEIXIN
    // 微信关闭自动授权
    if (!mnp_auto_wechat_auth) return
    // #endif

    const code = await getWxCode()
    //#ifdef  MP-WEIXIN
    const loginData = await apiSilentLogin({
        code
    })
    // #endif
    const { options, onLoad, onShow, route } = getCurrentPages()[getCurrentPages().length - 1]
    // 需要强制绑定手机号
    if (coerce_mobile && !loginData.mobile) {
        return
    }
    if (loginData.token) {
        userStore.setToken(loginData.token)
        userStore.initUserInfoFunc()
        // 刷新页面
        onLoad && onLoad(options)
        onShow && onShow()
    }
}

export const toLogin = throttle(_toLogin, 2000)

function _toLogin() {
    uni.navigateTo({ url: '/pages/login/login' })
    //#ifdef  MP
    // mpLogin()
    // #endif
    //#ifndef MP
    // setTimeout(() => {
    //     uni.navigateTo({ url: '/pages/login/login' })
    // })
    // #endif
}
