<template>
	<view class="skeleton">
        <view class="course-skt">
            <view class="course-title base-bg"></view>
            <view class="course-item flex" v-for="item in 2" :key="item">
                <view class="course-item-image base-bg"></view>
                <view class="course-item-info">
                    <view class="course-item-info--name base-bg"></view>
                    <view class="course-item-info--desc base-bg"></view>
                    <view class="course-item-info--price base-bg"></view>
                </view>
            </view>
        </view>
        
        <view class="course-skt m-t-40">
            <view class="course-title base-bg"></view>
            <view class="course-item flex" v-for="item in 2" :key="item">
                <view class="course-item-image base-bg"></view>
                <view class="course-item-info">
                    <view class="course-item-info--name base-bg"></view>
                    <view class="course-item-info--desc base-bg"></view>
                    <view class="course-item-info--price base-bg"></view>
                </view>
            </view>
        </view>
	</view>
</template>


<style lang="scss" scoped>
	@keyframes twinkle {
		0% {
			opacity: 1;
		}

		50% {
			opacity: .5;
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
		z-index: 999998;
		opacity: 1;
		width: 100vw;
		height: 100vh;
		background: #fff;
		box-sizing: border-box;
		transition: all .2s ease;
        
        .course-skt {
            margin-top: 30rpx;
            padding: 0 20rpx;
            .course-title {
                width: 200rpx;
                height: 60rpx;
            }
            .course-item {
                margin-top: 40rpx;
                .course-item-image {
                    width: 320rpx;
                    height: 194rpx;
                    border-radius: 10rpx;
                }
                .course-item-info {
                    margin-left: 20rpx;
                    &--name {
                        width: 343rpx;
                        height: 84rpx;
                    }
                    &--desc {
                        width: 275rpx;
                        height: 35rpx;
                        margin: 15rpx 0;
                    }
                    &--price {
                        width: 80rpx;
                        height: 40rpx;
                    }
                }
            }
		}
	}
</style>
