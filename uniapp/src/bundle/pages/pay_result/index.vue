<template>
    <view class="px-[24rpx]">
        <view class="w-full mt-[20rpx] bg-white rounded-lg">
            <view class="pt-[80rpx] pb-[60rpx] flex flex-col justify-center items-center">
                <image
                    class="w-[80rpx] h-[80rpx]"
                    src="@/bundle/static/images/paySuccess.png"
                    v-if="orderDetail.pay_status == 1"
                ></image>
                <image
                    class="w-[80rpx] h-[80rpx]"
                    src="@/bundle/static/images/payFail.png"
                    v-if="orderDetail.pay_status == 0"
                ></image>
                <view class="text-2xl font-medium">{{
                    orderDetail.pay_status == 0 ? '支付失败' : '支付成功'
                }}</view>
            </view>
            <view class="px-[30rpx] pb-[40rpx]">
                <view class="flex justify-between">
                    <view>订单编号</view>
                    <view>{{ orderDetail.sn || '-' }}</view>
                </view>
                <view class="flex justify-between mt-[24rpx]">
                    <view>下单时间</view>
                    <view>{{ orderDetail.create_time || '-' }}</view>
                </view>
                <view class="flex justify-between mt-[24rpx]">
                    <view>支付方式</view>
                    <view>{{ orderDetail.pay_way_desc || '-' }}</view>
                </view>
                <view class="flex justify-between mt-[24rpx]">
                    <view>支付金额</view>
                    <view>{{ Number(orderDetail.order_amount).toFixed(2) || '-' }}</view>
                </view>
            </view>
        </view>
        <button
            class="w-full bg-primary text-white h-[84rpx] rounded-[84rpx] mt-[60rpx]"
            @click="toOrderList"
            v-if="type != PayEnum.RECHARGE"
        >
            查看订单
        </button>
        <button class="w-full bg-white h-[84rpx] rounded-[84rpx] mt-[20rpx]" @click="toIndex">
            返回首页
        </button>
    </view>
</template>
<script setup lang="ts">
import { onLoad, onShow } from '@dcloudio/uni-app'
import { apiOrderDetail } from '@/api/order'
import { nextTick, ref } from 'vue'
import { PayEnum } from '@/utils/enum'

interface IorderDetail {
    pay_status: number | string
    sn: string
    create_time: string
    pay_way_desc: number | string
    order_amount: number | string
}

let id = '' as number | string
const type = ref<number | string>('')
const orderDetail = ref<IorderDetail>({
    pay_status: 1, //支付状态 0-未支付 1-已支付
    sn: '',
    create_time: '',
    pay_way_desc: '',
    order_amount: ''
})

//获取订单详情
const getOrderDetail = async () => {
    const res = await apiOrderDetail({ id })
    // if (type.value == PayEnum.ORDER) res = await apiOrderDetail({ id })
    // if (type.value == PayEnum.RECHARGE) res = await apiRechargeDetail({ id })
    let key: keyof IorderDetail
    for (key in orderDetail.value) {
        orderDetail.value[key] = res[key]
    }
}
//商品详情页
const toOrderList = () => {
    uni.navigateTo({ url: '/bundle/pages/order_list/index' })
}

//跳转至首页
const toIndex = () => {
    uni.navigateTo({ url: '/pages/index/index' })
}

onShow(async () => {
    uni.showLoading({
        title: '加载中'
    })
    setTimeout(() => {
        uni.hideLoading()
        getOrderDetail()
    }, 1000)
})

onLoad(async (options: any) => {
    console.log(options)
    id = options.orderId
    // type.value = options.type
    await getOrderDetail()
})
</script>

<style lang="scss" scoped></style>
