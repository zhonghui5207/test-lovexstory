<template>
    <view class="page w-[690rpx] rounded-lg overflow-hidden flex" :class="{ gray: disabled }">
        <view class="left w-[220rpx]">
            <view class="flex flex-col items-center justify-center h-full">
                <price
                    :content="couponData.money"
                    color="#fff"
                    :prec="3"
                    :main-size="'60rpx'"
                    :minor-size="'35rpx'"
                >
                </price>
                <view class="text-center text-white mt-[10rpx]">{{
                    // couponData.fullAmount ? `满${couponData.fullAmount}元可用` : '无门槛'
                    couponData.content
                }}</view>
            </view>
        </view>
        <view class="right bg-[#F3F8FF] flex-1 px-[20rpx]" :class="{ 'bg-white': isPageMC }">
            <view style="border-bottom: 1px solid #eaeaea" class="py-[20rpx]">
                <view class="text-lg font-medium">{{ couponData.name }}</view>
                <view class="flex items-center justify-between mt-[20rpx]">
                    <view class="text-xs text-muted pr-[20rpx] break-all">{{
                        couponData.use_time_desc
                    }}</view>
                    <view class="flex-none">
                        <view
                            v-if="(toUse || type == 1) && type != 3 && !disabled"
                            class="btn1 bg-[#DDEAFE] rounded-full px-[33rpx] py-[8rpx] text-primary"
                            @click="useCoupon(couponData.use_scope)"
                            >使用</view
                        >
                        <view v-if="type == 2 && !disabled">
                            <image
                                v-if="isSelectCoupon"
                                class="w-[38rpx] h-[38rpx]"
                                src="@/static/images/coupon/select.png"
                            ></image>
                            <image
                                v-if="!isSelectCoupon"
                                class="w-[38rpx] h-[38rpx]"
                                src="@/static/images/coupon/unselect.png"
                            ></image>
                        </view>

                        <view v-if="(type == 3 || type == 0) && !toUse">
                            <view
                                v-if="couponData.get_num != 0 && couponData.is_get == false"
                                @click="getCoupon(couponData.id)"
                                class="btn1 bg-primary rounded-full px-[33rpx] py-[8rpx] text-white"
                                >立即抢</view
                            >
                            <view
                                v-if="couponData.get_num == 0"
                                class="btn1 bg-[#DDEAFE] rounded-full px-[33rpx] py-[8rpx] text-primary"
                                >已抢光</view
                            >
                            <view
                                v-else-if="couponData.is_get == true"
                                class="btn1 bg-[#DDEAFE] rounded-full px-[33rpx] py-[8rpx] text-primary"
                                >已领取</view
                            >
                        </view>
                    </view>
                </view>
            </view>
            <view class="py-[10rpx] flex justify-between rounded-lg" @click="openDrop">
                <view class="text-sm text-muted" v-if="couponData.use_scope == 1">全场通用</view>
                <view class="text-sm text-muted" v-if="couponData.use_scope == 2"
                    >指定商品可用</view
                >
                <view class="text-sm text-muted" v-if="couponData.use_scope == 3"
                    >指定商品不可用</view
                >
                <view v-if="couponData.use_scope != 1 && courseData.length">
                    <u-icon name="arrow-down" v-if="!goodsList" color="#666666" size="30"></u-icon>
                    <u-icon name="arrow-up" v-if="goodsList" color="#666666" size="30"></u-icon>
                </view>
            </view>
        </view>
    </view>
    <view class="bg-[#F3F8FF] px-[20rpx] pt-[15rpx] pb-[30rpx] text-xs text-muted" v-if="goodsList">
        商品：{{ courseData }}
    </view>
</template>
<script setup lang="ts">
import price from '@/components/price/index.vue'
import { apiUserGetCoupon } from '@/api/coupon'
import { computed, ref } from 'vue'

//是否显示商品列表
const goodsList = ref(false)
//去使用
const toUse = ref(false)
//商品信息
const courseData = computed(() => {
    try {
        const courseList = props.couponData.course_lists
            .map((item: any) => {
                return item.name
            })
            .join()
        return courseList
    } catch (error) {
        return []
    }
})

const props = withDefaults(
    defineProps<{
        type: number
        couponData: any
        disabled: boolean
        isSelectCoupon?: boolean
        isPageMC: boolean
    }>(),
    {
        type: 0, //0-领券 1-我的优惠券页面 2-支付页面 3-商品详情页面
        couponData: {},
        disabled: false,
        isSelectCoupon: false,
        isPageMC: false
    }
)

//打开可用/不可用商品列表
const openDrop = () => {
    if (props.couponData.use_scope == 1 || courseData.value.length == 0) return
    goodsList.value == false ? (goodsList.value = true) : (goodsList.value = false)
}

//领取优惠券
const getCoupon = async (id: any) => {
    try {
        await apiUserGetCoupon({ id })
        uni.$u.toast('领取成功')
        toUse.value = true
    } catch (error) {
        console.log(error)
    }
}

//使用优惠券
const useCoupon = (value: number | string) => {
    const data = props.couponData
    if (value == 2) {
        // const title = props.couponData.condition
        //     ? `满${data.fullAmount}减${data.amount}`
        //     : `满0.00减${data.amount}`
        // const title = `${props.couponData.content},减${props.couponData.money}`
        uni.navigateTo({
            url: `/bundle/pages/coupon_goods/index?id=${props.couponData.id}&title=${props.couponData.content}`
        })
        return
    }
    uni.navigateTo({ url: '/pages/index/index' })
}
</script>
<style lang="scss" scoped>
.left {
    background-image: linear-gradient(#609fff, #2073f4);
}

.gray {
    filter: grayscale(100%);
}
</style>
