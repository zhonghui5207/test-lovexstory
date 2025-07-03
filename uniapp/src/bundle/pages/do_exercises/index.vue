<template>
    <view class="flex flex-col h-[100vh]">
        <view class="p-[30rpx] flex items-center justify-between">
            <view class="text-xs text-primary">{{
                listData[activeItem]?.type == 1 ? '单选' : '多选'
            }}</view>
            <view @click="toAnswerSheet">
                <text class="text-primary">{{ activeItem + 1 }}</text>
                <text class="text-xs text-info">/{{ listData.length }}</text>
            </view>
        </view>
        <view
            class="flex-1 min-h-0"
            @touchstart="onTouchStart"
            @touchmove="onTouchMove"
            @touchcancel="onTouchEnd"
            @touchend="onTouchEnd"
        >
            <template v-for="(item, index) in listData" :key="index">
                <listItem
                    ref="itemRef"
                    :class="{ ' hidden': index != activeItem }"
                    v-model="listData[index]"
                    :is-last="index + 1 == listData.length"
                    @to-next="toNext"
                ></listItem>
            </template>
        </view>
        <!-- #ifdef MP-WEIXIN -->
        <view v-if="interceptStatus">
            <page-container
                :show="true"
                :duration="false"
                :overlay="false"
                @beforeleave="beforeLeave"
            ></page-container>
        </view>
        <!-- #endif -->
    </view>
</template>

<script setup lang="ts">
import { nextTick, onMounted, onUnmounted, ref, shallowRef, watch } from 'vue'
import { useTouch } from '@/hooks/useTouch'
import listItem from './components/item.vue'
import { onLoad, onShow } from '@dcloudio/uni-app'
import { apiGetTopicLists } from '@/api/question_bank'

const itemRef = shallowRef()

const activeItem = ref(0)

const { touchStart, touchMove, touch } = useTouch()

const listData: any = ref([])

const swiping = ref(false)

const id = ref<number | string>(0)
const type = ref<string>('answer')

//拦截器状态
const interceptStatus = ref(true)

// 手指触摸
const onTouchStart = (event: any) => {
    swiping.value = true
    touchStart(event)
}
// 手指滑动
const onTouchMove = (event: any) => {
    if (!swiping.value) return
    touchMove(event)
}
// 手指滑动结束
const onTouchEnd = () => {
    if (!swiping.value) return
    const minSwipeDistance = 50
    if (touch.direction === 'horizontal' && touch.offsetX >= minSwipeDistance) {
        let index: any
        const len = listData.value.length
        const curIndex = activeItem.value
        if (touch.deltaX <= 0) {
            curIndex >= len - 1
                ? (() => {
                      if (!type.value.includes('analysis')) {
                          toAnswerSheet()
                          index = curIndex
                      } else {
                          uni.showToast({ title: '已经是最后一题了', icon: 'none' })
                          index = curIndex
                      }
                  })()
                : (index = curIndex + 1)
        } else {
            // curIndex <= 0 ? (index = len - 1) : (index = curIndex - 1)
            curIndex <= 0 ? (index = curIndex) : (index = curIndex - 1)
        }
        nextTick(() => {
            activeItem.value = index
        })
    }
    swiping.value = false
}

//跳转至下一题
const toNext = () => {
    let index: any
    const len = listData.value.length
    const curIndex = activeItem.value
    curIndex >= len - 1
        ? (() => {
              if (!type.value.includes('analysis')) {
                  toAnswerSheet()
                  index = curIndex
              } else {
                  uni.showToast({ title: '已经是最后一题了', icon: 'none' })
                  index = curIndex
              }
          })()
        : (index = curIndex + 1)
    nextTick(() => {
        activeItem.value = index
    })
}

//跳转至答题卡页面
const toAnswerSheet = () => {
    uni.navigateTo({
        url: `/bundle/pages/answer_sheet/index?id=${id.value}`
    })
}

//获取题目列表
const getTopicLists = async () => {
    uni.showLoading({ title: '加载中！' })
    if (type.value == 'analysisFalse') {
        const res = await apiGetTopicLists({ id: id.value })
        listData.value = res.filter((item) => item.is_true == 0)
        console.log(listData.value)
    } else {
        listData.value = await apiGetTopicLists({ id: id.value })
    }
    if (type.value == 'answer') {
        const index = listData.value.findIndex((item) => !item.hasOwnProperty('user_answer'))
        activeItem.value = index == -1 ? 0 : index
    }
    uni.hideLoading()
}

//解析模式
const toShowAnswer = () => {
    itemRef.value.forEach((item: any) => {
        item.showAnswer()
    })
}

//离开前触发
const beforeLeave = () => {
    // #ifdef MP-WEIXIN
    interceptStatus.value = false
    //#endif

    uni.showModal({
        title: '提示',
        content: '确定要退出练习？退出后未完成的练习会保存在练习历史中',
        success: function (res) {
            if (res.confirm) {
                uni.navigateBack()
            } else if (res.cancel) {
                // #ifdef MP-WEIXIN
                interceptStatus.value = true
                //#endif
                // #ifdef H5
                window.history.pushState(null, '', document.URL)
                // #endif
            }
        }
    })
}

onLoad(async (option: any) => {
    id.value = option.id
    if (option.hasOwnProperty('type')) {
        type.value = option.type || ''
    }
    if (option.hasOwnProperty('index')) {
        activeItem.value = option.index - 1
    }
    setTimeout(() => {
        if (type.value.includes('analysis')) {
            //修改拦截器状态
            interceptStatus.value = false
            toShowAnswer()
        }
    }, 1000)
})

onMounted(async () => {
    await nextTick()
    // #ifdef H5
    if (!type.value.includes('analysis')) {
        window.history.pushState(null, '', document.URL)
        window.addEventListener('popstate', beforeLeave, false)
    }
    // #endif
})

onUnmounted(() => {
    // #ifdef H5
    window.removeEventListener('popstate', beforeLeave, false)
    // #endif
})

onShow(async () => {
    await getTopicLists()
})
</script>

<style scoped lang="scss">
.pageContainer {
    height: calc(100vh - env(safe-area-inset-bottom));
}
.bottomBtn {
    padding-bottom: calc(20rpx + env(safe-area-inset-bottom));
}
</style>
