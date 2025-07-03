import request from '@/utils/request'

// 用户信息
export function apiUserInfo() {
    return request.get({ url: '/auth.admin/mySelf' })
}

// 用户列表
export function apiUserLists(params: {
    keyword: string
    create_time: string
    end_time: string
    page_no: number
    page_size: number
}) {
    return request.get({ url: '/user.user/lists', params })
}

// 用户详情
export function apiUserDetail(params: any) {
    return request.get({ url: '/user.user/detail', params })
}

// 调整用户钱包
export function apiAdjustUserWallet(params: {
    money: number
    action: number
    id: number
    remark: string
}) {
    return request.post({ url: '/user.user/adjustUserWallet', params })
}

// 设置用户信息
export function apiSetUserInfo(params: {
    id: number | string
    field: string
    value: string | number
}) {
    return request.post({ url: '/user.user/setinfo', params }, { delEmptyData: false })
}

// 回收课程
export function apiRecycleCourse(params: { id: number | string; course_id: number }) {
    return request.post({ url: '/user.user/recycleCourse', params })
}
