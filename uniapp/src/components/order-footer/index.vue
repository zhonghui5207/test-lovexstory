<template>
    <view class="flex">
        <!-- 取消订单 -->
        <template v-if="cancel">
            <u-button
                :ripple="true"
                :plain="true"
                @click="handleOrderCancel(orderId)"
                :hair-line="false"
                type="default"
                shape="circle"
                :custom-style="{ height: '70rpx' }"
                >取消订单</u-button
            >
        </template>

        <!-- 去评价 -->
        <template v-if="evaluate">
            <u-button
                :ripple="true"
                :plain="true"
                @click="goPage('/bundle/pages/evaluate_list/index')"
                :hair-line="false"
                type="info"
                shape="circle"
                :custom-style="{ height: '70rpx' }"
                >去评价</u-button
            >
        </template>

        <!-- 去支付 -->
        <template v-if="pay">
            <u-button
                :ripple="true"
                class="ml-[20rpx]"
                @click="handlePayment"
                :hair-line="false"
                type="primary"
                shape="circle"
                :custom-style="{ height: '70rpx' }"
                >去支付</u-button
            >
        </template>

        <template v-if="del">
            <u-button
                :ripple="true"
                class="ml-[20rpx]"
                @click="delOrder(orderId)"
                :hair-line="false"
                type="info"
                shape="circle"
                :custom-style="{ height: '70rpx' }"
            >
                删除订单
            </u-button>
        </template>
    </view>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { apiOrderCancel, apiDelOrder } from '@/api/order'
import { usePay } from '@/core/hooks/payment.ts'
import { toast } from '@/utils/util.ts'

/** Emit Start **/
const emit = defineEmits(['refresh'])
/** Emit End **/

/** Props Start **/
const props = withDefaults(
    defineProps<{
        orderId?: string | number | null // ID
        cancel?: boolean | number | null // 取消订单按钮
        evaluate?: boolean | number | null // 评价按钮
        pay?: boolean | number | null // 支付按钮
        del: boolean
    }>(),
    {
        orderId: '',
        cancel: false,
        evaluate: false,
        pay: false,
        del: false
    }
)
/** Props End **/

/** Methods Start **/
// 页面跳转
const goPage = (url: string) => {
    uni.navigateTo({ url: url })
}

// 取消订单
const handleOrderCancel = async (id: number | string): Promise<void> => {
    const modelRes = await uni.showModal({
        title: '温馨提示',
        content: '确认取消该订单吗？'
    })
    if (modelRes.cancel) return
    await apiOrderCancel({ id: id })
    emit('refresh')
}

// 支付
const handlePayment = () => {
    const params = {
        order_id: props.orderId,
        from: 'order'
    }
    goPage(`/pages/order_buy/index?params=${JSON.stringify(params)}`)
}
/** Methods End **/

//删除订单
const delOrder = (id: number | string) => {
    console.log(id)
    uni.showModal({
        title: '提示',
        content: '是否确认删除订单！',
        success: async (res) => {
            if (res.confirm) {
                await apiDelOrder({ id: id })
                await emit('refresh')
            } else if (res.cancel) {
                console.log('cancel')
            }
        }
    })
}
</script>

<style></style>
