// 接口code类型
export const APICodeEnum = {
    '1': 'success', // 成功
    '0': 'error', // 失败
    '-1': 'redirect' // 重定向
}

// 客户端
export const ClientEnum = {
    MP_WEIXIN: 1, // 微信-小程序
    OA_WEIXIN: 2, // 微信-公众号
    H5: 3, // H5
    PC: 4, // PC
    APP: 5, // APP
    MP_TOUTIAO: 7 // 头条
}

// 支付页面
export const PayEnum = {
    ORDER: 'order', // 订单
    RECHARGE: 'recharge', // 充值
    QUESTION_BANK: 'question_bank' //题库
}

// 支付方式
export const PayWayEnum = {
    WECHAT: 1, // 微信
    ALIPAY: 2, // 支付宝
    WALLET: 3 // 钱包
}

// 用户资料
export const FieldType = {
    NONE: '',
    GENDER: 'gender',
    NICKNAME: 'nickname',
    AVATAR: 'avatar',
    MOBILE: 'mobile'
}

// 我的评价
export const MyEvaluateEnum = {
    WAIT: 0, // 待评价
    ALREADY: 1 // 已评价
}

// 商品评价
export const EvaluateGoodsEnum = {}

// 余额明细
export const AccountLogEnum = {
    ALL: '', // 全部
    EXPEND: 0, // 支出
    INCOME: 1 // 收入
}
