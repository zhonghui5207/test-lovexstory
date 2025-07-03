<template>
    <div class="catalogue">
        <el-card shadow="never" class="!border-none">
            <el-page-header content="课程目录" @back="$router.back()" />
        </el-card>

        <el-card shadow="never" class="!border-none mt-4">
            <!-- Header Form Start -->
            <el-form :model="formData" label-width="90px" inline>
                <el-form-item label="目录名称">
                    <el-input
                        class="ls-input"
                        v-model="formData.name"
                        placeholder="请输入目录名称"
                    />
                </el-form-item>
                <el-form-item label="是否免费">
                    <el-select v-model="formData.fee_type" class="ls-input" placeholder="请选择">
                        <el-option label="全部" value></el-option>
                        <el-option label="免费" :value="0"></el-option>
                        <el-option label="收费" :value="1"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="状态">
                    <el-select v-model="formData.status" class="ls-input" placeholder="placeholder">
                        <el-option label="全部" value />
                        <el-option label="显示" :value="1" />
                        <el-option label="隐藏" :value="0" />
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
            <!-- Main BtnGroup Start -->
            <div class="mt-4 mb-4">
                <el-button
                    type="primary"
                    v-if="pageType == 1"
                    @click="
                        $router.push({
                            path: route.path + '/detail',
                            query: {
                                type: pageType,
                                course_id: id
                            }
                        })
                    "
                    >新增图文目录
                </el-button>
                <el-button
                    type="primary"
                    v-if="pageType == 2"
                    @click="
                        $router.push({
                            path: route.path + '/detail',
                            query: {
                                type: pageType,
                                course_id: id
                            }
                        })
                    "
                    >新增音频目录
                </el-button>
                <el-button
                    type="primary"
                    v-if="pageType == 3"
                    @click="
                        $router.push({
                            path: route.path + '/detail',
                            query: {
                                type: pageType,
                                course_id: id
                            }
                        })
                    "
                    >新增视频目录
                </el-button>
                <el-button
                    type="primary"
                    v-if="pageType == 4"
                    @click="
                        $router.push({
                            path: route.path + '/detail',
                            query: {
                                type: pageType,
                                course_id: id
                            }
                        })
                    "
                    >新增专栏目录
                </el-button>
            </div>
            <!-- Main BtnGroup Start -->

            <!-- Main TableData Start -->
            <div class="m-t-20">
                <el-table
                    ref="tableData"
                    :data="pager.lists"
                    style="width: 100%"
                    v-loading="pager.loading"
                >
                    <el-table-column property="id" label="ID" width="80" />
                    <el-table-column property="name" label="目录名称" min-width="320">
                        <template #default="{ row }">
                            <el-tooltip
                                class="box-item"
                                effect="dark"
                                :content="row.name"
                                placement="top-start"
                            >
                                <div class="ml-[10px] text_hidden" :title="row.name">
                                    {{ row.name }}
                                </div>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                    <el-table-column
                        property="fee_type"
                        :label="pageType == 2 ? '免费试听' : '免费试看'"
                        min-width="180"
                    >
                        <template #default="scope">
                            <el-tag type="success" v-if="scope.row.fee_type == 0">免费</el-tag>
                            <el-tag type="warning" v-if="scope.row.fee_type == 1">收费</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column property="status" label="状态" min-width="180">
                        <template #default="scope">
                            <el-switch
                                v-model="scope.row.status"
                                :active-value="1"
                                :inactive-value="0"
                                @change="handleStatusChange($event, scope.row.id)"
                            />
                        </template>
                    </el-table-column>
                    <el-table-column property="sort" label="排序" min-width="140" />
                    <el-table-column property="create_time" label="添加时间" min-width="180" />
                    <el-table-column property="address" fixed="right" label="操作" min-width="180">
                        <template #default="scope">
                            <div class="flex">
                                <!-- 编辑 -->
                                <router-link
                                    class="m-r-10"
                                    :to="{
                                        path: route.path + '/detail',
                                        query: {
                                            type: pageType,
                                            id: scope.row.id,
                                            course_id: id
                                        }
                                    }"
                                >
                                    <el-button link type="primary">编辑目录</el-button>
                                </router-link>
                                <!-- 删除 -->
                                <el-button link type="primary" @click="handleDelete(scope.row.id)"
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
    apiCourseCatalogueLists,
    apiCourseCatalogueDel,
    apiCourseCatalogueChangeFeeType
} from '@/api/course/course'
import { reactive } from 'vue'
import Pagination from '@/components/pagination/index.vue'
import { usePaging } from '@/hooks/usePaging'
import feedback from '@/utils/feedback'
// import { useAdmin } from '@/core/hooks/app'

/** Interface Start **/
interface FormDataObj {
    course_id: any // 课程目录ID
    type: any // 课程类型1
    name: string // 课程目录名称
    fee_type?: number | string // 是否免费
    status: any //课程状态
}
/** Interface End **/

/** Data Start **/
const route = useRoute()
// 课程目录ID
const id: any = route.query.id
// 课程类型1-图文 2-音频 3-视频 4-专栏
const pageType: any = route.query.type

// 表单数据
const formData = reactive<FormDataObj>({
    course_id: id,
    type: pageType,
    name: '',
    fee_type: '',
    status: ''
})
const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: apiCourseCatalogueLists,
    params: formData
})
/** Data End **/

/** Methods Start **/
/**
 * @description 修改目录收费方式
 */
const handleStatusChange = async (status: number, id: number): Promise<void> => {
    try {
        await apiCourseCatalogueChangeFeeType({ id, status: status ? 1 : 0 })
        getLists()
    } catch (err) {
        console.log('设置专栏收费方式=>', err)
    }
}

/**
 * @description 删除目录
 */
const handleDelete = async (id: number) => {
    await feedback.confirm('确认删除？')
    await apiCourseCatalogueDel({ id })
    getLists()
}
/** Methods End **/

/** LifeCycle Start **/
getLists()
/** LifeCycle End **/
</script>

<style lang="scss"></style>
