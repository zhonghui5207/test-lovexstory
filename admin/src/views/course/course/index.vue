<template>
    <div>
        <el-card shadow="never" class="!border-none">
            <!-- Header Form Start -->
            <el-form :model="formData" label-width="90px" inline>
                <el-form-item label="课程名称">
                    <el-input
                        class="w-[280px]"
                        v-model="formData.name"
                        placeholder="请输入课程名称"
                    />
                </el-form-item>
                <el-form-item label="讲师名称">
                    <el-input
                        class="w-[280px]"
                        v-model="formData.teacher_name"
                        placeholder="请输入讲师名称"
                    />
                </el-form-item>
                <el-form-item label="课程分类">
                    <el-cascader
                        class="w-[280px]"
                        v-model="formData.category_id"
                        :options="categoryData"
                        :props="{
                            checkStrictly: true,
                            label: 'name',
                            value: 'id',
                            children: 'sons',
                            emitPath: false
                        }"
                        clearable
                        filterable
                        placeholder="请选择"
                    ></el-cascader>
                </el-form-item>
                <el-form-item label="收费方式">
                    <el-select v-model="formData.fee_type" class="w-[280px]" placeholder="请选择">
                        <el-option label="全部" value></el-option>
                        <el-option label="免费" value="0"></el-option>
                        <el-option label="收费" value="1"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="课程状态">
                    <el-select v-model="formData.status" class="w-[280px]" placeholder="请选择">
                        <el-option label="全部" value></el-option>
                        <el-option label="上架中" :value="1"></el-option>
                        <el-option label="已下架" :value="0"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <div class="ml-[20px]">
                        <el-button
                            type="primary"
                            @click="resetPage"
                            v-perms="['course.course/lists']"
                            >查询</el-button
                        >
                        <el-button @click="resetParams" v-perms="['course.course/reset']"
                            >重置</el-button
                        >
                    </div>
                </el-form-item>
            </el-form>
            <!-- Header Form End -->
        </el-card>

        <el-card shadow="never" class="!border-none mt-4">
            <!-- Main BtnGroup Start -->
            <div class="mb-4 flex">
                <el-button
                    type="primary"
                    v-if="pageType === 1"
                    @click="$router.push(`imageText/detail?type=${pageType}`)"
                    v-perms="['course.course/add']"
                    >新增图文
                </el-button>
                <el-button
                    type="primary"
                    v-if="pageType === 2"
                    @click="$router.push(`audio/detail?type=${pageType}`)"
                    v-perms="['course.course/add']"
                >
                    新增音频
                </el-button>
                <el-button
                    type="primary"
                    v-if="pageType === 3"
                    @click="$router.push(`video/detail?type=${pageType}`)"
                    v-perms="['course.course/add']"
                >
                    新增视频
                </el-button>
                <el-button
                    type="primary"
                    v-if="pageType === 4"
                    @click="$router.push(`column/detail?type=${pageType}`)"
                    v-perms="['course.course/add']"
                >
                    新增专栏
                </el-button>
                <div class="ml-4">
                    <el-button
                        :disabled="selectionData.length == 0"
                        @click="handleChangeStatus(selectionData, 0)"
                        v-perms="['course.Course/status']"
                        >批量上架</el-button
                    >
                    <el-button
                        :disabled="selectionData.length == 0"
                        @click="handleChangeStatus(selectionData, 1)"
                        v-perms="['course.Course/status']"
                        >批量下架</el-button
                    >
                    <el-button
                        :disabled="selectionData.length == 0"
                        @click="handleDelete(selectionData)"
                        v-perms="['course.course/del']"
                        >批量删除</el-button
                    >
                </div>
            </div>
            <!-- Main BtnGroup Start -->

            <!-- Main TableData Start -->
            <div class="m-t-20">
                <el-table
                    ref="tableData"
                    :data="pager.lists"
                    style="width: 100%"
                    @selection-change="handleSelectionChange"
                    v-loading="pager.loading"
                >
                    <el-table-column type="selection" min-width="55" />
                    <el-table-column property="id" label="ID" min-width="55" />
                    <el-table-column property="name" label="课程信息" min-width="280">
                        <template #default="scope">
                            <div class="flex items-center">
                                <div>
                                    <el-image
                                        style="width: 80px; height: 60px"
                                        :src="scope.row.cover"
                                        :preview-src-list="[scope.row.cover]"
                                        :hide-on-click-modal="true"
                                        :preview-teleported="true"
                                        :fit="'contain'"
                                    >
                                    </el-image>
                                </div>
                                <el-tooltip
                                    class="box-item"
                                    effect="dark"
                                    :content="scope.row.name"
                                    placement="top-start"
                                >
                                    <div class="ml-[10px] text_hidden" :title="scope.row.name">
                                        {{ scope.row.name }}
                                    </div>
                                </el-tooltip>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column property="category_name" label="课程分类" min-width="160" />
                    <el-table-column property="fee_type" label="收费方式" min-width="160">
                        <template #default="{ row }">
                            <span>{{ row.fee_type == 0 ? '免费' : '收费' }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column property="sell_price" label="价格" min-width="120">
                        <template #default="scope"> ¥{{ scope.row.sell_price }} </template>
                    </el-table-column>
                    <el-table-column property="study_num" label="学习人数" min-width="120" />
                    <el-table-column property="order_num" label="课程状态" min-width="120">
                        <template #default="scope">
                            <el-tag class="ml-2" v-if="scope.row.status === 1" type="success"
                                >上架中</el-tag
                            >
                            <el-tag class="ml-2" v-if="scope.row.status === 0" type="info"
                                >已下架</el-tag
                            >
                        </template>
                    </el-table-column>
                    <el-table-column property="sort" label="排序" min-width="120" />
                    <el-table-column fixed="right" property="address" label="操作" min-width="200">
                        <template #default="scope">
                            <div class="flex items-center">
                                <div>
                                    <router-link
                                        :to="{
                                            path: route.path + '/detail',
                                            query: {
                                                id: scope.row.id,
                                                type: pageType
                                            }
                                        }"
                                    >
                                        <el-button
                                            type="primary"
                                            link
                                            v-perms="['course.course/edit']"
                                            >编辑</el-button
                                        >
                                    </router-link>
                                    <router-link
                                        :to="{
                                            path: route.path + '/catalogue',
                                            query: {
                                                id: scope.row.id,
                                                type: pageType
                                            }
                                        }"
                                    >
                                        <el-button
                                            link
                                            type="primary"
                                            v-perms="['course.course/lists']"
                                            >课程目录</el-button
                                        >
                                    </router-link>
                                </div>
                                <el-dropdown>
                                    <el-button link type="primary" class="mt-[2px]">
                                        更多<el-icon>
                                            <ArrowDown />
                                        </el-icon>
                                    </el-button>
                                    <template #dropdown>
                                        <el-dropdown-menu>
                                            <!-- 编辑 -->
                                            <el-dropdown-item>
                                                <el-button
                                                    link
                                                    type="primary"
                                                    class="more_btn"
                                                    @click="openPop(scope.row.id, pageType)"
                                                    v-perms="['course.CourseMaterial/set']"
                                                    >{{
                                                        pageType === 4 ? '课程资料' : '课件资料'
                                                    }}</el-button
                                                >
                                            </el-dropdown-item>
                                            <!-- 上下架 -->
                                            <el-dropdown-item>
                                                <el-button
                                                    type="text"
                                                    link
                                                    class="more_btn"
                                                    @click="
                                                        handleChangeStatus(
                                                            [scope.row.id],
                                                            scope.row.status
                                                        )
                                                    "
                                                    v-perms="['course.Course/status']"
                                                    >{{
                                                        !scope.row.status ? '上架课程' : '下架课程'
                                                    }}
                                                </el-button>
                                            </el-dropdown-item>
                                            <!-- 删除 -->
                                            <el-dropdown-item>
                                                <el-button
                                                    type="text"
                                                    @click="handleDelete([scope.row.id])"
                                                    link
                                                    class="more_btn"
                                                    v-perms="['course.course/del']"
                                                    >删除课程</el-button
                                                >
                                            </el-dropdown-item>
                                        </el-dropdown-menu>
                                    </template>
                                </el-dropdown>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <!-- Main TableData End -->

            <!-- Footer Pagination Start -->
            <div class="flex justify-end mt-4">
                <pagination v-model="pager" @change="getLists" />
            </div>
            <!-- Footer Pagination End -->
        </el-card>
    </div>
    <courseMaterial ref="popRef"></courseMaterial>
    <!-- <layout-footer /> -->
</template>

<script lang="ts" setup name="courseLists">
import {
    apiCourseLists, // 课程列表
    apiCourseDel, // 课程删除
    apiCourseStatus, // 课程状态
    apiCategoryLists
} from '@/api/course/course'
import LayoutFooter from '@/layout/components/footer.vue'
import { ref, computed, onMounted, watch } from 'vue'
import Pagination from '@/components/pagination/index.vue'
import { usePaging } from '@/hooks/usePaging'
import feedback from '@/utils/feedback'
import courseMaterial from './course_material.vue'

/** Interface Start **/
interface FormDataObj {
    type?: number | any // 课程类型 1-图文 2-音频 3-视频
    name: string // 课程名称
    teacher_name?: string // 讲师名称
    category_id: number | string // 分类ID
    status?: number | string // 状态：1-显示；0-隐藏
    fee_type: number | string //收费方式
}
/** Interface End **/

/** Data Start **/
const route = useRoute()
// 表单数据
const formData = ref<FormDataObj>({
    name: '',
    teacher_name: '',
    status: '',
    category_id: 0,
    fee_type: ''
})
const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: apiCourseLists,
    params: formData.value
})
// 分类数据
const categoryData = ref<any>([{ name: '全部', id: 0, sons: [] }])
// 选中的课程
const selectionData = ref<number[]>([])
//弹框ref
const popRef = shallowRef()
/** Data End **/

/** Computed Start **/
const pageType = computed(() => {
    switch (route.path) {
        // 图文
        case '/course/course/imageText':
            return 1
        // 音频
        case '/course/course/audio':
            return 2
        // 视频
        case '/course/course/video':
            return 3
        // 专栏
        case '/course/column':
            return 4
    }
    return null
})
/** Computed End **/

/** Watch Start **/
watch(
    route,
    (val) => {
        formData.value.type = pageType
    },
    { immediate: true }
)
/** Watch End **/

/** Methods Start **/
/**
 * @description 获取通用二级分类
 */
const getCategoryCommonLists = async () => {
    const { lists } = await apiCategoryLists()
    lists.map((item: any) => {
        categoryData.value.push(item)
    })
}

/**
 * @description 表格多选选择的数据
 */
const handleSelectionChange = (val: Event | any) => {
    selectionData.value = val.map((item: Event | any) => item.id)
}

/**
 * @description 课程状态 存放仓库 || 立即上架
 */
const handleChangeStatus = async (id: number[], status: number) => {
    await feedback.confirm(!status ? '确认立即上架？' : '确认存入仓库？')
    // await apiCourseStatus({ id, status: status ? 0 : 1 })
    await apiCourseStatus({ id: id, status: status ? 0 : 1 })
    getLists()
}

/**
 * @description 删除课程 || 专栏
 */
const handleDelete = async (id: number[]) => {
    await feedback.confirm('是否确认删除！')
    try {
        // await apiCourseDel({ id: id })
        await apiCourseDel({ id: id })
        getLists()
        feedback.msgSuccess('删除成功！')
    } catch (err) {
        console.log('删除课程=>', err)
    }
}

//打开弹框
const openPop = (id: any = '', type: any) => {
    popRef.value.openPop(id, type)
}

/** Methods End **/

/** LifeCycle Start **/
onMounted(() => {
    getLists()
    getCategoryCommonLists()
})
/** LifeCycle End **/
</script>

<style lang="scss" scoped>
.more_btn {
    color: #101010;
}

.more_btn:hover {
    // color: $color-primary;
}

// .text_hidden {
//     display: -webkit-box;
//     -webkit-box-orient: vertical;
//     -webkit-line-clamp: 2;
//     overflow: hidden;
// }
</style>
