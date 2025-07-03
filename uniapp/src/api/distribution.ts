import request from '@/utils/request'

//申请页配置
interface ApplyConfig {
    /**
     * 申请页宣传图
     */
    apply_image: string
    /**
     * 协议内容
     */
    apply_protocol_content: number
    /**
     * 申请协议：1-显示；0-隐藏；
     */
    is_apply_protocol: number
}

//分销等级信息
interface DistributionLevel {
    /**
     * 等级背景图
     */
    level_bg: string
    /**
     * 等级图标
     */
    level_ico: string
    /**
     * 等级名称
     */
    name: string
}

//用户信息
interface User {
    /**
     * 用户头像
     */
    avatar: string
    /**
     * 用户ID
     */
    id: number
    /**
     * 用户昵称
     */
    nickname: string
    /**
     * 可提现佣金
     */
    user_earnings: string
}

export interface IMyData {
    /**
     * 申请页配置
     */
    apply_config: ApplyConfig
    /**
     * 邀请码
     */
    code: string
    /**
     * 分销等级信息
     */
    distribution_level: DistributionLevel
    /**
     * 分销订单数
     */
    distribution_order: number
    /**
     * 粉丝数
     */
    fans: number
    /**
     * 上级分销商
     */
    first_leader_name: string
    /**
     * 是否是分销商：1-是；0-否；
     */
    is_distributon: number
    /**
     * 本月预估收益
     */
    month_earnings: number
    /**
     * 今日预估收益
     */
    today_earnings: number
    /**
     * 累计获得收益
     */
    total_earnings: number
    /**
     * 用户信息
     */
    user: User
}

/**
 * @param { Object } params
 * @return { Promise }
 * @description 分销主页
 */
export const apiDistributeData = (params?: any) =>
    request.get({ url: '/distribution/index', data: params })

// To parse this data:
//
//   import { Convert, Response } from "./file";
//
//   const response = Convert.toResponse(json);

export interface Response {
    code: number
    data: Data
    msg: string
    show: number
}

export interface IApplyDetail {
    /**
     * 审核说明
     */
    audit_remark: null
    /**
     * 审核时间
     */
    audit_time: string
    /**
     * 市
     */
    city_desc: string
    /**
     * 申请时间
     */
    create_time: string
    /**
     * 区
     */
    district_desc: string
    /**
     * 申请ID
     */
    id: number
    /**
     * 手机号码
     */
    mobile: string
    /**
     * 省
     */
    province_desc: string
    /**
     * 真实姓名
     */
    real_name: string
    /**
     * 申请原因
     */
    reason: string
    /**
     * 审核状态：0-待审核；1-审核通过；2-审核不通过
     */
    status: number
    /**
     * 审核状态
     */
    status_desc: string
    /**
     * 用户ID
     */
    user_id: number
}

//分销申请详情
export const apiApplyDetail = (params?: any) =>
    request.get({ url: '/distribution/applyDetail', data: params })

//分销申请
export const apiApply = (params?: any) => request.post({ url: '/distribution/apply', data: params })

//分销粉丝
export const getMyFansList = (params?: any) =>
    request.get({ url: '/distribution/fansLists', data: params })

//分销订单
export const getOrderList = (params?: any) =>
    request.get({ url: '/distribution/orderLists', data: params })

//分销等级列表
export const getLevelList = (params?: any) =>
    request.get({ url: '/distribution/levelLists', data: params })

//填写邀请码
export const postInvite = (params?: any) =>
    request.post({ url: '/distribution/code', data: params })

export const postIndexInvite = (params?: any) =>
    request.post({ url: '/distribution/indexCode', data: params })
