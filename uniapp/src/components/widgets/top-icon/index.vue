<template>
    <view class="user bg-white mx-[20rpx] mt-[20rpx] rounded-lg" v-if="content.enabled">
        <view class="order-info card">
            <view class="order-info--main flex row-around">
                <view class="order-menu-item" v-for="(item, index) in content.data" :key="index">
                    <navigator hover-class="none" :url="item.link">
                        <!-- <text class="badge" v-if="userInfo?.wait_pay_num">{{
                            userInfo?.wait_pay_num
                        }}</text> -->
                        <image :src="appStore.getImageUrl(item.image)"></image>
                        <view class="order-menu-item--text"> {{ item.name }} </view>
                        <view class="second-title">{{ item.secName }}</view>
                    </navigator>
                </view>
                <!-- <view class="order-menu-item" @click="goPage('/bundle/pages/order_list/index')">
                    <text class="badge" v-if="userInfo?.appoint_num">{{
                        userInfo?.appoint_num
                    }}</text>
                    <image src="../../static/images/icon_order.png"></image>
                    <view class="order-menu-item--text"> 我的订单 </view>
                    <view class="second-title">查看全部订单</view>
                </view>
                <view class="order-menu-item" @click="goPage('/bundle/pages/user_wallet/index')">
                    <text class="badge" v-if="userInfo?.service_num">{{
                        userInfo?.service_num
                    }}</text>
                    <image src="../../static/images/icon_wallet.png"></image>
                    <view class="order-menu-item--text"> 钱包余额 </view>
                    <view class="second-title">充值有优惠</view>
                </view> -->
            </view>
        </view>
    </view>
</template>

<script lang="ts" setup>
import { useAppStore } from '@/stores/app'

const appStore = useAppStore()
const props = defineProps({
    content: {
        type: Object,
        default: () => ({})
    },
    styles: {
        type: Object,
        default: () => ({})
    }
})
</script>

<style lang="scss" scoped>
.user {
    // padding: 0 24rpx;
    // background: linear-gradient(180deg, #f0f7ff 0%, rgba(255, 255, 255, 0) 130rpx, transparent 0);
    // background-color: white;

    // 订单信息
    .order-info {
        padding: 24rpx 0;
        // padding-bottom: 38rpx;
        &--main {
            .order-menu-item {
                flex: auto;
                text-align: center;
                position: relative;
                image {
                    width: 70rpx;
                    height: 70rpx;
                }
                &--text {
                    color: $color-text-deep;
                    font-size: $font-size-md;
                    font-weight: 500;
                    margin-bottom: 10rpx;
                }
                .second-title {
                    font-family: 'PingFang SC';
                    font-weight: 400;
                    font-size: 24rpx;
                    text-align: center;
                    color: #888;
                }
            }
        }
    }
}
</style>
