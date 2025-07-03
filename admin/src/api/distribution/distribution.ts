import request from '@/utils/request'

// 获取分销商品列表
export function getDistributionGoods(params: any) {
    return request.get({ url: '/distribution.distribution_course/lists', params })
}

// 获取分销商品详情
export function getGoodsDetail(params: any) {
    return request.get({ url: '/distribution.distribution_course/detail', params })
}

//商品参与/不参与分销
export function postChangStatus(params: any) {
    return request.post({ url: '/distribution.distribution_course/join', params })
}

//设置分销佣金
export function distributionSave(params: any) {
    return request.post({ url: '/distribution.distribution_course/set', params })
}
//分销设置
export function getconfig(params?: any) {
    return request.get({ url: '/distribution.distribution_config/getConfig', params })
}

export function Saveconfig(data?: any) {
    return request.post({ url: '/distribution.distribution_config/setConfig', data })
}

//结算设置
export function getSettleConfig(params?: any) {
    return request.get({ url: '/distribution.distribution_config/getSettleConfig', params })
}

export function setSettleConfig(data?: any) {
    return request.post({ url: '/distribution.distribution_config/setSettleConfig', data })
}

//分销概览
export function getsurveydata(params?: any) {
    return request.get({ url: '/distribution.distribution_data/overview', params })
}
//收入排行
export function surveytopEarnings(params?: any) {
    return request.get({ url: '/distribution.distribution_data/topDistributorEarnings', params })
}
//粉丝排行
export function surveytopFans(params?: any) {
    return request.get({ url: '/distribution.distribution_data/topDistributorFans', params })
}
//分销订单
export function getorder(params?: any) {
    return request.get({ url: '/distribution.distribution_order/lists', params })
}
