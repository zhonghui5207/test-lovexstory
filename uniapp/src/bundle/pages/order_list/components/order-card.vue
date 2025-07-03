<template>
    <view class="card" @click.stop="goPage">
        <!-- Header Start -->
        <view class="card--header flex justify-between">
            <view class="order-sn">订单编号：{{ orderInfo.sn }}</view>
            <view class="status">{{ orderInfo.order_status_desc }}</view>
        </view>
        <!-- Header End -->

        <!-- Main Start -->
        <block v-for="item3 in orderInfo.order_course" :key="item3.id">
            <view class="card--main flex">
                <u-image
                    :src="item3.course_snap.cover"
                    width="250"
                    height="160"
                    borderRadius="10"
                ></u-image>
                <view class="ml-[20rpx] order-text">
                    <view class="order-text--name line-2">{{ item3.course_snap.name }}</view>
                    <view class="mt-[19rpx]">
                        <price
                            :content="orderInfo.order_amount"
                            mainSize="36rpx"
                            minorSize="34rpx"
                            fontWeight="500"
                        ></price>
                    </view>
                </view>
            </view>
        </block>
        <!-- Main End -->

        <!-- Footer Start -->
        <view class="card--footer flex justify-between items-center">
            <!-- Left Tips -->
            <view class="primary">
                <template v-if="timeStamp">
                    <view class="flex items-center" v-if="timeStamp >= 0">
                        <u-count-down
                            :timestamp="timeStamp"
                            format="mm:ss"
                            :separator-size="26"
                            @end="countDownEnd"
                            color="#2073F4"
                            class="text-primary font-normal text-sm"
                        />
                        <text class="ml-[10rpx] primaryColor">后自动取消</text>
                    </view>
                </template>
            </view>
            <!-- Right Button -->
            <view>
                <slot></slot>
            </view>
        </view>
        <!-- Footer End -->
    </view>
</template>

<script lang="ts" setup>
import { ref, reactive, watchEffect } from 'vue'
import Price from '@/components/price/index.vue'

/** Props Start **/
const props = withDefaults(
    defineProps<{
        orderInfo?: any
    }>(),
    {
        orderInfo: {}
    }
)
/** Props End **/

const emit = defineEmits(['refresh'])

/** Data Start **/
const timeStamp = ref<number | null>(0)
/** Data End **/
/** Methods Start **/

const getCountDownSegment = watchEffect(() => {
    // 获取倒计时段
    const endTimestamp = props.orderInfo.cancel_time
    const startTimestamp = new Date().getTime() / 1000
    timeStamp.value = (endTimestamp - startTimestamp) * 1000
})

const countDownEnd = () => {
    timeStamp.value = 0
    emit('refresh')
}

const goPage = () => {
    uni.navigateTo({
        url: `/bundle/pages/order_detail/index?id=${props.orderInfo.id}`
    })
}
/** Methods End **/
</script>

<style lang="scss" scoped>
.card {
    border-radius: 14rpx;
    background-color: #ffffff;
    margin: 20rpx 20rpx 0 20rpx;

    &--header {
        padding: 24rpx 30rpx;
        font-size: $font-size-sm;
        border-bottom: 1px solid $color-border-light;
        .order-sn {
            color: $color-text-deep;
        }
        .status {
            color: $color-primary;
        }
    }

    &--main {
        padding: 30rpx;
        color: #555555;
        font-size: $font-size-sm;

        .order-text {
            &--name {
                width: 400rpx;
                font-weight: 500;
                color: $color-text-deep;
                font-size: $font-size-xl;
            }
        }
    }

    &--footer {
        padding: 20rpx 30rpx;
        font-size: $font-size-sm;
        border-top: 1px solid $color-border-light;
    }
}
.primaryColor {
    color: #2073f4;
}
</style>
