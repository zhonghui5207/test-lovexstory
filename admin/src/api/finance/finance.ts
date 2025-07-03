import request from '@/utils/request'

// 财务概括
export function apiFinanceDataCenter() {
    return request.get({ url: '/finance.finance/dataCenter' })
}

// 充值记录
export function apiRechargeRecord(params: any) {
    return request.get({ url: '/recharge.recharge/lists', params })
}

// 余额明细
export function apiAccountLogLists(params: any) {
    return request.get({ url: '/AccountLog/lists', params })
}

// 变动类型列表
export function apiAccountLogOtherLists(params?: any) {
    return request.get({ url: '/account_log/changeTypeLists', params })
}

// 退款列表
export function apiRefundList(params: any) {
    return request.get({ url: '/order.orderRefund/lists', params })
}

// 退款日志
export function apiRefundLogList(params: any) {
    return request.get({ url: '/order.orderRefund/logLists', params })
}

// 重新退款
export function apiReRefund(params: any) {
    return request.get({ url: '/order.orderRefund/reRefund', params })
}

//退款下拉列表
export function apiDropDownList() {
    return request.get({ url: '/order.orderRefund/otherlists' })
}

//佣金明细
export function commissionDetail(params?: any) {
    return request.get({ url: '/finance/earnings/list', params })
}
//提现记录
export function getWithdrawLists(params?: any) {
    return request.get({ url: '/finance.withdraw/lists', params })
}
//审核
export function apiWithdrawAuditSuccess(params?: any) {
    return request.post({ url: '/finance.withdraw/pass', params })
}
export function apiWithdrawAuditFail(params?: any) {
    return request.post({ url: '/finance.withdraw/refuse', params })
}

//转账WithDrawTransferType
export function WithDrawTransferSuccess(params?: any) {
    return request.post({ url: '/finance.withdraw/transferSuccess', params })
}

//转账WithDrawTransferType
export function WithDrawTransferFail(params?: any) {
    return request.post({ url: '/finance.withdraw/transferFail', params })
}
//提现详情getWithdrawDetail
export function getWithdrawDetail(params?: any) {
    return request.get({ url: '/finance.withdraw/detail', params })
}
//查询结果
export function withdrawquery(params?: any) {
    return request.get({ url: '/finance.withdraw/search', params })
}
