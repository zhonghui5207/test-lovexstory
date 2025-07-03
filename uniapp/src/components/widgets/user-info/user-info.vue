<template>
    <view class="user-info flex px-[50rpx] justify-between py-[50rpx]">
        <view
            v-if="isLogin"
            class="flex items-center"
            @click="navigateTo('/pages/user_data/user_data')"
        >
            <u-avatar :src="user.avatar" :size="120"></u-avatar>
            <view class="text-black ml-[20rpx]">
                <view class="text-2xl">{{ user.nickname }}</view>
                <view class="text-xs mt-[18rpx]" @click.stop="copy(user.sn)">
                    学号：{{ user.sn }}
                </view>
            </view>
        </view>
        <navigator v-else class="flex items-center" hover-class="none" url="/pages/login/login">
            <u-avatar src="/static/images/user/default_avatar.png" :size="120"></u-avatar>
            <view class="text-black text-3xl ml-[20rpx]">未登录</view>
        </navigator>
        <navigator v-if="isLogin" hover-class="none" url="/pages/user_set/user_set">
            <u-icon name="setting" color="#434343" :size="48"></u-icon>
        </navigator>
    </view>
</template>
<script lang="ts" setup>
import { useCopy } from '@/hooks/useCopy'

const props = defineProps({
    content: {
        type: Object,
        default: () => ({})
    },
    styles: {
        type: Object,
        default: () => ({})
    },
    user: {
        type: Object,
        default: () => ({})
    },
    isLogin: {
        type: Boolean
    }
})
const { copy } = useCopy()
const navigateTo = (url: string) => {
    uni.navigateTo({
        url
    })
}
</script>

<style lang="scss" scoped>
.user-info {
    background: linear-gradient(180deg, #f0f7ff 0%, rgba(255, 255, 255, 0) 100%);
    height: 230rpx;
    background-position: bottom;
    background-size: 100% auto;
}
.user {
    padding: 0 24rpx;
    background: linear-gradient(180deg, #f0f7ff 0%, rgba(255, 255, 255, 0) 130rpx, transparent 0);

    .card {
        border-radius: 14rpx;
        background-color: #ffffff;
    }

    // 用户信息
    &--info {
        padding: 50rpx 0 24rpx 0;
        .user-name {
            .copy {
                text-decoration: underline;
            }
        }
        image {
            width: 48rpx;
            height: 48rpx;
        }
    }

    // 订单信息
    .order-info {
        padding: 24rpx;
        padding-bottom: 38rpx;
        &--main {
            .order-menu-item {
                flex: auto;
                text-align: center;
                position: relative;
                image {
                    width: 70rpx;
                    height: 70rpx;
                }
                &--text {
                    color: $color-text-deep;
                    font-size: $font-size-md;
                    font-weight: 500;
                    margin-bottom: 10rpx;
                }
            }
        }
    }
}
</style>
