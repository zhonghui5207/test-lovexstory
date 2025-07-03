<template>
    <div>
        <el-card shadow="never" class="!border-none">
            <!-- Header Form Start -->
            <el-form
                class="mb-[-16px]"
                :model="formData"
                label-width="80px"
                inline
                @submit.navtive.prevent
            >
                <el-form-item label="用户信息">
                    <el-input
                        class="w-[270px]"
                        v-model="formData.user_info"
                        placeholder="请输入用户昵称/账号/手机号"
                    />
                </el-form-item>
                <el-form-item label="题库名称">
                    <el-input
                        class="w-[270px]"
                        v-model="formData.questionbank_name"
                        placeholder="请输入题库名称"
                    />
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
                    <div class="m-l-20">
                        <el-button
                            type="primary"
                            v-perms="['course.courseCategory/lists']"
                            @click="resetPage"
                            >查询</el-button
                        >
                        <el-button v-perms="['course.courseCategory/reset']" @click="resetParams"
                            >重置</el-button
                        >
                    </div>
                </el-form-item>
            </el-form>
            <!-- Header Form End -->
        </el-card>
        <el-card shadow="never" class="!border-none mt-4">
            <div class="mt-4">
                <el-table
                    :data="pager.lists"
                    style="width: 100%; height: 100%"
                    v-loading="pager.loading"
                >
                    <el-table-column property="nickname" label="用户信息" min-width="180" />
                    <el-table-column property="questionbank_name" label="题库" min-width="180" />
                    <el-table-column label="题目进度" min-width="180">
                        <template #default="{ row }">
                            <div>{{ row.finish_num + '/' + row.topic_num }}</div>
                        </template>
                    </el-table-column>
                    <el-table-column property="status_desc" label="答题状态" min-width="180" />
                    <el-table-column property="last_time" label="最后记录时间" min-width="180" />
                    <el-table-column label="操作" fixed="right" min-width="180">
                        <template #default="{ row }">
                            <el-button @click="toDetail(row.id)" link type="primary"
                                >查看答题详情</el-button
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
</template>

<script setup lang="ts">
import { usePaging } from '@/hooks/usePaging'
import { noPageCategorizeList } from '@/api/questionBank/manage'
import { getList } from '@/api/questionBank/record'

const router = useRouter()

const formData = reactive({
    user_info: '' as string,
    questionbank_name: '',
    category_id: ''
})

const categorizeList = ref([])

const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: getList,
    params: formData
})

//获取分类列表
const getCategorize = async () => {
    categorizeList.value = await noPageCategorizeList()
}

//跳转至详情
const toDetail = async (id: number) => {
    router.push(`record/detail?id=${id}`)
}

onMounted(() => {
    getLists()
    getCategorize()
})
</script>
