<template>
    <view class="px-[68rpx] audio">
        <image class="w-full h-[330rpx] rounded-lg" :src="courseDetailInfo!.cover"></image>
        <slider
            :value="speedProgress"
            activeColor="#2073F4"
            backgroundColor="#B1CFFF"
            block-color="#2073F4"
            @change="sliderChange"
            @changing="sliderChangeIng"
            :block-size="12"
            min="0"
            max="100"
        />
        <view class="flex justify-between mt-[20rpx]">
            <text>{{ currentTime }}</text>
            <text>{{ endTime }}</text>
        </view>
        <view class="flex justify-center items-center">
            <view @click="back">
                <u-icon name="rewind-left-fill" size="32"></u-icon>
            </view>
            <view
                class="mx-[30rpx] control w-[64rpx] h-[64rpx] bg-primary rounded-full flex items-center justify-center text-white pl-[5rpx]"
                @click="handleControl"
            >
                <u-icon v-if="isPlay" name="pause" size="32"></u-icon>
                <u-icon v-else name="play-right-fill" size="32"></u-icon>
            </view>
            <view @click="advance">
                <u-icon name="rewind-right-fill" size="32"></u-icon>
            </view>
        </view>
    </view>
</template>

<script setup lang="ts">
import { ref, reactive, watch, computed, onBeforeUnmount } from 'vue'
import { apiUpdateSchedule } from '@/api/goods'
import { throttle } from '@/utils/util'

const emits = defineEmits(['refresh'])

/** Props Start **/
const props = withDefaults(
    defineProps<{
        src?: string // 音频链接
        cataId: number
        courseId: number
        courseDetailInfo: any
        initialTime: number
    }>(),
    {
        src: '',
        cataId: 0,
        courseId: 0,
        courseDetailInfo: (): any => {},
        initialTime: 0
    }
)
/** Props End **/

/** Dat Start **/
// 音频实例
let innerAudioContext: any = reactive({})
// 当前时间
const currentMinutes = ref(0)
// 音频结束时间
const endMinutes = ref(0)
// 是否播放
const isPlay = ref<boolean>(false)
// 是否拖拽中
const isDrag = ref<boolean>(false)
// 是否静音
const isMute = ref<boolean>(false)

/** Dat End **/

/** Watch Start **/
// 获取音频链接并实例音频API
watch(
    () => props.src,
    (val) => {
        const audioContext = uni.createInnerAudioContext()
        audioContext.autoplay = true
        audioContext.src = val
        audioContext.volume = 1
        audioContext.startTime = props.initialTime
        innerAudioContext = audioContext
        // 监听音频进入可以播放状态的事件
        innerAudioContext.onCanplay((e: any) => {
            // console.log('进入可播放状态！')
            //获取音频时长
            const intervalID = setInterval(() => {
                console.log('定时器循环' + innerAudioContext.duration)
                if (innerAudioContext.duration != 0) {
                    clearInterval(intervalID) // 清除定时器
                    endMinutes.value = innerAudioContext.duration
                    setTimeout(() => {
                        innerAudioContext.play()
                    }, 0)
                }
            }, 500)
        })
        // 监听开始播放
        innerAudioContext.onPlay((event: any) => {
            isPlay.value = true
        })
        // 进度
        innerAudioContext.onTimeUpdate((event: any) => {
            throttleUpDate()
            currentMinutes.value = innerAudioContext.currentTime
        })
        // 音频加载中事件，当音频因为数据不足，需要停下来加载时会触发
        innerAudioContext.onWaiting((event: any) => {
            console.log('加载中')
        })
        // 监听音频停止
        innerAudioContext.onPause((event: any) => {
            console.log('音频停止')
            isPlay.value = false
        })
        // 播放完成
        innerAudioContext.onEnded((event: any) => {
            console.log('播放完成')
            isPlay.value = false
        })
        // 播放错误
        innerAudioContext.onError((event: any) => {
            console.log('播放错误')
            isPlay.value = false
        })
    },
    {
        immediate: true
    }
)
watch(
    () => props.initialTime,
    (value) => {
        if (value > 0) {
            uni.$u.toast('将跳转至上次播放的位置开始播放')
        }
    },
    {
        immediate: true
    }
)
/** Watch End **/

/** Computed Start **/
// 当前播放时间
const currentTime = computed(() => {
    const time = currentMinutes.value
    return toHHmmss(time) || '00:00'
})
// 结束时间
const endTime = computed(() => {
    const time = endMinutes.value
    if (time != 0) handlePause()
    return toHHmmss(time) || '00:00'
})
// 进度条
const speedProgress = computed(() => {
    // if (isDrag.value) return
    const time = currentMinutes.value
    const endTime = endMinutes.value
    return (time / endTime) * 100
})
//目录数据
const catelogue = computed(() => {
    return props.courseDetailInfo.catalogue_list as any[]
})
/** Computed End **/

/** Methods Start **/
/**
 * @description 拖动进度条
 */
const sliderChange = (event: any) => {
    isDrag.value = false
    currentMinutes.value = (event.detail.value / 100) * endMinutes.value
    innerAudioContext.seek(currentMinutes.value)
}

/**
 * @description 拖拽中
 */
const sliderChangeIng = (event: any) => {
    isDrag.value = true
}

/**
 * @description 控制播放
 */
const handleControl = () => {
    if (!isPlay.value) {
        handlePlay()
    } else {
        handlePause()
    }
    isPlay.value = !isPlay.value
}

//获取当前章节索引
const getNowIndex = () => {
    const nowIndex: number = catelogue.value.findIndex((item: any) => {
        return item.id == props.cataId
    })
    return nowIndex
}

//快进10s
const advance = () => {
    const nowIndex = getNowIndex()
    if (nowIndex + 1 == catelogue.value.length) {
        uni.$u.toast('已经是最后一章！')
        return
    }
    emits('refresh', catelogue.value[nowIndex + 1])
}

//回退10s
const back = () => {
    const nowIndex = getNowIndex()
    if (nowIndex - 1 < 0) {
        uni.$u.toast('已经是第一章！')
        return
    }
    emits('refresh', catelogue.value[nowIndex - 1])
}

//调用更新接口
const callUpdate = () => {
    apiUpdateSchedule({
        catalogue_id: props.cataId,
        schedule: Math.floor(currentMinutes.value) * 1
    })
}

//节流函数
const throttleUpDate = throttle(callUpdate, 2000)

/**
 * @description 播放
 */
const handlePlay = () => {
    innerAudioContext.play()
}

/**
 * @description 停止
 */
const handlePause = () => {
    innerAudioContext.pause()
}

/**
 * @description 静音
 */
const handleMute = () => {
    if (!isMute.value) {
        innerAudioContext.volume = 0
    } else {
        innerAudioContext.volume = 1
    }
    isMute.value = !isMute.value
}

/**
 * { string } time
 * @description 秒数转为时分秒
 */
const toHHmmss = (time: number | string) => {
    let result
    let hours = parseInt((time / 60 / 60) % 24)
    hours = hours < 10 ? '0' + hours : hours
    let minutes = parseInt((time / 60) % 60)
    minutes = minutes < 10 ? '0' + minutes : minutes
    let seconds = parseInt(time % 60)
    seconds = seconds < 10 ? '0' + seconds : seconds
    result = `${hours == '00' ? '' : hours + ':'}${minutes}:${seconds}`
    return result
}

onBeforeUnmount(() => {
    innerAudioContext.destroy()
    console.log('组件销毁！！！！')
})
/** Methods End **/
</script>

<style lang="scss" scoped>
.audio {
    :deep(.uni-slider-handle-wrapper) {
        height: 10rpx !important;
        border: 16rpx !important;
        background: #b1cfff !important;
    }

    :deep(.uni-slider-track) {
        background-color: #2073f4 !important;
    }
    .audio-wrapper {
        ::v-deep .wx-slider-handle-wrapper {
            height: 10rpx !important;
            border: 16rpx !important;
            background: #2073f4 !important;
        }

        ::v-deep .wx-slider-track {
            background-color: #b1cfff !important;
        }
    }
}
</style>
