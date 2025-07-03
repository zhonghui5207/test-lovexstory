import request from '@/utils/request'

/**
 * @param { Object } params
 * @return { Promise }
 * @description 领券中心
 */
export const apiCouponCenter = (params?: any) => {
    return request.get({ url: '/coupon/lists', data: params })
}

/**
 * @param { Object } params
 * @return { Promise }
 * @description 领取优惠券
 */
export const apiUserGetCoupon = (params?: any) => {
    return request.post({ url: '/coupon/receive', data: params })
}

/**
 * @param { Object } params
 * @return { Promise }
 * @description 我的优惠券
 */
export const apiMyCoupon = (params?: any) => {
    return request.get({ url: '/coupon/userCouponLists', data: params }, { ignoreCancel: true })
}

/**
 * @param { Object } params
 * @return { Promise }
 * @description 我的优惠券不同状态个数
 */
export const apiStatusCount = (params?: any) => {
    return request.get({ url: '/coupon/mine/count', data: params })
}

/**
 * @param { Object } params
 * @return { Promise }
 * @description 课程可用优惠券列表
 */
export const courseCouponList = (params?: any) => {
    return request.get({ url: '/coupon/courseCoupon', data: params })
}

/**
 * @param { Object } params
 * @return { Promise }
 * @description 用户已领取的课程可用优惠券
 */
export const orderCouponList = (params?: any) => {
    return request.get({ url: '/coupon/orderCoupon', data: params })
}

// /**
//  * @param { Object } params
//  * @return { Promise }
//  * @description 用户已领取的课程不可用优惠券
//  */
// export const unavaliableCoupon = (params?: any) => {
//     return request.get({ url: '/coupon/course/mine/unavailable', data: params })
// }

/**
 * @param { Object } params
 * @return { Promise }
 * @description 优惠券可用课程
 */
export const couponCourse = (params?: any) => {
    return request.get({ url: '/coupon/couponCourseLists', data: params })
}
