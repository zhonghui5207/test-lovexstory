import request from '@/utils/request'

// 分销等级列表
export function levelList(params?: any) {
    return request.get({ url: '/distribution.distribution_level/lists', params })
}

// 分销等级详情
export function levelDetail(params?: any) {
    return request.get({ url: '/distribution.distribution_level/detail', params })
}

// 分销等级删除
export function levelDel(params?: any) {
    return request.post({ url: '/distribution.distribution_level/del', params })
}
// 分销等级新增
export function levelAdd(params?: any) {
    return request.post({ url: '/distribution.distribution_level/add', params })
}
// 分销等级编辑
export function levelEdit(params?: any) {
    return request.post({ url: '/distribution.distribution_level/edit', params })
}
