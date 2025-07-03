import request from '@/utils/request'

/** 交易设置 Start **/
// 获取交易设置
export function apiOrderConfigGet() {
    return request.get({ url: '/setting.transaction_settings/getConfig' })
}

// 交易设置
export function apiOrderConfigSet(params: any) {
    return request.post({ url: '/setting.transaction_settings/setConfig', params })
}
/** 交易设置 End **/
