<template>
    <view class="h-full flex flex-col">
        <view class="flex-1 min-h-0">
            <scroll-view class="h-full" scroll-y="true">
                <view class="bg-white p-[30rpx]">
                    <view>
                        {{ data.stem }}
                    </view>
                    <view class="mt-[30rpx]">
                        <image
                            v-if="data.illustration"
                            mode="widthFix"
                            class="w-full rounded-lg"
                            :src="data.illustration"
                        ></image>
                    </view>
                    <view class="option mt-[60rpx]">
                        <view
                            class="optionItem flex items-center mb-[40rpx]"
                            v-for="(item, index) in data.option"
                            :key="index"
                            @click="selectOption(index)"
                        >
                            <view
                                class="w-[76rpx] h-[76rpx] flex items-center justify-center head flex-none"
                                :class="{
                                    'head--active':
                                        data.user_answer?.includes(index) && !showAnalysis,
                                    'head--yourAnswer':
                                        data.user_answer?.includes(index) && showAnalysis,
                                    'head--trueAnswer': data.answer.includes(index) && showAnalysis
                                }"
                            >
                                {{ indexToString(index) }}
                            </view>
                            <view class="content ml-2 flex-1 break-all">{{ item }}</view>
                        </view>
                    </view>
                    <view class="flex mt-[40rpx]" v-if="showAnalysis">
                        <view class="flex-1 text-xl text-error"
                            >你的答案：{{
                                data.user_answer?.map((item) => indexToString(item)).join(',') ||
                                '空'
                            }}</view
                        >
                        <view class="flex-1 text-xl text-success"
                            >正确答案：{{
                                data.answer?.map((item) => indexToString(item)).join(',')
                            }}</view
                        >
                    </view>
                </view>
                <view class="mt-[20rpx] bg-white p-[30rpx]" v-if="showAnalysis">
                    <view class="title text-xl font-medium">难度</view>
                    <view class="flex justify-around mt-[30rpx]">
                        <view class="text-info" :class="{ 'text-[#FEC617]': data.difficulty == 1 }">
                            <u-icon size="56" name="star-fill"></u-icon>
                            <view>简单</view>
                        </view>
                        <view class="text-info" :class="{ 'text-[#FEC617]': data.difficulty == 2 }">
                            <u-icon size="56" name="star-fill"></u-icon>
                            <view>中等</view>
                        </view>
                        <view class="text-info" :class="{ 'text-[#FEC617]': data.difficulty == 3 }">
                            <u-icon size="56" name="star-fill"></u-icon>
                            <view>困难</view>
                        </view>
                    </view>
                </view>
                <view class="mt-[20rpx] bg-white p-[30rpx]" v-if="showAnalysis">
                    <view class="title text-xl font-medium">题目解析</view>
                    <view class="mt-[30rpx]">
                        <rich-text class="break-all" :nodes="data.analysis"></rich-text>
                    </view>
                </view>
                <view class="mt-[20rpx] bg-white p-[30rpx]" v-if="showAnalysis && data.points">
                    <view class="title text-xl font-medium">考点</view>
                    <view class="mt-[30rpx] break-all">
                        <view class="pointItem mb-2" v-for="(item, index) in points" :key="index">
                            <template v-if="item != ''">
                                <view>{{ item }}</view></template
                            >
                        </view>
                    </view>
                </view>
            </scroll-view>
        </view>
        <view class="px-[30rpx] pt-[20rpx] bottomBtn bg-white shadow">
            <u-button type="primary" shape="circle" @click="showAnswer" v-if="!showAnalysis"
                >显示答案</u-button
            >
            <u-button
                type="primary"
                shape="circle"
                @click="$emit('toNext')"
                v-if="showAnalysis && !isLast"
                >下一题</u-button
            >
            <u-button
                type="primary"
                shape="circle"
                @click="$emit('toNext')"
                v-if="showAnalysis && isLast"
                >最后一题</u-button
            >
        </view>
    </view>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue'
import { indexToString, debounce } from '@/utils/util'
import { apiHandleAnswer } from '@/api/question_bank'

const props = defineProps(['modelValue', 'isLast'])
const emit = defineEmits(['update:modelValue', 'toNext'])

const showAnalysis = ref(false)

const points: any = ref([])

const data = computed({
    get() {
        points.value = props.modelValue.points.split(/[(\r\n)\r\n]+/)

        return props.modelValue as any
    },
    set(value) {
        emit('update:modelValue', value)
    }
})

//提交答案
const postAnswer = async () => {
    apiHandleAnswer({ topic_id: data.value.id, answer: data.value.user_answer })
}
//防抖
const postDebounce = debounce(postAnswer, 500)

//选择答案
const selectOption = async (key: number) => {
    if (showAnalysis.value) {
        return
    }
    if (!data.value.hasOwnProperty('user_answer')) {
        data.value.user_answer = []
    }
    if (data.value.type == 2) {
        if (data.value.user_answer.includes(key)) {
            const index = data.value.user_answer.findIndex((item) => item == key)
            console.log(index)

            data.value.user_answer.splice(index, 1)
        } else {
            data.value.user_answer.push(key)
        }
    }
    if (data.value.type == 1) {
        data.value.user_answer[0] = key
    }
    postDebounce()
}

//显示答案
const showAnswer = () => {
    showAnalysis.value = true
}

defineExpose({ showAnswer })
</script>

<style scoped lang="scss">
.option {
    .optionItem {
        .head {
            border: 2px solid #e3e3e3;
            border-radius: 14px;
            &--active {
                border: 2px solid $u-type-primary;
                background-color: $u-type-primary;
                color: white;
            }
            &--yourAnswer {
                border: 2px solid $u-type-error;
                background-color: $u-type-error;
                color: white;
            }
            &--trueAnswer {
                border: 2px solid $u-type-success;
                background-color: $u-type-success;
                color: white;
            }
        }
    }
}
.bottomBtn {
    padding-bottom: calc(20rpx + env(safe-area-inset-bottom));
}
.title {
    position: relative;
    padding-left: 12rpx;
    &::before {
        content: '';
        width: 6rpx;
        height: 100%;
        background-color: $u-type-primary;
        position: absolute;
        top: 0;
        left: 0;
    }
}
.pointItem {
    position: relative;
    padding-left: 30rpx;
    &::before {
        content: '';
        width: 16rpx;
        height: 16rpx;
        position: absolute;
        background-color: $u-type-primary;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        border-radius: 9999rpx;
    }
}
</style>
