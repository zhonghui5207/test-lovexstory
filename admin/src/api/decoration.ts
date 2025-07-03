import request from '@/utils/request'

// 页面装修详情
export function getDecoratePages(params: any) {
    return request.get({ url: '/decorate.page/detail', params }, { ignoreCancelToken: true })
}

// 页面装修保存
export function setDecoratePages(params: any) {
    return request.post({ url: '/decorate.page/save', params })
}

// 获取首页文章数据
export function getDecorateArticle(params?: any) {
    return request.get({ url: '/decorate.data/article', params })
}

// 底部导航详情
export function getDecorateTabbar(params?: any) {
    return request.get({ url: '/decorate.tabbar/detail', params })
}

// 底部导航保存
export function setDecorateTabbar(params: any) {
    return request.post({ url: '/decorate.tabbar/save', params })
}

// 获取商城页面
export function apiLinkPage() {
    return request.get({ url: '/decorate.decoratePage/getShopPage' })
}

/** 专题专区 Start **/
// 专区列表
export function apiSubjectLists(params: { page_no: number; page_size: number }) {
    return request.get({ url: '/decorate.Subject/lists', params })
}
