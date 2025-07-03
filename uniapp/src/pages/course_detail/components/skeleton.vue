<template>
	<view class="skeleton">
		<view class="banner-skt base-bg base-br"></view>
		<view class="info-skt">
			<view class="course-header flex row-between">
				<view class="course-header--name base-bg"></view>
				<view class="course-header--share base-bg"></view>
			</view>
            <view class="line-skt base-bg"></view>
            <view class="line-skt base-bg"></view>
            <view class="line-skt base-bg"></view>
            <view class="line-skt base-bg"></view>
            <view class="course-footer flex row-between">
            	<view class="course-footer--price base-bg"></view>
            	<view class="course-footer--desc base-bg"></view>
            </view>
		</view>
		<view class="line-skt base-bg"></view>
        <view class="teacher-skt flex">
            <view class="teacher-avatar base-bg"></view>
            <view class="">
                <view class="teacher-name base-bg"></view>
                <view class="teacher-desc base-bg"></view>
            </view>
        </view>
        <view class="line-skt base-bg"></view>
        <view class="meterial-skt">
            <view class="meterial-title base-bg"></view>
            <view class="meterial-info flex">
                <view class="meterial-info--icon base-bg"></view>
                <view class="meterial-info--text base-bg"></view>
            </view>
        </view>
        <view class="line-skt base-bg"></view>
		<view class="goods-skt base-bg"></view>
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
		z-index: 99999;
		opacity: 1;
		width: 100vw;
		height: 100vh;
		background: #fff;
		box-sizing: border-box;
		transition: all .2s ease;

		.banner-skt {
			height: 400rpx;
		}

		.line-skt {
			height: 20rpx;
		}

		.info-skt {
			padding: 30rpx;
			.course-header {
                margin-bottom: 20rpx;
				&--name {
                    height: 80rpx;
                    width: 600rpx;
                }
                &--share {
                    height: 80rpx;
                    width: 50rpx;
                }
			}
            .course-footer {
                margin-top: 20rpx;
                &--price {
                    height: 70rpx;
                    width: 300rpx;
                }
                &--desc {
                    height: 70rpx;
                    width: 450rpx;
                }
            }
		}
        
        .teacher-skt {
            padding: 30rpx;
            .teacher-avatar {
                width: 100rpx;
                height: 100rpx;
                border-radius: 50%;
                margin-right: 20rpx;
            }
            .teacher-name {
                width: 140rpx;
                height: 50rpx;
            }
            .teacher-desc {
                width: 440rpx;
                height: 40rpx;
                margin-top: 10rpx;
            }
        }
        
        .meterial-skt {
            padding: 30rpx;
            .meterial-title {
                width: 140rpx;
                height: 50rpx;
            }
            .meterial-info {
                margin-top: 30rpx;
                padding-bottom: 20rpx;
                &--icon {
                    width: 60rpx;
                    height: 60rpx;
                    margin-right: 30rpx;
                }
                &--text {
                    width: 250rpx;
                    height: 45rpx;
                }
            }
        }

		.goods-skt {
			padding: 32rpx 20rpx 0;
			height: 500rpx;
		}
	}
</style>
