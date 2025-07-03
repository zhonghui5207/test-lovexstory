import request from '@/utils/request'

// 获取系统环境
export function systemInfo() {
    return request.get({ url: '/setting.system.system/info' })
}

// 获取系统日志列表
export function systemLogLists(params: any) {
    return request.get({ url: '/setting.system.log/lists', params })
}

// 清除系统缓存
export function systemCacheClear() {
    return request.post({ url: '/setting.system.cache/clear' })
}

// 定时任务列表
export const apiCrontabLists = () => request.get({ url: '/crontab.crontab/lists' })

// 添加定时任务
export const apiCrontabAdd = (params: any) => request.post({ url: '/crontab.crontab/add', params })

// 查看详情
export const apiCrontabDetail = (params: any) =>
    request.get({ url: '/crontab.crontab/detail', params })

// 编辑定时任务
export const apiCrontabEdit = (params: any) =>
    request.post({ url: '/crontab.crontab/edit', params })

// 删除定时任务
export const apiCrontabDel = (params: any) =>
    request.post({ url: '/crontab.crontab/delete', params })

// 获取规则执行时间
export const apiCrontabExpression = (params: any) =>
    request.get({ url: '/crontab.crontab/expression', params })

// 操作定时任务
export const apiSrontabOperate = (params: any) =>
    request.post({ url: '/crontab.crontab/operate', params })

/** S 系统更新 **/
// 系统更新列表
export const apiSystemUpgradeLists = (params: any) =>
    request.get({ url: '/setting.system.upgrade/lists', params })

// 下载更新包
export const apiSystemUpgradeDownloadPkg = (params: any) =>
    request.post({ url: '/setting.system.upgrade/downloadPkg', params })

// 一键更新
export const apiSystemUpgrade = (params: any) =>
    request.post({ url: '/setting.system.upgrade/upgrade', params })
/** E 系统更新 **/
