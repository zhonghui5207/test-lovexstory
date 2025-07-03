import request from '@/utils/request'

// 配置
export function getConfig() {
    return request.get({ url: '/config/getConfig' })
}

// 工作台主页
export function apiWorkbench() {
    return request.get({ url: '/Workbench/index' })
}

//下拉数据
export function getSelectData(params: any) {
    return request.get({ url: '/config/selectData', params }, { ignoreCancelToken: true })
}

//字典数据
export function getDictData(params: any) {
    return request.get({ url: '/config/dict', params })
}

//正版检测
export function apiCheckLegal(params: any) {
    return request.get({ url: '/config/checkLegal', params })
}

//新版检测
export function checkVersion(params: any) {
    return request.get({ url: '/config/checkVersion', params })
}
