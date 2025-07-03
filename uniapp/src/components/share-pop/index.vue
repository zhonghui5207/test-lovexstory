<template>
    <view class="body">
        <u-popup
            class="share-tips"
            v-model="showTips"
            mode="top"
            :custom-style="{ backgroundColor: 'rgba(0, 0, 0, 0)' }"
        >
            <view style="overflow: hidden">
                <image src="../../static/images/share_arrow.png" class="share-arrow" />
                <view style="text-align: center; margin-top: 280rpx; color: white">
                    <view class="font-bold text-lg">立即分享给好友吧</view>
                    <view class="text-sm mt-[10rpx]">点击屏幕右上角将本页面分享给好友</view>
                </view>
            </view>
        </u-popup>
    </view>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
const showTips = ref(false)

const openPop = () => {
    showTips.value = true
}

defineExpose({ openPop })
</script>

<style scoped lang="scss">
::v-deep .share-tips .u-drawer-top {
    background: rgba(0, 0, 0, 0);
}
.share-tips .share-arrow {
    width: 140rpx;
    height: 250rpx;
    float: right;
    margin: 15rpx 31rpx 0 0;
}
</style>
