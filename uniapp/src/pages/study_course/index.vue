<template>
    <view v-show="isEmpty" class="study-course safeArea" @click="pageClick">
        <view>
            <!-- BGpercent -->
            <u-navbar
                :is-back="1"
                :background="{ background: `rgba(255,255,255,${1})` }"
                :border-bottom="false"
                :immersive="type == 1"
            >
                <view
                    class="w-[400rpx] overflow-hidden whitespace-nowrap text-ellipsis"
                    :style="{ color: `rgba(0,0,0,${1})` }"
                    style="min-width: 0"
                >
                    {{ activeCourse?.name || courseData?.name }}
                </view>
            </u-navbar>
        </view>
        <!-- 课程信息 -->
        <view class="course bg-white">
            <!-- 音频 -->

            <view class="h-[600rpx]" v-if="type == 2 && !isnoBuy">
                <u-sticky :enable="true" :offset-top="0" :h5-nav-height="0">
                    <view class="p-[40rpx]">
                        <l-audio
                            v-if="cateId == courseData.id"
                            :src="courseData.content"
                            :cata-id="courseData.id"
                            :course-id="courseData.course_id"
                            :initial-time="courseData.schedule"
                            :course-detail-info="courseDetailInfo"
                            @refresh="handleRefresh"
                        ></l-audio>
                    </view>
                </u-sticky>
            </view>
            <!-- <view v-else class="flex w-full h-[400rpx] justify-center items-center">
                    <u-button type="primary" @click="backToBuy" shape="circle" class=""
                        >购买课程</u-button
                    >
                </view> -->

            <!-- 视频 -->
            <view class="h-[400rpx] bg-black" v-if="type == 3 && !isnoBuy">
                <l-video
                    v-if="cateId == courseData.id"
                    :src="courseData.content"
                    :initial-time="courseData.schedule"
                    :time="courseData.duration"
                    :cata-id="courseData.id"
                    :course-id="courseData.course_id"
                ></l-video>
            </view>
            <view v-if="type == 1 && !isnoBuy">
                <l-text
                    v-if="cateId == courseData.id"
                    :courseData="courseData"
                    :cata-id="courseData.id"
                    :course-id="courseData.course_id"
                    :course-detail-info="courseDetailInfo"
                ></l-text>
            </view>
            <view v-if="isnoBuy" class="flex w-full h-[400rpx] justify-center items-center">
                <u-button type="primary" @click="backToBuy" shape="circle" class=""
                    >购买课程</u-button
                >
            </view>
        </view>
        <!--图文目录-->
        <view
            class="bottom-0 px-[20rpx] safeArea fixed w-full bg-white"
            v-show="!TWcateHide"
            v-if="type == 1"
        >
            <ITbottom
                :cate-id="cateId"
                :course-detail-info="courseDetailInfo"
                @refresh="handleRefresh"
            ></ITbottom>
        </view>
        <!-- 课程目录 -->
        <view class="mt-[30rpx]" v-if="type != 1">
            <tabs
                :current="active"
                fontSize="32"
                height="100"
                bar-width="60"
                inactiveColor="#888888"
                :top="offsetHeight(type)"
                isFixed
                :tabsStyle="{ 'border-bottom': '1px solid #F6F6F6' }"
                :barStyle="{ bottom: '-2rpx' }"
                barHeight="6"
            >
                <tab name="目录" :i="1" :index="active">
                    <view style="min-height: 700rpx">
                        <course-catalogue
                            :list="courseDetailInfo.catalogue_list"
                            :type="courseDetailInfo.type"
                            :status="courseDetailInfo.course_status"
                            :cateId="cateId"
                            @refresh="handleRefresh"
                        >
                        </course-catalogue>
                    </view>
                </tab>
            </tabs>
        </view>
        <!-- Main End -->
    </view>

    <!-- empty Start -->
    <view v-show="!isEmpty" class="empty">
        <u-empty
            text="抱歉，该课程不存在~"
            :src="'/static/images/empty/collection.png'"
            :icon-size="300"
            color="#888888"
        >
            <template #bottom>
                <view class="empty-bottom">
                    <u-button
                        @click="goPage('/pages/index/index')"
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
    <!-- empty End -->

    <!-- 购买课程弹窗 Start -->
    <!-- <u-popup v-model="buyCourse" mode="center" borderRadius="14" :mask-close-able="false">
        <view class="course-pop text-center">
            <view class="text-lg font-medium normal pt-[45rpx] pb-[40rpx]"
                >抱歉，您还未加入过该课程</view
            >
            <view class="flex justify-between mt-[60rpx]">
                <u-button
                    @click="handleOrder"
                    class="flex-1"
                    :ripple="true"
                    shape="circle"
                    :hair-line="false"
                    type="primary"
                    >去购买</u-button
                >
                <u-button
                    @click="buyCourse = false"
                    class="flex-1 m-l-30"
                    :ripple="true"
                    shape="circle"
                    :hair-line="false"
                    type="gary"
                    >取消</u-button
                >
            </view>
        </view>
    </u-popup> -->
    <!-- 购买课程弹窗 End -->
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue'
import { onLoad, onPageScroll, onShow } from '@dcloudio/uni-app'
import AdSwiper from '@/components/ad-swiper/index.vue'
import Price from '@/components/price/index.vue'
import LAudio from './components/l-audio.vue'
import LVideo from './components/l-video.vue'
import LText from './components/I-text.vue'
import ITbottom from './components/ITbottom.vue'
import CourseCatalogue from '@/components/catalogue/index.vue'
import { useUserStore } from '@/stores/user'
// import { apiPlaceOrder } from '@/api/order'
import { apiCourseDetail, apiCourseCatalogue, apiCourseStudy } from '@/api/goods'

/** Interface Start **/
interface CourseDataObj {
    id: number // 目录ID
    course_id: number // 课程ID
    name: string // 课程名称
    fee_type: number // 是否免费
    cover: string // 封面
    content: string // 内容
    schedule: number //记录时间
    duration: string
}
/** Interface End **/

/** Data Start **/
const courseData = ref<CourseDataObj>({
    id: 0,
    course_id: 0,
    name: '',
    fee_type: 0,
    cover: '',
    content: '',
    schedule: 0,
    duration: ''
})
const courseDetailInfo = ref<any>({
    course_status: 0,
    catalogue_list: []
})
// 是否已下架
const isEmpty = ref<boolean>(true)
//课程是否未购买
const isnoBuy = ref<boolean>(false)
// 课程ID
const courseId = ref<number | string>('')
// 目录ID
const cateId = ref<number | string>('')
//正在播放的目录
const activeCourse: any = ref()
// 购买课程弹窗
// const buyCourse = ref<boolean>(false)
// 课程目录｜｜评价｜｜介绍索引
const active = ref(0)
// 课程类型
const type = ref(0)
//顶部导航栏背景颜色
const BGpercent = ref(0)
//图文目录显示/隐藏
const TWcateHide = ref(false)
/** Data End **/

const userStore = useUserStore()
const isLogin = computed(() => userStore.token)

const offsetHeight = computed(() => {
    return (type: any) => {
        switch (type) {
            case 1:
                return 0
            case 2:
                return 400
            case 3:
                return 400
        }
    }
})
/** Computed End **/

/** Methods Start **/
//课程详情信息
const initCourseDetail = async (): Promise<void> => {
    try {
        courseDetailInfo.value = await apiCourseDetail({
            id: courseId.value
        })
    } catch (err) {
        console.log('获取课程详情=> ', err)
        isEmpty.value = false
    }
}

//获取课程目录信息
const initCourseCatalogue = async (): Promise<void> => {
    uni.showLoading({
        title: '载入中',
        mask: true
    })
    try {
        isnoBuy.value = false
        const res = await apiCourseCatalogue({
            id: cateId.value,
            course_id: courseId.value
        })
        courseData.value = res
    } catch (err) {
        isnoBuy.value = true
        console.log(err)
    }
    uni.hideLoading()
}

//选择课程目录
const handleRefresh = (event: any) => {
    if (event.id == cateId.value) return
    activeCourse.value = event
    courseId.value = event.course_id
    cateId.value = event.id
    // 请求目录
    initCourseCatalogue()
    // 切换课程回到顶部
    uni.pageScrollTo({
        scrollTop: 0,
        duration: 100
    })
}

/**
 * @param { number } goodsNum
 * @return { void }
 * @description 处理下单
 */
// const handleOrder = async (): Promise<void> => {
//     if (!isLogin.value) return goPage('/pages/login/login')
//     try {
//         const { order_id, type } = await apiPlaceOrder({ course_id: courseId.value })
//         const params = {
//             order_id,
//             from: type
//         }
//         goPage(`/pages/order_buy/index?params=${JSON.stringify(params)}`)
//     } catch (err) {
//         throw new Error(err + '订单下单报错')
//     }
// }
/**

 * @description 返回购买
 */
const backToBuy = () => {
    uni.navigateBack()
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

//页面被点击
const pageClick = () => {
    TWcateHide.value = false
}
let lastScrollTop = 0

onPageScroll(({ scrollTop }: any) => {
    if (scrollTop < lastScrollTop) {
        TWcateHide.value = false
    } else {
        TWcateHide.value = true
    }
    lastScrollTop = scrollTop

    if (type.value == 1) {
        // TWcateHide.value = true
        // TWdebounce()
        const top = uni.upx2px(100)
        BGpercent.value = scrollTop / top > 1 ? 1 : scrollTop / top
    }
})

/** Life Cycle Start **/
onLoad((options: any) => {
    type.value = options.type
    //图文修改导航栏背景
    type.value != 1 ? (BGpercent.value = 1) : ''
    cateId.value = options.cate_id
    courseId.value = options.course_id
})

onShow(() => {
    // buyCourse.value = false
    initCourseDetail()
    initCourseCatalogue()
})

/** Life Cycle End **/
</script>

<style lang="scss">
.safeArea {
    padding-bottom: calc(env(safe-area-inset-bottom));
}

.study-course {
    // 课程信息
    .course {
        // .audio-wrapper {
        //     ::v-deep .wx-slider-handle-wrapper {
        //         height: 10rpx !important;
        //         border: 16rpx !important;
        //         background: #bfbfbf !important;
        //     }

        //     ::v-deep .wx-slider-track {
        //         background-color: #ffffff !important;
        //     }
        // }
        .course-info {
            padding: 30rpx;
        }
    }
}

// 服务下架或不存在时
.empty {
    padding-top: 200rpx;

    .empty-bottom {
        width: 90vw;
        margin-top: 130rpx;
    }
}

// 通用弹窗
.course-pop {
    width: 640rpx;
    padding: 70rpx 80rpx 40rpx 80rpx;
}
</style>
