<template>
    <view>
        <tabs :is-scroll="false" @change="tabsChange">
            <tab
                v-for="(value, key, index) in tabsNameList"
                :key="index"
                :name="`${value.name}(${value.count})`"
            >
            </tab>
        </tabs>

        <view class="scrollView">
            <z-paging
                ref="paging"
                v-model="couponList"
                @query="queryList"
                height="100%"
                :fixed="false"
            >
                <view
                    class="px-[30rpx] my-[15rpx]"
                    v-for="(item, index) in couponList"
                    :key="index"
                >
                    <couponCard
                        :type="1"
                        :coupon-data="item"
                        :disabled="tabsSelect != 1"
                        :isPageMC="true"
                    ></couponCard>
                </view>
            </z-paging>
        </view>
    </view>
</template>
<script setup lang="ts">
import tabs from '@/components/tabs/tabs.vue'
import tab from '@/components/tab/tab.vue'
import couponCard from '@/components/coupon-card/index.vue'
import { apiMyCoupon, apiStatusCount } from '@/api/coupon'
import { nextTick, ref, shallowRef } from 'vue'
import { onLoad, onShow } from '@dcloudio/uni-app'

enum tabsNameListEnum {
    'normal' = 0,
    'use' = 1,
    'invalid' = 2
}

//优惠券列表
const couponList = ref<any[]>([])

//tabs当前选中项
const tabsSelect = ref(1)

//下拉组件ref
const paging: any = ref(null)

const tabsNameList: any = ref({
    normal: { name: '可使用', count: 0, type: 1 },
    use: { name: '已使用', count: 0, type: 2 },
    invalid: { name: '已过期', count: 0, type: 3 }
})

//tabs切换
const tabsChange = async (index: any) => {
    tabsSelect.value = tabsNameList.value[tabsNameListEnum[index]].type
    await nextTick()
    paging.value.reload()
}
//下拉组件
const queryList = async (page_no: any, page_size: any) => {
    try {
        const { lists, extend } = await apiMyCoupon({
            page_no,
            page_size,
            status: tabsSelect.value
        })
        Object.keys(extend).map((item) => {
            tabsNameList.value[item].count = extend[item]
        })
        console.log(lists)
        paging.value.complete(lists)
    } catch (error) {
        paging.value.complete(false)
    }
}

// //获取不同状态数量
// const getStatusCount = async () => {
//     const res = await apiStatusCount()
//     for (const key in tabsNameList.value) {
//         tabsNameList.value[key].count = res[key]
//     }
// }

onLoad(() => {
    // getStatusCount()
})
</script>
<style lang="scss" scoped>
.scrollView {
    height: calc(100vh - 80rpx - env(safe-area-inset-bottom));
}
</style>
