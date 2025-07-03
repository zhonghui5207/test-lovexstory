import request from '@/utils/request'

/**
 * @description 首页数据
 */
// export const apiIndex = (params: any) =>
//     request.get(url:'index/index',{ ...params, terminal: getClient() })

/**
 * @description 获取装修数据
 */
export const apiDecoratePage = (params: { type: number }) =>
    request.get({ url: '/decoratePage/getDecorate', data: params })

/**
 * @description 精选课程列表
 */
export const apiChoiceCourseLists = (params: { name?: string; is_choice?: number }) =>
    request.get({ url: '/course/lists', data: params })

/**
 * @description 热门课程列表
 */
export const apihotCourseLists = () => request.get({ url: '/course/hostLists' })

/**
 * @description 热门课程列表
 */
export const apiIndexHotCourseLists = () => request.get({ url: '/course/indexHost' })

/**
 * @description 分类
 */
export const apiCategoryLists = () => request.get({ url: '/course_cagegory/lists' })

/**
 * @description 商品分类
 */
export const apiGoodsCategoryLists = (params: any) =>
    request.get({ url: '/goods_category/otherLists', data: params })

/**
 * @description 专区列表
 */
export const apiGoodsLists = (params: any) => request.get({ url: '/goods/lists', data: params })

/**
 * @description 专区
 */
export const apiStaffLists = (params: any) => request.get({ url: '/staff/lists', data: params })

/**
 * @description 讲师主页
 */
export const apiTeacherDetail = (params: any) =>
    request.get({ url: '/teacher/detail', data: params })

/**
 * @description 学习课程列表
 */
export const apiCtudyCourseLists = () => request.get({ url: '/course/studyCourseLists' })

/**
 * @description 专题专区
 */
export const apiSubjectLists = (data: any) => request.get({ url: '/subject/lists', data })

/**
 * @description 专题专区
 */
export const apiSubjectDetail = (params: { id: number }) =>
    request.get({ url: '/subject/detail', data: params })

/**
 * @description 热门课程
 */
export const apiHotLists = (params: any) => request.get({ url: '/course/hostLists', data: params })
