<template>
    <view class="w-full">
        <video
            class="w-full h-[400rpx]"
            id="myVideo"
            :src="src"
            controls
            :initial-time="videoLookingTime"
            :duration="time"
            @pause="pauseVideo"
            @timeupdate="timeUpdate"
            @ended="videoEnd"
        ></video>
    </view>
</template>

<script setup lang="ts">
import { getCurrentInstance, ref, shallowRef, watch } from 'vue'
import { apiUpdateSchedule } from '@/api/goods'
import { throttle } from '@/utils/util'

const instance = getCurrentInstance()
const videoContext = uni.createVideoContext('myVideo', instance)
const emit = defineEmits(['end', 'pause'])

const props = defineProps({
    src: {
        type: String,
        default: ''
    },
    initialTime: {
        type: Number,
        default: 0
    },
    cataId: {
        type: Number,
        default: -1
    },
    courseId: {
        type: Number,
        default: -1
    },
    time: {
        type: String,
        default: ''
    }
})

//视频已看到的时长
const videoLookingTime = ref<number>(0)

watch(
    () => props.initialTime,
    (value) => {
        videoLookingTime.value = value
        if (value > 0) {
            uni.$u.toast('即将跳转到上次播放的位置')
            setTimeout(() => {
                videoContext.play()
            }, 2000)
        }
    },
    {
        immediate: true
    }
)

//暂停触发
const pauseVideo = (value: any) => {
    emit('pause')
    callUpdate()
}

//调用更新接口
const callUpdate = () => {
    apiUpdateSchedule({
        catalogue_id: props.cataId,
        schedule: videoLookingTime.value * 1
    })
}

const throttleUpDate = throttle(callUpdate, 4000)

//视频播放时间更新
const timeUpdate = (value: any) => {
    //获取视频播放秒数
    videoLookingTime.value = Math.round(value.detail.currentTime)
    throttleUpDate()
}

//视频结束
const videoEnd = () => {
    videoLookingTime.value = 0
    emit('end')
}
</script>

<style scoped lang="scss"></style>
