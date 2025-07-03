<template>
    <view class="bg-white h-[100vh]">
        <view class="px-[30rpx] main">
            <view class="pt-[30rpx]" v-for="(value, key, index) in listData" :key="index">
                <view class="flex justify-between items-center">
                    <view class="title text-xl font-medium">{{ key }}</view>
                    <view class="flex text-info" v-if="index == 0">
                        <view class="flex items-center">
                            <view class="w-[20rpx] h-[20rpx] bg-primary rounded-full"></view>
                            <view class="ml-2">已答</view>
                        </view>
                        <view class="flex items-center ml-2">
                            <view
                                class="w-[20rpx] h-[20rpx] border-1 border-info border-solid rounded-full"
                            ></view>
                            <view class="ml-2">未答</view>
                        </view>
                    </view>
                </view>
                <view class="mt-[30rpx] grid grid-cols-5 gap-4">
                    <view
                        class="w-[100rpx] h-[100rpx] rounded-full flex items-center justify-center"
                        :class="{
                            'text-white bg-primary': item.status != 1,
                            'text-black border-[2rpx] border-[#E3E3E3] border-solid':
                                item.status == 1
                        }"
                        v-for="item in value"
                        :key="item.id"
                    >
                        {{ item.index }}
                    </view>
                </view>
            </view>
        </view>
    </view>
    <view class="px-[30rpx] pt-[20rpx] bottomBtn bg-white shadow">
        <u-button type="primary" shape="circle" @click="toSubmit">交卷并查看结果</u-button>
    </view>
</template>

<script setup lang="ts">
import { apiResport, submitAnswer } from '@/api/question_bank'
import { onLoad } from '@dcloudio/uni-app'
import { ref } from 'vue'

const id: any = ref(0)

const listData: any = ref({})
const notAnswerNum = ref()
const getResport = async () => {
    const { topic_lists, not_num } = await apiResport({ id: id.value })
    listData.value = topic_lists
    notAnswerNum.value = not_num
}

const toSubmit = async () => {
    if (notAnswerNum.value != 0) {
        uni.showModal({
            title: '提示',
            content: '你还有题目未做完，确定交卷吗？',
            success: async function (res) {
                if (res.confirm) {
                    await submitAnswer({ id: id.value })
                    uni.reLaunch({
                        url: `/bundle/pages/answer_report/index?id=${id.value}`
                    })
                } else if (res.cancel) {
                    console.log('用户点击取消')
                }
            }
        })
    } else {
        await submitAnswer({ id: id.value })
        uni.reLaunch({
            url: `/bundle/pages/answer_report/index?id=${id.value}`
        })
    }
}

onLoad(async (option) => {
    id.value = option.id
    await getResport()
})
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
.main {
    padding-bottom: calc(20rpx + env(safe-area-inset-bottom) + 100rpx);
}
</style>
