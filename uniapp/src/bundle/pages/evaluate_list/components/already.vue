<template>
    <view v-for="(item, index) in list" :key="index">
        <view class="card" @click="goCourseDetail(item.course_snap.id)">
            <!-- Header Start -->
            <view class="card--header flex justify-between">
                <view class="text-muted font-xs">{{ item.create_time }}</view>
                <view class="flex">
                    <u-rate
                        :count="5"
                        v-model="item.course_score"
                        size="28"
                        inactive-icon="star-fill"
                    ></u-rate>
                    <view class="ml-[20rpx] lighter text-xs">
                        <text v-if="item.course_score == 5">非常好</text>
                        <text v-if="item.course_score == 4">好</text>
                        <text v-if="item.course_score == 3">一般</text>
                        <text v-if="item.course_score == 2">差</text>
                        <text v-if="item.course_score == 1">非常差</text>
                    </view>
                </view>
            </view>

            <!-- Main Start -->
            <view class="card--main">
                <view class="content break-words">
                    {{ item.comment }}
                </view>

                <view class="grid grid-cols-3 gap-1">
                    <block v-for="(item3, index3) in item.comment_image" :key="index3">
                        <view @click.stop="previewImage(item3)">
                            <u-image :src="item3" width="210" height="210"></u-image>
                        </view>
                    </block>
                </view>

                <view class="course flex">
                    <u-image
                        :src="item.course_snap.cover"
                        width="250"
                        height="160"
                        borderRadius="10"
                    ></u-image>
                    <view class="course-info">
                        <view class="course-name text_hidden">{{ item.course_snap.name }}</view>
                        <price
                            :content="item.course_snap.sell_price"
                            mainSize="28rpx"
                            minorSize="22rpx"
                            fontWeight="500"
                        ></price>
                    </view>
                </view>

                <view class="reply normal text-md mt-[20rpx]" v-if="item.reply">
                    <text class="font-normal">商家回复: </text>
                    <text>
                        {{ item.reply }}
                    </text>
                </view>
            </view>
        </view>
    </view>
</template>

<script lang="ts" setup>
import { ref, reactive, watchEffect } from 'vue'
import Price from '@/components/price/index.vue'

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
// 去商品详情
const goCourseDetail = (id: number | string) => {
    uni.navigateTo({
        url: `/pages/course_detail/index?id=${id}`
    })
}

// 查看评价图片
const previewImage = (image: string[]) => {
    uni.previewImage({
        current: 0,
        urls: image
    })
}
/** Methods End **/
</script>

<style lang="scss" scoped>
.card {
    border-radius: 14rpx;
    background-color: $color-white;
    margin: 20rpx 20rpx 0 20rpx;
    padding: 30rpx;
    &--header {
        width: 100%;
    }
    &--main {
        .content {
            padding: 20rpx 0;
            font-size: $font-size-md;
            color: $color-text-deep;
        }
        .course {
            margin-top: 20rpx;
            border-radius: $border-radius-large;
            background-color: $color-bg-light;
            .course-info {
                padding: 20rpx;
                .course-name {
                    width: 414rpx;
                    color: $color-text-deep;
                    font-size: $font-size-md;
                }
            }
        }
        .reply {
            padding: 24rpx 20rpx;
            background-color: $color-bg-light;
            border-radius: $border-radius-large;
        }
    }
}
</style>
