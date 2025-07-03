<template>
    <view
        class="coupon p-[30rpx] bg-white mt-[24rpx] flex items-center rounded-lg"
        @click="couponPop = true"
        v-if="couponList.length > 0"
    >
        <view class="text-base">优惠</view>
        <view
            class="px-[10rpx] py-[3rpx] text-primary rounded-md ml-[40rpx]"
            style="border: 1px solid #2073f4"
            >领券</view
        >

        <view
            class="tick w-[200rpx] px-[24rpx] py-[3rpx] p-[10rpx] text-white bg-primary ml-[20rpx] rounded-sm"
        >
            <view class="truncate">{{ couponList[0]?.content }}</view>
        </view>
        <view
            class="tick w-[200rpx] px-[24rpx] py-[3rpx] p-[10rpx] text-white bg-primary ml-[20rpx] rounded-sm"
            v-if="couponList.length > 1"
        >
            <view class="truncate">{{ couponList[1]?.content }}</view>
        </view>
        <view class="flex-1 flex justify-end">
            <u-icon name="arrow-right" color="##FFFFFF" size="28"></u-icon>
        </view>
    </view>
    <!--优惠券弹框-->
    <u-popup
        v-model="couponPop"
        mode="bottom"
        height="850rpx"
        borderRadius="14"
        safe-area-inset-bottom
        closeable
    >
        <view class="px-[30rpx]">
            <view
                class="title py-[25rpx] h-[100rpx] text-center text-3xl sticky top-0 bg-white"
                style="border-bottom: 1px solid #f6f6f6"
                >优惠</view
            >
            <view v-for="(item, index) in couponList" :key="index" class="py-[15rpx]">
                <coupon-card :coupon-data="item" :type="3"></coupon-card>
            </view>
        </view>
    </u-popup>
    <!--优惠券弹框 End-->
</template>

<script setup lang="ts">
import couponCard from '@/components/coupon-card/index.vue'
import { courseCouponList } from '@/api/coupon'
import { onMounted, reactive, ref } from 'vue'

interface Icoupon {
    content: string
    get_num: boolean
    id: number
    is_get: boolean
    money: string
    name: string
    use_scope: number
    use_scope_desc: string
    use_time_desc: string
}

const props = defineProps({
    courseId: {
        type: Number,
        default: 0
    }
})

//优惠券弹框是/否显示
const couponPop = ref(false)

//优惠券弹框
const couponList: Icoupon[] = reactive([])

//获取优惠券列表
const getCouponList = async () => {
    const res = await courseCouponList({ course_id: props.courseId })
    Object.keys(res).map((item: any) => {
        couponList[item] = res[item]
    })
    console.log(couponList)
}

defineExpose({ getCouponList })
</script>

<style lang="scss" scoped>
.coupon {
    .tick {
        -webkit-mask-image: radial-gradient(circle at 5rpx 50%, transparent 5rpx, red 5.5rpx);
        -webkit-mask-position: -5rpx;
    }
}
</style>
