<template>
    <div>
        <el-card shadow="never" class="!border-none">
            <!-- Header Form Start -->
            <el-form
                class="ls-form"
                :model="formData"
                label-width="80px"
                inline
                @submit.navtive.prevent
            >
                <el-form-item label="分类名称">
                    <el-input class="ls-input" v-model="formData.name" placeholder="分类名称" />
                </el-form-item>
                <el-form-item>
                    <div class="m-l-20">
                        <el-button
                            type="primary"
                            v-perms="['course.courseCategory/lists']"
                            @click="requestPagination"
                            >查询</el-button
                        >
                        <el-button
                            v-perms="['course.courseCategory/reset']"
                            @click="resetPaginationParams"
                            >重置</el-button
                        >
                    </div>
                </el-form-item>
            </el-form>
            <!-- Header Form End -->
        </el-card>

        <el-card shadow="never" class="!border-none mt-4">
            <div>
                <el-button type="primary" v-perms="['course.courseCategory/add']" @click="openPop()"
                    >新增分类</el-button
                >
                <el-button type="plain" @click="toggleRowExpansion">{{
                    !expand.length ? '展开' : '收起'
                }}</el-button>
            </div>

            <!-- Main TableData Start -->
            <div class="mt-4">
                <el-table
                    :data="pager.lists"
                    style="width: 100%; height: 100%"
                    row-key="id"
                    :expand-row-keys="expand"
                    :tree-props="{ children: 'sons' }"
                    v-loading="pager.loading"
                >
                    <el-table-column property="name" label="分类名称" min-width="180" />
                    <el-table-column label="分类图片" min-width="180">
                        <template #default="scope">
                            <el-image
                                style="width: 40px; height: 40px"
                                :src="scope.row.image"
                                :preview-src-list="[scope.row.image]"
                                :hide-on-click-modal="true"
                                :preview-teleported="true"
                                fit="cover"
                            >
                                <template #error>
                                    <div class="h-full flex justify-center items-center iconStyle">
                                        <el-icon :size="30"><Warning /></el-icon>
                                    </div>
                                </template>
                            </el-image>
                        </template>
                    </el-table-column>
                    <el-table-column property="course_num" label="课程数量" min-width="180" />
                    <el-table-column property="address" label="状态" min-width="180">
                        <template #default="scope">
                            <el-switch
                                v-model="scope.row.status"
                                :active-value="1"
                                :inactive-value="0"
                                @change="handleStatusChange($event, scope.row.id)"
                            />
                        </template>
                    </el-table-column>
                    <el-table-column property="sort" label="排序" min-width="180" />
                    <el-table-column fixed="right" label="操作" min-width="180">
                        <template #default="scope">
                            <div class="flex items-center">
                                <el-button
                                    type="primary"
                                    link
                                    @click="openPop(scope.row.id)"
                                    v-perms="['course.courseCategory/edit']"
                                    >编辑</el-button
                                >
                                <el-button
                                    type="danger"
                                    link
                                    class="mb-[2px]"
                                    v-perms="['course.courseCategory/del']"
                                    @click="handleDelete(scope.row.id)"
                                    >删除</el-button
                                >
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <!-- Main TableData End -->
        </el-card>
    </div>
    <edit ref="editRef" @refresh="getLists()"></edit>
    <!-- <LayoutFooter></LayoutFooter> -->
</template>

<script lang="ts" setup>
import { Picture as IconPicture } from '@element-plus/icons-vue'
import { apiCategoryLists, apiCategoryDel, apiCategoryStatusEdit } from '@/api/course/course'
import LayoutFooter from '@/layout/components/footer.vue'
import { onMounted, reactive, ref } from 'vue'
import edit from './edit.vue'
import { usePaging } from '@/hooks/usePaging'
import feedback from '@/utils/feedback'

/** Data Start **/
const formData = reactive({
    name: '' as string
})
const { pager, resetParams, getLists } = usePaging({
    fetchFun: apiCategoryLists,
    params: formData
})
const expand = ref<string[]>([])
const editRef = shallowRef()
/** Data End **/

/** Methods Start **/
/**
 * @description 重置
 */
const resetPaginationParams = async () => {
    resetParams()
    expand.value = []
}
/**
 * @description 请求
 */
const requestPagination = async () => {
    await getLists()
    expand.value = []
}
const toggleRowExpansion = () => {
    if (!expand.value.length) {
        for (let i = 0; i < pager.lists.length; i++) {
            expand.value = [...expand.value, pager.lists[i].id + '']
        }
    } else {
        expand.value = []
    }
}
/**
 * @description 删除分类
 */
const handleDelete = async (id: number): Promise<void> => {
    feedback.confirm('确定删除？').then(async (res) => {
        await apiCategoryDel({ id })
        getLists()
    })
}
/**
 * @description 状态修改
 */
const handleStatusChange = async (event: number, id: number) => {
    await apiCategoryStatusEdit({ status: event, id })
    getLists()
}
/**
 * @description 打开弹框
 */
const openPop = (value: any = '') => {
    editRef.value.open(value)
}
/** Methods End **/

/** LifeCycle Start **/
onMounted(() => {
    requestPagination()
})
/** LifeCycle End **/
</script>

<style lang="scss" scoped>
.iconStyle {
    background-color: var(--el-fill-color);
}
</style>
