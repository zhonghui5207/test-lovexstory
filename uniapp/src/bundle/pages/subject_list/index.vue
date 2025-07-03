<template>
    <!-- Main Start -->
    <z-paging ref="paging" v-model="subjectData" @query="queryList">
        <view class="list">
            <block v-for="item in subjectData" :key="item.id">
                <view
                    class="list--item"
                    @click="goPage(`/bundle/pages/subject_detail/index?id=${item.id}`)"
                >
                    <u-image
                        :src="item.cover"
                        width="334"
                        height="450"
                        borderRadius="10rpx 10rpx 0 0"
                    ></u-image>
                    <view class="list--item--name truncate">
                        {{ item.name }}
                    </view>
                    <view class="list--item--desc"> {{ item.study_num }} 人在学习 </view>
                </view>
            </block>
        </view>
    </z-paging>
    <!-- Main End -->
</template>

<script lang="ts" setup>
import { ref, shallowRef } from 'vue'

import { apiSubjectLists } from '@/api/store'
import { onLoad } from '@dcloudio/uni-app'
/** Data Start **/
const subjectData: any = ref([])
// 下拉组件的Ref
const paging = shallowRef()
/** Data End **/

/** Methods Start **/
/**
 * @description 下拉刷新
 */
const queryList = async (page_no: any, page_size: any) => {
    try {
        const { lists } = await apiSubjectLists({
            page_no,
            page_size
        })
        paging.value.complete(lists)
    } catch (e) {
        console.log('下拉加载', e)
        paging.value.complete(false)
    }
}

/**
 * @param { string } url
 * @return { void }
 * @description 跳转页面方法
 */
const goPage = (url: string) => {
    uni.navigateTo({
        url: url
    })
}
/** Methods End **/
onLoad((option: any) => {
    uni.setNavigationBarTitle({ title: option.title })
})
</script>

<style lang="scss">
.list {
    padding: 24rpx;

    &--item:nth-child(even) {
        margin-right: 0;
    }
    &--item {
        width: 334rpx;
        margin-right: 24rpx;
        margin-bottom: 24rpx;
        text-align: center;
        display: inline-block;
        border-radius: 0 0 10rpx 10rpx;
        background-color: $color-white;

        &--name {
            width: 100%;
            padding: 20rpx 18rpx;
            color: $color-text-deep;
            font-size: $font-size-lg;
        }
        &--desc {
            color: $color-text-muted;
            font-size: $font-size-xs;
            padding-bottom: 20rpx;
        }
    }
}
</style>
