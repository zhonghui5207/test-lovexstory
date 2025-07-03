<!-- 会员中心 -->
<template>
    <view class="member-center">
        <view class="member-center__header">
            <!-- 导航条 -->
            <u-sticky offset-top="0" h5-nav-height="0" bg-color="transparent">
                <u-navbar
                    :is-back="true"
                    title="等级中心"
                    :title-bold="true"
                    :is-fixed="false"
                    :border-bottom="false"
                    :background="{ background: 'transparent' }"
                    title-color="#fff"
                ></u-navbar>
            </u-sticky>

            <!-- 会员轮播卡片 -->
            <view class="swiper-container pt-[30rpx]">
                <swiper
                    class="swiper"
                    style="height: 320rpx"
                    previous-margin="60rpx"
                    next-margin="25rpx"
                    display-multiple-items="1"
                    :current="current"
                    @change="bindchange"
                >
                    <swiper-item
                        v-for="(item, index) in userLevel"
                        :key="index"
                        class="box-border pr-[35rpx]"
                    >
                        <view
                            class="level_card text-white"
                            :style="'background-image: url(' + item.level_bg + ');'"
                        >
                            <view class="level_tag" v-if="item.is_level"> 当前等级 </view>
                            <!-- 等级名称 -->
                            <view class="mt-[30rpx] text-[46rpx] font-medium">
                                {{ item.name }}
                            </view>
                            <view class="mt-[20rpx] text-[26rpx] font-medium">
                                自购佣金：{{ item.self_ratio }}%
                            </view>
                            <view class="mt-[20rpx] text-[26rpx] font-medium">
                                一级佣金：{{ item.first_ratio }}%
                            </view>
                            <view class="mt-[20rpx] text-[26rpx] font-medium">
                                二级佣金：{{ item.second_ratio }}%
                            </view>
                        </view>
                    </swiper-item>
                </swiper>
            </view>
        </view>

        <!-- 升级条件 -->
        <view class="member-center__content">
            <view class="title flex items-center">
                <text class="text-lg font-medium">分销任务</text>
                <text class="text-sm text-muted ml-[14rpx]">{{
                    userLevel[current]?.upgrade_conditions_desc
                }}</text>
            </view>

            <view class="condition_item" v-if="userLevel[current]?.single_amount_show">
                <view class="flex items-center">
                    <image class="w-[84rpx] h-[84rpx]" :src="imglists[0]"></image>
                    <view class="ml-[16rpx] text-[#222222]">
                        单笔消费金额满{{ userLevel[current]?.single_amount }}元
                        <view class="mt-[6rpx] text-muted text-xs">
                            {{ userLevel[current]?.single_amount_completed_num }}/1
                        </view>
                    </view>
                </view>
                <view v-if="userLevel[current]?.single_amount_completed == 1" class="finish"
                    >已完成</view
                >
                <navigator class="btn" hover-class="none" url="/pages/index/index" v-else>
                    去完成
                </navigator>
            </view>
            <view class="condition_item" v-if="userLevel[current]?.total_amount_show">
                <view class="flex items-center">
                    <image class="w-[84rpx] h-[84rpx]" :src="imglists[1]"></image>
                    <view class="ml-[16rpx] text-[#222222]">
                        累计消费金额{{ userLevel[current]?.total_amount }}元
                        <view class="mt-[6rpx] text-muted text-xs">
                            {{ userLevel[current]?.total_amount_completed_num }}/{{
                                userLevel[current]?.total_amount
                            }}
                        </view>
                    </view>
                </view>
                <view v-if="userLevel[current]?.total_amount_completed == 1" class="finish"
                    >已完成</view
                >
                <navigator class="btn" hover-class="none" url="/pages/index/index" v-else>
                    去完成
                </navigator>
            </view>
            <view class="condition_item" v-if="userLevel[current]?.consume_num_show">
                <view class="flex items-center">
                    <image class="w-[84rpx] h-[84rpx]" :src="imglists[2]"></image>
                    <view class="ml-[16rpx] text-[#222222]">
                        累计消费次数{{ userLevel[current]?.consume_num }}次
                        <view class="mt-[6rpx] text-muted text-xs">
                            {{ userLevel[current]?.consume_num_completed_num }}/{{
                                userLevel[current]?.consume_num
                            }}
                        </view>
                    </view>
                </view>
                <view v-if="userLevel[current]?.consume_num_completed == 1" class="finish"
                    >已完成</view
                >
                <navigator class="btn" hover-class="none" url="/pages/index/index" v-else>
                    去完成
                </navigator>
            </view>
            <view class="condition_item" v-if="userLevel[current]?.settled_commission_show">
                <view class="flex items-center">
                    <image class="w-[84rpx] h-[84rpx]" :src="imglists[3]"></image>
                    <view class="ml-[16rpx] text-[#222222]">
                        累计结算佣金收入{{ userLevel[current]?.settled_commission }}元
                        <view class="mt-[6rpx] text-muted text-xs">
                            {{ userLevel[current]?.settled_commission_completed_num }}/{{
                                userLevel[current]?.settled_commission
                            }}
                        </view>
                    </view>
                </view>
                <view v-if="userLevel[current]?.settled_commission_completed == 1" class="finish"
                    >已完成</view
                >
                <navigator class="btn" hover-class="none" url="/pages/index/index" v-else>
                    去完成
                </navigator>
            </view>
        </view>
    </view>
</template>

<script lang="ts" setup>
import { getLevelList } from '@/api/distribution'
import { computed, reactive, ref } from 'vue'
import { onLoad } from '@dcloudio/uni-app'

import condition1 from '@/bundle/static/vip/condition1.png'
import condition2 from '@/bundle/static/vip/condition2.png'
import condition3 from '@/bundle/static/vip/condition3.png'
import condition0 from '@/bundle/static/vip/condition0.png'
import { watch } from 'vue'

const userLevel = ref<any>([])
const imglists = [condition0, condition1, condition2, condition3]

const current = ref(0)
// 等级卡片切换时等级升级条件改变
const bindchange = (e: any) => {
    current.value = e.detail.current
}

// 获取会员等级信息
const getUserLevel = async () => {
    try {
        const result = await getLevelList()
        userLevel.value = result

        // 首次进入，根据用户等级 匹配对应 等级卡片块
        userLevel.value.forEach((item: any, index: number) => {
            if (item.is_level == 2) {
                current.value = index
            }
        })
    } catch (error) {
        console.log('error', error)
    }
}
onLoad(() => {
    getUserLevel()
})
</script>

<style lang="scss">
.member-center {
    &__header {
        padding-bottom: 60rpx;
        background-image: url('../../static/vip/vip_grade_bg.png');
        background-size: 100% 100%;

        .swiper-container {
            .level_card {
                padding: 24rpx 30rpx;
                border-radius: 20rpx;
                height: 320rpx;
                position: relative;
                background-size: 100% 100%;

                .level_tag {
                    position: absolute;
                    top: 0;
                    right: 0;
                    line-height: 50rpx;
                    background-color: rgba(0, 0, 0, 0.3);
                    border-top-right-radius: 20rpx;
                    border-bottom-left-radius: 20rpx;
                    height: 50rpx;
                    padding: 0 28rpx;
                }
            }
        }
    }

    &__content {
        padding: 44rpx 20rpx 0 20rpx;

        .condition_item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 24rpx;
            padding: 24rpx 30rpx;
            border-radius: 14rpx;
            background-color: #ffffff;

            .btn {
                width: 138rpx;
                height: 52rpx;
                text-align: center;
                line-height: 52rpx;
                border: 1px solid #ff2c3c;
                border-radius: 30px;
                color: #ff2c3c;
            }

            .finish {
                width: 138rpx;
                height: 52rpx;
                text-align: center;
                line-height: 52rpx;
                border: 1px solid #a4adb3;
                border-radius: 30px;
                color: #a4adb3;
            }
        }
    }
}
</style>
