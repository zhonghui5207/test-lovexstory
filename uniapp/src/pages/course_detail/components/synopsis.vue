<template>
    <view class="p-[24rpx] bg-white">
        <!-- 课件下载 -->
        <view
            class="bg-white mb-[30rpx]"
            @click="$emit('handleMeterialIsOpen')"
            v-if="courseData.meterial"
        >
            <view class="normal text-xl font-medium">教学服务</view>
            <view class="course-materials--info flex row-between rounded-lg mt-[20rpx]">
                <view class="flex">
                    <image src="/static/images/icon_course-materials.png"></image>
                    <view class="course-materials--info--name line-1 ml-[30px]"
                        >素材源文件.zip</view
                    >
                </view>
            </view>
        </view>
        <!-- 讲师信息 -->
        <view class="mb-[30rpx]">
            <view class="normal text-xl font-medium text-[32rpx]">讲师介绍</view>
            <view
                class="teacher-info bg-[#F3F5F9] flex rounded-lg"
                v-if="courseData.teacher"
                @click="goPage(`/bundle/pages/teacher_detail/index?id=${courseData.teacher.id}`)"
            >
                <u-image
                    :src="courseData.teacher.avatar"
                    width="100"
                    height="100"
                    borderRadius="50%"
                ></u-image>
                <view class="ml-[20rpx]">
                    <view class="normal text-xl font-medium mb-[8rpx]">{{
                        courseData.teacher.name
                    }}</view>
                    <view class="teacher-info--synopsis line-2 muted font-sm">
                        {{ courseData.teacher.synopsis }}
                    </view>
                </view>
            </view>
        </view>
        <view class="flex items-center px-[30rpx]">
            <view class="h-[1px] bg-[#e2e2e2] flex-1"></view>
            <view class="flex-none mx-[20rpx] text-[28rpx]">课程详情</view>
            <view class="h-[1px] bg-[#e2e2e2] flex-1"></view>
        </view>
        <mp-html :content="courseData.content" class="content" />
    </view>
</template>

<script setup lang="ts">
import { ref, withDefaults } from 'vue'

const emits = defineEmits(['handleMeterialIsOpen'])

/** Props Start **/
const props = withDefaults(
    defineProps<{
        courseData?: any // 标题
    }>(),
    {
        courseData: ''
    }
)
/** Props End **/

/**
 * @param { string } url
 * @return { void }
 * @description 跳转页面方法
 */
const goPage = (url: string) => {
    uni.navigateTo({ url: url })
}
</script>
<style scoped lang="scss">
// 课件资料
.course-materials {
    &--info {
        padding: 20rpx 20rpx;
        border: 1px solid #e2e2e2;
        image {
            width: 54rpx;
            height: 46rpx;
        }

        &--name {
            width: 500rpx;
            color: $color-text-deep;
            font-size: $font-size-lg;
        }
    }
}
// 讲师信息
.teacher-info {
    padding: 20rpx;
    margin-top: 24rpx;
    &--synopsis {
        width: 550rpx;
        color: #999;
    }
}

.content ::v-deep ._img {
    vertical-align: middle !important;
}
.content ::v-deep image {
    vertical-align: middle !important;
}
</style>
