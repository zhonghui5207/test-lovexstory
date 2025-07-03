<template>
    <view class="container">
        <tabs
            :current="current"
            @change="handleChange"
            height="100"
            bar-width="60"
            :is-scroll="false"
        >
            <tab
                v-for="(commentItem, commentIndex) in tabList"
                :key="commentIndex"
                :name="commentItem.name"
            >
                <view class="list">
                    <z-paging
                        auto-show-back-to-top
                        v-if="current === commentIndex"
                        :data-key="commentIndex"
                        ref="paging"
                        v-model="commentItem.list"
                        @query="queryList"
                        :fixed="false"
                        height="100%"
                    >
                        <template v-if="current == 0">
                            <wait :list="commentItem.list"></wait>
                        </template>

                        <template v-if="current == 1">
                            <already :list="commentItem.list"></already>
                        </template>
                    </z-paging>
                </view>
            </tab>
        </tabs>
    </view>
</template>

<script lang="ts" setup>
import { ref, shallowRef, onMounted } from 'vue'
import { apiEvaluateLists } from '@/api/user'
import Wait from './components/wait.vue'
import Already from './components/already.vue'
import { MyEvaluateEnum } from '@/utils/enum'
import { onShow } from '@dcloudio/uni-app'

/** Data Start **/
const tabList: any = ref([
    {
        name: '待评价',
        type: MyEvaluateEnum.WAIT,
        list: []
    },
    {
        name: '已评价',
        type: MyEvaluateEnum.ALREADY,
        list: []
    }
])
const current = ref<number>(0)
// 下拉组件的Ref
const paging: any = shallowRef()
/** Data End **/

/** Methods Start **/

const handleChange = (index: number) => {
    current.value = Number(index)
}

const queryList = async (page_no: any, page_size: any) => {
    const index = current.value
    try {
        const { lists, count } = await apiEvaluateLists({
            page_no,
            page_size,
            is_comment: tabList.value[index].type
        })
        if (tabList.value[index].type == MyEvaluateEnum.WAIT)
            tabList.value[index].name = `待评价(${count})`
        paging.value[0].complete(lists)
    } catch (e) {
        paging.value.complete(false)
    }
}

onShow(() => {
    setTimeout(() => {
        paging.value[0].refresh()
    }, 1)
})
</script>

<style lang="scss">
.list {
    height: calc(100vh - 50px - env(safe-area-inset-bottom));
}
</style>
