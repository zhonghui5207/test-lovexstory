<template>
    <div class="catalogue">
        <el-card shadow="never" class="!border-none">
            <el-page-header content="课程目录" @back="$router.back()" />
        </el-card>

        <el-card shadow="never" class="!border-none mt-4">
            <!-- Header Form Start -->
            <el-form :model="formData" label-width="90px" inline>
                <el-form-item label="目录名称：">
                    <el-input
                        class="ls-input"
                        v-model="formData.name"
                        placeholder="请输入目录名称"
                    />
                </el-form-item>
                <el-form-item label="课程类型">
                    <el-select v-model="formData.type" class="ls-input" placeholder="请选择">
                        <el-option label="全部" value></el-option>
                        <el-option label="图文课程" value="1"></el-option>
                        <el-option label="音频课程" value="2"></el-option>
                        <el-option label="视频课程" value="3"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <div class="ml-[20px]">
                        <el-button type="primary" @click="resetPage">查询</el-button>
                        <el-button @click="resetParams">重置</el-button>
                    </div>
                </el-form-item>
            </el-form>
            <!-- Header Form End -->
        </el-card>

        <el-card shadow="never" class="!border-none mt-4">
            <!-- Main BtnGroup Start -->
            <div class="my-4">
                <course-select :courseId="id" @confirm="handleConfirm">
                    <el-button type="primary">新增专栏目录</el-button>
                    <template> </template>
                </course-select>
            </div>
            <!-- Main BtnGroup Start -->

            <!-- Main TableData Start -->
            <div class="mt-[20px]">
                <el-table
                    ref="tableData"
                    :data="pager.lists"
                    style="width: 100%"
                    v-loading="pager.loading"
                >
                    <el-table-column property="name" label="课程名称" max-width="220" />
                    <el-table-column property="type_desc" label="课程类型" max-width="140" />
                    <el-table-column property="sell_price" label="价格" max-width="140" />
                    <el-table-column property="study_num" label="学习人数" max-width="140" />
                    <el-table-column property="order_num" label="课程状态" max-width="140">
                        <template #default="scope">
                            <el-tag class="ml-2" v-if="scope.row.status === 1" type="success"
                                >上架中</el-tag
                            >
                            <el-tag class="ml-2" v-if="scope.row.status === 0" type="info"
                                >已下架</el-tag
                            >
                        </template>
                    </el-table-column>
                    <el-table-column property="sort" label="排序" max-width="140" />
                    <el-table-column property="address" label="操作" min-width="180">
                        <template #default="scope">
                            <div class="flex">
                                <!-- 修改课程是否免费 -->
                                <popup
                                    class="m-r-10 inline"
                                    @confirm="handleChangeFeeType(scope.row.id, scope.row.fee_type)"
                                >
                                    <template #trigger>
                                        <el-button type="primary" link v-if="scope.row.fee_type">{{
                                            scope.row.fee_type ? '设为免费' : '取消免费'
                                        }}</el-button>
                                    </template>
                                    确定要调整当前课程是否免费吗？
                                </popup>

                                <!-- 移除课程 -->
                                <popup class="m-r-10 inline" @confirm="handleDelete(scope.row.id)">
                                    <template #trigger>
                                        <el-button type="primary" link>移除课程</el-button>
                                    </template>
                                    确定要移除当前课程吗？
                                </popup>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <!-- Main TableData End -->

            <!-- Footer Pagination Start -->
            <div class="flex justify-end mr-4">
                <pagination
                    v-model="pager"
                    @change="getLists"
                    layout="total, prev, pager, next, jumper"
                />
            </div>
            <!-- Footer Pagination End -->
        </el-card>
    </div>
</template>

<script lang="ts" setup>
import {
    apiCourseColumnLists, // 专栏目录列表
    apiCourseColumnDel, // 移除专栏目录
    apiCourseColumnChangeFeeType, // 设置专栏目录是否收费
    apiCourseColumnAdd // 添加专栏目录
} from '@/api/course/course'
import Pagination from '@/components/pagination/index.vue'
import Popup from '@/components/popup/index.vue'
import CourseSelect from '@/components/course-select/course-root.vue'
import { usePaging } from '@/hooks/usePaging'
import feekback from '@/utils/feedback'

/** Interface Start **/
interface FormDataObj {
    course_id: any // 课程目录ID
    name: string // 课程目录名称
    type?: number | string // 是否免费
}
/** Interface End **/

/** Data Start **/
const route = useRoute()
// 课程目录ID
const id: any = route.query.id

// 表单数据
const formData = ref<FormDataObj>({
    course_id: id,
    name: '',
    type: ''
})
const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: apiCourseColumnLists,
    params: formData.value
})
/** Data End **/

/** Methods Start **/
/**
 * @description 添加专栏目录课程
 */
const handleConfirm = async (course_ids: number[]): Promise<void> => {
    console.log(course_ids)
    try {
        await apiCourseColumnAdd({ course_ids: course_ids.map((item: any) => item.id), id })
        resetPage()
    } catch (err) {
        console.log('添加栏目组件报错err=>', err)
    }
}

/**
 * @description 设置专栏收费方式
 */
const handleChangeFeeType = (id: number, fee_type: number) => {
    try {
        apiCourseColumnChangeFeeType({ id, fee_type: fee_type ? 0 : 1 })
        feekback.msgSuccess('设置成功')
        getLists()
    } catch (err) {
        console.log('设置专栏收费方式=>', err)
    }
}

/**
 * @description 移除目录
 */
const handleDelete = async (column_id: number) => {
    try {
        await apiCourseColumnDel({ column_id: id, id: column_id })
        getLists()
    } catch (err) {
        console.log('移除目录报错=>', err)
    }
}
/** Methods End **/

/** LifeCycle Start **/
getLists()
/** LifeCycle End **/
</script>

<style lang="scss">
.catalogue {
    .el-form-item {
        margin-bottom: 0;
    }
}
</style>
