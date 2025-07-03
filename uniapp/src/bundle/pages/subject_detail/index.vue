<template>
    <view class="subject-detail">
        <!-- Header Start -->
        <view class="header">
            <u-image :src="subjectData.image" height="400" v-if="subjectData.image != ''"></u-image>
            <view class="p-[28rpx] bg-white flex items-center">
                <view class="title">{{ subjectData.name }}</view>
                <view class="desc"> {{ subjectData.study_num }} 人在学习 </view>
            </view>
        </view>
        <!-- Header End -->

        <!-- Main Start -->
        <view class="main">
            <course-list type="lists" :list="subjectData.course"></course-list>
        </view>
        <!-- Main Start -->
    </view>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { onLoad } from '@dcloudio/uni-app'
import { apiSubjectDetail } from '@/api/store.ts'
import CourseList from '@/components/course-list/index.vue'

/** Interface Start **/
interface SubjectDataObj {
    id: string // ID
    name: string // 内页名称
    cover: string // 封面
    image?: string // 头图
    study_num: number // 学习人数
    course: Array<any> // 课程信息
}
/** Interface End **/

/** Data Start **/
const subjectData = ref<SubjectDataObj>({
    id: '',
    name: '',
    cover: '',
    image: '',
    study_num: 0,
    course: []
})
// 专区内页ID
const subjectId = ref<number | string>('')
/** Data End **/

/** Methods Start **/
/**
 * @return { Promise }
 * @description 初始化请求专区内页详情
 */
const initSubjectDetail = async (): Promise<void> => {
    try {
        subjectData.value = await apiSubjectDetail({
            id: subjectId.value
        })
    } catch (e) {
        console.log('初始化请求专区内页详情', e)
    }
}
/** Methods End **/

/** Life Cycle Start **/
onLoad((options) => {
    subjectId.value = options?.id || ''
    initSubjectDetail()
})
/** Life Cycle End **/
</script>

<style lang="scss">
.subject-detail {
    .header {
        .title {
            width: 500rpx;
            color: $color-text-deep;
            font-size: $font-size-lg;
        }
        .desc {
            color: $color-text-muted;
            font-size: $font-size-xs;
        }
    }

    .main {
        margin-top: 20rpx;
        padding-top: 20rpx;
        background-color: $color-white;
    }
}
</style>
