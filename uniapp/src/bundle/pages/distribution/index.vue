<template>
    <scroll-view scroll-y class="scrollView">
        <view class="w-[full] relative top">
            <view
                class="top absolute top-0 px-[30rpx] w-full bg-cover bg-center h-[270rpx]"
                :style="{ backgroundImage: `url(${myData?.apply_config.apply_image})` }"
            >
                <view class="user flex py-[40rpx]">
                    <image
                        class="w-[110rpx] h-[110rpx] rounded-full bg-white"
                        :src="myData?.user.avatar"
                    ></image>
                    <view class="flex flex-col justify-between ml-[30rpx]">
                        <view class="">
                            <span class="text-[36rpx] text-white">{{ myData?.user.nickname }}</span>
                        </view>
                        <view class="text-white flex" v-if="myData?.is_distributon">
                            <view>上级分销商：{{ myData?.first_leader_name || '无' }}</view>
                            <view
                                @click="showInvitePop"
                                class="ml-2"
                                style="border-bottom: 1px solid white"
                                v-if="!myData?.first_leader_name"
                                >填写</view
                            >
                        </view>
                    </view>
                    <view
                        class="text-[22rpx] text-white absolute bottom-0 left-[20rpx] right-[20rpx] h-[100rpx] level text-[#C75629] rounded-t-lg px-[30rpx]"
                        v-if="myData?.is_distributon"
                    >
                        <view class="flex items-center h-full justify-between">
                            <view class="flex items-center">
                                <image
                                    class="w-[50rpx] h-[50rpx]"
                                    :src="myData.distribution_level.level_ico"
                                ></image>
                                <span class="text-[32rpx] ml-[15rpx]">
                                    {{ myData.distribution_level.name }}
                                </span>
                            </view>
                            <span @click="handleLeval"
                                >查看 <u-icon name="arrow-right" size="22rpx"
                            /></span>
                        </view>
                    </view>
                </view>
                <block v-if="myData?.is_distributon == 0">
                    <apply
                        @submit-apply="submitApply"
                        v-if="!myData?.is_distributon && !isApply"
                        :isShowProtocol="myData.apply_config.is_apply_protocol"
                    ></apply>
                    <result
                        @re-apply="isApply = false"
                        :data="applyDetail"
                        v-if="!myData?.is_distributon && isApply"
                    ></result>
                </block>
            </view>
        </view>
        <view class="mt-[20rpx]">
            <isDistribution :data="myData" v-if="myData?.is_distributon"></isDistribution>
        </view>
        <invitePop ref="invitePopRef" @success="getMyData"></invitePop>
    </scroll-view>
</template>

<script setup lang="ts">
import isDistribution from './components/isDistribution.vue'
import apply from './components/apply.vue'
import result from './components/result.vue'
import invitePop from './components/invitePop.vue'
import {
    apiDistributeData,
    apiApplyDetail,
    type IMyData,
    type IApplyDetail
} from '@/api/distribution'
import { onLoad } from '@dcloudio/uni-app'
import { computed, ref, shallowRef, watch } from 'vue'

const myData = ref<IMyData>()

const applyDetail = ref<IApplyDetail>()

//弹框ref
const invitePopRef = shallowRef()

//是/否已提交申请
const isApply = ref<boolean>()

//获取分销商数据
const getMyData = async () => {
    myData.value = await apiDistributeData()
}

//获取申请数据
const getApplyData = async () => {
    applyDetail.value = await apiApplyDetail()
}

//是/否已提交申请
const handleIsApply = () => {
    isApply.value = Object.keys(applyDetail.value as IApplyDetail).length > 0
}

//跳转至分销等级页面
const handleLeval = () => {
    uni.$u.route('/bundle/pages/member_center/index')
}

//邀请弹框
const showInvitePop = () => {
    invitePopRef.value.open()
}

watch(
    () => isApply.value,
    () => {
        getApplyData()
    }
)

//提交申请
const submitApply = () => {
    isApply.value = true
}

onLoad(async () => {
    await getMyData()
    // await getApplyData()
    handleIsApply()
})
</script>

<style scoped lang="scss">
.scrollView {
    height: calc(100vh - env(safe-area-inset-bottom));
}
.top {
    height: 270rpx;
    background-position: top;
    background: url(@/static/images/user/my_topbg.png) no-repeat,
        linear-gradient(90deg, var(--theme-color) 0, var(--theme-dark-color) 100%);
    background-size: 100% auto;
}
.level {
    background: linear-gradient(
        134.09deg,
        #f9e3c2 0%,
        #fff1dd 8.68%,
        #f4cba8 35.74%,
        #ffc9ad 53.53%,
        #ffb683 100%
    );
}
</style>
