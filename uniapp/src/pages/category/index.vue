<template>
    <view class="container">
        <!-- <view class="px-[16rpx] py-[24rpx] bg-white" @click="goPage('/bundle/pages/search/index')">
            <u-search
                :show-action="false"
                placeholder="请输入搜索内容"
                height="70"
                disabled
            ></u-search>
        </view> -->
        <w-search />
        <view class="main flex">
            <view class="w-full h-full flex">
                <scroll-view
                    scroll-y="true"
                    class="box-border w-[235rpx] pt-[10rpx] h-full bg-white"
                >
                    <block v-for="(item, index) in categoryLists" :key="item.id">
                        <view
                            class="w-[180rpx] px-2 py-3 w-full text-[#555] text-center text-base"
                            :class="{ active: index === current }"
                            @click="clickBar(index)"
                        >
                            {{ item.name }}
                        </view>
                    </block>
                </scroll-view>

                <view class="w-full">
                    <z-paging
                        ref="paging"
                        v-model="categoryData"
                        @query="queryList"
                        :fixed="false"
                        height="100%"
                        :show-loading-more-no-more-view="false"
						:loading-more-enabled="false"
                    >
                        <!-- Content Start -->
                        <view class="px-[20rpx] py-[20rpx] w-full h-full">
                            <template v-if="categoryData.length">
                                <card :cateData="categoryData" />
                            </template>
                        </view>
                        <!-- Content End -->
                    </z-paging>
                </view>
            </view>
        </view>

        <tabbar />
    </view>
</template>

<script lang="ts" setup>
import { ref, shallowRef } from 'vue'
import Card from './component/card.vue'
import { onLoad } from '@dcloudio/uni-app'
import { apiCategoryLists } from '@/api/store'

const categoryData = ref<any[]>([])
const categoryLists: any = ref([])
const current: any = ref(0)
const paging = shallowRef()

const getCategoryLists = async () => {
    const { lists } = await apiCategoryLists()
    categoryLists.value = lists
    console.log(categoryLists.value)
}

const queryList = async (pageNo: number, pageSize: number) => {
    try {
        // const { lists } = await apiCategoryLists()
        // console.log(lists)
        paging.value.complete(categoryLists.value[current.value].sons)
    } catch (e) {
        console.log('报错=>', e)
        //TODO handle the exception
        paging.value.complete(false)
    }
}

const clickBar = (index: any) => {
    current.value = index
    console.log(index)
    paging.value.reload()
}

const goPage = (url: string) => {
    uni.navigateTo({
        url: url
    })
}
onLoad(async () => {
    await getCategoryLists()
    paging.value.reload()
})
/** Life Cycle End **/
</script>

<style lang="scss">
.container {
    display: flex;
    height: 100vh;
    overflow: hidden;
    flex-direction: column;

    .main {
        flex: 1;
        min-height: 0;
        overflow: scroll;

        // 左侧菜单分类
        .active {
            font-weight: 500;
            color: $color-primary;
            position: relative;
        }

        .active::before {
            content: '';
            height: 30rpx;
            width: 6rpx;
            position: absolute;
            left: 10rpx;
            top: 50%;
            transform: translateY(-50%);
            background-color: $color-primary;
        }
    }
}
</style>
