<template>
    <view class="main">
        <z-paging ref="paging" v-model="chargeRecord" @query="queryList">
            <!-- 充值记录列表 -->
            <view class="list mt-[20rpx]">
                <view v-for="item in chargeRecord" :key="item.create_time" class="item bg-white">
                    <view class="flex justify-between text-lg">
                        <view class="normal mb-[10rpx]">{{ item.tips || '-' }}</view>
                        <view class="primary">{{ item.order_amount || '-' }}</view>
                    </view>
                    <view class="text-xs text-muted">{{ item.create_time || '-' }}</view>
                </view>
            </view>
        </z-paging>
    </view>
</template>

<script lang="ts" setup>
import { ref, shallowRef } from 'vue'

import { apiChargeLogLists } from '@/api/user'

/** Data Start **/
const chargeRecord = ref([1])
// 下拉组件的Ref
const paging = shallowRef()
/** Data End **/

/** Methods Start **/
const queryList = async (page_no: any, page_size: any) => {
    try {
        const { lists } = await apiChargeLogLists({
            page_no,
            page_size
        })
        paging.value.complete(lists)
    } catch (e) {
        console.log('下拉加载', e)
        paging.value.complete(false)
    }
}
</script>

<style lang="scss">
.list {
    .item {
        padding: 20rpx 30rpx;
        &:not(:last-of-type) {
            border-bottom: 1rpx solid $color-border-light;
        }
    }
}
</style>
