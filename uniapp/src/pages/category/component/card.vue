<template>
    <view class="mb-[20rpx] rounded-md pb-[20rpx] bg-white" v-show="cateData.length">
        <view class="pt-[26rpx] text-xs">
            <block v-for="cateItem in cateData" :key="cateItem.id">
                <view
                    class="pt-[26rpx] w-[176rpx] inline-block mb-[20rpx]"
                    @click="
                        goPage(`/bundle/pages/search/index?id=${cateItem.id}&name=${cateItem.name}`)
                    "
                >
                    <view class="flex justify-center">
                        <u-image
                            :src="cateItem.image"
                            width="120"
                            height="120"
                            borderRadius="50%"
                        ></u-image>
                    </view>
                    <view class="mt-[20rpx] text-center line-1">{{ cateItem.name }}</view>
                </view>
            </block>
        </view>
    </view>
</template>

<script lang="ts" setup>
withDefaults(
    defineProps<{
        cateData: any
    }>(),
    {
        cateData: {
            id: 0,
            name: '',
            course_num: '',
            image: '',
            pid: '',
            status: 0,
            create_time: ''
        }
    }
)

const goPage = (url: string) => {
    uni.navigateTo({ url: url })
}
</script>

