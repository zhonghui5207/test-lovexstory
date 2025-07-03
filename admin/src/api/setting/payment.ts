import request from '@/utils/request'
/** 支付配置 Start **/
// 获取支付配置
export function apiPaymentConfigLists() {
    return request.get({ url: '/setting.pay.pay_config/lists' })
}

// 支付配置设置
export function apiPaymentConfigSet(params: any) {
    return request.post({ url: '/setting.pay.pay_config/edit', params })
}

// 支付配置详情
export function apiPaymentConfigDetail(params: any) {
    return request.get({ url: '/setting.pay.pay_config/detail', params })
}

// 获取支付方式列表
export function apiPaymentWayLists() {
    return request.get({ url: '/setting.pay.pay_way/getPayWay' })
}

// 支付方式设置
export function apiPaymentWaySet(params: any) {
    return request.post({ url: '/setting.pay.pay_way/setPayWay', params })
}
/** 支付配置 End **/
