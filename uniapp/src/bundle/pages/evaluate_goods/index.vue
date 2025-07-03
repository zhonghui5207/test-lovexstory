<template>
    <view class="container" v-if="tabList.length">
        <!-- Header Start -->
        <u-tabs
            :list="tabList"
            :is-scroll="true"
            :current="current"
            @change="changeTabs"
            bg-color="white"
            inactive-color="#222222"
            active-color="#F36161"
            height="100"
        ></u-tabs>
        <!-- Header End -->

        <!-- Main Start -->
        <view class="main">
            <swiper :duration="400" @change="changeTabs" :current="current">
                <swiper-item v-for="(item, index) in tabList" :key="item.order_status">
                    <refresh
                        v-if="index === current"
                        @up="upCallback"
                        :ref="initRefFunc"
                        :empty="'/static/images/empty/evaluate.png'"
                        emptyText="暂无评价数据～"
                    >
                        <!-- <uni-transition mode-class="zoom-in" needLayout="true" :show="listData.length" :duration="500"> -->
                        <block v-for="(item2, index2) in listData" :key="item2.id">
                            <card
                                :comment="item2.comment"
                                :goods_comment_image="item2.goods_comment_image"
                                :reply="item2.reply"
                                :create_time="item2.create_time"
                                :goods_id="item2.goods_id"
                                :service_comment="item2.service_comment"
                                :user="item2.user"
                            ></card>
                        </block>
                        <!-- </uni-transition> -->
                    </refresh>
                </swiper-item>
            </swiper>
        </view>
        <!-- Main End -->
    </view>
</template>

<script lang="ts" setup>
import { ref, reactive } from 'vue'
import { onLoad, onShow } from '@dcloudio/uni-app'
import { apiEvaluateGoodsLists, apiEvaluateGoodsCategory } from '@/api/goods.ts'
import refresh from '@/components/refresh/index.vue'
import Card from './components/card.vue'
import { EvaluateGoodsEnum } from '@/utils/enum.ts'

/** Data Start **/
const tabList = ref([])
const listData = ref<any>([])
const goodsId = ref<number>(0)
const current = ref<number>(0)
// 下拉组件的Ref
const refreshRef = ref<InstanceType<typeof refresh> | null>(null)
/** Data End **/

/** Methods Start **/
const initEvaluateGoodsCategory = async (): Promise<void> => {
    try {
        const res: any = await apiEvaluateGoodsCategory({ goods_id: goodsId.value })
        tabList.value = res.comment
    } catch (err) {
        console.log(err)
    }
}

const changeTabs = (event: Event) => {
    let index
    event.detail ? (index = event.detail.current) : (index = event)
    current.value = Number(index)
    refreshRef.value?.resetUpScroll()
}

const upCallback = async (param: any): Promise<void> => {
    const index = current.value
    try {
        const res: any = await apiEvaluateGoodsLists({
            page_no: param.num,
            page_size: param.size,
            goods_id: goodsId.value,
            id: tabList.value[index].id
        })
        if (param.num === 1) listData.value = []
        listData.value = [...listData.value, ...res.lists]

        refreshRef.value.endSuccess(res.count)
    } catch (e) {
        console.log('下拉加载', e)
        refreshRef.value.endErr()
    }
}
/**
 * @param { Object } 下拉组件实例
 * @return { void }
 * @description 获取下拉组件实例数据
 */
const initRefFunc = (el: Event) => {
    if (el == null) return
    refreshRef.value = el
}
/** Methods End **/

/** Life Cycle Start **/
onLoad((options) => {
    goodsId.value = options.id || 0
    initEvaluateGoodsCategory()
})
onShow(() => {
    refreshRef.value?.resetUpScroll()
})
/** Life Cycle End **/
</script>

<style lang="scss">
.container {
    display: flex;
    height: 100vh;
    overflow: hidden;
    flex-direction: column;
}

.main {
    flex: 1;
    min-height: 0;
    overflow: scroll;
    swiper {
        height: 100%;
    }
}
</style>
