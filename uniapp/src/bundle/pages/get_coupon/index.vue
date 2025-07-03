<template>
    <view class="viewH">
        <z-paging
            ref="paging"
            v-model="couponlist"
            @query="queryList"
            :fixed="false"
            safe-area-inset-bottom
            :empty-view-style="{ top: '400rpx' }"
        >
            <view class="couponPage">
                <view class="relative">
                    <view>
                        <image
                            src="@/bundle/static/images/coupon_banner.png"
                            class="w-full h-[400rpx]"
                        ></image>
                    </view>

                    <view
                        v-for="(item, index) in couponlist"
                        :key="index"
                        class="py-[15rpx] px-[30rpx]"
                    >
                        <couponCard :coupon-data="item"></couponCard>
                    </view>
                </view>
            </view>
        </z-paging>
    </view>
</template>

<script setup lang="ts">
import couponCard from '@/components/coupon-card/index.vue'
import { onLoad } from '@dcloudio/uni-app'
import { apiCouponCenter } from '@/api/coupon'
import { ref, shallowRef } from 'vue'

// //优惠券数据
const couponlist: any = ref({})
// //下拉组件ref
const paging = shallowRef()

const queryList = async (page_no: any, page_size: any) => {
    try {
        const { lists } = await apiCouponCenter({ page_no, page_size })
        // paging.value.complete(lists)
        paging.value.setLocalPaging(lists)
    } catch (error) {
        paging.value.setLocalPaging(error)
    }
}
</script>

<style lang="scss" scoped>
.viewH {
    height: calc(100vh - env(safe-area-inset-bottom));
}
</style>
