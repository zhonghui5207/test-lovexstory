<template>
    <block v-for="commentItem in list" :key="commentItem.id">
        <view class="card">
            <!-- Header Start -->
            <view class="card--header flex justify-between items-start">
                <view class="flex">
                    <u-image
                        :src="commentItem.avatar"
                        width="80"
                        height="80"
                        border-radius="50%"
                    ></u-image>
                    <view class="ml-[20rpx]">
                        <view class="text-md normal font-medium">{{ commentItem.nickname }}</view>
                        <view class="text-muted text-xs mt-[10rpx]">{{
                            commentItem.create_time
                        }}</view>
                    </view>
                </view>
                <u-rate
                    :count="5"
                    v-model="commentItem.course_score"
                    size="28"
                    inactive-icon="star-fill"
                    active-color="#FABB19"
                    disabled
                ></u-rate>
            </view>

            <!-- Main Start -->
            <view class="card--main">
                <view class="content">
                    {{ commentItem.comment }}
                </view>

                <view class="flex flex-wrap">
                    <block v-for="(item3, index3) in commentItem.comment_image" :key="index3">
                        <view
                            class="mt-[10rpx]"
                            :class="{ 'mr-[10rpx]': (index + 1) % 3 != 0 }"
                            @click.stop="previewImage(index3)"
                        >
                            <u-image :src="item3.uri" width="210" height="210"></u-image>
                        </view>
                    </block>
                </view>

                <!-- <view class="reply normal font-md m-t-20" v-if="commentItem.reply">
                    <text class="f-w-500">商家回复: </text>
                    <text>
                        {{ commentItem.reply }}
                    </text>
                </view> -->
            </view>
        </view>
    </block>
</template>

<script lang="ts" setup>
import { ref, reactive, watchEffect } from 'vue'

/** Props Start **/
const props = withDefaults(
    defineProps<{
        list: any
    }>(),
    {
        list: []
    }
)
/** Props End **/

/** Methods Start **/
// 查看评价图片
const previewImage = (current: number) => {
    // uni.previewImage({
    //     current,
    //     urls: props.goods_comment_image.map(el => el.uri)
    // })
}
/** Methods End **/
</script>

<style lang="scss" scoped>
.card:last-child {
    border: 0;
}
.card {
    background-color: $color-white;
    border-bottom: 1px solid $color-border-light;
    padding: 30rpx;
    &--header {
        width: 100%;
    }
    &--main {
        .content {
            padding: 20rpx 0;
            font-size: $font-size-md;
            color: $color-text-deep;
            white-space: pre-line;
        }
        .reply {
            padding: 24rpx 20rpx;
            background-color: $color-bg-light;
            border-radius: $border-radius-large;
        }
    }
}
</style>
