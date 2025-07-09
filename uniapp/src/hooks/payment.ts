import { apiPayPayWay, apiPayPrePay, countPrice } from '@/api/app'
import { wxOaPay } from '@/core/wechat'
import { wxpay } from '@/utils/pay'
import { toast, getClient } from '@/utils/util'
import { PayEnum, PayWayEnum, ClientEnum } from '@/utils/enum'
import { reactive, ref } from 'vue'

interface PaymentObj {
    from: string
    order_id: number | string
    cancelTime: string | number
    orderAmount: string
    payWayLists: object
    couponId: number | string
}

/**
 * @description 支付函数钩子
 * @param { from } 支付来源
 * @return { Function } 暴露钩子
 */
export function usePay() {
    const loading = ref<boolean>(true)
    // 支付对象
    const payment = reactive<PaymentObj>({
        from: '',
        order_id: '',
        cancelTime: 0,
        payWayLists: [] as any[],
        orderAmount: '',
        couponId: ''
    })

    /**
     * @description 1. 初始化获取支付方式
     * @param { order_id } 需要支付的订单ID
     */
    const initPayWay = async (order_id: number, from: string): Promise<void> => {
        try {
            payment.order_id = order_id
            payment.from = from
            const { cancel_time, lists, order_amount } = await apiPayPayWay({
                from: from,
                order_id: order_id,
                scene: getClient()
            })
            payment.cancelTime = parseInt(String(cancel_time * 1000 - new Date().getTime()))
            payment.payWayLists = lists
            payment.orderAmount = order_amount
            loading.value = false
        } catch (err) {
            toast('错误：', err)
        }
    }

    /**
     * @description 2. 预支付
     * @param { pay_way } 支付方式
     */
    const handlePayPrepay = async (pay_way: number): Promise<void> => {
        try {
            const res = await apiPayPrePay({
                from: payment.from,
                pay_way: pay_way,
                order_id: payment.order_id,
                coupon_list_id: payment.couponId
            })
            console.log(
                res,
                {
                    from: payment.from,
                    pay_way: pay_way,
                    order_id: payment.order_id,
                    coupon_list_id: payment.couponId
                },
                123
            )

            let result = null
            // 支付
            switch (pay_way * 1) {
                case PayWayEnum.WALLET:
                    result = await handleWalletPay()
                    break
                case PayWayEnum.WECHAT:
                    result = await handleWechatPay(res)
                    break
                case PayWayEnum.ALIPAY:
                    result = await handleAliPay(res)
                    break
                default:
                    throw '支付方式不对'
            }
            handlePayResult(result)
        } catch (err: any) {
            toast(err)
            console.log('预支付', err)
        }
    }

    /**
     * @description 微信支付
     * @param { params } 支付订单
     */
    const handleWechatPay = async (params: any): Promise<boolean> => {
        try {
            // #ifndef H5
            await wxpay(params.config)
            // #endif
            // #ifdef H5
            if (getClient() == ClientEnum.H5) {
                await wxpay(params.config)
            } else {
                await wxOaPay(params.config)
            }
            // #endif
            return true
        } catch (err) {
            toast('支付取消')
            console.log('微信支付失败', err)
            return false
        }
    }

    /**
     * @description 支付宝支付
     * @param { params } 支付订单
     */
    const handleAliPay = async (params: any): Promise<boolean> => {
        try {
            // 支付宝H5支付，直接跳转
            // #ifdef H5
            if (params.config) {
                // 创建临时表单并提交
                const tempForm = document.createElement('div')
                tempForm.innerHTML = params.config
                document.body.appendChild(tempForm)
                const form = tempForm.querySelector('form')
                if (form) {
                    form.submit()
                }
                return true
            }
            // #endif
            throw '支付宝支付配置错误'
        } catch (err) {
            console.log('支付宝支付失败', err)
            return false
        }
    }

    /**
     * @description 钱包支付
     */
    const handleWalletPay = async (): Promise<boolean> => {
        return true
    }

    /**
     * @description 3. 支付结果
     * @param { result } 支付结果
     */
    const handlePayResult = (result: boolean) => {
        // 只在结算订单界面跳转
        if (getClient() != ClientEnum.H5) {
            //非h5端
            if (payment.from == PayEnum.RECHARGE && result) {
                uni.$emit('payment')
                uni.navigateBack()
                return
            }
            if (payment.from == PayEnum.ORDER && result) {
                uni.redirectTo({
                    url: `/bundle/pages/pay_result/index?orderId=${payment.order_id}`
                })
                return
            }
            const pages = getCurrentPages()
            const page = pages[pages.length - 2]
            switch (page.route) {
                case 'pages/course_detail/index':
                    uni.redirectTo({ url: '/bundle/pages/order_list/index' })
                    break
                default:
                    uni.navigateBack()
                    break
            }
        } else {
            //h5端
            if (payment.from == PayEnum.RECHARGE) {
                if (result) {
                    uni.$emit('payment')
                    uni.redirectTo({
                        url: `/bundle/pages/user_charge/index`
                    })
                    return
                } else {
                    uni.redirectTo({
                        url: `/bundle/pages/user_charge/index`
                    })
                    return
                }
            }
            if (payment.from == PayEnum.ORDER) {
                if (result) {
                    uni.redirectTo({
                        url: `/bundle/pages/pay_result/index?orderId=${payment.order_id}`
                    })
                    return
                } else {
                    uni.redirectTo({ url: '/bundle/pages/order_list/index' })
                    return
                }
            }
        }
    }

    //优惠券/积分计算价格
    const getPrice = async (couponId = '') => {
        payment.couponId = couponId
        const { order_amount } = await countPrice({
            from: payment.from,
            order_id: payment.order_id,
            coupon_list_id: couponId
        })
        payment.orderAmount = order_amount
    }

    return {
        loading,
        payment,
        initPayWay,
        handlePayPrepay,
        handlePayResult,
        getPrice
    }
}
