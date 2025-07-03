<template>
    <view class="container">
        <tabs bgColor="#fff" :isScroll="false" @change="tabsChange" fontSize="30px">
            <tab name="免费课程"></tab>
            <tab name="付费课程"></tab>
        </tabs>
        <!-- Main Start -->
        <view class="main bg-white pt-[10px]">
            <z-paging
                ref="paging"
                @query="queryList"
                :fixed="false"
                height="100%"
                v-if="isLogin"
                :show-loading-more-no-more-view="false"
            >
                <div v-if="courseList.fee_list.length && currentTab == 0" class="px-[10rpx]">
                    <course-list type="study" :list="courseList.fee_list"></course-list>
                    <!-- <empty v-if="courseList.fee_list.length==0"></empty> -->
                </div>
                <div
                    v-else-if="courseList.charge_list.length && currentTab == 1"
                    class="px-[10rpx]"
                >
                    <course-list type="study" :list="courseList.charge_list"></course-list>
                    <!-- <empty v-if="courseList.fee_list.length==0"></empty> -->
                </div>

                <view v-else class="empty">
                    <u-empty
                        text="暂无学习课程~"
                        :src="'/static/images/empty/course.png'"
                        :icon-size="300"
                        color="#888888"
                    >
                        <template #bottom>
                            <view class="empty-bottom">
                                <u-button
                                    @click="goHome"
                                    shape="circle"
                                    :ripple="true"
                                    :hair-line="false"
                                    type="primary"
                                >
                                    去看看其它</u-button
                                >
                            </view>
                        </template>
                    </u-empty>
                </view>
            </z-paging>
        </view>

        <!-- 底部导航 -->
        <tabbar />

        <!-- 骨架屏 -->
        <skeleton v-show="loading && isLogin"></skeleton>
    </view>
</template>

<script lang="ts" setup>
import { ref, shallowRef, computed, unref } from 'vue'
import { apiCtudyCourseLists } from '@/api/store'
import { useUserStore } from '@/stores/user'
import Skeleton from './component/skeleton.vue'
import CourseList from '@/components/course-list/index.vue'

const userStore = useUserStore()
const courseList = ref({
    charge_list: [],
    fee_list: []
})
const paging = shallowRef<any>(null)
// 加载状态
const loading = ref<boolean>(true)

const currentTab = ref(0)

// 是否登录
const isLogin = computed(() => userStore.token)
// 是否为空
const isEmpty = computed(() => {
    const list = unref(courseList)
    return list.charge_list.length == 0 && list.fee_list.length == 0
})

const queryList = async () => {
    try {
        const { charge_list, fee_list } = await apiCtudyCourseLists()
        courseList.value.fee_list = fee_list
        courseList.value.charge_list = charge_list
        loading.value = false
        paging.value.complete([true])
    } catch (e) {
        loading.value = false
        console.log('报错=>', e)
        paging.value.complete(false)
    }
}

//tabs切换
const tabsChange = (value: number) => {
    currentTab.value = value
}
const goHome = () => {
    uni.navigateTo({ url: '../index/index' })
}
</script>

<style lang="scss" scoped>
.container {
    display: flex;
    height: 100vh;
    overflow: hidden;
    flex-direction: column;
}

.main {
    flex: 1;
    min-height: 0;

    swiper {
        height: 100%;
    }

    .title {
        font-size: 36rpx;
        font-weight: 500;
        padding: 24rpx 30rpx;
        color: $color-text-deep;
    }

    // 课程为空 或 为登陆时
    .empty {
        padding-top: 200rpx;
        .empty-bottom {
            width: 460rpx;
            margin-top: 130rpx;
        }
    }
}
</style>
