<template>
    <uni-transition
        mode-class="zoom-in"
        needLayout="true"
        :show="orderData.order_course.length"
        :duration="500"
    >
        <view class="order_detail">
            <!-- Main Start -->
            <view class="flex pb-[30rpx] text-white">
                <image
                    v-if="orderData.order_status === 3"
                    class="header-image"
                    src="/static/images/icon_pay.png"
                ></image>
                <image
                    v-if="orderData.order_status === 1"
                    class="header-image"
                    src="/static/images/icon_wait.png"
                ></image>
                <image
                    v-if="orderData.order_status === 2"
                    class="header-image"
                    src="/static/images/icon_success.png"
                ></image>
                <text class="ml-[15rpx]">{{ orderData.order_status_desc }}</text>
            </view>

            <!-- 商品卡片 -->
            <view class="card" @click="goCourseDetail(orderData.order_course[0]?.course_id)">
                <view class="course-item">
                    <u-image
                        :src="orderData.order_course[0]?.course_snap.cover"
                        width="260"
                        height="160"
                        border-radius="10"
                    ></u-image>
                    <view class="course-item--text ml-[20rpx] mt-[4rpx]">
                        <view class="flex justify-between title">
                            <view class="line-2">{{
                                orderData.order_course[0]?.course_snap.name
                            }}</view>
                        </view>
                        <view class="mt-[16rpx]">
                            <price
                                :content="orderData.order_course[0]?.course_snap.sell_price"
                                mainSize="36rpx"
                                minorSize="34rpx"
                                fontWeight="500"
                            ></price>
                        </view>
                    </view>
                </view>
            </view>

            <!-- 服务金额 -->
            <view class="card text-md">
                <view class="flex justify-between">
                    <view>优惠券</view>
                    <view class="primary font-medium">-¥{{ orderData.discount_amount }}</view>
                </view>
                <view class="flex justify-between mt-[30rpx]">
                    <view>实付金额</view>
                    <view class="primary font-medium">¥{{ orderData.order_amount }}</view>
                </view>
            </view>

            <!-- 订单信息 -->
            <view class="card text-md">
                <view class="flex justify-between">
                    <view>订单编号</view>
                    <view>{{ orderData.sn }}</view>
                </view>
                <view class="mt-[30rpx] flex justify-between">
                    <view>支付方式</view>
                    <view>{{ orderData.pay_way_desc || '-' }}</view>
                </view>
                <view v-if="orderData.refund_status > 1" class="mt-[30rpx] flex justify-between">
                    <view>退款状态</view>
                    <view>{{ orderData.refund_status_desc }}</view>
                </view>
                <view class="mt-[30rpx] flex justify-between">
                    <view>下单时间</view>
                    <view>{{ orderData.create_time }}</view>
                </view>
            </view>
            <!-- Main End -->
        </view>
    </uni-transition>
    <!-- Footer Start -->
    <view class="footer flex justify-end">
        <view class="footer--wrap">
            <order-footer
                :orderId="orderId"
                :cancel="orderData.cancel_btn"
                :evaluate="orderData.comment_btn"
                :pay="orderData.pay_btn"
                :del="orderData.del_btn"
                @refresh="initOrderDetail"
            />
        </view>
    </view>
    <!-- Footer End -->
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue'
import { onLoad, onUnload } from '@dcloudio/uni-app'
import { apiOrderDetail } from '@/api/order'
import Price from '@/components/price/index.vue'
import OrderFooter from '@/components/order-footer/index.vue'

/** Data Start **/
const orderData = ref<any>({
    order_course: [],
    order_amount: '',
    total_amount: '',
    total_goods_price: ''
})
const orderId = ref<number | string>('')
/** Data End **/

/** Methods Start **/
// 初始化订单详情
const initOrderDetail = async (): Promise<void> => {
    orderData.value = await apiOrderDetail({ id: orderId.value })
}
// 查看课程
const goCourseDetail = (id: number) => {
    uni.navigateTo({
        url: `/pages/course_detail/index?id=${id}`
    })
}
/** Methods End **/

/** Life Cycle Start **/
onLoad((options) => {
    // 获取订单ID
    orderId.value = parseInt(options?.id)

    // 初始化订单信息
    initOrderDetail()
})
/** Life Cycle End **/
</script>

<style lang="scss">
.order_detail {
    height: 100%;
    padding: 30rpx 24rpx;
    padding-bottom: 140rpx;
    background: linear-gradient(to bottom, $color-primary 200rpx, transparent 0);

    .header-image {
        width: 44rpx;
        height: 44rpx;
    }

    .card {
        padding: 24rpx;
        margin-bottom: 20rpx;
        background-color: $color-white;
        border-radius: $border-radius-large;

        &--header {
            padding-bottom: 20rpx;
        }
        .title {
            font-weight: 500;
            color: $color-text-deep;
            font-size: $font-size-xl;
        }
    }

    .course-item {
        display: flex;
        &--text {
            width: 400rpx;
        }
    }
}
.footer {
    left: 0;
    bottom: 0;
    width: 100%;
    position: fixed;
    padding: 20rpx 30rpx;
    background-color: #ffffff;
    box-shadow: 2rpx 2rpx 22rpx rgba($color: #000000, $alpha: 0.2);

    &--wrap {
        padding-bottom: env(safe-area-inset-bottom);
    }
}
</style>
