<template>
    <div>
        <el-card shadow="never" class="!border-none">
            <el-form
                class="mb-[-16px]"
                :model="formData"
                label-width="80px"
                inline
                @submit.navtive.prevent
            >
                <el-form-item label="题库名称">
                    <el-input class="w-[270px]" v-model="formData.name" placeholder="分类名称" />
                </el-form-item>
                <el-form-item label="题目分类">
                    <el-select class="w-[270px]" v-model="formData.category_id">
                        <el-option label="全部" value />
                        <el-option
                            v-for="(item, index) in categorizeList"
                            :key="index"
                            :label="item.name"
                            :value="item.id"
                        />
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <div>
                        <el-button type="primary" @click="resetPage">查询</el-button>
                        <el-button @click="resetParams">重置</el-button>
                    </div>
                </el-form-item>
            </el-form>
        </el-card>

        <el-card shadow="never" class="!border-none mt-4">
            <el-tabs v-model="formData.status" class="demo-tabs" @tab-change="getLists">
                <el-tab-pane label="全部" name="" />
                <el-tab-pane label="未开始" name="1" />
                <el-tab-pane label="进行中" name="2" />
                <el-tab-pane label="已下架" name="3" />
            </el-tabs>
            <el-button type="primary" @click="toAdd" v-perms="['questionbank.questionbank/add']"
                >新增题库</el-button
            >
            <div class="mt-4">
                <el-table
                    :data="pager.lists"
                    style="width: 100%; height: 100%"
                    v-loading="pager.loading"
                >
                    <el-table-column property="name" label="题库" min-width="180" />
                    <el-table-column property="category_name" label="题库分类" min-width="180" />
                    <el-table-column property="topic_num" label="题目数量" min-width="180" />
                    <el-table-column property="pay_amount" label="价格" min-width="180" />
                    <el-table-column property="course_num" label="关联课程" min-width="180" />
                    <el-table-column label="题库状态" min-width="180">
                        <template #default="{ row }">
                            <el-tag v-if="row.status == 3" class="ml-2" type="info">已下架</el-tag>
                            <el-tag v-if="row.status == 2" class="ml-2" type="success"
                                >进行中</el-tag
                            >
                            <el-tag v-if="row.status == 1" class="ml-2" type="danger"
                                >未开始</el-tag
                            >
                        </template>
                    </el-table-column>
                    <el-table-column label="操作" fixed="right" min-width="230">
                        <template #default="{ row }">
                            <div class="flex items-center">
                                <div>
                                    <el-button
                                        link
                                        type="primary"
                                        @click="toEdit(row.id)"
                                        v-perms="['questionbank.questionbank/edit']"
                                        >编辑</el-button
                                    >
                                    <el-button
                                        link
                                        type="primary"
                                        @click="toTopicList(row.id)"
                                        v-perms="['questionbank.questionbank_topic/lists']"
                                        >题目</el-button
                                    >
                                    <el-button
                                        link
                                        @click="toDel(row.id)"
                                        v-if="row.status == 3"
                                        type="primary"
                                        v-perms="['questionbank.questionbank/del']"
                                        >删除</el-button
                                    >
                                </div>
                                <el-dropdown
                                    class="ml-1 mt-[1px]"
                                    v-if="row.status != 3"
                                    @command="handleCommand"
                                >
                                    <span class="flex items-center text-primary ml-2">
                                        更多
                                        <el-icon>
                                            <arrow-down />
                                        </el-icon>
                                    </span>
                                    <template #dropdown>
                                        <el-dropdown-menu>
                                            <el-dropdown-item
                                                v-if="row.status == 1"
                                                :command="{ id: row.id, type: 'public' }"
                                                v-perms="['questionbank.questionbank/publish']"
                                                >发布</el-dropdown-item
                                            >
                                            <el-dropdown-item
                                                v-if="row.status == 2"
                                                :command="{ id: row.id, type: 'withdraw' }"
                                                v-perms="['questionbank.questionbank/off']"
                                                >下架</el-dropdown-item
                                            >
                                            <el-dropdown-item
                                                :command="{ id: row.id, type: 'del' }"
                                                v-perms="['questionbank.questionbank/del']"
                                                >删除</el-dropdown-item
                                            >
                                        </el-dropdown-menu>
                                    </template>
                                </el-dropdown>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
                <div class="flex justify-end mr-2 mt-4">
                    <pagination v-model="pager" @change="getLists" />
                </div>
            </div>
        </el-card>
    </div>
</template>

<script setup lang="ts">
import {
    getList,
    publish,
    withdraw,
    deleteQusetionbank,
    noPageCategorizeList
} from '@/api/questionBank/manage'
import { usePaging } from '@/hooks/usePaging'
import router from '@/router'
import feedback from '@/utils/feedback'

const formData = reactive({
    name: '',
    status: '',
    category_id: ''
})

const categorizeList = ref([])

const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: getList,
    params: formData
})

//新增题库
const toAdd = () => {
    router.push('/questionBank/edit')
}

//编辑
const toEdit = (id: number) => {
    router.push(`/questionBank/edit?id=${id}`)
}

const handleCommand = (command: any) => {
    switch (command.type) {
        case 'public':
            toPublic(command.id)
            break
        case 'withdraw':
            toWithDraw(command.id)
            break
        case 'del':
            toDel(command.id)
            break
        default:
            break
    }
}

//发布
const toPublic = async (id: number) => {
    await feedback.confirm('发布后将不能新增/修改题目，是否确认发布？')
    await publish({ id })
    getLists()
}

//下架
const toWithDraw = async (id: number) => {
    await feedback.confirm('下架后用户将无法答题，是否确认下架？')
    await withdraw({ id })
    getLists()
}

//删除
const toDel = async (id: number) => {
    await feedback.confirm('删除后将无法查看题库，是否确认删除？')
    await deleteQusetionbank({ id })
    getLists()
}

//获取分类列表
const getCategorize = async () => {
    categorizeList.value = await noPageCategorizeList()
}

//跳转至题目页面
const toTopicList = async (id: number) => {
    router.push(`/questionBank/topic_list?id=${id}`)
}

onMounted(async () => {
    await getLists()
    await getCategorize()
})
</script>

<style scoped lang="scss"></style>
