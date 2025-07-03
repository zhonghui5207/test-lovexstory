<template>
    <u-popup v-model="popShow" mode="bottom" height="600" border-radius="14" safe-area-inset-bottom>
        <view class="px-[30rpx] h-full relative">
            <view class="title text-3xl font-medium text-center py-[25rpx]">购买题库</view>
            <view
                class="py-[30rpx] px-[20rpx] flex items-center justify-between bg-[#F0F6FF] rounded-lg mt-[20rpx]"
            >
                <view class="font-medium">{{ popData.name }}</view>
                <view class="text-[#FA8919] font-medium">¥{{ popData.pay_amount }}</view>
            </view>
            <view class="absolute bottom-0 w-[700rpx]">
                <u-button type="primary" shape="circle" @click="creatOrder">立即下单</u-button>
            </view>
        </view>
    </u-popup>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { apiBuyQuestionBank } from '@/api/question_bank'
import { PayEnum } from '@/utils/enum'

const popShow = ref(false)

const popData: any = ref({})

//打开弹框
const open = (data: any) => {
    popShow.value = true
    console.log(data)
    popData.value = data
}

//创建订单
const creatOrder = async () => {
    const { order_id } = await apiBuyQuestionBank({ id: popData.value.id })
    const params = JSON.stringify({ order_id: order_id, from: PayEnum.ORDER })
    uni.navigateTo({
        url: `/pages/order_buy/index?params=${params}`
    })
}

defineExpose({ open })
</script>

<style scoped lang="scss"></style>
