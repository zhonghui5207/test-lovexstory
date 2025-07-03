<template>
    <view class="teacher overflow-scroll">
        <!-- Header Start -->
        <view class="header">
            <view class="avatar flex">
                <u-image
                    :src="teacherInfo.avatar"
                    width="120"
                    height="120"
                    border-radius="50%"
                ></u-image>
                <view class="ml-[30rpx] text-white">
                    <view class="text-2xl font-medium mb-[8rpx]">{{ teacherInfo.name }}</view>
                    <view class="teacher-info--synopsis text-xs">
                        {{ teacherInfo.synopsis }}
                    </view>
                </view>
            </view>
        </view>
        <!-- Header End -->

        <!-- Main Start -->
        <tabs
            :current="active"
            fontSize="32"
            stickyBgColor="transparent"
            height="100"
            bar-width="60"
            inactiveColor="#888888"
            borderRadius="20rpx 20rpx 0 0"
            :tabsStyle="{ 'border-bottom': '1px solid #F6F6F6' }"
            :barStyle="{ bottom: '-2rpx' }"
            barHeight="6"
            :isScroll="false"
            itemWidth="200"
        >
            <tab name="讲师介绍" :i="0" :index="active">
                <view class="p-[30rpx] bg-white">
                    <mp-html :content="teacherInfo.introduce" />
                </view>
            </tab>
            <tab name="TA的课程" :i="1" :index="active">
                <view class="pt-[40rpx] bg-white">
                    <course-list type="lists" :list="teacherInfo.course_list"></course-list>
                </view>
            </tab>
        </tabs>
        <!-- Main Start -->
    </view>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { onLoad } from '@dcloudio/uni-app'
import { apiTeacherDetail } from '@/api/store'
import CourseList from '@/components/course-list/index.vue'

/** Interface Start **/
interface TeacherInfoObj {
    avatar: string // 头像
    course_list: Array<any> // 课程列表
    gender: string // 性别
    id?: number // ID
    introduce: string // 讲师介绍
    name: string // 讲师名称
    number: number // 编号
    status: number // 状态
    synopsis: string // 简介
}
/** Interface End **/

/** Data Start **/
const teacherInfo = ref<TeacherInfoObj>({
    avatar: '',
    course_list: [],
    gender: '',
    introduce: '',
    name: '',
    number: '',
    status: 0,
    synopsis: ''
})
const active = ref<number>(0)
// 讲师ID
const teacherId = ref<number | string>('')
/** Data End **/

/** Methods Start **/
/**
 * @return { Promise }
 * @description 初始化请求讲师详情
 */
const initTeacherDetail = async (): Promise<void> => {
    try {
        teacherInfo.value = await apiTeacherDetail({
            id: teacherId.value
        })
    } catch (e) {
        console.log('初始化请求讲师详情', e)
    }
}
/** Methods End **/

/** Life Cycle Start **/
onLoad((options) => {
    teacherId.value = options?.id || ''
    initTeacherDetail()
})
/** Life Cycle End **/
</script>

<style lang="scss">
.teacher {
    height: calc(100vh - env(safe-area-inset-bottom));
    background: linear-gradient(180deg, $color-primary 0%, $color-primary 260rpx, transparent 0);
    .header {
        .avatar {
            height: 240rpx;
            // padding: 50 24rpx;
            padding-top: 50rpx;
            padding-left: 24rpx;
        }
    }
}
</style>
