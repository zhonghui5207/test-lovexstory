<template>
    <view class="container">
        <!-- Header Search Start -->
        <view class="search-box flex bg-white">
            <view class="search flex">
                <!-- 左侧图标 -->
                <u-icon name="search" size="34" color="#888888"></u-icon>
                <!-- 输入 -->
                <input
                    v-model="keyword"
                    type="text"
                    class="search--input"
                    placeholder="请输入关键词搜索"
                    @focus="showClear = true"
                    @blur="showClear = false"
                />
                <!-- 清空图标 -->
                <!-- #ifndef H5 -->
                <image
                    src="../../../static/images/icon_clear.png"
                    class="clear"
                    v-show="showClear"
                    @click="
                        () => {
                            keyword = ''
                            searchStatus = false
                        }
                    "
                ></image>
                <!--#endif -->
            </view>
            <view class="flex justify-center items-center text-lg ml-2" @click="handleSearch">
                搜索
            </view>
        </view>
        <!-- Header Search End -->

        <!-- Main History Start -->
        <template v-if="searchStatus === false">
            <view class="history bg-white">
                <!-- Hot Header -->
                <view class="flex justify-between" v-if="hotArr.status != 0">
                    <text class="font-medium text-base">热门搜索</text>
                </view>
                <!-- Hot Main -->
                <view class="flex flex-wrap mt-[30rpx]" v-if="hotArr.status != 0">
                    <block v-for="item2 in hotArr.data" :key="item2">
                        <view class="history-item" @click="handleHistoreSearch(item2.name)">{{
                            item2.name
                        }}</view>
                    </block>
                </view>
                <!-- Main Hot End -->

                <!-- History Header -->
                <view class="flex justify-between">
                    <text class="font-medium text-base">历史搜索</text>
                    <image
                        :src="'../../static/images/icon_del.png'"
                        @click="handleHistoryClear"
                    ></image>
                </view>
                <!-- History Main -->
                <view class="flex flex-wrap mt-[30rpx]">
                    <block v-for="item2 in historyArr" :key="item2">
                        <view class="history-item" @click="handleHistoreSearch(item2)">{{
                            item2
                        }}</view>
                    </block>
                </view>
                <!-- Main History End -->
            </view>
        </template>

        <!-- Main GoodsLists Start -->
        <template v-if="searchStatus === true">
            <view class="content pt-[24rpx] bg-white">
                <z-paging ref="paging" v-model="courseData" @query="queryList" :fixed="false">
                    <course-list type="lists" :list="courseData"></course-list>
                </z-paging>
            </view>
        </template>
        <!-- Main GoodsLists End -->
    </view>
</template>

<script lang="ts" setup>
import { ref, shallowRef } from 'vue'
import cache from '@/utils/cache'
import { HISTORY } from '@/config/cachekey'
import { apiChoiceCourseLists } from '@/api/store'
import { getHotSearch } from '@/api/shop'
import CourseList from '@/components/course-list/index.vue'
import { onLoad, onShow } from '@dcloudio/uni-app'

/** Interface Start **/
interface GoodsDataObj {
    id: number
    name: string
    image: string
    remarks: string
    price: string
    unit_desc: string
}
/** Interface End **/

/** Data Start **/
// 页面状态
const searchStatus = ref<boolean>(false)
// 搜索关键字
const keyword = ref<string | number>('')
// 清空输入框
const showClear = ref<boolean>(false)
// 分类ID
const categoryId = ref<string | number>('')
// 下拉组件的Ref
const paging = shallowRef()
// 搜索结果
const courseData = ref<Array<GoodsDataObj> | null>([])
// 历史记录
const historyArr = ref<Array<any> | null>([])
//热门搜索记录
const hotArr = ref<Array<any> | null>([])
/** Data End **/

/** Methods Start **/
/**
 * @return { void }
 * @description 点击搜索
 */
const handleSearch = () => {
    searchStatus.value = true
    if (categoryId.value != '') {
        categoryId.value = ''
        uni.setNavigationBarTitle({ title: '搜索' })
    }
    paging.value.reload()
}

/**
 * @param { Object } params
 * @return { Promise }
 * @description 下拉刷新
 */
const queryList = async (page_no: any, page_size: any) => {
    // courseData.value = []

    if (keyword.value) {
        // 去除两边空格
        const value = keyword.value.replace(/^\s+|\s+$/g)
        // 同样keyword不加入历史记录
        if (!historyArr.value.includes(value)) {
            historyArr.value.unshift(value)
            cache.set(HISTORY, historyArr.value)
        }
    }

    try {
        const { lists }: any = await apiChoiceCourseLists({
            page_no,
            page_size,
            category_id: categoryId.value,
            name: keyword.value
        })
        // courseData.value = [...courseData.value, ...lists]
        paging.value.complete(lists)
    } catch (e) {
        console.log('下拉加载', e)
        paging.value.complete(false)
    }
}

/**
 * @return { Promise }
 * @description 清空历史记录
 */
const handleHistoryClear = async (): Promise<void> => {
    const resModel = await uni.showModal({
        title: '温馨提示',
        content: '是否清空历史记录？'
    })
    if (resModel.confirm) {
        cache.set(HISTORY, '')
        historyArr.value = []
    }
}
/**
 * @return { Promise }
 * @description 历史记录搜索
 */
const handleHistoreSearch = (text: string) => {
    keyword.value = text
    searchStatus.value = true
}
/** Methods End **/

/**
 * @return { Promise }
 * @description 热门搜索记录
 */
const getHotArr = async () => {
    try {
        hotArr.value = await getHotSearch()
        console.log(hotArr.value)
    } catch (e) {
        //TODO handle the exception
        console.log('获取热门搜索失败=>', e)
    }
}
/** Methods End **/

/** Life Cycle Start **/
onLoad((options) => {
    historyArr.value = cache.get(HISTORY) || []
    const id = options?.id || undefined
    const name = options?.name || undefined
    if (id) {
        categoryId.value = id
        searchStatus.value = true
        uni.setNavigationBarTitle({ title: name })
    }
    getHotArr()
})
/** Life Cycle End **/
</script>

<style lang="scss">
.container {
    display: flex;
    height: 100vh;
    overflow: hidden;
    flex-direction: column;

    // 头部搜索
    .search-box {
        width: 100%;
        height: 100rpx;
        padding: 15rpx 24rpx;
        .search {
            width: 620rpx;
            height: 100%;
            padding: 15rpx 30rpx;
            border-radius: 36rpx;
            background-color: #f6f6f6;
            // 输入框
            &--input {
                width: 84%;
                padding-left: 20rpx;
            }
            // 清空
            .clear {
                width: 34rpx;
                height: 34rpx;
                padding-left: 20rpx;
            }
        }
    }

    // 历史记录
    .history {
        padding: 20rpx 24rpx 16rpx 24rpx;
        image {
            width: 34rpx;
            height: 34rpx;
        }
        .history-item {
            margin-right: 22rpx;
            margin-bottom: 22rpx;
            padding: 10rpx 24rpx;
            color: $color-text-deep;
            font-size: $font-size-sm;
            background-color: #f4f4f4;
            border-radius: $border-radius-large;
        }
    }
    .content,
    .history {
        flex: 1;
        min-height: 0;
        overflow: scroll;
    }
}
</style>
