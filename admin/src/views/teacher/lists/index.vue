<template>
    <div class="teacher">
        <el-card shadow="never" class="!border-none">
            <!-- Header Form Start -->
            <el-form class="ls-form" :model="formData" label-width="80px" inline>
                <el-form-item label="讲师名称">
                    <el-input class="ls-input" v-model="formData.name" placeholder="讲师名称" />
                </el-form-item>
                <el-form-item label="状态">
                    <el-select v-model="formData.status" class="ls-input" placeholder="请选择">
                        <el-option label="全部" value></el-option>
                        <el-option label="停用" value="0"></el-option>
                        <el-option label="正常" value="1"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <div class="ml-[20px]">
                        <el-button
                            type="primary"
                            @click="getLists"
                            v-perms="['teacher.teacher/lists']"
                            >查询</el-button
                        >
                        <el-button @click="resetParams" v-perms="['teacher.teacher/reset']"
                            >重置</el-button
                        >
                    </div>
                </el-form-item>
            </el-form>
            <!-- Header Form End -->
        </el-card>

        <el-card shadow="never" class="!border-none mt-4">
            <div>
                <el-button
                    type="primary"
                    @click="$router.push('/teacher/detail')"
                    v-perms="['teacher.teacher/add']"
                    >添加讲师</el-button
                >
            </div>

            <!-- Main TableData Start -->
            <div class="mt-4">
                <el-table :data="pager.lists" style="width: 100%" v-loading="pager.loading">
                    <el-table-column property="number" label="讲师编号" min-width="180" />
                    <el-table-column label="讲师头像" min-width="180">
                        <template #default="scope">
                            <el-image
                                style="width: 40px; height: 40px"
                                :src="scope.row.avatar"
                                :preview-src-list="[scope.row.avatar]"
                                :hide-on-click-modal="true"
                                :preview-teleported="true"
                                fit="cover"
                            ></el-image>
                        </template>
                    </el-table-column>
                    <el-table-column property="name" label="姓名" min-width="180" />
                    <!-- <el-table-column property="gender" label="性别" min-width="100" /> -->
                    <el-table-column property="course_num" label="课程数量" min-width="100" />
                    <el-table-column property="study_num" label="学习人数" min-width="100" />
                    <el-table-column property="status" label="状态" min-width="100">
                        <template #default="scope">
                            <el-switch
                                v-model="scope.row.status"
                                :active-value="1"
                                :inactive-value="0"
                                @change="handleStatusChange($event, scope.row.id)"
                            />
                        </template>
                    </el-table-column>
                    <el-table-column property="sort" label="排序" min-width="100" />
                    <el-table-column property="create_time" label="添加时间" min-width="180" />
                    <el-table-column fixed="right" label="操作" min-width="130">
                        <template #default="scope">
                            <div class="flex">
                                <router-link
                                    :to="{
                                        path: '/teacher/detail',
                                        query: {
                                            id: scope.row.id
                                        }
                                    }"
                                >
                                    <el-button
                                        link
                                        v-perms="['teacher.teacher/edit']"
                                        type="primary"
                                        >编辑</el-button
                                    >
                                </router-link>
                                <el-button
                                    link
                                    type="danger"
                                    v-perms="['teacher.teacher/del']"
                                    @click="handleDelete(scope.row.id)"
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
        <!-- <layout-footer></layout-footer> -->
    </div>
</template>

<script lang="ts" setup>
import { apiTeacherLists, apiTeacherDel, apiTeacherStatus } from '@/api/teacher/teacher'
import { onMounted, reactive, ref } from 'vue'
import Pagination from '@/components/pagination/index.vue'
import { usePaging } from '@/hooks/usePaging'
import feedback from '@/utils/feedback'
import LayoutFooter from '@/layout/components/footer.vue'

/** Data Start **/
const formData = reactive({
    name: '' as string,
    status: ''
})
const { pager, resetParams, getLists } = usePaging({
    fetchFun: apiTeacherLists,
    params: formData
})
/** Data End **/

/** Methods Start **/
/**
 * @description 删除讲师
 */
const handleDelete = async (id: number) => {
    await feedback.confirm('确定是否删除！')
    await apiTeacherDel({ id })
    getLists()
}

/**
 * @description 状态修改
 */
const handleStatusChange = async (event: number, id: number) => {
    await apiTeacherStatus({ status: event, id })
    getLists()
}
/** Methods End **/

/** LifeCycle Start **/
onMounted(() => {
    getLists()
})
/** LifeCycle End **/
</script>

<style lang="scss"></style>
