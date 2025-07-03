import request from '@/utils/request'

// 获取列表
export function getList(params: any) {
    return request.get({ url: '/questionbank.questionbank_record/lists', params })
}

// 获取列表
export function Detail(params: any) {
    return request.get({ url: '/questionbank.questionbank_record/detail', params })
}
