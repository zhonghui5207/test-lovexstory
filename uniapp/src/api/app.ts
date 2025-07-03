import request from '@/utils/request'
// #ifdef H5
import { getSignLink } from '@/core/wechat'
// #endif
import { isWeixinClient, getClient } from '@/utils/client'

//发送短信
export function smsSend(data: any) {
    return request.post({ url: '/sms/sendCode', data: data })
}

export function getConfig() {
    return request.get({ url: '/index/config' })
}
// app微信登陆
export const apiAppWxLogin = (params: any) =>
    request.post({
        url: '/login/uniAppLogin',
        data: {
            ...params,
            terminal: getClient()
        }
    })
export function getPolicy(data: any) {
    return request.get({ url: '/index/policy', data: data })
}
/**
 * @param { Object } params
 * @return { Promise }
 * @description 支付方式列表
 */
export const apiPayPayWay = (params: { from: string; order_id: number; scene: any }) =>
    request.get({ url: '/pay/payway', data: params })

/**
 * @param { Object } params
 * @return { Promise }
 * @description 预支付
 */
export const apiPayPrePay = (params: {
    from: string
    pay_way: number
    order_id: string | number
    coupon_list_id: string | number
}) =>
    request.post(
        { url: '/pay/prepay', data: params }
        // {
        //     isTransformResponse: false
        // }
    )

//计算订单价格
export const countPrice = (params: any) => request.get({ url: '/pay/orderAmount', data: params })

/**
 * @param { Object } params
 * @return { Promise }
 * @description 公众号登录
 */
export const apiOALogin = (params) => request.post({ url: 'login/oaLogin', data: params })

/**
 * @param { Object } params
 * @return { Promise }
 * @description 向微信请求code的链接
 */
export const apiCodeUrlGet = () =>
    request.get({
        url: 'login/codeUrl',
        data: {
            url: location.href,
            headers: { 1: 1 }
        }
    })

/**
 * @param { Object } params
 * @return { Promise }
 * @description 微信sdk配置
 */
export const apiJsConfig = () =>
    request.get({ url: '/wechat/jsConfig', data: { url: getSignLink() } })

export function uploadImage(file: any, token?: string) {
    return request.uploadFile({
        url: '/upload/image',
        filePath: file,
        name: 'file',
        header: {
            token
        },
        fileType: 'image'
    })
}
