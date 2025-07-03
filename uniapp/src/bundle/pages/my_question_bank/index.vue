<template>
    <view class="pageContainer flex flex-col">
        <view>
            <tabs :isScroll="false" @change="tabChange">
                <tab v-for="(item, index) in tabList" :name="item.name" :key="index"></tab>
            </tabs>
        </view>
        <view class="flex-1">
            <z-paging
                ref="paging"
                height="100%"
                v-model="dataList"
                @query="queryList"
                :fixed="false"
            >
                <view class="px-[20rpx]">
                    <view
                        class="mt-[20rpx] bg-white py-[30rpx] px-[20rpx] rounded-lg flex items-center justify-between"
                        v-for="item in dataList"
                        :key="item"
                    >
                        <view class="flex-1">
                            <view class="text-base font-medium">{{ item.name }}</view>
                            <view class="mt-[14rpx] text-info">
                                <u-icon name="edit-pen"></u-icon>
                                <text>{{ item.finish_num }}/{{ item.topic_num }}题</text>
                            </view>
                        </view>
                        <view
                            class="text-primary flex-none"
                            v-if="item.btn_status == 1"
                            @click="toDoExercise(item.questionbank_id)"
                        >
                            <text class="mr-2">开始答题</text>
                            <u-icon name="arrow-right"></u-icon>
                        </view>
                        <view
                            class="text-primary"
                            v-if="item.btn_status == 2"
                            @click="toDoExercise(item.questionbank_id)"
                        >
                            <text class="mr-2">继续答题</text>
                            <u-icon name="arrow-right"></u-icon>
                        </view>
                        <view
                            class="text-primary"
                            v-if="item.btn_status == 3"
                            @click="toCheckReport(item.questionbank_id)"
                        >
                            <text class="mr-2">查看报告</text>
                            <u-icon name="arrow-right"></u-icon>
                        </view>
                        <view
                            class="text-primary"
                            v-if="item.btn_status == 4"
                            @click="openPop(item)"
                        >
                            <text class="mr-2">立即解锁</text>
                            <u-icon name="arrow-right"></u-icon>
                        </view>
                    </view>
                </view>
            </z-paging>
        </view>
        <payPop ref="popRef"></payPop>
    </view>
</template>

<script setup lang="ts">
import tabs from '@/components/tabs/tabs.vue'
import tab from '@/components/tab/tab.vue'
import { apiGetMyQuestionList } from '@/api/question_bank'
import payPop from '@/components/QB-pay-pop/payPop.vue'
import { ref, shallowRef } from 'vue'

//弹框ref
const popRef = shallowRef()

const paging: any = ref(null)
const dataList: any = ref([])

const currentIndex = ref('')

const tabList = ref([
    { name: '全部', type: '' },
    { name: '未开始', type: '1' },
    { name: '进行中', type: '2' },
    { name: '已完成', type: '3' }
])

const tabChange = (index: any) => {
    currentIndex.value = index
    paging.value?.reload()
}

const queryList = async (page_no: number, page_size: number) => {
    const { lists } = await apiGetMyQuestionList({ page_no, page_size, status: currentIndex.value })
    paging.value?.complete(lists)
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

//打开弹框
const openPop = (data: any) => {
    popRef.value.open(data)
}
</script>

<style scoped lang="scss">
.pageContainer {
    height: calc(100vh - env(safe-area-inset-bottom));
}
</style>
