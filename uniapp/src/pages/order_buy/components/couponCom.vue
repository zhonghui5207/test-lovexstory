<template>
    <!-- <view
                class="coupon flex p-[30rpx] justify-between"
                style="border-bottom: 1px solid #f6f6f6"
                @click="couponPop = true"
                v-if="payment.orderType != PayEnum.RECHARGE"
            > -->
    <view
        class="coupon flex p-[30rpx] justify-between"
        style="border-bottom: 1px solid #f6f6f6"
        @click="couponPop = true"
    >
        <view>优惠券</view>
        <view class="flex" v-if="isUseCoupon == ''">
            <view class="mr-[10rpx]" v-if="!couponData.avaliable.data.length"
                >暂无可用的优惠券</view
            >
            <view class="mr-[10rpx]" v-else>{{
                `${couponData.avaliable.data.length || '无'}张优惠券可用`
            }}</view>
            <u-icon name="arrow-right" color="#666666" size="28"></u-icon>
        </view>
        <view class="flex" v-if="isUseCoupon != ''">
            <view class="mr-[10rpx] text-[#FA8919]">{{ `-¥${isUseCoupon.money}` }}</view>
            <u-icon name="arrow-right" color="#666666" size="28"></u-icon>
        </view>
    </view>
    <u-popup
        v-model="couponPop"
        mode="bottom"
        height="850rpx"
        borderRadius="14"
        safe-area-inset-bottom
        closeable
    >
        <view class="px-[30rpx]">
            <view class="title py-[25rpx] h-[100rpx] text-center text-3xl sticky top-0 bg-white"
                >优惠</view
            >
            <view>
                <tabs :isScroll="false" :current="current" height="80" :showBar="false">
                    <tab
                        v-for="(value, key, index) in couponData"
                        :key="index"
                        :name="`${value.name}(${value.data.length})`"
                    >
                        <view class="tabH">
                            <scroll-view scroll-y class="h-full">
                                <view
                                    v-for="(item, index1) in value.data"
                                    :key="index1"
                                    class="py-[15rpx]"
                                    @click="selectCoupon(item, index)"
                                >
                                    <coupon-card
                                        :is-select-coupon="
                                            isSelectCoupon.coupon_list_id == item.coupon_list_id
                                        "
                                        :disabled="index == 1"
                                        :coupon-data="item"
                                        :type="2"
                                    ></coupon-card>
                                </view>
                                <view
                                    v-if="value.data.length == 0"
                                    class="flex flex-col items-center justify-center h-full"
                                >
                                    <image
                                        src="@/static/images/empty/collection.png"
                                        class="w-[200rpx] h-[200rpx]"
                                    >
                                    </image>
                                    <view>暂无数据～</view>
                                </view>
                            </scroll-view>
                        </view>
                    </tab>
                </tabs>
            </view>
            <view class="h-[100rpx]">
                <button class="bg-primary rounded-[100rpx] text-white" @click="confirm">
                    确定
                </button>
            </view>
        </view>
    </u-popup>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { orderCouponList } from '@/api/coupon'
import couponCard from '@/components/coupon-card/index.vue'

const porps = defineProps({
    payment: {
        type: Object,
        default: {}
    },
    getPrice: {
        type: Function,
        default: () => {}
    }
})

const current = ref(0)

//优惠券弹框是/否显示
const couponPop = ref(false)
//被选中优惠券
const isSelectCoupon = ref<any>('')
//使用优惠券
const isUseCoupon = ref<any>('')

//优惠券数据
const couponData: any = ref({
    avaliable: {
        name: '可用优惠券',
        data: []
    },
    unavaliable: {
        name: '不可用优惠券',
        data: []
    }
})

//获取优惠券列表
const getCouponList = async () => {
    // if (porps.payment.orderType == PayEnum.RECHARGE) return
    const { unusable, usable } = await orderCouponList({
        order_id: porps.payment.order_id
    })

    couponData.value.avaliable.data = usable
    couponData.value.unavaliable.data = unusable
    selectFirst()
}

//默认选中第一张优惠券
const selectFirst = () => {
    if (couponData.value.avaliable.data.length == 0) return
    try {
        selectCoupon(couponData.value.avaliable.data[0], 0)
        confirm()
    } catch (error) {
        console.log(error)
    }
}

//选择优惠券
const selectCoupon = (value: any, index: any) => {
    //index 0-可用优惠券 1-不可用优惠券
    if (index == 1) return
    if (isSelectCoupon.value.coupon_list_id == value.coupon_list_id) {
        isSelectCoupon.value = ''
        return
    }
    isSelectCoupon.value = value
}

//优惠券确认
const confirm = async () => {
    isUseCoupon.value = isSelectCoupon.value
    porps.getPrice(isUseCoupon.value.coupon_list_id)
    // await nextTick()
    couponPop.value = false
}

const initCoupon = () => {
    getCouponList()
}

defineExpose({ initCoupon })
</script>

<style scoped lang="scss">
.tabH {
    height: calc(850rpx - 280rpx - env(safe-area-inset-bottom));
}

// ::v-deep .u-checkbox {
//     display: block !important;
// }
</style>
