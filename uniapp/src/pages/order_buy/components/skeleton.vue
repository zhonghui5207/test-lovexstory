<template>
    <view class="skeleton">
        <view class="payinfo-skt">
            <view class="money base-bg"></view>
            <view class="time base-bg"></view>
        </view>
        <view class="methods-skt base-margin">
            <view class="methods--title base-bg"></view>
            <view class="methods--item flex-col col-center" v-for="item in 2" :key="item">
                <view class="methods--item--text base-bg"></view>
                <view class="methods--item--icon base-bg"></view>
            </view>
        </view>
        <view class="line-skt base-bg"></view>
        <view class="special-skt flex row-between">
            <view class="special-text base-bg"></view>
            <view class="special-icon base-bg"></view>
        </view>
    </view>
</template>

<style lang="scss" scoped>
@keyframes twinkle {
    0% {
        opacity: 1;
    }

    50% {
        opacity: 0.5;
    }

    100% {
        opacity: 1;
    }
}

.base-bg {
    background: #f0f0f2;
    animation: twinkle 2s ease infinite forwards;
}

.base-margin {
    margin: 0 20rpx 20rpx;
}

.base-br {
    border-radius: 8rpx;
}

.skeleton {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 99999;
    opacity: 1;
    width: 100vw;
    height: 100vh;
    background: #fff;
    box-sizing: border-box;
    transition: all 0.2s ease;

    .payinfo-skt {
        height: 220rpx;
        padding-top: 40rpx;
        .money {
            margin: 0 auto;
            width: 100rpx;
            height: 60rpx;
        }
        .time {
            margin: 0 auto;
            width: 290rpx;
            height: 36rpx;
            margin-top: 13rpx;
        }
    }

    .methods-skt {
        &--title {
            margin-top: 20rpx;
            width: 110rpx;
            height: 40rpx;
        }
        // this
    }

    .line-skt {
        height: 20rpx;
    }

    .menus-skt {
        padding: 6rpx 24rpx 0;
        margin-bottom: 48rpx;

        .menuitem {
            width: 20%;
            margin-top: 26rpx;

            .menuitem-icon {
                width: 84rpx;
                height: 84rpx;
                border-radius: 50%;
            }

            .menuitem-text {
                width: 84rpx;
                height: 28rpx;
                border-radius: 8rpx;
                margin-top: 10rpx;
            }
        }
    }
}
</style>
