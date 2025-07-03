<template>
    <u-image
        v-if="!list.length"
        src="../../static/images/img_null.png"
        width="100%"
        :height="height"
    ></u-image>
    <u-swiper
        v-else
        :list="list"
        :mode="mode"
        :height="height"
        :effect3d="effect3d"
        :indicator-pos="indicatorPos"
        :autoplay="autoplay"
        :interval="interval"
        :circular="circular"
        border-radius="0"
        :current="current"
        :name="name"
        @click="menuJumpPage($event)"
    >
    </u-swiper>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { menuJump } from '@/utils/util.ts'

/** Props Start **/
const props = withDefaults(
    defineProps<{
        list?: any //轮播图数据
        mode?: string //指示器模式 rect / dot / number / none
        height?: string //轮播图组件高度
        indicatorPos?: string //指示器的位置 topLeft / topCenter / topRight / bottomLeft / bottomRight
        effect3d?: boolean //是否开启3D效果
        autoplay?: boolean //是否自动播放
        interval?: number | string //自动轮播时间间隔，单位ms
        circular?: boolean //是否衔接播放
        current?: number //默认显示第几项
        name?: string // 显示的属性
        pid?: number | string | undefined
    }>(),
    {
        list: [],
        mode: 'round',
        indicatorPos: 'bottomCenter',
        height: '340',
        effect3d: false,
        autoplay: true,
        interval: '2500',
        circular: true,
        current: 0,
        name: 'image',
        pid: undefined
    }
)
/** Props End **/
console.log(props.list)
/** Methods Start **/
//
const menuJumpPage = (index: number) => {
    menuJump(props.list[index].link)
}
/** Methods End **/
</script>

<style></style>
