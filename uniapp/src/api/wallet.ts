import request from '@/utils/request'
// 申请提现表单类型
export type ApplyWithdrawFormType = {
    type: number // 是 类型 1钱包余额 2微信零钱 3银行卡 4微信收款码 5支付宝收款码
    money: string // 是 提现金额
    bank_name: string // 否 type=3时需要 银行
    bank_sub_name: string // 否 type=3时需要 支行
    bank_account: string // 否 type=3时需要 账号
    bank_account_name: string // 否 type=3时需要 持卡人姓名
    wechat_account: string // 否 type=4时需要 微信账号
    wechat_account_name: string // 否 type=4时需要 微信真实姓名
    wechat_qr_code: string[] | string // 否 type=4时需要 微信二维码
    alipay_account: string // 否 type=5时需要 支付宝账号
    alipay_account_name: string // 否 type=5时需要 支付宝真实姓名
    alipay_qr_code: string[] | string // 否 type=5时需要 支付宝收款码
    remark: string // 否 提现备注
}
//钱包数据
export const walletData = (params?: any) => request.get({ url: '/user/wallet', data: params })

//提现列表
export function userWithdrawList(data: { page_no: number; page_size: number }) {
    return request.get({ url: '/withdraw/lists', data }, { isAuth: true })
}

//提现配置
export function userWithdrawConfig() {
    return request.get({ url: '/withdraw/getConfig' }, { isAuth: true })
}

//申请提现
export function userWithdrawApply(data: ApplyWithdrawFormType) {
    return request.post({ url: '/withdraw/apply', data }, { isAuth: true })
}

//提现详情
export function userWithdrawDetail(data: { id: number }) {
    return request.get({ url: '/withdraw/detail', data }, { isAuth: true })
}
