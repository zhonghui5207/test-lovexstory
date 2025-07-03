import { client } from '@/utils/client'
import request from '@/utils/request'

// 登录
export function login(data: Record<string, any>) {
    return request.post({ url: '/login/account', data: { ...data, terminal: client } })
}

//注册
export function register(data: Record<string, any>) {
    return request.post({ url: '/login/register', data: { ...data, channel: client } })
}

//向微信请求code的链接
export function getWxCodeUrl() {
    const url = location.href.split('?')
    return request.get({ url: '/login/codeUrl', data: { url: url[0] } })
}

export function OALogin(data: Record<string, any>) {
    return request.post({ url: '/login/oaLogin', data })
}

export function mnpLogin(data: Record<string, any>) {
    return request.post({ url: '/login/mnpLogin', data })
}
//小程序绑定微信
export function mnpBindingWX(data: Record<string, any>) {
    return request.post({ url: '/login/mnpAuthBind', data })
}
//公众号绑定微信
export function oaBindingWX(data: Record<string, any>) {
    return request.post({ url: '/login/oaAuthBind', data })
}

//更新微信小程序头像昵称
export function updateUser(data: Record<string, any>, header: any) {
    return request.post({ url: '/login/updateUser', data, header })
}

/**
 * @param { Object } params
 * @return { Promise }
 * @description 小程序静默登录
 */
export const apiSilentLogin = (params: any) =>
    request.post({ url: 'login/silentLogin', data: params })

/** Login Start **/
/**
 * @param { Object } params
 * @return { Promise }
 * @description 微信登录
 */
export const apiWechatAuthLogin = (params: any) =>
    request.post({ url: '/login/authLogin', data: params })

/**
 * @param { Object } params
 * @return { Promise }
 * @description 手机号登录--发送验证码
 */
export const apiCaptchaLogin = (params: any) =>
    request.post({ url: '/login/captcha', data: params })

/**
 * @param { Object } params
 * @return { Promise }
 * @description 手机号密码/手机号验证码登录
 */
export const apiAccountLogin = (params: any) => request.post({ url: 'login/account', data: params })
