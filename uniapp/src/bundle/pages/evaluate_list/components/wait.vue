<template>
    <view class="course-list flex w-full" v-for="(item, index) in list" :key="index">
        <view class="cover mr-2 w-[250rpx] h-[160rpx]">
            <view class="tags text-xs text-white">{{ getCourseType(item.course_snap?.type) }}</view>
            <u-image
                :src="item.course_snap?.cover"
                width="250"
                height="160"
                borderRadius="10"
            ></u-image>
        </view>
        <view class="info flex flex-col justify-between flex-1 w-full">
            <view class="normal font-normal line-2 w-full text_ellipsis text_hidden">{{
                item.course_snap?.name
            }}</view>
            <view class="flex justify-end">
                <u-button
                    @click="goPage(`/bundle/pages/evaluate_submit/index?id=${item?.id}`)"
                    type="primary"
                    size="medium"
                    shape="circle"
                    class="ml-auto"
                    style="margin: 0"
                    >评价</u-button
                >
            </view>
        </view>
    </view>
</template>

<script setup lang="ts">
import { computed } from 'vue'

/** Props Start **/
const props = withDefaults(
    defineProps<{
        list: any // 数据列表
    }>(),
    {
        list: []
    }
)
/** Props End **/

/** Computed Start **/
/**
 * @description 获取课程类型
 */
const getCourseType = computed(() => {
    return (type: number) => {
        switch (type) {
            case 1:
                return '图文'
            case 2:
                return '音频'
            case 3:
                return '视频'
            case 4:
                return '专栏'
        }
    }
})
/** Computed End **/

/** Methods Start **/
/**
 * @param { string } url
 * @return { void }
 * @description 跳转页面方法
 */
const goPage = (url: string) => {
    uni.navigateTo({
        url: url
    })
}
/** Methods End **/
</script>

<style lang="scss">
.course-list {
    padding: 24rpx 40rpx;
    margin: 24rpx 0;
    background-color: #ffffff;

    // 封面
    .cover {
        position: relative;

        // 封面标签
        .tags {
            top: 16rpx;
            left: 20rpx;
            z-index: 10;
            padding: 2rpx 8rpx;
            position: absolute;
            border-radius: 4rpx;
            background: rgba(0, 0, 0, 0.2);
        }
    }

    // .info {
    //     height: 194rpx;
    // }
    .text_ellipsis {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }
}
</style>
