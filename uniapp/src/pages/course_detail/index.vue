<template>
    <view class="course" v-show="isEmpty">
        <!-- 轮播图 -->
        <view>
            <ad-swiper
                v-if="catalog.id == 0"
                :list="courseData.course_image"
                height="400"
                mode="none"
                name="uri"
                indicatorPos="bottomRight"
            ></ad-swiper>
        </view>

        <!-- Main Start -->
        <!-- 课程信息 -->
        <view class="course-info bg-white rounded-b-lg">
            <!-- 价格信息 && 学习信息 -->
            <view class="flex justify-between">
                <!-- 价格 -->
                <view>
                    <template v-if="courseData.fee_type === 1">
                        <!-- 销售价 -->
                        <price
                            :content="courseData.sell_price"
                            mainSize="36rpx"
                            minorSize="34rpx"
                            fontWeight="500"
                        ></price>
                        <!-- 划线价 -->
                        <price
                            :content="courseData.line_price"
                            v-if="courseData.line_price != '0'"
                            mainSize="26rpx"
                            minorSize="26rpx"
                            class="m-l-20"
                            color="#888888"
                            lineThrough
                        ></price>
                    </template>
                    <text v-else class="text-2xl font-medium" style="color: #fa8919">免费</text>
                </view>
                <!-- 学习信息 -->
                <view class="font-xs flex items-center">
                    <text class="ml-[15rpx] text-muted"
                        >{{ courseData.study_num || 0 }}人已学习｜共{{
                            courseData.course_num
                        }}讲</text
                    >
                </view>
            </view>
            <view class="flex col-start mt-[20rpx]">
                <!-- 课程名称 -->
                <view class="text_hidden align-middle">
                    <text
                        class="tags"
                        :class="{
                            'bg-[#E1F6F3] text-[#19D1B6]': courseData.type == 1,
                            'bg-[#EBEAFF] text-[#2171FF]': courseData.type == 2,
                            'bg-[#E4EEFF] text-[#817AFF]': courseData.type == 3
                        }"
                        >{{ getCourseType(courseData.type) }}</text
                    >
                    <text class="text-xl font-medium flex-1 break-all ml-[10rpx]">{{
                        courseData.name
                    }}</text>
                </view>
                <!-- 分享 -->
                <!-- #ifdef MP-WEIXIN-->
                <!-- <button class="btn flex" open-type="share">
                    <view class="share flex items-center">
                        <image src="/static/images/icon_share.png"></image>
                        <view class="text-xs lighter flex">分享</view>
                    </view>
                </button> -->
                <!-- #endif -->
                <!-- #ifdef H5 -->
                <!-- <button class="btn flex" @click="showSharePop">
                    <view class="share flex items-center">
                        <image src="/static/images/icon_share.png"></image>
                        <view class="text-xs lighter flex">分享</view>
                    </view>
                </button> -->
                <!-- #endif -->
                <!--分享-->
            </view>
            <!-- 介绍 -->
            <view class="text-muted mt-[18rpx] font-sm break-words">
                {{ courseData.synopsis }}
            </view>
        </view>

        <distributeCom :distribute-data="courseData.distribution"></distributeCom>

        <couponCom ref="couponComRef" :course-id="courseData.id"></couponCom>

        <!-- 课程介绍 ｜｜ 课程目录 ｜｜ 课程评价 -->
        <view class="course_detail">
            <tabs
                :current="active"
                fontSize="32"
                :top="0"
                isFixed
                height="100"
                bar-width="60"
                inactiveColor="#888888"
                :tabsStyle="{ 'border-bottom': '1px solid #F6F6F6' }"
                :barStyle="{ bottom: '-2rpx' }"
                barHeight="6"
            >
                <tab name="课程介绍" :i="0" :index="active">
                    <course-synopsis
                        @handleMeterialIsOpen="handleMeterialIsOpen"
                        :courseData="courseData"
                    ></course-synopsis>
                </tab>
                <tab name="课程目录" :i="1" :index="active" v-if="courseData.catalogue_list.length">
                    <course-catalogue
                        :list="courseData.catalogue_list"
                        :status="courseData.course_status"
                        :type="courseData.type"
                        @refresh="handleCateRefresh"
                    ></course-catalogue>
                </tab>
                <tab name="学员评价" :i="2" :index="active" v-if="courseData.comment.length">
                    <evaluate-card :list="courseData.comment"></evaluate-card>
                </tab>
            </tabs>
        </view>
        <!-- Main End -->

        <!-- Footer Start -->
        <view class="footer">
            <view class="flex text-center">
                <view class="footer--item text-center" @click="goHome">
                    <image src="/static/images/icon_home.png"></image>
                    <view>首页</view>
                </view>
                <view
                    class="footer--item text-center"
                    @click="handleCollection(courseData.is_collect)"
                >
                    <image
                        :src="
                            courseData.is_collect
                                ? '/static/images/icon_collection_s.png'
                                : '/static/images/icon_collection.png'
                        "
                    ></image>
                    <view>收藏</view>
                </view>
                <template
                    v-if="
                        courseData.course_status === 1 && (!platform || courseData.is_buy_btn == 1)
                    "
                >
                    <u-button
                        @click="handleAddCourse"
                        class="ml-[20rpx] flex-1"
                        :ripple="true"
                        shape="circle"
                        :hair-line="false"
                        type="primary"
                        >加入课程</u-button
                    >
                </template>
                <template
                    v-if="
                        courseData.course_status === 2 && (!platform || courseData.is_buy_btn == 1)
                    "
                >
                    <u-button
                        @click="handleOrder"
                        class="ml-[20rpx] flex-1"
                        :ripple="true"
                        shape="circle"
                        :hair-line="false"
                        type="primary"
                        >购买课程</u-button
                    >
                </template>
                <template
                    v-if="
                        courseData.course_status === 3 && (!platform || courseData.is_buy_btn == 1)
                    "
                >
                    <u-button
                        @click="goStudyCourse"
                        class="ml-[20rpx] flex-1"
                        :ripple="true"
                        shape="circle"
                        :hair-line="false"
                        type="primary"
                        >去学习</u-button
                    >
                </template>
                <template v-if="platform && courseData.is_buy_btn == 2">
                    <u-button
                        class="ml-[20rpx] flex-1"
                        :ripple="true"
                        shape="circle"
                        :hair-line="false"
                        type="gary"
                        disabled
                        >{{ courseData.is_buy_btn_desc }}</u-button
                    >
                </template>
            </view>
        </view>
        <!-- Footer End -->
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
    <!-- empty End -->

    <!-- 课件下载弹窗 Start -->
    <u-popup v-model="isDownload" mode="center" closeable bgColor="none" close-icon-color="#FFFFFF">
        <view class="download-container" v-if="courseData.meterial">
            <view class="download-header">
                <view class="p-[30rpx] text-white text-lg font-normal">课件下载</view>
                <image src="/static/images/icon_download.png" class="mt-[60rpx]"></image>
            </view>
            <view class="download-main bg-white">
                <view class="download-main--link flex col-start pt-[40rpx]">
                    <text>链接：</text>
                    <view class="line-4">
                        {{ courseData.meterial.link }}
                    </view>
                </view>
                <view class="mt-[30rpx]">密码：{{ courseData.meterial.code }}</view>
                <view class="copy-btn">
                    <u-button @click="handleMeterial" :plain="true" shape="circle" type="primary"
                        >一键复制</u-button
                    >
                </view>
            </view>
        </view>
    </u-popup>
    <!-- 课件下载弹窗 End -->

    <!-- 加入课程弹窗 Start -->
    <u-popup v-model="addCourse" mode="center" borderRadius="14" :mask-close-able="false">
        <view class="course-pop text-center">
            <view class="text-2xl font-medium normal">添加成功</view>
            <view class="text-lg normal mt-[16rpx] pb-[20rpx]"> 可在【我的课程】进行查看 </view>
            <view class="m-t-60">
                <u-button
                    @click="addCourse = false"
                    :ripple="true"
                    shape="circle"
                    :hair-line="false"
                    type="primary"
                    >好的，我知道了</u-button
                >
            </view>
        </view>
    </u-popup>
    <!-- 加入课程弹窗 End -->

    <!-- 购买课程弹窗 Start -->
    <u-popup v-model="buyCourse" mode="center" borderRadius="14" :mask-close-able="false">
        <view class="course-pop text-center">
            <view class="font-lg font-medium normal pt-[45px] pb-[40px]"
                >抱歉，您还未购买过该课程</view
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
    </u-popup>
    <!-- 购买课程弹窗 End -->

    <!-- 骨架屏 -->
    <skeleton v-show="loading"></skeleton>
</template>

<script setup lang="ts">
import { ref, computed, shallowRef, reactive } from 'vue'
import { onLoad, onShareAppMessage, onShow } from '@dcloudio/uni-app'
import AdSwiper from '@/components/ad-swiper/index.vue'
import Price from '@/components/price/index.vue'
import CourseSynopsis from './components/synopsis.vue'
import CourseCatalogue from '@/components/catalogue/index.vue'
import EvaluateCard from './components/evaluate-card.vue'
import skeleton from './components/skeleton.vue'
import couponCom from './components/couponCom.vue'
import distributeCom from './components/distributeCom.vue'
import { apiCourseDetail, apiCourseCatalogue, apiCourseStudy } from '@/api/goods'
import { apiCourseCollection } from '@/api/user'
import { apiPlaceOrder } from '@/api/order'
import { copy, isWeixinClient, getClient } from '@/utils/util'
import { useUserStore } from '@/stores/user'
import LVideo from '../study_course/components/l-video.vue'

interface courseDataObj {
    id: number
    name: string // 课程名称
    synopsis: string // 课程简介
    course_num: string // 单位描述
    course_image: string // 轮播图
    cover: string // 封面图
    sell_price: string // 价格
    line_price: string // 划线价
    comment: string // 评论
    content: string // 课程详情
    fee_type: number // 是否免费
    is_buy: boolean // 是否已购买
    is_collect: boolean // 是否已收藏
    study_num: number // 学习人数
    catalogue_list: Array<any> // 目录列表
    teacher: any // 讲师
    type: number // 课程类型
    meterial: any // 课程资料
    is_buy_btn: number // 1-允许购买 2-不允许购买
    is_buy_btn_desc: string
    course_status: number
    distribution: any //分销数据
}
interface Icatalog {
    content: string
    id: number
    course_id: number
    duration: number
}

const courseData = ref<courseDataObj>({
    id: 0,
    name: '',
    synopsis: '',
    course_num: '',
    course_image: '',
    cover: '',
    sell_price: '',
    line_price: '',
    comment: '',
    content: '',
    fee_type: 0,
    is_buy: false,
    study_num: 0,
    is_collect: false,
    catalogue_list: [],
    teacher: {},
    type: 0,
    meterial: {},
    is_buy_btn: 1,
    is_buy_btn_desc: '',
    course_status: 0,
    distribution: {}
})

const catalog = reactive<Icatalog>({
    content: '',
    id: 0,
    course_id: 0,
    duration: 0
})

//优惠券弹框ref
const couponComRef = shallowRef()
// 是否已下架
const isEmpty = ref<boolean>(true)
// 课程ID
const courseId = ref<number | string>(0)
// 课件下载弹窗
const isDownload = ref<boolean>(false)
// 加入课程成功弹窗
const addCourse = ref<boolean>(false)
// 购买课程弹窗
const buyCourse = ref<boolean>(false)
// 课程目录｜｜评价｜｜介绍索引
const active = ref(0)
// 加载状态
const loading = ref<boolean>(true)

//是否登录
const userStore = useUserStore()
const isLogin = computed(() => userStore.token)

//判断是否为苹果设备
const platform = computed(() => uni.getSystemInfoSync().platform == 'ios' && getClient() == 1)

//获取课程类型
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

//获取课程详情
const initGoodaDetail = async (): Promise<void> => {
    try {
        courseData.value = await apiCourseDetail({ id: courseId.value })
        setTimeout(() => {
            loading.value = false
        }, 100)
    } catch (err) {
        isEmpty.value = false
        loading.value = false
    }
}

//课件下载弹框
const handleMeterialIsOpen = () => {
    if (!isLogin.value) return goPage('/pages/login/login')
    if (courseData.value.course_status == 3) {
        isDownload.value = true
    } else {
        buyCourse.value = true
    }
}

//课件下载
const handleMeterial = () => {
    if (courseData.value.meterial.is_link || isWeixinClient()) {
        copy(`链接：${courseData.value.meterial.link}\n密码：${courseData.value.meterial.code}`)
        return
    }
    // #ifdef MP-WEIXIN
    copy(`链接：${courseData.value.meterial.link}\n密码：${courseData.value.meterial.code}`)
    // #endif

    // #ifdef H5
    if (!isWeixinClient()) {
        const downloadTask = uni.downloadFile({
            url: courseData.value.meterial.content, //仅为示例，并非真实的资源
            success: (res) => {
                console.log(res)
            }
        })

        downloadTask.onProgressUpdate((res) => {
            console.log('下载进度', res)
        })
    }
    // #endif
}

//点击目录
const handleCateRefresh = (cateItem: any) => {
    if (cateItem.fee_type == 1 && courseData.value.course_status == 2) {
        if (platform.value && courseData.value.is_buy_btn == 2) {
            uni.showModal({
                title: '提示',
                content: '本设备不支持购买',
                showCancel: false
            })
            return
        }
        buyCourse.value = true
    } else {
        cateItem.type = courseData.value.type
        goPage(
            `/pages/study_course/index?course_id=${courseData.value.id}&cate_id=${cateItem.id}&type=${cateItem.type}`
        )
    }
}

//收藏
const handleCollection = async (collect: boolean | number | null): Promise<void> => {
    if (!isLogin.value) return goPage('/pages/login/login')
    await apiCourseCollection({ id: courseId.value, collect: collect ? 0 : 1 })
    initGoodaDetail()
}

//加入课程
const handleAddCourse = async (): Promise<void> => {
    if (!isLogin.value) return goPage('/pages/login/login')
    try {
        await apiCourseStudy({ id: courseId.value })
        addCourse.value = true
        initGoodaDetail()
    } catch (err) {
        console.log('加入课程=>', err)
    }
}
//下单
const handleOrder = async (): Promise<void> => {
    if (!isLogin.value) return goPage('/pages/login/login')
    try {
        const { order_id, type } = await apiPlaceOrder({ course_id: courseId.value })
        const params = {
            order_id,
            from: type
        }
        goPage(`/pages/order_buy/index?params=${JSON.stringify(params)}`)
    } catch (err) {
        throw new Error(err + '订单下单报错')
    }
}

//去学习
const goStudyCourse = () => {
    const course = courseData.value
    const noStudyIndex = course.catalogue_list.findIndex((item) => {
        return item.is_study == false
    })

    goPage(
        `/pages/study_course/index?course_id=${course.id}&cate_id=${
            course.catalogue_list[noStudyIndex == -1 ? 0 : noStudyIndex]?.id
        }&type=${course.type}`
    )
}

//跳转页面
const goPage = (url: string) => {
    uni.navigateTo({ url: url })
}

//返回首页
const goHome = () => {
    uni.navigateTo({ url: '/pages/index/index' })
}

onLoad((options) => {
    courseId.value = options?.id || 0
})

onShow(async () => {
    buyCourse.value = false
    await initGoodaDetail()
    await couponComRef.value.getCouponList()
})
</script>

<style lang="scss" scoped>
.btn {
    color: #333;
    text-align: center;
    border: none;
    border-radius: 0;
    background-color: transparent;
    padding-right: 0;
    .share {
        image {
            width: 34rpx;
            height: 34rpx;
        }
        width: 90rpx;
    }
}
.btn::after {
    border: none;
}
.course {
    padding-bottom: calc(env(safe-area-inset-bottom) + 120rpx);

    // 课程信息
    .course-info {
        padding: 20rpx;
        padding-top: 30rpx;
        .tags {
            font-size: 22rpx;
            padding: 2px 13rpx;
            border-radius: 6rpx;
            color: $color-primary;
            background-color: rgba($color-primary, 0.2);
        }
    }

    // 讲师信息
    .teacher-info {
        padding: 20rpx;
        margin-top: 24rpx;
        &--synopsis {
            width: 550rpx;
            color: #999;
        }
    }

    // 课件资料
    .course-materials {
        padding: 30rpx 20rpx;
        margin-top: 24rpx;
        &--info {
            padding: 20rpx 0;
            padding-top: 40rpx;
            image {
                width: 54rpx;
                height: 46rpx;
            }
            &--name {
                width: 500rpx;
                color: $color-text-deep;
                font-size: $font-size-lg;
            }
        }
    }

    // 课程详情
    .course_detail {
        margin-top: 24rpx;
        background-color: #ffffff;
    }
}

// 底部按钮
.footer {
    left: 0;
    bottom: 0;
    width: 100%;
    position: fixed;
    padding: 20rpx 30rpx;
    background-color: #ffffff;
    box-shadow: 2rpx 2rpx 22rpx rgba($color: #000000, $alpha: 0.1);
    padding-bottom: calc(env(safe-area-inset-bottom) + 20rpx);

    &--item {
        width: 94rpx;
        display: inline-block;
        image {
            width: 38rpx;
            height: 38rpx;
        }
        color: $color-text-light;
        font-size: $font-size-xs;
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

// 下载课件弹窗
.download-container {
    width: 640rpx;
    .download-header {
        height: 400rpx;
        text-align: center;
        border-radius: 14rpx 14rpx 0 0;
        background-color: $color-primary;
        image {
            width: 177rpx;
            height: 140rpx;
        }
    }
    .download-main {
        height: 413rpx;
        padding: 0 50rpx;
        position: relative;
        font-size: $font-size-md;
        border-radius: 0 0 14rpx 14rpx;
        &--link {
            text {
                white-space: nowrap;
            }
            view {
                width: 460rpx;
                word-break: break-all;
                word-break: break-word;
            }
        }
        .copy-btn {
            width: 540rpx;
            bottom: 30rpx;
            padding: 0 40rpx;
            position: absolute;
        }
    }
}

// 通用弹窗
.course-pop {
    width: 640rpx;
    padding: 70rpx 80rpx 40rpx 80rpx;
}
</style>
