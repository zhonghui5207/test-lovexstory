import request from '@/utils/request'

/**
 * @param { Object } params
 * @return { Promise }
 * @description 订单下单
 */
export const apiPlaceOrder = (params: { course_id: number | string }) =>
    request.post({ url: '/order/sumbitOrder', data: params })

/**
 * @param { Object } params
 * @return { Promise }
 * @description 订单列表
 */
export const apiOrderLists = (params: any) =>
    request.get({ url: '/order/lists', data: params }, { ignoreCancel: true })

/**
 * @param { Object } params
 * @return { Promise }
 * @description 订单详情
 */
export const apiOrderDetail = (params: any) => request.get({ url: '/order/detail', data: params })

/**
 * @param { Object } params
 * @return { Promise }
 * @description 取消订单
 */
export const apiOrderCancel = (params: any) => request.post({ url: '/order/cancel', data: params })

/**
 * @param { Object } params
 * @return { Promise }
 * @description 删除订单
 */
export const apiDelOrder = (params: any) => request.post({ url: '/order/del', data: params })
