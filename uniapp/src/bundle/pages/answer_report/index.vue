<template>
    <view class="px-[20rpx]">
        <view
            class="mt-[20rpx] bg-white rounded-lg py-[40rpx] px-[30rpx] flex flex-col items-center"
        >
            <image class="w-[140rpx] h-[140rpx] rounded-full" :src="data?.user_image"></image>
            <view class="text-2xl font-medium mt-[30rpx] truncate w-full text-center">{{
                data?.questionbank_name
            }}</view>
            <view class="mt-[20rpx] text-info">
                <text>做对</text>
                <text class="text-[#20CC92] mx-1">{{ data?.right_num }}</text>
                <text>题，</text>
                <text>做错</text>
                <text class="text-error mx-1">{{ data?.error_num }}</text>
                <text>题，</text>
                <text>未答</text>
                <text class="text-[#666666] mx-1">{{ data?.not_num }}</text>
                <text>题</text>
            </view>
        </view>
        <view class="mt-[20rpx] p-[30rpx] bg-white rounded-lg">
            <view class="flex justify-between">
                <view class="title text-xl font-medium">答题卡</view>
                <view class="text-[#666666]" @click="toAnalysis">
                    <text>全部解析</text>
                    <u-icon name="arrow-right"></u-icon>
                </view>
            </view>
            <view class="mt-[30rpx]">
                <view class="flex justify-center text-info">
                    <view class="flex items-center">
                        <view class="w-[20rpx] h-[20rpx] bg-success rounded-full"></view>
                        <view class="ml-2">正确</view>
                    </view>
                    <view class="flex items-center ml-2">
                        <view class="w-[20rpx] h-[20rpx] bg-error rounded-full"></view>
                        <view class="ml-2">错误</view>
                    </view>
                    <view class="flex items-center ml-2">
                        <view
                            class="w-[20rpx] h-[20rpx] border-1 border-info border-solid rounded-full"
                        ></view>
                        <view class="ml-2">未答</view>
                    </view>
                </view>
                <view
                    class="pt-[30rpx]"
                    v-for="(value, key, index) in data?.topic_lists"
                    :key="index"
                >
                    <view class="text-xl font-medium">{{ key }}</view>
                    <view class="mt-[30rpx] grid grid-cols-5 gap-4">
                        <view
                            class="w-[100rpx] h-[100rpx] rounded-full flex items-center justify-center"
                            :class="{
                                'text-white bg-success': item.status == 2,
                                'text-white bg-error': item.status == 3,
                                'text-black border-[2rpx] border-[#E3E3E3] border-solid':
                                    item.status == 1
                            }"
                            v-for="item in value"
                            :key="item.id"
                            @click="toIndexAnalysis(item.index)"
                        >
                            {{ item.index }}
                        </view>
                    </view>
                </view>
            </view>
        </view>
    </view>
    <view class="pt-[20rpx] bottomBtn bg-white shadow flex items-center">
        <view
            class="pl-[30rpx] pr-[20rpx] flex flex-col justify-center items-center"
            @click="handleBack"
        >
            <u-icon name="home" size="40"></u-icon><view class="mt-[6rpx]">返回题库</view></view
        >
        <view class="flex-1 px-[20rpx]">
            <u-button type="primary" plain shape="circle" @click="toFalseAnalysis"
                >错题解析</u-button
            >
        </view>
        <view class="flex-1 px-[20rpx]">
            <u-button type="primary" shape="circle" @click="againAnswer">重新做题</u-button>
        </view>
    </view>
</template>

<script setup lang="ts">
import { apiResport, apiAnswerAgain } from '@/api/question_bank'
import { onLoad } from '@dcloudio/uni-app'
import { nextTick, ref } from 'vue'

interface IData {
    error_num: number
    not_num: number
    right_num: number
    questionbank_name: string
    user_image: string
    topic_lists: any
}

const id: any = ref(0)

const data: any = ref<IData>()

const getResport = async () => {
    data.value = await apiResport({ id: id.value })
}

//跳转至重新做题
const againAnswer = async () => {
    await apiAnswerAgain({ id: id.value })
    await nextTick()
    uni.redirectTo({
        url: `/bundle/pages/do_exercises/index?id=${id.value}&type=again`
    })
}

//跳转至解析页面
const toAnalysis = async () => {
    uni.navigateTo({
        url: `/bundle/pages/do_exercises/index?id=${id.value}&type=analysis`
    })
}

//跳转至指定解析页面
const toIndexAnalysis = async (index: number) => {
    uni.navigateTo({
        url: `/bundle/pages/do_exercises/index?id=${id.value}&type=analysis&index=${index}`
    })
}

//错题解析
const toFalseAnalysis = async () => {
    if (data.value.error_num == 0) {
        uni.showToast({ title: '无错题！', icon: 'none' })
        return
    }
    uni.navigateTo({
        url: `/bundle/pages/do_exercises/index?id=${id.value}&type=analysisFalse`
    })
}

onLoad(async (option) => {
    id.value = option.id
    await getResport()
})
const handleBack = () => {
    uni.reLaunch({
        url: `/bundle/pages/question_bank/index`
    })
}
</script>

<style scoped lang="scss">
.title {
    position: relative;
    padding-left: 12rpx;
    &::before {
        content: '';
        width: 6rpx;
        height: 100%;
        background-color: $u-type-primary;
        position: absolute;
        top: 0;
        left: 0;
    }
}
.bottomBtn {
    width: 100vw;
    position: absolute;
    bottom: 0;
    left: 0;
    padding-bottom: calc(20rpx + env(safe-area-inset-bottom));
}
.back {
    @apply bg-white flex flex-col items-center justify-center w-[120rpx] h-[120rpx] rounded-full text-[18rpx];
}
</style>
