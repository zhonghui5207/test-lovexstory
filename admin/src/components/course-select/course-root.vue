<template>
    <popup
        class="mr-[10px] inline"
        :clickModalClose="false"
        title="选择课程"
        :center="true"
        @confirm="confirm"
        width="60vw"
        @open="openPop"
    >
        <template #trigger>
            <slot></slot>
        </template>

        <el-tabs v-model="activeName">
            <!-- 图文课程 -->
            <el-tab-pane label="图文课程" name="0">
                <!-- <course v-model="addData" ref="courseRef" :course="courseData" :type="1"> </course> -->
                <course v-model="dataList.ITData" ref="courseRef" :course="courseData" :type="1">
                </course>
            </el-tab-pane>
            <!-- 音频课程 -->
            <el-tab-pane label="音频课程" name="1">
                <!-- <course v-model="addData" ref="courseRef" :course="courseData" :type="2"> </course> -->
                <course v-model="dataList.audioData" ref="courseRef" :course="courseData" :type="2">
                </course>
            </el-tab-pane>
            <!-- 视频课程 -->
            <el-tab-pane label="视频课程" name="2">
                <!-- <course v-model="addData" ref="courseRef" :course="courseData" :type="3"> </course> -->
                <course v-model="dataList.videoData" ref="courseRef" :course="courseData" :type="3">
                </course>
            </el-tab-pane>
        </el-tabs>
    </popup>
</template>

<script lang="ts" setup>
import { withDefaults, ref } from 'vue'
import Popup from '@/components/popup/index.vue'
import course from './course.vue'

const courseRef = shallowRef()
const addData: any = ref([])

const dataList = ref({ ITData: [], audioData: [], videoData: [] })

withDefaults(
    defineProps<{
        courseData: any
    }>(),
    {
        courseData: []
    }
)

const emit = defineEmits(['confirm'])

const confirm = () => {
    const totalData = dataList.value.ITData.concat(dataList.value.audioData).concat(
        dataList.value.videoData
    )
    emit('confirm', totalData)
}

// watch(
//     () => courseRef,
//     (value) => {
//         console.log(value)
//         courseRef.value.getIsSelect()
//     },
//     { deep: true }
// )

//弹框打开
const openPop = async () => {
    setTimeout(() => {
        console.log(courseRef.value)

        courseRef.value.getIsSelect()
    }, 100)
}

//tabs active
const activeName = ref('0')
</script>
