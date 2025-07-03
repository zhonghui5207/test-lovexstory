<template>
    <view class="mx-[20rpx]">
        <!--金额卡片-->
        <view class="bg-white py-[28rpx] px-[20rpx] w-full rounded">
            <view class="text-xs text-info">可提现金额（元）</view>
            <view class="text-primary text-[40rpx] mt-[10rpx]">{{ data?.user.user_earnings }}</view>
            <view class="grid grid-cols-3 gap-1 mt-[30rpx]">
                <view>
                    <view class="text-xs text-info">今日预估收益(元)</view>
                    <view class="text-2xl mt-[10rpx]">{{ data?.today_earnings }}</view>
                </view>
                <view>
                    <view class="text-xs text-info">本月预估收益(元)</view>
                    <view class="text-2xl mt-[10rpx]">{{ data?.month_earnings }}</view>
                </view>
                <view>
                    <view class="text-xs text-info">累计预估收益(元)</view>
                    <view class="text-2xl mt-[10rpx]">{{ data?.total_earnings }}</view>
                </view>
            </view>
            <view class="w-full mt-[60rpx]">
                <button
                    class="w-full rounded-full text-center text-white bg-primary text-xl"
                    @click="goToCashOut"
                >
                    立即提现
                </button>
            </view>
        </view>
        <!--概览卡片-->
        <view
            class="bg-white py-[20rpx] px-[20rpx] w-full rounded mt-[20rpx] grid grid-cols-2 gap-0"
        >
            <view class="flex" @click="toFans">
                <image src="../images/fans.png" class="w-[84rpx] h-[84rpx]"></image>
                <view class="flex flex-col justify-between ml-[16rpx]">
                    <view class="text-base">我的粉丝</view>
                    <view class="text-xs text-info">{{ data?.fans }}人</view>
                </view>
            </view>
            <view class="flex" @click="copyCode(data?.code)">
                <image src="../images/code.png" class="w-[84rpx] h-[84rpx]"></image>
                <view class="flex flex-col justify-between ml-[16rpx]">
                    <view class="text-base">我的邀请码</view>
                    <div>
                        <text class="text-xs text-info">{{ data?.code }}</text>
                        <text class="text-xs text-info underline ml-[5rpx]">复制</text>
                    </div>
                </view>
            </view>
            <view class="flex mt-[20rpx]" @click="toOrder">
                <image src="../images/order.png" class="w-[84rpx] h-[84rpx]"></image>
                <view class="flex flex-col justify-between ml-[16rpx]">
                    <view class="text-base">分销订单</view>
                    <view class="text-xs text-info">{{ data?.distribution_order }}笔</view>
                </view>
            </view>
            <view class="flex mt-[20rpx]" @click="toCommission">
                <image src="../images/earning.png" class="w-[84rpx] h-[84rpx]"></image>
                <view class="flex flex-col justify-between ml-[16rpx]">
                    <view class="text-base">佣金明细</view>
                    <view class="text-xs text-info">佣金明细记录</view>
                </view>
            </view>
        </view>
    </view>
</template>

<script setup lang="ts">
import { copy } from '@/utils/util'
const props = defineProps({
    data: {
        type: Object,
        default: {} as any
    }
})

const copyCode = (code: string) => {
    copy(code)
}

//跳转至提现页面
const goToCashOut = () => {
    uni.$u.route('/bundle/pages/withdraw_deposit/index')
}

//跳转至粉丝页面
const toFans = () => {
    uni.$u.route('/bundle/pages/distribution_fans/index')
}

//跳转至分销订单
const toOrder = () => {
    uni.$u.route('/bundle/pages/distribution_order/index')
}

//跳转至佣金明细
const toCommission = () => {
    uni.$u.route('bundle/pages/balance_detail/index?type=2')
}
</script>

<style scoped lang="scss"></style>
