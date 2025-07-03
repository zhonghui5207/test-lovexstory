<template>
    <z-paging
        auto-show-back-to-top
        :auto="i == index"
        ref="paging"
        v-model="orderList"
        :data-key="i"
        @query="queryList"
        :fixed="false"
        height="100%"
        v-if="isLogin"
    >
        <block v-for="(item2, index2) in orderList" :key="item2.id">
            <!-- 订单卡片 -->
            <order-card :orderInfo="item2" @refresh="orderListRefresh">
                <order-footer
                    :orderId="item2?.id"
                    :cancel="item2.cancel_btn"
                    :evaluate="item2.comment_btn"
                    :pay="item2.pay_btn"
                    :del="item2.del_btn"
                    @refresh="orderListRefresh"
                />
            </order-card>
        </block>
    </z-paging>

    <!-- 未登录 -->
    <view class="empty" v-if="!isLogin">
        <u-empty
            :src="'/static/images/empty/order.png'"
            text="您还没有登录～"
            mode="data"
            :icon-size="300"
            margin-top="300"
            color="#888888"
        >
            <template #bottom>
                <view class="m-t-30">
                    <u-button
                        @click="toLogin"
                        shape="circle"
                        :ripple="true"
                        :hair-line="false"
                        type="info"
                    >
                        去登录</u-button
                    >
                </view>
            </template>
        </u-empty>
    </view>
</template>

<script lang="ts" setup>
import { ref, reactive, computed, shallowRef, watch, nextTick, unref } from 'vue'
import { apiOrderLists } from '@/api/order'
import OrderCard from './order-card.vue'
import OrderFooter from '@/components/order-footer/index.vue'
import { useUserStore } from '@/stores/user'
import { toLogin } from '@/utils/login'
import { onShow } from '@dcloudio/uni-app'

/** Props Start **/
const props = withDefaults(
    defineProps<{
        type?: number // 底部
        i: number
        index: number
    }>(),
    {
        type: 1,
        i: 0,
        index: 0
    }
)
/** Props End **/

/** Data Start **/
const userStore = useUserStore()
const orderList: any = ref([])
const paging = shallowRef()
/** Data End **/

/** Computed Start **/
// 是否登录
const isLogin = computed(() => userStore.token)
/** Computed End **/

const orderListRefresh = () => {
    console.log(1)
    paging.value.refresh()
}

/** Methods Start **/
const isFirst = ref<boolean>(true)

watch(
    () => props.index,
    async () => {
        await nextTick()
        if (props.i == props.index && unref(isFirst)) {
            isFirst.value = false
            paging.value?.reload()
        }
    },
    { immediate: true }
)
const queryList = async (page_no: any, page_size: any) => {
    try {
        const { lists } = await apiOrderLists({
            page_no,
            page_size,
            type: props.type
        })
        // if (param.num === 1) orderList.value = []
        // orderList.value = [...orderList.value, ...res.lists]
        paging.value.complete(lists)
    } catch (e) {
        console.log('下拉加载', e)
        paging.value.complete(false)
    }
}
onShow(async () => {
    await nextTick()
    paging.value.refresh()
})
/** Methods End **/
</script>

<style>
/* .main {
    height: calc(100vh - env(safe-area-inset-bottom));
} */
</style>
