<template>
    <div>
        <div class="file-item relative" :style="{ height: fileSize, width: fileSize }">
            <el-image class="image" v-if="type == 'image'" fit="contain" :src="uri"></el-image>
            <video class="video" v-else-if="type == 'video'" :src="uri"></video>
            <div
                v-if="type == 'video'"
                class="absolute left-1/2 top-1/2 translate-x-[-50%] translate-y-[-50%] rounded-full w-5 h-5 flex justify-center items-center bg-[rgba(0,0,0,0.3)]"
            >
                <icon name="el-icon-CaretRight" :size="18" color="#fff" />
            </div>
            <img v-if="type == 'audio'" src="@/assets/mp3_def.png" />
            <img v-if="type == 'file'" src="@/assets/rar_def.png" />
            <slot></slot>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
    props: {
        // 图片地址
        uri: {
            type: String,
            default: ''
        },
        // 图片尺寸
        fileSize: {
            type: String,
            default: '100px'
        },
        // 文件类型
        type: {
            type: String,
            default: 'image'
        }
    },
    emits: ['close']
})
</script>

<style scoped lang="scss">
.file-item {
    box-sizing: border-box;
    position: relative;
    border-radius: 4px;
    overflow: hidden;
    @apply bg-br-extra-light border border-br-extra-light;
    .image,
    .video {
        display: block;
        box-sizing: border-box;
        width: 100%;
        height: 100%;
    }
}
</style>
