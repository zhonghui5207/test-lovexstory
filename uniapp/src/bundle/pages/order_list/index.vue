<template>
    <view class="order-list">
        <tabs
            :current="current"
            @change="handleChange"
            height="80"
            bar-width="60"
            :is-scroll="false"
        >
            <tab v-for="(item, index) in tabList" :key="index" :name="item.name">
                <view class="orderList">
                    <order-list :type="item.type" :i="index" :index="current"></order-list>
                </view>
            </tab>
        </tabs>
    </view>
</template>

<script lang="ts" setup>
import { ref, reactive, computed } from 'vue'
import { onLoad, onShow, onReady } from '@dcloudio/uni-app'
import OrderList from './components/order-list.vue'
import Tabbar from '@/components/tabbar/index.vue'

/** Data Start **/
const tabList = ref<any>([
    {
        name: '全部',
        type: 0
    },
    {
        name: '待支付',
        type: 1
    },
    {
        name: '已完成',
        type: 2
    },
    {
        name: '已关闭',
        type: 3
    }
])
const current = ref<number>(0)
/** Data End **/

/** Methods Start **/
const handleChange = (index: number) => {
    current.value = Number(index)
}
/** Methods End **/

/** Life Cycle Start **/
onLoad((options) => {
    current.value = options?.type * 1 || 0
})
/** Life Cycle End **/
</script>

<style lang="scss">
.orderList {
    height: calc(100vh - 40px - env(safe-area-inset-bottom));
}
</style>
