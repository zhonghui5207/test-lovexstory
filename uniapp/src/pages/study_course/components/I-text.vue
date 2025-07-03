<template>
    <view class="pt-[150rpx] mx-[20rpx]">
        <!--图文课程顶部显示标题-->
        <view class="course-info">
            <!-- 课程名称 -->
            <!-- <view class="text-xl font-medium flex-1"> {{ courseData.name }} </view> -->

            <!-- 介绍 -->
            <view class="text-muted mt-[18rpx] text-sm">
                {{ courseDetailInfo?.create_time }} {{ courseDetailInfo?.teacher?.name }}
            </view>
        </view>

        <!-- 图文 -->
        <view class="bg-white p-[30rpx]">
            <mp-html :content="courseData.content" />
        </view>
    </view>
</template>

<script setup lang="ts">
import { computed, onMounted, onUpdated, ref } from 'vue'

const props = withDefaults(
    defineProps<{
        courseData: any
        cataId: number
        courseId: number
        courseDetailInfo: any
    }>(),
    {
        courseData: { name: 'ces' },
        cataId: 0,
        courseId: 0,
        courseDetailInfo: {}
    }
)

onMounted(() => {
    console.log('Mounted - courseData.name:', props.courseData.name)
})

onUpdated(() => {
    console.log('Updated - courseData.name:', props.courseData.name)
})
</script>

<style scoped lang="scss"></style>
