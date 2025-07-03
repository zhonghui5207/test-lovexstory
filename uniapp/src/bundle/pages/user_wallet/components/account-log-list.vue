<template>
    <view class="main">
        <z-paging
            auto-show-back-to-top
            :auto="i == index"
            ref="paging"
            v-model="accountLogList"
            :data-key="i"
            @query="queryList"
            :fixed="false"
            height="100%"
        >
            <!-- 订单卡片 -->
            <view class="list">
                <view v-for="item in accountLogList" :key="item.id" class="item bg-white">
                    <view class="flex justify-between text-lg">
                        <view class="normal mb-[10rpx]">{{ item.type_desc || '余额充值' }}</view>
                        <view class="primary" v-if="item.action"
                            >+{{ item.change_amount || '500' }}</view
                        >
                        <view class="normal" v-else>-{{ item.change_amount || '500' }}</view>
                    </view>
                    <view class="text-xs text-muted">{{
                        item.create_time || '2022-04-25 00:19:07'
                    }}</view>
                </view>
            </view>
        </z-paging>
    </view>
</template>

<script lang="ts" setup>
import { ref, shallowRef, watch, nextTick, unref } from 'vue'
import { apiAccountLogLists } from '@/api/user'

/** Props Start **/
const props = withDefaults(
    defineProps<{
        type?: any
    }>(),
    {
        type: 1
    }
)
/** Props End **/

/** Data Start **/
const accountLogList: any = ref([])
// 下拉组件的Ref
const paging = shallowRef()
/** Data End **/

const queryList = async (page_no: any, page_size: any) => {
    try {
        const { lists, count } = await apiAccountLogLists({
            page_no,
            page_size,
            type: props.type,
            change_object: 1
        })

        paging.value.complete(lists)
    } catch (e) {
        console.log('下拉加载', e)
        paging.value.complete(false)
    }
}
</script>

<style lang="scss" scoped>
.main {
    height: calc(100vh - 200px - env(safe-area-inset-bottom));
    padding-top: 20rpx;

    .list {
        .item {
            padding: 20rpx 30rpx;
            &:not(:last-of-type) {
                border-bottom: 1rpx solid $color-border-light;
            }
        }
    }
}
</style>
