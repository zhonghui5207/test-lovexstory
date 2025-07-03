<template>
    <view class="pt-[20rpx] px-[20rpx] flex flex-col pageContainer">
        <view class="bg-white rounded-lg p-[20rpx]">
            <view class="flex justify-between">
                <view class="font-medium text-[32rpx] title">我的题库</view>
                <view class="text-[#888888] flex items-center" @click="toMyQuestionBank">
                    <text>全部</text>
                    <text class="ml-1 mr-1">{{ myQuestionBank.count }}</text>
                    <u-icon name="arrow-right"></u-icon>
                </view>
            </view>
            <template v-if="myQuestionBank.lists.length">
                <view class="pt-[34rpx] flex">
                    <view class="flex-1">
                        <view class="font-medium text-base">
                            {{ myQuestionBank.lists[0].name }}
                        </view>
                        <view class="flex mt-[14rpx]">
                            <u-icon size="34" color="#888888" name="edit-pen"></u-icon>
                            <div class="text-[#888888]">
                                {{ myQuestionBank.lists[0].finish_num }}/{{
                                    myQuestionBank.lists[0].topic_num
                                }}题
                            </div>
                        </view>
                    </view>
                    <u-button
                        :custom-style="{ 'font-size': '28rpx', padding: '10rpx 24rpx' }"
                        shape="circle"
                        type="primary"
                        v-if="myQuestionBank.lists[0].btn_status == 1"
                        @click="toDoExercise(myQuestionBank.lists[0].id)"
                        >开始答题</u-button
                    >
                    <u-button
                        :custom-style="{ 'font-size': '28rpx', padding: '10rpx 24rpx' }"
                        shape="circle"
                        type="primary"
                        v-if="myQuestionBank.lists[0].btn_status == 2"
                        @click="toDoExercise(myQuestionBank.lists[0].id)"
                        >继续答题</u-button
                    >
                    <u-button
                        :custom-style="{ 'font-size': '28rpx', padding: '10rpx 24rpx' }"
                        shape="circle"
                        type="primary"
                        v-if="myQuestionBank.lists[0].btn_status == 3"
                        @click="toCheckReport(myQuestionBank.lists[0].id)"
                        >查看报告</u-button
                    >
                    <u-button
                        :custom-style="{ 'font-size': '28rpx', padding: '10rpx 24rpx' }"
                        shape="circle"
                        type="primary"
                        v-if="myQuestionBank.lists[0].btn_status == 4"
                        @click="openPop(myQuestionBank.lists[0])"
                        >立即解锁</u-button
                    >
                </view>
            </template>
            <template v-else>
                <div class="py-[50rpx] text-center text-info">暂无任何题库~</div>
            </template>
        </view>
        <view class="bg-white rounded-lg p-[20rpx] mt-[20rpx] flex-1 flex flex-col">
            <view class="flex justify-between">
                <view class="font-medium text-[32rpx] title">题库列表</view>
            </view>
            <view class="flex-1 flex flex-col">
                <view class="flex-none">
                    <Tabs @change="tabsChange">
                        <Tab v-for="(item, index) in tabList" :key="index" :name="item.name"> </Tab>
                    </Tabs>
                </view>
                <view class="flex-1">
                    <list :category_id="tabList[isSelectTabs]?.id" @to-unlock="openPop"></list>
                </view>
            </view>
        </view>
    </view>
    <payPop ref="popRef"></payPop>
    <Tabbar></Tabbar>
</template>

<script setup lang="ts">
import Tabs from '@/components/tabs/tabs.vue'
import Tab from '@/components/tab/tab.vue'
import list from './components/QBLIst.vue'
import payPop from '@/components/QB-pay-pop/payPop.vue'
import { apiGetMyQuestionList, apiGetCategoryLists } from '@/api/question_bank'
import { onMounted, reactive, ref, shallowRef } from 'vue'
import Tabbar from '@/components/tabbar/tabbar.vue'
import { onShow } from '@dcloudio/uni-app'
const tabList: any = ref([])
const isSelectTabs = ref(0)

//弹框ref
const popRef = shallowRef()

const myQuestionBank: any = reactive({ count: 0, lists: [] })

//获取分类列表
const getCategoryList = async () => {
    tabList.value = await apiGetCategoryLists()
}

//导航栏change
const tabsChange = async (index: number) => {
    isSelectTabs.value = index
}

//获取我的题库
const getMyData = async () => {
    const { count, lists } = await apiGetMyQuestionList()
    myQuestionBank.count = count
    myQuestionBank.lists = lists
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

//跳转至我的题库
const toMyQuestionBank = () => {
    uni.navigateTo({
        url: `/bundle/pages/my_question_bank/index`
    })
}

//打开弹框
const openPop = (data: any) => {
    popRef.value.open(data)
}

onMounted(() => {
    getCategoryList()
})

onShow(() => {
    getMyData()
})
</script>

<style scoped lang="scss">
.title {
    position: relative;
    z-index: 11;
    &::before {
        content: '';
        position: absolute;
        background-color: #d8e7ff;
        height: 20rpx;
        width: 100%;
        bottom: 0;
        left: 0;
        z-index: -1;
    }
}

.pageContainer {
    height: calc(100vh - env(safe-area-inset-bottom) - 110rpx);
}
</style>
