import request from '@/utils/request'

export function getUserCenter(header?: any) {
    return request.get({ url: '/user/center', header })
}

// 个人信息
export function getUserInfo() {
    return request.get({ url: '/user/info' }, { isAuth: true })
}

// 个人编辑
export function userEdit(data: any) {
    return request.post({ url: '/user/setInfo', data }, { isAuth: true })
}

// 绑定手机
export function userBindMobile(data: any, header?: any) {
    return request.post({ url: '/user/bindMobile', data, header }, { isAuth: true })
}

// 微信电话
export function userMnpMobile(data: any) {
    return request.post({ url: '/user/getMobileByMnp', data }, { isAuth: true })
}

// 更改手机号
export function userChangePwd(data: any) {
    return request.post({ url: '/user/changePassword', data }, { isAuth: true })
}

//忘记密码
export function forgotPassword(data: Record<string, any>) {
    return request.post({ url: '/user/resetPassword', data })
}

/**
 * @description 发送验证码-绑定手机号
 */
export const apiBindMobileCaptcha = (data: any, header?: any) =>
    request.post({ url: '/user/bindMobileCaptcha', data, header })

/**
 * @description 绑定手机号
 */
export const apiBindMobile = (params: any) =>
    request.post({ url: '/user/bindMobile', data: params })

/**
 * @description 获取我的收藏列表
 */
export const apiCollectLists = (params: any) => request.get({ url: '/collect/lists', data: params })

/**
 * @description 课程收藏
 */
export const apiCourseCollection = (params) =>
    request.post({ url: '/collect/collect', data: params })
/** Collect End **/

/** Evaluate Start **/
/**
 * @description 获取我的评价列表
 */
export const apiEvaluateLists = (params: any) =>
    request.get({ url: '/CourseComment/lists', data: params })

/**
 * @description 评价服务信息
 */
export const apiEvaluateGoodsInfo = (params: any) =>
    request.get({ url: '/CourseComment/commentGoodsInfo', data: params })

/**
 * @description 提交评价信息
 */
export const apiEvaluateAdd = (params: any) =>
    request.post({ url: '/CourseComment/comment', data: params })
/** Evaluate End **/

/** Wallet Start **/
/**
 * @return { Promise }
 * @description 我的钱包
 */
export const apiUserWallet = () => request.get({ url: '/user/wallet' }, { isAuth: true })

/**
 * @return { Promise }
 * @description 账户流水
 */
export const apiAccountLogLists = (params: {
    page_no: number
    page_size: number
    type: number
    change_object: number
}) => request.get({ url: '/AccountLog/lists', data: params }, { ignoreCancel: true })
/** Wallet End **/

/** Charge Start **/
/**
 * @return { Promise }
 * @description 充值模版
 */
export const apiChargeTemplateLists = () => request.get({ url: '/recharge/templateLists' })

/**
 * @description 充值金额
 */
export const apiChargeMoney = (params: { money: number }) =>
    request.post({ url: '/recharge/recharge', data: params })

/**
 * @return { Promise }
 * @description 充值记录
 */
export const apiChargeLogLists = (params: { page_no: number; page_size: number }) =>
    request.get({ url: '/recharge/logLists', data: params })
/** Charge End **/
