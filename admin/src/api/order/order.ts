import request from '@/utils/request'

/** 订单管理 Start **/
// 订单列表
export function apiOrderLists(params: any) {
    return request.get({ url: '/order.order/lists', params })
}

// 订单列表
export function apiOrderOtherLists() {
    return request.get({ url: '/order.order/otherLists' })
}

// 订单详情
export function apiOrderDetail(params: any) {
    return request.get({ url: '/order.order/detail', params })
}

// 订单取消
export function apiOrderCancel(params: any) {
    return request.post({ url: '/order.order/cancel', params })
}

// 订单商家备注
export function apiOrderRemark(params: any) {
    return request.post({ url: '/order.order/shopRemark', params })
}

// 订单删除
export function apiOrderDel(params: any) {
    return request.post({ url: '/order.order/del', params })
}

// 订单退款
export function apiOrderRefund(params: any) {
    return request.post({ url: '/order.order/refund', params })
}
/** 订单管理 End **/
