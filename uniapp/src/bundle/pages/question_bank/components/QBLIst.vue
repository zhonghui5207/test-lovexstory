<template>
    <view class="mt-[21rpx] h-full">
        <z-paging ref="paging" v-model="dataList" @query="queryList" :fixed="false" height="100%">
            <view
                class="flex items-center py-[20rpx]"
                v-for="(item, index) in dataList"
                :key="index"
            >
                <view
                    class="bg-primary rounded-full flex items-center justify-center w-[60rpx] h-[60rpx]"
                >
                    <u-icon size="34" color="#ffffff" name="edit-pen"></u-icon>
                </view>
                <view class="ml-[20rpx] flex-1">
                    <view class="font-medium text-base"> {{ item.name }} </view>
                    <view class="flex mt-[14rpx]">
                        <u-icon size="34" color="#888888" name="edit-pen"></u-icon>
                        <div class="text-[#888888]">
                            {{ item.finish_num }}/{{ item.topic_num }}题
                        </div>
                    </view>
                </view>
                <template v-if="item.btn_status == 1">
                    <view class="flex items-center">
                        <view class="text-primary mr-2" @click="toDoExercise(item.id)"
                            >开始答题</view
                        >
                        <u-icon name="arrow-right" color="#888888"></u-icon>
                    </view>
                </template>
                <template v-if="item.btn_status == 2">
                    <view class="flex items-center" @click="toDoExercise(item.id)">
                        <view class="text-primary mr-2">继续答题</view>
                        <u-icon name="arrow-right" color="#888888"></u-icon>
                    </view>
                </template>
                <template v-if="item.btn_status == 3">
                    <view class="flex items-center" @click="toCheckReport(item.id)">
                        <view class="text-primary mr-2">查看报告</view>
                        <u-icon name="arrow-right" color="#888888"></u-icon>
                    </view>
                </template>
                <template v-if="item.btn_status == 4">
                    <view class="flex items-center" @click="toUnlock(item)">
                        <view class="text-primary mr-2">解锁答题</view>
                        <u-icon name="arrow-right" color="#888888"></u-icon>
                    </view>
                </template>
            </view>
        </z-paging>
    </view>
</template>

<script setup lang="ts">
import { apiQuestionBankList } from '@/api/question_bank'
import { nextTick, onMounted, reactive, ref, watch } from 'vue'

const emits = defineEmits(['toUnlock'])

const props = defineProps({
    category_id: {
        type: Number,
        default: -1
    }
})

const paging: any = ref(null)
const dataList: any = ref([])

const queryList = async (page_no: number, page_size: number) => {
    uni.showLoading({
        title: '加载中...'
    })
    const res = await apiQuestionBankList({ page_no, page_size, category_id: props.category_id })
    paging.value.complete(res.lists)
    uni.hideLoading()
}

//做练习
const toDoExercise = (id: number) => {
    uni.navigateTo({
        url: `/bundle/pages/do_exercises/index?id=${id}`
    })
}

//查看报告
const toCheckReport = (id: number) => {
    uni.navigateTo({
        url: `/bundle/pages/answer_report/index?id=${id}`
    })
}

//解锁
const toUnlock = (data: any) => {
    emits('toUnlock', data)
}

watch(
    () => props.category_id,
    async () => {
        await nextTick()
        paging.value.reload()
    },
    {
        deep: true
    }
)
</script>

<style scoped lang="scss"></style>
