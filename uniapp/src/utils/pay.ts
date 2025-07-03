// #ifdef H5
import { wxOaPay } from '@/core/wechat'
import { useStore } from '@/hooks/app'
// #endif
import { useAppStore } from '@/stores/app'
import { handleClientEvent } from './util.ts'

// 微信支付
export const wxpay = async (options: any) => {
    const appStore: any = useAppStore()
    await handleClientEvent({
        // 微信小程序
        MP_WEIXIN: () => {
            return new Promise((resolve, reject) => {
                console.log(options, 'weixin')
                uni.requestPayment({
                    provider: 'wxpay',
                    timeStamp: options.timeStamp,
                    // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
                    nonceStr: options.nonceStr,
                    // 支付签名随机串，不长于 32 位
                    package: options.package,
                    // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
                    signType: options.signType,
                    // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
                    paySign: options.paySign,
                    success: (res) => resolve(res),
                    cancel: (res) => reject(res),
                    fail: (res) => reject(res)
                })
            })
        },

        // 微信公众号
        OA_WEIXIN: () => {
            return wxOaPay(options)
        },

        // H5
        H5: () => {
            return new Promise((resolve, reject) => {
                // location.href =
                //     options +
                //     '&redirect_url=' +
                //     encodeURIComponent(
                //         `${appStore.config.domain}mobile/bundle/pages/order_list/index`
                //     )
                const url =
                    options +
                    '&redirect_url=' +
                    encodeURIComponent(window.location.href + '&showPop=1')
                window.open(url, '_self')
                // resolve(true)
            })
        },

        // APP
        APP: () => {
            return new Promise((resolve, reject) => {
                uni.requestPayment({
                    provider: 'wxpay',
                    orderInfo: options,
                    success: (res) => resolve(res),
                    cancel: (res) => reject(res),
                    fail: (res) => reject(res)
                })
            })
        }
    })
}

// 支付宝支付
export const alipay = async (options: any) => {
    await handleClientEvent({
        // H5
        H5: () => {
            return new Promise((resolve, reject) => {
                const alipayPage: any = window.open('', '_self')
                alipayPage.document.body.innerHTML = options
                alipayPage.document.forms[0].submit()
                resolve()
            })
        },

        // APP
        APP: () => {
            return new Promise((resolve, reject) => {
                uni.requestPayment({
                    provider: 'alipay',
                    orderInfo: options,

                    success: (res) => resolve('success'),
                    cancel: (res) => reject('fail'),
                    fail: (res) => reject('fail')
                })
            })
        },
        MP_TOUTIAO: () => {
            console.log(options)
            return new Promise((resolve, reject) => {
                uni.requestPayment({
                    provider: 'alipay',
                    orderInfo: options,
                    service: 1,
                    success: (res) => resolve(res),
                    cancel: (res) => reject(res),
                    fail: (res) => reject(res)
                })
            })
        }
    })
}
export const ttpay = async (options) => {
    return new Promise((resolve, reject) => {
        tt.pay({
            orderInfo: options,
            service: 5,
            success: (res) => {
                if (res.code == 0) {
                    resolve(res)
                } else {
                    reject(res)
                }
            },
            cancel: (res) => reject(res),
            fail: (res) => reject(res)
        })
    })
}
