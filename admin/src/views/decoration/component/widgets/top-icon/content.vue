<template>
    <div class="my-topIcon">
        <div class="flex flex-wrap pt-[20px] pb-[10px] justify-around">
            <div
                v-for="(item, index) in content.data"
                :key="index"
                class="flex flex-col items-center w-1/4 mb-[15px]"
            >
                <decoration-img width="26px" height="26px" :src="item.image" alt="" />
                <div class="mt-[7px]">{{ item.name }}</div>
                <div class="mt-[7px] form-tips">{{ item.secName }}</div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { PropType } from 'vue'
import type options from './options'
import DecorationImg from '../../decoration-img.vue'
type OptionsType = ReturnType<typeof options>
defineProps({
    content: {
        type: Object as PropType<OptionsType['content']>,
        default: () => ({})
    },
    styles: {
        type: Object as PropType<OptionsType['styles']>,
        default: () => ({})
    }
})
</script>

<style lang="scss" scoped>
.my-topIcon {
    margin: 10px 10px 0;
    background-color: #fff;
    border-radius: 7px;
}
</style>
