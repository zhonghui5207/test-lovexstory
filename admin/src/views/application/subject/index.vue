<template>
    <div class="course">
        <el-card shadow="never" class="!border-none">
            <!-- Header Start -->
            <el-alert
                title="温馨提示：1、专区活动能很方便的聚合需要促销的商品，形成各类专题页；2、新增专区活动，可设置专区在首页上架显示；3、活动专区可任意删除，请谨慎操作。"
                type="success"
                :closable="false"
            />
            <!-- Header End -->
            <el-form class="mt-4" label-width="80px" inline>
                <el-form-item label="状态">
                    <el-select v-model="formData.status">
                        <el-option label="全部" value></el-option>
                        <el-option label="开启" :value="1"></el-option>
                        <el-option label="关闭" :value="0"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <div class="ml-[20px]">
                        <el-button
                            type="primary"
                            @click="resetPage"
                            v-perms="['decorate.Subject/lists']"
                            >查询</el-button
                        >
                        <el-button @click="resetParams" v-perms="['decorate.Subject/reset']"
                            >重置</el-button
                        >
                    </div>
                </el-form-item>
            </el-form>
        </el-card>

        <el-card shadow="never" class="mt-4 !border-none">
            <!-- Main BtnGroup Start -->
            <div>
                <el-button
                    type="primary"
                    v-perms="['decorate.Subject/add']"
                    @click="$router.push(`subject/detail`)"
                    >新增专区活动
                </el-button>
            </div>
            <!-- Main BtnGroup Start -->

            <!-- Main TableData Start -->
            <div class="mt-4">
                <el-table
                    ref="tableData"
                    :data="pager.lists"
                    style="width: 100%"
                    v-loading="pager.loading"
                >
                    <el-table-column type="selection" min-width="55" />
                    <el-table-column property="name" label="专区封面" min-width="180">
                        <template #default="scope">
                            <div class="flex col-center">
                                <el-image
                                    style="width: 58px; height: 68px"
                                    :src="scope.row.cover"
                                    :preview-src-list="[scope.row.cover]"
                                    :hide-on-click-modal="true"
                                    :preview-teleported="true"
                                    :fit="'contain'"
                                >
                                </el-image>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column property="name" label="专区名称" min-width="180" />
                    <el-table-column property="course_count" label="课程数量" min-width="140" />
                    <el-table-column property="status" label="专区状态" min-width="140">
                        <template #default="scope">
                            <el-switch
                                v-model="scope.row.status"
                                :active-value="1"
                                :inactive-value="0"
                                @change="handleStatusChange($event, scope.row.id)"
                            />
                        </template>
                    </el-table-column>
                    <el-table-column property="create_time" label="添加时间" min-width="140" />
                    <el-table-column fixed="right" property="address" label="操作" min-width="200">
                        <template #default="scope">
                            <div class="flex items-center">
                                <router-link
                                    :to="{
                                        path: 'subject/detail',
                                        query: {
                                            id: scope.row.id
                                        }
                                    }"
                                >
                                    <el-button
                                        v-perms="['decorate.Subject/edit']"
                                        link
                                        type="primary"
                                        >编辑</el-button
                                    >
                                </router-link>
                                <router-link
                                    :to="{
                                        path: 'subject_course',
                                        query: {
                                            id: scope.row.id
                                        }
                                    }"
                                >
                                    <el-button
                                        v-perms="['decorate.SubjectCourse/detail']"
                                        link
                                        type="primary"
                                        >专区课程</el-button
                                    >
                                </router-link>

                                <el-button
                                    class="mt-[1px]"
                                    @click="handleDelete(scope.row.id)"
                                    link
                                    type="danger"
                                    v-perms="['decorate.Subject/del']"
                                    >删除</el-button
                                >
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <!-- Main TableData End -->

            <!-- Footer Pagination Start -->
            <div class="flex justify-end mr-2">
                <pagination v-model="pager" @change="getLists" />
            </div>
            <!-- Footer Pagination End -->
        </el-card>
    </div>
    <!-- <layout-footer></layout-footer> -->
</template>

<script lang="ts" setup>
import {
    apiSubjectLists, // 专区列表
    apiSubjectDel, // 专区删除
    apiSubjectChangeStatus // 活动状态
} from '@/api/application/application'
import Pagination from '@/components/pagination/index.vue'
import { usePaging } from '@/hooks/usePaging'
import feedback from '@/utils/feedback'
import LayoutFooter from '@/layout/components/footer.vue'

/** Interface Start **/
interface FormDataObj {
    type: number | any // 课程类型 1-图文 2-音频 3-视频
    name: string // 课程名称
    teacher_name?: string // 讲师名称
    category_id: number | string // 分类ID
    status?: number | string // 状态：1-显示；0-隐藏
}
/** Interface End **/

/** Data Start **/
// 表单数据
const formData = ref<FormDataObj>({
    type: 1,
    name: '',
    teacher_name: '',
    status: '',
    category_id: ''
})
const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: apiSubjectLists,
    params: formData.value
})
/** Data End **/

/** Methods Start **/
/**
 * @description 专区状态
 */
const handleStatusChange = async (status: number, id: number) => {
    try {
        await apiSubjectChangeStatus({ id, status: status ? 1 : 0 })
        getLists()
    } catch (err) {
        console.log('专区状态修改=>', err)
    }
}

/**
 * @description 删除专区 || 专栏
 */
const handleDelete = async (id: number) => {
    try {
        await feedback.confirm('是否确认删除！')
        await apiSubjectDel({ id })
        getLists()
    } catch (err) {
        console.log('删除专区=>', err)
    }
}
/** Methods End **/

/** LifeCycle Start **/
onMounted(() => {
    getLists()
})
/** LifeCycle End **/
</script>

<style lang="scss" scoped>
.course {
    .ls-input {
        width: 170px;
    }
}
.el-alert--success.is-light {
    background-color: #edefff;
    color: #4a5dff;
}
</style>
