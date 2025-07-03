<template>
    <card :title="content.title">
        <template #header-right>
            <view
                @click="goPage(`/bundle/pages/subject_list/index?title=${content.title}`)"
                class=""
            >
                <text class="text-xs mr-[10px]">更多</text>
                <u-icon name="arrow-right" size="22"></u-icon>
            </view>
        </template>
        <scroll-view class="whitespace-nowrap" scroll-x="true">
            <view class="ml-[30rpx] flex flex-nowrap">
                <block v-for="subjectItem in content.data" :key="subjectItem.id">
                    <view class="mr-[20rpx] w-[266rpx]">
                        <view
                            class="h-[480rpx] rounded-md overflow-hidden"
                            @click="
                                goPage(`/bundle/pages/subject_detail/index?id=${subjectItem.id}`)
                            "
                        >
                            <view class="flex row-center">
                                <u-image
                                    :src="subjectItem.cover"
                                    width="268"
                                    height="360"
                                ></u-image>
                            </view>
                            <view class="h-[120rpx] text-center">
                                <view class="truncate text-lg font-medium pt-[10rpx]">{{
                                    subjectItem.name
                                }}</view>
                                <view class="text-[#666666] pt-[10rpx]">
                                    {{ subjectItem.course_num || 0 }}人在学习
                                </view>
                            </view>
                        </view>
                    </view>
                </block>
            </view>
        </scroll-view>
    </card>
</template>

<script lang="ts" setup>
import Card from './card.vue'
import { navigateTo, menuJump } from '@/utils/util'

defineProps({
    content: {
        type: Object,
        default: {}
    }
})

const goPage = (url: string) => {
    uni.navigateTo({
        url: url
    })
}
</script>

<style></style>
