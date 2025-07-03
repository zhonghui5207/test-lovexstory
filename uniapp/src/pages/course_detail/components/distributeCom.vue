<template>
    <view
        v-if="distributeData.is_show"
        class="coupon p-[30rpx] bg-white mt-[24rpx] flex items-center rounded-lg flex"
    >
        <view>
            <span class="text-base">佣金</span>
            <span class="ml-2 text-error">{{ distributeData.ratio }}%</span>
            <span>（预计{{ distributeData.earnings }}）</span>
        </view>
        <!--#ifndef H5-->
        <view class="flex-1 flex justify-end">
            <button id="btn" class="!border-none" open-type="share">
                <view class="items-center">
                    <span class="mr-1">立即分享</span>
                    <u-icon name="arrow-right" color="##FFFFFF" size="28"></u-icon>
                </view>
            </button>
        </view>
        <!--#endif-->
        <!--#ifdef H5-->
        <view class="flex-1 flex justify-end" @click="showSharePop">
            <view class="items-center">
                <span class="mr-1">立即分享</span>
                <u-icon name="arrow-right" color="##FFFFFF" size="28"></u-icon>
            </view>
        </view>
        <!--#endif-->
    </view>
    <!--H5分享遮盖层 Start-->
    <sharePop ref="sharePopRef"></sharePop>
</template>

<script setup lang="ts">
import { shallowRef } from 'vue'
import sharePop from '@/components/share-pop/index.vue'

//H5分享弹框遮盖层ref
const sharePopRef = shallowRef()

const props = defineProps({
    distributeData: {
        type: Object,
        default: {}
    }
})

//分享
const showSharePop = async () => {
    sharePopRef.value.openPop()
}
</script>

<style lang="scss" scoped>
button {
    padding: 0;
    margin: 0;
    font-size: inherit;
    background-color: transparent;
    color: inherit;
    border: none;
}
button::after {
    border: none;
    padding: 0;
    margin: 0;
}
</style>
