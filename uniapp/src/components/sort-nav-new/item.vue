<template>
    <view class="flex items-center justify-center">
        <view @click="changeStatus(itemData?.name)">{{ itemData?.label }}</view>
        <sort-icon :status="status"></sort-icon>
    </view>
</template>

<script setup lang="ts">
import sortIcon from './sort-icon.vue'
import { nextTick, ref } from 'vue'

const emit = defineEmits(['changeSort', 'beforeChange'])
const props = defineProps({
    index: {
        type: String
    },
    itemData: {
        type: Object,
        default: {} as any
    }
})

const status = ref('null')

const changeStatus = async (name: string) => {
    emit('beforeChange', props.index)
    await nextTick()
    status.value = status.value == '1' ? '2' : '1'
    const formateName = name + status.value
    emit('changeSort', formateName)
}

const initStatus = () => {
    status.value = ''
}

defineExpose({ initStatus })
</script>

<style scoped lang="scss"></style>
