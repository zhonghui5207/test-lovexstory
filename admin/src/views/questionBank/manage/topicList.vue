<template>
    <div>
        <el-card shadow="never" class="!border-none">
            <el-page-header :content="pager.extend.questionbank_name" @back="$router.back()" />
        </el-card>
        <div>
            <el-card shadow="never" class="!border-none mt-4">
                <!-- Header Form Start -->
                <el-form
                    class="mb-[-16px]"
                    :model="formData"
                    label-width="80px"
                    inline
                    @submit.navtive.prevent
                >
                    <el-form-item label="题目名称">
                        <el-input
                            class="w-[270px]"
                            v-model="formData.stem"
                            placeholder="分类名称"
                        />
                    </el-form-item>
                    <el-form-item label="题目类型">
                        <el-select class="w-[270px]" v-model="formData.type">
                            <el-option label="全部" value></el-option>
                            <el-option label="单选题" value="1"></el-option>
                            <el-option label="多选题" value="2"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <div>
                            <el-button type="primary" @click="resetPage">查询</el-button>
                            <el-button @click="resetParams">重置</el-button>
                        </div>
                    </el-form-item>
                </el-form>
                <!-- Header Form End -->
            </el-card>
            <el-card shadow="never" class="!border-none mt-4">
                <el-button
                    type="primary"
                    @click="toAdd"
                    v-perms="['questionbank.questionbank_topic/add']"
                    >新增题目</el-button
                >
                <div class="mt-4">
                    <el-table
                        :data="pager.lists"
                        style="width: 100%; height: 100%"
                        v-loading="pager.loading"
                    >
                        <el-table-column
                            show-overflow-tooltip
                            property="stem"
                            label="题干"
                            min-width="250"
                        />
                        <el-table-column property="type_desc" label="题型" min-width="180" />
                        <el-table-column
                            property="category_name"
                            label="题库类型"
                            min-width="180"
                        />
                        <el-table-column property="difficulty" label="难度" min-width="180">
                            <template #default="{ row }">
                                {{
                                    row.difficulty == 1
                                        ? '简单'
                                        : row.difficulty == 2
                                        ? '中等'
                                        : '困难'
                                }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            property="create_time"
                            label="最近编辑时间"
                            min-width="180"
                        />
                        <el-table-column fixed="right" label="操作" min-width="180">
                            <template #default="{ row }">
                                <el-button
                                    link
                                    type="primary"
                                    @click="toEdit(row.id)"
                                    v-perms="['questionbank.questionbank_topic/edit']"
                                    >编辑</el-button
                                >
                                <el-button
                                    @click="del(row.id)"
                                    link
                                    type="danger"
                                    v-perms="['questionbank.questionbank_topic/del']"
                                    >删除</el-button
                                >
                            </template>
                        </el-table-column>
                    </el-table>
                    <div class="flex justify-end mr-2 mt-4">
                        <pagination v-model="pager" @change="getLists" />
                    </div>
                </div>
            </el-card>
        </div>
    </div>
</template>

<script setup lang="ts">
import { usePaging } from '@/hooks/usePaging'
import { topicListData, deleteTopic } from '@/api/questionBank/manage'
import feedback from '@/utils/feedback'

const route = useRoute()
const router = useRouter()
const questionBankId = route.query.id

const formData = reactive({
    stem: '', //题目
    type: '',
    questionbank_id: questionBankId
})

const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: topicListData,
    params: formData
})

//新增题目
const toAdd = () => {
    router.push(
        `/questionBank/topic_edit?questionbank_id=${questionBankId}&title=${pager.extend.questionbank_name}`
    )
}

//编辑
const toEdit = (id: number) => {
    router.push(
        `/questionBank/topic_edit?questionbank_id=${questionBankId}&title=${pager.extend.questionbank_name}&id=${id}&status=${pager.extend.questionbank_status}`
    )
}

//删除
const del = async (id: number) => {
    await feedback.confirm('是否确定删除！')
    await deleteTopic({ id })
    getLists()
}

onMounted(() => {
    getLists()
})
</script>
