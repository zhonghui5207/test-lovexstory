import request from '@/utils/request'

// 获取列表
export function getList(params: any) {
    return request.get({ url: '/questionbank.questionbank/lists', params })
}

//无分页分类列表
export function noPageCategorizeList(params?: any) {
    return request.get({ url: '/questionbank.questionbank_category/otherLists', params })
}

// 添加题库
export function addQuestionbank(params: any) {
    return request.post({ url: '/questionbank.questionbank/add', params })
}

// 题库详情
export function questionbankDetail(params: any) {
    return request.get({ url: '/questionbank.questionbank/detail', params })
}

// 编辑题库
export function editQuestionbank(params: any) {
    return request.post({ url: '/questionbank.questionbank/edit', params })
}

// 发布题库
export function publish(params: any) {
    return request.post({ url: '/questionbank.questionbank/publish', params })
}

// 下架
export function withdraw(params: any) {
    return request.post({ url: '/questionbank.questionbank/off', params })
}

// 删除题库
export function deleteQusetionbank(params: any) {
    return request.post({ url: '/questionbank.questionbank/del', params })
}

/**题目 */

// 题目列表
export function topicListData(params: any) {
    return request.get({ url: '/questionbank.questionbank_topic/lists', params })
}

// 题目详情
export function topicDetail(params: any) {
    return request.get({ url: '/questionbank.questionbank_topic/detail', params })
}

// 编辑题目
export function tipicEdit(params: any) {
    return request.post({ url: '/questionbank.questionbank_topic/edit', params })
}

// 添加题目
export function tipicAdd(params: any) {
    return request.post({ url: '/questionbank.questionbank_topic/add', params })
}

// 删除题目
export function deleteTopic(params: any) {
    return request.post({ url: '/questionbank.questionbank_topic/del', params })
}
