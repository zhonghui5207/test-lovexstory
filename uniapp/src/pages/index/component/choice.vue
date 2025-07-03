<template>
    <card title="精选课程">
        <!-- <template #header-right>
            <view @click="goPage('/bundle/pages/master_worker_list/index')" class="muted">
                <text class="text-xs mr-[10rpx]">更多</text>
                <u-icon name="arrow-right" size="22"></u-icon>
            </view>
        </template> -->

        <course-list type="larger" :list="content"></course-list>
    </card>
</template>

<script lang="ts" setup>
import Card from './card.vue'
import CourseList from '@/components/course-list/index.vue'

defineProps({
    content: {
        type: Object,
        default: {}
    }
})

const goPage = (url: string) => {
    uni.navigateTo({ url: url })
}
</script>
