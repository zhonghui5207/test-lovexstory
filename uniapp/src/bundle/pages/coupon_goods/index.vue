<template>
    <view>
        <scroll-view scroll-y class="scrollView">
            <view class="px-[16rpx] py-[24rpx] bg-white">
                <u-search
                    :show-action="false"
                    placeholder="搜索该优惠券可使用商品"
                    height="70"
                    v-model="keyword"
                    @change="search"
                ></u-search>
            </view>
            <view class="px-[20rpx] bg-white">
                <view class="bg-[#FFF9F5] py-[19rpx] text-center text-[#FA8919]">
                    {{ title }}
                </view>
                <view class="mt-[30rpx] paging">
                    <z-paging
                        class=""
                        ref="paging"
                        v-model="courseList"
                        @query="queryList"
                        :fixed="false"
                    >
                        <course-list type="lists" :list="courseList"></course-list>
                    </z-paging>
                </view>
            </view>
        </scroll-view>
    </view>
</template>
<script setup lang="ts">
import CourseList from '@/components/course-list/index.vue'
import { couponCourse } from '@/api/coupon'
import { onLoad } from '@dcloudio/uni-app'
import { ref } from 'vue'
//课程列表
const courseList = ref([])
//优惠券id
const couponId = ref<number | string>('')
//搜索参数
const keyword = ref<string>('')
//标题
const title = ref<string>('')

//下拉刷新组件ref
const paging: any = ref(null)

const queryList = async (pageNo: any, pageSize: any) => {
    const { lists } = await couponCourse({
        pageNo,
        pageSize,
        coupon_list_id: couponId.value,
        courseName: keyword.value
    })
    paging.value.complete(lists)
}
//搜索
const search = () => {
    paging.value.reload()
}

onLoad((option: any) => {
    console.log(option)
    couponId.value = option.id
    title.value = option.title
})
</script>
<style lang="scss" scoped>
.scrollView {
    height: calc(100vh - env(safe-area-inset-bottom));
}
.paging {
    height: calc(100vh - 230rpx - env(safe-area-inset-bottom));
}
</style>
