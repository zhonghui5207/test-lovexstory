import request from '@/utils/request'

/** 讲师 Start **/
// 讲师列表
export function apiTeacherLists(params?: {
    page_no: number
    page_size: number
    name: string | undefined
    status: number | string
}) {
    return request.get({ url: '/teacher.teacher/lists', params })
}

// 讲师添加
export function apiTeacherAdd(params: any) {
    return request.post({ url: '/teacher.teacher/add', params }, { delEmptyData: false })
}

// 讲师编辑
export function apiTeacherEdit(params: any) {
    return request.post({ url: '/teacher.teacher/edit', params }, { delEmptyData: false })
}

// 讲师详情
export function apiTeacherDetail(params: { id: number }) {
    return request.get({ url: '/teacher.teacher/detail', params })
}

// 删除讲师
export function apiTeacherDel(params: { id: number }) {
    return request.post({ url: '/teacher.teacher/del', params })
}

// 讲师状态
export function apiTeacherStatus(params: { id: number; status: number }) {
    return request.post({ url: '/teacher.teacher/status', params })
}
/** 讲师 End **/
