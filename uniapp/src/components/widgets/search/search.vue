<template>
    <view
        class="search px-[24rpx] py-[14rpx] bg-white relative"
        @click="goPage('/bundle/pages/search/index')"
    >
        <view class="bg-[#f2f2f2] rounded-full px-[20rpx] py-[10rpx] flex items-center">
            <u-icon name="search"></u-icon>
            <view class="text-info ml-2">请输入关键词搜索</view>
        </view>
        <!-- <u-search placeholder="请输入关键词搜索" disabled :show-action="false"></u-search> -->
    </view>
</template>

<script setup lang="ts">
const goPage = (url: string) => {
    uni.navigateTo({
        url: url
    })
}
</script>

<style></style>
