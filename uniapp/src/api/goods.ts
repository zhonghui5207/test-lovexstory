import request from '@/utils/request'
/**
 * @param { Object } params
 * @return { Promise }
 * @description 获取课程详情
 */
export const apiCourseDetail = (params) => request.get({ url: '/Course/detail', data: params })

/**
 * @param { Object } params
 * @return { Promise }
 * @description 获取课程目录
 */
export const apiCourseCatalogue = (params) =>
    request.get({ url: '/course/catalogue', data: params })

/**
 * @param { Object } params
 * @return { Promise }
 * @description 获取商品评价列表
 */
export const apiEvaluateGoodsLists = (params: any) =>
    request.get({ url: '/goods_comment/lists', data: params })

/**
 * @param { Object } params
 * @return { Promise }
 * @description 获取商品评价分类列表
 */
export const apiEvaluateGoodsCategory = (params: any) =>
    request.get({ url: '/goods_comment/commentCategory', data: params })

/**
 * @param { Object } params
 * @return { Promise }
 * @description 获取商品评价分类列表
 */
export const apiCourseStudy = (params: { id: number }) =>
    request.post({ url: '/course/study', data: params })

//更新目录进度
export const apiUpdateSchedule = (params: {
    catalogue_id: number | string
    schedule: number | string
}) => request.post({ url: '/course/updateSchedule', data: params }, {ignoreCancel:true})
