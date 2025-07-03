<template>
    <div>
        <el-card shadow="never" class="!border-none">
            <el-page-header content="答题情况" @back="$router.back()" />
        </el-card>
        <el-card shadow="never" class="!border-none mt-4">
            <el-form class="mb-[-16px]">
                <el-form-item label="题库名称">{{ detailData?.questionbank_name }}</el-form-item>
                <el-form-item label="答题进度">{{
                    `${detailData?.finish_num}/${detailData?.topic_num}`
                }}</el-form-item>
            </el-form>
        </el-card>
        <el-card
            shadow="never"
            class="!border-none mt-4"
            v-for="(item, index) in detailData?.topic_lists"
            :key="index"
        >
            <div class="flex items-center mb-4">
                <div class="border border-primary px-1 text-primary rounded-md">
                    {{ item.type_desc }}
                </div>
                <div class="ml-2">
                    {{ `${index + 1}、${item.stem}` }}
                </div>
            </div>
            <div class="mt-2" v-for="(item1, index1) in item.option" :key="index1">
                {{ `${indexToString(index1)} \ ${item1}` }}
            </div>
            <div class="mt-4 flex items-center">
                <div>
                    <span>正确答案</span>
                    <span class="ml-4">{{ indexToString(item.correct_answer) }}</span>
                </div>
                <div class="ml-[40px]">
                    <span>用户答案</span>
                    <span class="ml-4">{{ indexToString(item.user_answer) }}</span>
                </div>
                <div class="ml-[40px]">
                    <span v-if="item.is_true == 1" class="text-success">回答正确</span>
                    <span v-if="item.is_true == 0" class="text-danger">回答错误</span>
                </div>
            </div>
        </el-card>
    </div>
</template>

<script setup lang="ts">
import { Detail } from '@/api/questionBank/record'
import { indexToString } from '@/utils/util'
const route = useRoute()

const id = route.query.id

const detailData = ref()

//获取详情
const getDetail = async () => {
    detailData.value = await Detail({ id })
}

onMounted(() => {
    getDetail()
})
</script>
