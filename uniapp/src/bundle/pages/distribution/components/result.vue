<template>
    <view class="bg-white py-[40rpx] px-[35rpx] w-full rounded">
        <view class="flex flex-col items-center">
            <image v-if="data?.status == 0" class="w-[100rpx] h-[100rpx]" :src="wait"></image>
            <image v-if="data?.status == 2" class="w-[100rpx] h-[100rpx]" :src="fail"></image>
            <view v-if="data?.status == 0" class="text-2xl font-medium mt-[16rpx]"
                >已提交，请耐心等待审核</view
            >
            <view v-if="data?.status == 2" class="text-2xl font-medium mt-[16rpx]"
                >审核失败，请重新提交申请</view
            >
            <view class="text-sm text-error text-center mt-[10rpx]" v-if="data?.status == 2"
                >拒绝原因：{{ data?.audit_remark }}
            </view>
        </view>
        <view class="mt-[40rpx]">
            <view class="flex py-[20rpx]">
                <view class="text-[#555555] flex-none">真实姓名</view>
                <view class="ml-[68px]">{{ data?.real_name }}</view>
            </view>
            <view class="flex py-[20rpx]">
                <view class="text-[#555555] flex-none">手机号码</view>
                <view class="ml-[68px]">{{ data?.mobile }}</view>
            </view>
            <view class="flex py-[20rpx]">
                <view class="text-[#555555] flex-none">现住省份</view>
                <view class="ml-[68px]">{{
                    `${data?.province_desc}/${data?.city_desc}/${data?.district_desc}`
                }}</view>
            </view>
            <view class="flex py-[20rpx]">
                <view class="text-[#555555] flex-none">申请说明</view>
                <view class="ml-[68px]">{{ data?.reason }}</view>
            </view>
            <button
                @click="reApply"
                class="bg-primary rounded-full text-white"
                v-if="data?.status == 2"
            >
                重新申请
            </button>
        </view>
    </view>
</template>

<script setup lang="ts">
import wait from '@/bundle/static/distribution/applyWait.png'
import fail from '@/bundle/static/distribution/applyFail.png'
const emit = defineEmits(['reApply'])

const props = defineProps({
    data: {
        type: Object,
        default: {} as any
    }
})

const reApply = () => {
    emit('reApply')
}
</script>

<style scoped lang="scss"></style>
