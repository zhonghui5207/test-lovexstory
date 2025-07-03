<template>
    <!-- 单个卡片模式 -->
    <template v-if="type == 'card'">
        <view class="course-list px-[30rpx] py-[24rpx] flex">
            <view class="cover mr-[20rpx]">
                <view class="tags text-xs text-white">{{ getCourseType(list.type) }}</view>
                <u-image :src="list.cover" width="320" height="194" borderRadius="10"></u-image>
            </view>
            <view class="info">
                <view class="normal font-medium line-2 text_hidden" style="width: 345rpx">{{
                    list.name
                }}</view>
                <view class="muted garyText text-xs mt-[15rpx] mb-[15rpx]"
                    >{{ list.study_num | 0 }}人在学习 ｜ 共{{ list.categlogue_num }}讲</view
                >
                <template v-if="list.fee_type === 1">
                    <!-- 销售价 -->
                    <price
                        :content="list.sell_price"
                        mainSize="36rpx"
                        minorSize="34rpx"
                        fontWeight="500"
                    ></price>
                </template>
                <text v-else class="price">免费</text>
            </view>
        </view>
    </template>
    <!-- 课程列表 -->
    <template v-if="type == 'lists'">
        <view
            class="course-list lists px-[40rpx] pb-[24rpx] flex"
            v-for="item in list"
            :key="item.id"
            @click="goPage(`/pages/course_detail/index?id=${item.id}`)"
        >
            <view class="cover mr-[20rpx]">
                <view class="tags text-xs text-white">{{ getCourseType(item.type) }}</view>
                <u-image :src="item.cover" width="320" height="194" borderRadius="10"></u-image>
            </view>
            <view class="info flex flex-col justify-between">
                <view
                    class="normal font-medium line-2 text_hidden"
                    style="width: 345rpx; font-size: 30rpx"
                    >{{ item.name }}</view
                >
                <view>
                    <view class="muted garyText text-xs mt-[15rpx] mb-[15rpx]"
                        >{{ item.study_num | 0 }}人在学习 ｜ 共{{ item.catalogue_count }}讲</view
                    >

                    <view class="mb-2">
                        <template v-if="item.fee_type === 1">
                            <!-- 销售价 -->
                            <price
                                :content="item.sell_price"
                                mainSize="36rpx"
                                minorSize="34rpx"
                                fontWeight="500"
                            ></price>
                        </template>
                        <text v-else class="price">免费</text>
                    </view>
                </view>
            </view>
        </view>
    </template>
    <!-- 大图模式列表 -->
    <template v-if="type == 'larger'">
        <view
            class="course-list larger"
            v-for="item in list"
            :key="item.id"
            @click="goPage(`/pages/course_detail/index?id=${item.id}`)"
        >
            <view class="cover">
                <view class="tags text-xs text-white">{{ getCourseType(item.type) }}</view>
                <u-image :src="item.cover" height="425" borderRadius="10"></u-image>
            </view>
            <view class="info">
                <view
                    class="normal font-medium line-2 text_hidden"
                    style="width: 660rpx; font-size: 30rpx"
                    >{{ item.name }}</view
                >
                <view class="flex justify-between mt-[20rpx]">
                    <view class="muted garyText text-xs mb-[15rpx]"
                        >{{ item.study_num | 0 }}人在学习 ｜ 共{{ item.catalogue_count }}讲</view
                    >
                    <template v-if="item.fee_type === 1">
                        <!-- 销售价 -->
                        <price
                            :content="item.sell_price"
                            mainSize="36rpx"
                            minorSize="34rpx"
                            fontWeight="500"
                        ></price>
                    </template>
                    <text v-else class="price">免费</text>
                </view>
            </view>
        </view>
    </template>
    <!-- 学习课程 -->
    <template v-if="type == 'study'">
        <view
            class="course-list lists flex"
            v-for="item in list"
            :key="item.id"
            @click="goPage(`/pages/course_detail/index?id=${item.id}`)"
        >
            <view class="cover mr-[20rpx]">
                <view class="tags text-xs text-white">{{ getCourseType(item.type) }}</view>
                <u-image :src="item.cover" width="320" height="194" borderRadius="10"></u-image>
            </view>
            <view class="info min-w-0 flex flex-col flex-1">
                <view
                    class="normal font-medium text_hidden min-w-0 break-all h-[84rpx] leading-[42rpx]"
                    >{{ item.name }}</view
                >
                <view
                    class="text-xs text-[#2073F4] mt-[15rpx] mb-[15rpx] truncate"
                    v-if="!!item.study_catelogue_name"
                    >学习到: {{ item.study_catelogue_name }}</view
                >
                <view class="flex text-muted justify-between items-center">
                    <view class="flex-none text-[22rpx]">共{{ item.catelogue_count }}讲</view>
                    <view class="flex-none text-[22rpx]">学习进度：{{ item.plan }}</view>
                    <view
                        class="text-xs rounded-full px-[20rpx] py-[8rpx] flex-none text bg-primary text-white"
                        :class="{
                            '!bg-[#DDEAFE] !text-[#2073F4]': !!item.study_catelogue_name
                        }"
                    >
                        {{ !!item.study_catelogue_name ? '继续学习' : '立即学习' }}
                    </view>
                </view>
            </view>
        </view>
    </template>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import Price from '../price/index.vue'

/** Props Start **/
const props = withDefaults(
    defineProps<{
        type?: any // 列表类型
        list: any // 数据列表
    }>(),
    {
        type: [],
        list: []
    }
)
/** Props End **/

/** Computed Start **/
/**
 * @description 获取课程类型
 */
const getCourseType = computed(() => {
    return (type: any) => {
        switch (type) {
            case 1:
                return '图文'
            case 2:
                return '音频'
            case 3:
                return '视频'
            case 4:
                return '专栏'
        }
    }
})
/** Computed End **/

/** Methods Start **/
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
</script>

<style lang="scss">
// 列表模式
.lists:last-child {
    border: 0;
}

.lists {
    margin-bottom: 24rpx;
    border-bottom: 1px solid $color-border-light;
    padding-bottom: 24rpx;
}

// 大图模式
.larger {
    margin: 0 24rpx;
    margin-bottom: 40rpx;
    box-shadow: 0 2px 10px rgba(64, 115, 250, 0.1);

    .info {
        padding: 20rpx 20rpx 30rpx 20rpx;
    }
}

// 公用样式
.course-list {
    // 封面
    .cover {
        position: relative;

        // 封面标签
        .tags {
            top: 16rpx;
            left: 20rpx;
            z-index: 10;
            padding: 2rpx 8rpx;
            position: absolute;
            border-radius: 4rpx;
            background: rgba(0, 0, 0, 0.2);
        }
    }

    // 课程信息
    .price {
        font-weight: 500;
        font-size: 30rpx;
        color: #fa8919;
    }
}
.text_hidden {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    overflow: hidden;
}
.garyText {
    color: #999;
}
</style>
