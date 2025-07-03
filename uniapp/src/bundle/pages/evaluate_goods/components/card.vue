<template>

    <view class="card">
        <!-- Header Start -->
        <view class="card--header flex row-between col-start">
            <view class="flex">
                <u-image :src="user.avatar" width="80" height="80" border-radius="50%"></u-image>
                <view class="m-l-20">
                    <view class="font-md normal f-w-500">{{ user.nickname }}</view>
                    <view class="muted font-xs m-t-10">{{ create_time }}</view>
                </view>
            </view>
            <view class="flex">
                <u-rate :count="5" v-model="service_comment" size="28" inactive-icon="star-fill"></u-rate>
                <view class="m-l-20 lighter font-xs">
                    <text v-if="service_comment == 5">非常好</text>
                    <text v-if="service_comment == 4">好</text>
                    <text v-if="service_comment == 3">一般</text>
                    <text v-if="service_comment == 2">差</text>
                    <text v-if="service_comment == 1">非常差</text>
                </view>
            </view>
        </view>
        
        <!-- Main Start -->
        <view class="card--main">
            <view class="content">
                {{ comment }}
            </view>
            
            <view class="flex flex-wrap">
                <block v-for="(item3, index) in goods_comment_image">
                    <view class="m-t-10" :class="{'m-r-10': (index+1)%3!=0}" @click.stop="previewImage(index)">
                        <u-image :src="item3.uri" width="210" height="210" ></u-image>
                    </view>
                </block>
            </view>
            
            <view class="reply normal font-md m-t-20" v-if="reply">
                <text class="f-w-500">商家回复: </text>
                <text>
                    {{ reply }}
                </text>
            </view>
        </view>
    </view>
</template>

<script lang="ts" setup>
    import { ref, reactive,  watchEffect } from "vue"
    import Price from "@/components/price/index.vue"

    /** Props Start **/
    const props = withDefaults(defineProps < {
        goods_id: string | number
        comment: string | null
        goods_comment_image: string | null
        reply: string | null
        create_time: string | null
        service_comment: string | number
        user: any
    } > (), {
        goods_id: '',
        comment: '',
        goods_comment_image: '',
        reply: '',
        create_time: '',
        service_comment: '',
        user: {
            avatar: '',
            nickname: ''
        }
    })
    /** Props End **/
    

    /** Methods Start **/    
    // 查看评价图片
    const previewImage = (current: number) => {
        uni.previewImage({
            current,
            urls: props.goods_comment_image.map(el => el.uri)
        })
    }
    /** Methods End **/
</script>

<style lang="scss" scoped>
    .card {
        border-radius: 14rpx;
        background-color: $color-white;
        margin: 20rpx 20rpx 0 20rpx;
        padding: 30rpx;
        &--header {
            width: 100%;
        }
        &--main {
            .content {
                padding: 20rpx 0;
                font-size: $font-size-md;
                color: $color-text-deep;
            }
            .reply {
                padding: 24rpx 20rpx;
                background-color: $color-bg-light;
                border-radius: $border-radius-large;
            }
        }
    }
</style>
