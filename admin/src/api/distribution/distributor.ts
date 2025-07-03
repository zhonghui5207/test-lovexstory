import request from '@/utils/request'

// 分销商列表
export function getDistributorList(params?: any) {
    return request.get({ url: '/distribution.distributor/lists', params })
}

//下拉列表
export function getOtherLists(params?: any) {
    return request.get({ url: '/distribution.distribution_level/commonLists', params })
}

//开通分销商
export function addDistributor(params?: any) {
    return request.post({ url: '/distribution.distributor/add', params })
}

//关闭分销状态
export function closeDistributionStatus(params?: any) {
    return request.post({ url: '/distribution.distributor/freeze', params })
}

//分销等级调整
export function levelAdjust(params?: any) {
    return request.post({ url: '/distribution.distributor/adjustLevel', params })
}
//调整上级分销商
export function adjustLeader(data?: any) {
    return request.post({ url: '/distribution.distributor/adjustFirstLeader', data })
}

//分销商详情
export function getDetail(params?: any) {
    return request.get({ url: '/distribution.distributor/detail', params })
}

//调整上级推荐人
export function adjustPid(params?: any) {
    return request.post({ url: '/mall.userDistribution/change_pid', params })
}

//下级列表
export function getLowerList(params?: any) {
    return request.get({ url: '/distribution.distributor/fansLists', params })
}

//申请列表
export function applyList(params?: any) {
    return request.get({ url: '/distribution.distribution_apply/lists', params })
}

//申请审核
export function postExamine(params?: any) {
    return request.post({ url: '/distribution.distribution_apply/audit', params })
}

//审核详情
export function emamineDetail(params?: any) {
    return request.get({ url: '/distribution.distribution_apply/detail', params })
}

//分销基础设置获取
export function getBaseSet(params?: any) {
    return request.get({ url: '/config/distribution_base_get', params })
}

//分销基础设置获取
export function setBaseSet(params?: any) {
    return request.post({ url: '/config/distribution_base_set', params })
}

//分销基础设置获取
export function getSettlementSet(params?: any) {
    return request.get({ url: '/config/distribution_settlement_get', params })
}

//分销基础设置获取
export function setSettlementSet(params?: any) {
    return request.post({ url: '/config/distribution_settlement_set', params })
}
//粉丝详情
export function fansInfo(params?: any) {
    return request.get({ url: '/distribution/store/fansInfo', params })
}
