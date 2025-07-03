import request from '@/utils/request'

// 获取分类列表
export function getList(params: any) {
    return request.get({ url: '/questionbank.questionbank_category/lists', params })
}
// 添加题库分类
export function addCategorize(params: any) {
    return request.post({ url: '/questionbank.questionbank_category/add', params })
}
// 编辑题库分类
export function editCategorize(params: any) {
    return request.post({ url: '/questionbank.questionbank_category/edit', params })
}
// 删除题库分类
export function delCategorize(params: any) {
    return request.post({ url: '/questionbank.questionbank_category/del', params })
}
// 获取分类详情
export function getCategorizeDetail(params: any) {
    return request.get({ url: '/questionbank.questionbank_category/detail', params })
}
