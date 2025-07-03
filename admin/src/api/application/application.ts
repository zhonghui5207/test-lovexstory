import request from '@/utils/request'
/** Start 短信设置 **/
// 短信通知列表
export function apiNoticeLists(params: any) {
    return request.get({ url: '/notice.notice/settingLists', params })
}

// 短信通知详情
export function apiNoticeDetail(params: any) {
    return request.get({ url: '/notice.notice/detail', params })
}

// 设置短信通知
export function apiNoticeEdit(params: any) {
    return request.post({ url: '/notice.notice/set', params })
}

// 短信设置列表
export function apiSmsLists() {
    return request.get({ url: '/notice.sms_config/getConfig' })
}

// 短信设置详情
export function apiSmsDetail(params: any) {
    return request.get({ url: '/notice.sms_config/detail', params })
}

// 设置短信通知
export function apiSmsEdit(params: any) {
    return request.post({ url: '/notice.sms_config/setConfig', params })
}
/** End 短信设置 **/

/** Start 用户充值 **/
// 充值规则
export function apiRechargeGetRule() {
    return request.get({ url: '/recharge.recharge/getRechargeConfig' })
}

// 设置充值规则
export function apiRechargeSetRule(params: any) {
    return request.post({ url: '/recharge.recharge/setRechargeConfig', params })
}
/** End 用户充值 **/

/** 专题专区 Start **/
// 专区列表
export function apiSubjectLists(params: any) {
    return request.get({ url: '/decorate.Subject/lists', params })
}

// 专区活动状态修改
export function apiSubjectChangeStatus(params: { id: number; status: number }) {
    return request.post({ url: '/decorate.Subject/status', params })
}

// 专区添加
export function apiSubjectAdd(params: any) {
    return request.post({ url: '/decorate.Subject/add', params })
}

// 专区编辑
export function apiSubjectEdit(params: any) {
    return request.post({ url: '/decorate.Subject/edit', params })
}

// 专区删除
export function apiSubjectDel(params: { id: number }) {
    return request.post({ url: '/decorate.Subject/del', params })
}

// 专区详情
export function apiSubjectDetail(params: { id: number }) {
    return request.get({ url: '/decorate.Subject/detail', params })
}

// 专区课程列表
export function apiSubjectCourseDetail(params: { subject_id: number }) {
    return request.get({ url: '/decorate.SubjectCourse/detail', params })
}

// 专区课程保存
export function apiSubjectDecorateSave(params: any) {
    return request.post({ url: '/decorate.SubjectCourse/save', params })
}
/** 专题专区 End **/
