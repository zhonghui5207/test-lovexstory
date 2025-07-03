<template>
    <view class="main">
        <z-paging
            auto-show-back-to-top
            ref="paging"
            v-model="accountLogList"
            @query="queryList"
            :fixed="false"
            height="100%"
        >
            <!-- 订单卡片 -->
            <view class="list mt-[10rpx]">
                <view v-for="item in accountLogList" :key="item.id" class="item bg-white">
                    <view class="flex justify-between text-lg">
                        <view class="normal mb-[10rpx]">{{ item.type_desc || '-' }}</view>
                        <view class="text-error" v-if="item.action"
                            >+{{ item.change_amount || '-' }}</view
                        >
                        <view class="normal" v-else>-{{ item.change_amount || '-' }}</view>
                    </view>
                    <view class="text-xs text-muted">{{ item.create_time || '-' }}</view>
                </view>
            </view>
        </z-paging>
    </view>
</template>

<script lang="ts" setup>
import { ref, shallowRef } from 'vue'
import { apiAccountLogLists } from '@/api/user'
import { onLoad } from '@dcloudio/uni-app'

const props = withDefaults(
    defineProps<{
        type?: any
    }>(),
    {
        type: 1
    }
)

const type = ref(0)

const accountLogList: any = ref([])
// 下拉组件的Ref
const paging = shallowRef()

const queryList = async (page_no: any, page_size: any) => {
    try {
        const { lists, count } = await apiAccountLogLists({
            page_no,
            page_size,
            type: props.type,
            change_object: type.value
        })

        paging.value.complete(lists)
    } catch (e) {
        console.log('下拉加载', e)
        paging.value.complete(false)
    }
}

onLoad((option: any) => {
    type.value = option.type
})
</script>

<style lang="scss" scoped>
.main {
    height: calc(100vh - env(safe-area-inset-bottom) - 100rpx);

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
