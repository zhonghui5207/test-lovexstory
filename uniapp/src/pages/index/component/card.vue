<template>
    <view class="card">
        <!-- 卡片头部 -->
        <view class="card--header flex justify-between">
            <view class="title">{{ title }}</view>
            <slot name="header-right"></slot>
        </view>
        <!-- 内容商品 -->
        <slot></slot>
    </view>
</template>

<script setup lang="ts">
import { ref, withDefaults } from 'vue'

/** Props Start **/
const props = withDefaults(
    defineProps<{
        title?: string // 标题
    }>(),
    {
        title: ''
    }
)
/** Props End **/
</script>

<style lang="scss">
.card {
    margin-top: 20rpx;
    padding-bottom: 40rpx;
    background-color: $color-white;
    border-radius: $border-radius-large;

    &--header {
        height: 102rpx;
        padding: 24rpx 30rpx;
        .title {
            font-weight: 500;
            color: $color-text-deep;
            font-size: 36rpx;
        }
    }
}
</style>
