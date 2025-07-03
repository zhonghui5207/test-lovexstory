<template>
    <view class="index-skeleton">
        <view class="search-skt base-bg base-br base-margin"></view>
        <view class="banner-skt base-bg base-br"></view>
        <view class="menus-skt flex flex-wrap">
            <view class="menuitem flex-col col-center" v-for="item in 10" :key="item">
                <view class="menuitem-icon base-bg"></view>
                <view class="menuitem-text base-bg"></view>
            </view>
        </view>
        <view class="line-skt base-bg"></view>
        <view class="special-skt flex justify-between">
            <view class="special-text base-bg"></view>
            <view class="special-icon base-bg"></view>
        </view>
        <view class="goods-skt flex justify-between col-top">
            <view class="goods-item base-bg base-br" v-for="item in 3" :key="item"></view>
        </view>
        <view class="line-skt base-bg"></view>
        <view class="goods-skt flex justify-between col-top">
            <view class="goods-item base-bg base-br" v-for="item in 3" :key="item"></view>
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

.index-skeleton {
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

    .search-skt {
        height: 64rpx;
        margin-top: 20rpx;
        border-radius: 36rpx;
    }

    .banner-skt {
        height: 320rpx;
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

    .special-skt {
        margin-top: 30rpx;
        padding: 0 20rpx;
        .special-text {
            width: 140rpx;
            height: 50rpx;
        }
        .special-icon {
            width: 70rpx;
            height: 40rpx;
        }
    }

    .goods-skt {
        padding: 32rpx 20rpx 0;
        height: 500rpx;

        .goods-item {
            width: 250rpx;
            height: 490rpx;
            position: relative;

            &::before {
                content: '';
                position: absolute;
                width: 160rpx;
                height: 32rpx;
                top: 240rpx;
                left: 0;
                border-radius: 4px;
                background: #f0f0f2;
            }
        }

        .goods-item:last-child {
            width: 150rpx;
        }
    }
}
</style>
