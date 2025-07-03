<template>
    <view class="payment">
        <!-- Header Start -->
        <view class="header">
            <price
                :content="payment.orderAmount"
                mainSize="44rpx"
                minorSize="34rpx"
                fontWeight="500"
                color="#ffffff"
            ></price>
            <view
                class="flex justify-center text-white mt-[20rpx] font-xs"
                v-if="payment.cancelTime > 0 && payment.from != PayEnum.RECHARGE"
            >
                <text class="mr-[10rpx]">请在</text>
                <u-count-down
                    :timestamp="payment.cancelTime"
                    @end="handleCountDownEnd"
                    format="mm:ss"
                    :font-size="22"
                />
                <text class="ml-[10rpx]">内进行支付</text>
            </view>
        </view>
        <!-- Header End -->

        <!-- Main Start -->
        <view class="main bg-white">
            <couponCom
                v-if="payment.from != PayEnum.RECHARGE"
                ref="couponComRef"
                :payment="payment"
                :get-price="getPrice"
            ></couponCom>
            <view class="title">支付方式</view>
            <!-- 支付方式 -->
            <view class="payway">
                <view
                    class="payway-item"
                    v-for="(item, index) in payment.payWayLists"
                    @click="selectPayWay(item.pay_way)"
                    :key="index"
                >
                    <image class="payway-item--icon" :src="item.image"></image>
                    <view class="payway-item--name flex-1">{{ item.name }}</view>
                    <view class="payway-item--select">
                        <image
                            v-if="payWay === item.pay_way"
                            src="/static/images/icon_select.png"
                        ></image>
                    </view>
                </view>
            </view>

            <view class="submit-btn">
                <u-button
                    @click="debouncePlaceOrder"
                    :ripple="true"
                    shape="circle"
                    :hair-line="false"
                    type="primary"
                    >立即支付</u-button
                >
            </view>
        </view>
        <u-popup v-model="bindWX" mode="center" borderRadius="14" :mask-close-able="false">
            <view class="course-pop text-center">
                <view class="text-lg font-medium normal pt-[45rpx] pb-[40rpx]"
                    >抱歉，您还未绑定微信</view
                >
                <view class="flex justify-between mt-[60rpx]">
                    <u-button
                        @click="goPage('/pages/user_set/user_set')"
                        class="flex-1"
                        :ripple="true"
                        shape="circle"
                        :hair-line="false"
                        type="primary"
                        >去绑定</u-button
                    >
                    <u-button
                        @click="bindWX = false"
                        class="flex-1 m-l-30"
                        :ripple="true"
                        shape="circle"
                        :hair-line="false"
                        type="gary"
                        >取消</u-button
                    >
                </view>
            </view>
        </u-popup>
        <!-- Main End -->
        <skeleton v-if="loading"></skeleton>
    </view>
</template>

<script lang="ts" setup>
import { ref, reactive, watch, shallowRef, onMounted, nextTick } from 'vue'
import { onLoad, onUnload } from '@dcloudio/uni-app'
import Price from '@/components/price/index.vue'
import skeleton from './components/skeleton.vue'
import couponCom from './components/couponCom.vue'
import { usePay } from '@/hooks/payment'
import { useUserStore } from '@/stores/user'
import { PayEnum } from '@/utils/enum'

//优惠券组件ref
const couponComRef = shallowRef()

const useAppStore = useUserStore()
const bindWX = ref(false)
// 支付方式
const payWay = ref<number>(0)
// 支付钩子
const { loading, initPayWay, handlePayPrepay, payment, handlePayResult, getPrice } = usePay()

// 监听支付方式，默认为第一位支付
watch(
    () => payment.payWayLists,
    (value: any) => {
        payWay.value = value[0]?.pay_way
    }
)

//选择支付方式
const selectPayWay = (payway: number) => {
    payWay.value = payway
}

const debouncePlaceOrder = () => {
    uni.$u.debounce(handlePlaceOrder, 500)
}

//支付
const handlePlaceOrder = () => {
    if (payWay.value == 1 && useAppStore.$state.userInfo.is_auth == 0) {
        bindWX.value = true
        return
    }
    try {
        // 预支付
        handlePayPrepay(payWay.value)
    } catch (err) {
        console.log('预支付调用', err)
    }
}

//倒计时结束
const handleCountDownEnd = async (e): Promise<void> => {
    if (payment.cancelTime < 0) return
    await uni.showModal({
        title: '温馨提示',
        showCancel: false,
        content: '当前订单支付时间已过，请重新下单',
        success: () => {
            uni.navigateBack()
        }
    })
}

const afterPay = () => {
    uni.showModal({
        title: '提示',
        content: '请确认是否已完成支付？',
        success: (res) => {
            if (res.confirm) {
                handlePayResult(true)
            } else if (res.cancel) {
                handlePayResult(false)
            }
        }
    })
}

// 跳转页面方法
const goPage = (url: string) => {
    uni.navigateTo({
        url: url
    })
}

onLoad(async (options: any) => {
    // 获取课程详情传来的参数
    const { order_id, from } = JSON.parse(options.params)
    // #ifdef H5
    if (options.showPop == 1) {
        afterPay()
    }
    // #endif
    // 初始化支付方式
    await initPayWay(order_id, from)
    console.log(order_id, from)
    couponComRef.value.initCoupon()
})
</script>

<style lang="scss">
.payment {
    background: linear-gradient(to bottom, $color-primary 500rpx, transparent 0);

    .header {
        height: 200rpx;
        padding: 40rpx 0;
        text-align: center;
    }

    .main {
        border-radius: 20rpx 20rpx 0 0;
        height: calc(100vh - 200rpx - env(safe-area-inset-bottom));

        .title {
            color: $color-text-muted;
            font-size: $font-size-sm;
            padding: 30rpx 0 20rpx 30rpx;
        }

        .payway {
            .payway-item {
                padding: 24rpx;
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid $color-bg;

                &--icon {
                    width: 40rpx;
                    height: 40rpx;
                }
                &--name {
                    color: $color-text-deep;
                    font-size: $font-size-lg;
                    margin-left: 24rpx;
                }
                &--select {
                    width: 36rpx;
                    height: 36rpx;
                    image {
                        width: 100%;
                        height: 100%;
                    }
                }
            }
        }

        .submit-btn {
            padding: 0 24rpx;
            margin-top: 60rpx;
        }
    }
}
// 服务下架或不存在时
.empty {
    padding-top: 200rpx;

    .empty-bottom {
        width: 90vw;
        margin-top: 130rpx;
    }
}

// 通用弹窗
.course-pop {
    width: 640rpx;
    padding: 70rpx 80rpx 40rpx 80rpx;
}
</style>
