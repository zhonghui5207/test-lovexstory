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
                <el-form-item label="分类名称">
                    <el-input
                        class="w-[270px]"
                        v-model="formData.name"
                        placeholder="请输入分类名称"
                    />
                </el-form-item>
                <el-form-item>
                    <div class="m-l-20">
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
                @click="openPop()"
                v-perms="['questionbank.questionbank_category/add']"
                >新增分类</el-button
            >
            <div class="mt-4">
                <el-table
                    :data="pager.lists"
                    style="width: 100%; height: 100%"
                    v-loading="pager.loading"
                >
                    <el-table-column property="name" label="分类名称" min-width="180" />
                    <el-table-column property="questionbank_num" label="题库数量" min-width="180" />
                    <el-table-column property="sort" label="排序" min-width="180" />
                    <el-table-column property="name" label="操作" min-width="180">
                        <template #default="{ row }">
                            <el-button
                                link
                                type="primary"
                                v-perms="['questionbank.questionbank_category/edit']"
                                @click="openPop(row.id)"
                                >编辑</el-button
                            >
                            <el-button
                                link
                                type="danger"
                                v-perms="['questionbank.questionbank_category/del']"
                                @click="del(row.id)"
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
        <edit-pop
            v-if="popShow"
            ref="popRef"
            @success="
                () => {
                    popShow = false
                    getLists()
                }
            "
            @close="popShow = false"
        ></edit-pop>
    </div>
</template>

<script setup lang="ts">
import { usePaging } from '@/hooks/usePaging'
import editPop from './editPop.vue'
import { getList, delCategorize } from '@/api/questionBank/categorize'
import feedback from '@/utils/feedback'

const popRef = shallowRef()
const popShow = ref(false)

const formData = reactive({
    name: '' as string
})

const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: getList,
    params: formData
})

//打开弹框
const openPop = async (id?: any) => {
    popShow.value = true
    await nextTick()
    popRef.value.open({ id })
}

const del = async (id: any) => {
    await feedback.confirm('请确定是否删除！')
    await delCategorize({ id })
    getLists()
}

onMounted(() => {
    getLists()
})
</script>

<style scoped lang="scss"></style>
