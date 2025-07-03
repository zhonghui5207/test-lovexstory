import request from '@/utils/request'

//优惠券列表
export function apiCouponList(params: any) {
    return request.get({ url: '/marketing.coupon/lists', params })
}

//修改优惠券状态(开始发放、结束发放)
export function apiChangeCouponState(params: any) {
    return request.post({ url: '/marketing.coupon/status', params })
}

//删除优惠券
export function apiDelCoupon(params: any) {
    return request.post({ url: '/marketing.coupon/del', params })
}

//优惠券详情
export function apiCouponDetail(params: any) {
    return request.get({ url: '/marketing.coupon/detail', params })
}

//新增优惠券
export function apiAddCoupon(params: any) {
    return request.post({ url: '/marketing.coupon/add', params })
}

//编辑优惠券
export function apiEditCoupon(params: any) {
    return request.post({ url: '/marketing.coupon/edit', params })
}

//优惠券其他列表
export function apiOtherLists(params?: any) {
    return request.get({ url: '/marketing.coupon/otherlists', params })
}

//领取记录
export function apiCouponGetRecord(params: any) {
    return request.get({ url: '/marketing.couponList/lists', params })
}

//领取记录其他列表
export function apiGetRecordOtherLists(params?: any) {
    return request.get({ url: '/marketing.couponList/otherlists', params })
}

//作废优惠券
export function apiCancelCoupon(params: any) {
    return request.post({ url: '/marketing.couponList/cancel', params })
}

//发放优惠券
export function provideCoupon(params: any) {
    return request.post({ url: '/marketing.coupon/send', params })
}
