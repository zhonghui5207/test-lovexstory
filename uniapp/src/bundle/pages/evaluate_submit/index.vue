<template>
    <!-- Header Start -->
    <view class="card flex">
        <u-image :src="evaluateInfo?.course_snap?.cover" width="250" height="160"></u-image>
        <view class="ml-[20rpx]">
            <view class="normal font-normal text-xl">{{ evaluateInfo?.course_snap?.name }}</view>
            <view class="mt-[20rpx]">
                <price
                    :content="evaluateInfo?.course_snap?.sell_price"
                    mainSize="36rpx"
                    minorSize="34rpx"
                    fontWeight="500"
                ></price>
            </view>
        </view>
    </view>
    <!-- Header End -->

    <!-- Main Start -->
    <view class="card mt-[20rpx]">
        <view class="flex">
            <text class="normal font-normal text-md mr-[20rpx]">课程评分</text>
            <u-rate
                :count="5"
                v-model="formData.score"
                :min-count="1"
                inactive-icon="star-fill"
                size="34"
            ></u-rate>
            <view class="m-l-20 lighter font-xs">
                <text v-if="formData.score == 5">非常好</text>
                <text v-if="formData.score == 4">好</text>
                <text v-if="formData.score == 3">一般</text>
                <text v-if="formData.score == 2">差</text>
                <text v-if="formData.score == 1">非常差</text>
            </view>
        </view>

        <view class="content mt-[30rpx]">
            <u-input
                v-model="formData.comment"
                type="textarea"
                placeholder="请输入评价内容"
                height="200"
            />
        </view>

        <view class="mt-[30rpx]">
            <uploader v-model="formData.image" :maxUpload="6" image-fit="aspectFill" />
        </view>
    </view>
    <!-- Main End -->

    <!-- Footer Start -->
    <view class="footer mt-[30rpx]">
        <u-button @click="onSubmit" :ripple="true" type="primary" :hair-line="false" shape="circle"
            >提交</u-button
        >
    </view>
    <!-- Footer End -->
</template>

<script lang="ts" setup>
import { ref, reactive, watchEffect, shallowRef } from 'vue'
import { onLoad } from '@dcloudio/uni-app'
import { apiEvaluateGoodsInfo, apiEvaluateAdd } from '@/api/user'
import Price from '@/components/price/index.vue'
import Uploader from '@/components/uploader/index.vue'
import { toast } from '@/utils/util'

/** Interface Start **/
interface FormDataObj {
    id: number
    score: number | string
    comment: string | number
    image: Array<string | null>
}
/** Interface End **/

/** Data Start **/
const evaluateInfo = ref<EvaluateInfoObj | null>({
    course_id: '',
    course_snap: {},
    id: '',
    order_id: ''
})
const formData = ref<FormDataObj | null>({
    id: 0,
    score: 0,
    comment: '',
    image: []
})
/** Data End **/

/** Methods Start **/
/**
 * @return { Promise }
 * @description 提交服务评价
 */
const onSubmit = async (): Promise<void> => {
    try {
        if (formData.value.score === 0) return toast('请选择课程评分')
        await apiEvaluateAdd({ ...formData.value })
        await uni.showToast({
            title: '提交成功！',
            duration: 5000,
            icon: 'success'
        })
        setTimeout(() => {
            uni.navigateBack()
        }, 1000)
    } catch (error) {
        console.log('提交评价: ', error)
    }
}

/**
 * @return { Promise }
 * @description 获取服务评价商品信息
 */
const initEvaluateGoodsInfo = async (): Promise<void> => {
    try {
        evaluateInfo.value = await apiEvaluateGoodsInfo({ id: formData.value.id })
    } catch (error) {
        console.log('获取评价: ', error)
    }
}
/** Methods End **/

/** Life Cycle Start **/
onLoad((options) => {
    console.log(options)
    formData.value.id = options.id || 0
    initEvaluateGoodsInfo()
})
/** Life Cycle End **/
</script>

<style lang="scss">
.card {
    border-radius: 14rpx;
    background-color: $color-white;
    margin: 20rpx 20rpx 0 20rpx;
    padding: 30rpx;

    .content {
        padding: 0 24rpx;
        border-radius: 14rpx;
        background-color: $color-bg-light;
    }
}

.footer {
    padding: 0 30rpx;
}
</style>
