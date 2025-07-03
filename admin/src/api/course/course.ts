import request from '@/utils/request'

/** 课程管理 Start **/
// 课程管理列表
export function apiCourseLists(params: {
    page_no: number
    page_size: number
    name: string | undefined
}) {
    return request.get({ url: '/course.course/lists', params })
}

// 课程添加
export function apiCourseAdd(params: any) {
    return request.post({ url: '/course.course/add', params }, { delEmptyData: false })
}

// 课程编辑
export function apiCourseEdit(params: any) {
    return request.post({ url: '/course.course/edit', params }, { delEmptyData: false })
}

// 课程删除
export function apiCourseDel(params: any) {
    return request.post({ url: '/course.course/del', params })
}

// 课程状态
export function apiCourseStatus(params: any) {
    return request.post({ url: '/course.Course/status', params })
}

// 课程详情
export function apiCourseDetail(params: any) {
    return request.get({ url: '/course.course/detail', params })
}

// 其他列表
export function apiCourseOtherLists() {
    return request.get({ url: '/course.course/otherLists' })
}

// 公共课程列表
export function apiCourseCommonLists(params: {
    id: number
    type: number
    page_no: number
    page_size: number
    name: string
}) {
    return request.get({ url: '/course.Course/commonLists', params }, { ignoreCancelToken: true })
}
/** 课程管理 Start **/

/** 课程资料 Start **/
// 课程资料详情
export function apiGetCourseMaterial(params: any) {
    return request.get({ url: '/course.CourseMaterial/detail', params })
}

// 课程资料设置
export function apiSetCourseMaterial(params: any) {
    return request.post({ url: '/course.CourseMaterial/set', params })
}
/** 课程资料 Start **/

/** 目录 Start **/
// 课程目录列表
export function apiCourseCatalogueLists(params: {
    page_no: number
    page_size: number
    name: string | undefined
}) {
    return request.get({ url: '/course.CourseCatalogue/lists', params })
}

// 课程目录添加
export function apiCourseCatalogueAdd(params: any) {
    return request.post({ url: '/course.CourseCatalogue/add', params })
}

// 课程目录编辑
export function apiCourseCatalogueEdit(params: any) {
    return request.post({ url: '/course.CourseCatalogue/edit', params })
}

// 课程目录删除
export function apiCourseCatalogueDel(params: { id: number }) {
    return request.post({ url: '/course.CourseCatalogue/del', params })
}

// 课程目录详情
export function apiCourseCatalogueDetail(params: { id: number }) {
    return request.get({ url: '/course.CourseCatalogue/detail', params })
}

// 课程目录状态
export function apiCourseCatalogueChangeFeeType(params: any) {
    return request.post({ url: '/course.courseCatalogue/status', params })
}

// 课程收费类型
export function apiCourseFeeType(params: { id: number }) {
    return request.get({ url: '/course.course/getFeeType', params })
}

// 专栏列表
export function apiCourseColumnLists(params: { id: number }) {
    return request.get({ url: '/course.CourseColumn/lists', params })
}

// 添加专栏
export function apiCourseColumnAdd(params: { course_ids: number[]; id: number }) {
    return request.post({ url: '/course.CourseColumn/add', params })
}

// 删除专栏
export function apiCourseColumnDel(params: { column_id: number; id: number }) {
    return request.post({ url: '/course.CourseColumn/del', params })
}

// 设置专栏收费方式
export function apiCourseColumnChangeFeeType(params: { id: number; fee_type: number }) {
    return request.post({ url: '/course.CourseColumn/changeFeeType', params })
}
/** 目录 End **/

/** 分类 Start **/
// 分类列表
export function apiCategoryLists(params?: {
    page_no: number
    page_size: number
    name: string | undefined
}) {
    return request.get({ url: '/course.courseCategory/lists', params })
}

// 添加分类
export function apiCategoryAdd(params: any) {
    return request.post({ url: '/course.courseCategory/add', params })
}

// 删除分类
export function apiCategoryDel(params: { id: number }) {
    return request.post({ url: '/course.courseCategory/del', params })
}

// 编辑分类
export function apiCategoryEdit(params: any) {
    return request.post({ url: '/course.courseCategory/edit', params }, { delEmptyData: false })
}

// 编辑分类状态
export function apiCategoryStatusEdit(params: { id: number; status: number }) {
    return request.post({ url: '/course.courseCategory/status', params })
}

// 分类详情
export function apiCategoryDetail(params: any) {
    return request.get({ url: '/course.courseCategory/detail', params })
}

// 通用分类列表
export function apiCategoryCommonLists(params?: { level?: number | undefined }) {
    return request.get({ url: '/course.CourseCategory/levelByList', params })
}
/** 分类 End **/

/** 服务评价 Start **/
// 评价列表
export function apiCommentLists(params: any) {
    return request.get({ url: '/order.courseComment/lists', params })
}

// 评价回复
export function apiCommentReply(params: any) {
    return request.post({ url: '/order.courseComment/reply', params })
}

// 删除评价
export function apiCommentDel(params: any) {
    return request.post({ url: '/order.courseComment/del', params })
}

// 评价详情
export function apiCommentDetail(params: any) {
    return request.get({ url: '/order.courseComment/detail', params })
}
/** 单位 End **/
