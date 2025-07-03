<template>
    <!-- 课程选择 Start -->
    <subject-root v-model="modelValue" @confirm="handleConfirm">
        <slot></slot>
    </subject-root>
    <!-- 课程选择 End -->

    <!-- Table Start -->
    <table-detail v-model="modelValue" @del="handleConfirm"></table-detail>
    <!-- Table End -->
</template>

<script lang="ts" setup>
import { withDefaults, provide, ref, watch } from 'vue'
import subjectRoot from './subject-root.vue'
import tableDetail from './table-detail.vue'
import type { Course } from '@/types/course'
import { deepClone } from '@/utils/util'

/** Props Start **/
const props = withDefaults(
    defineProps<{
        modelValue?: any
        courseId: number | string
    }>(),
    {
        modelValue: [],
        courseId: ''
    }
)
/** Props End **/

/** Emit Start **/
const emit = defineEmits(['update:modelValue', 'confirm'])
/** Emit End **/

/** Data Start **/
const selectData = ref<Array<Course>>(props.modelValue)
const tableData = ref<Array<Course>>(props.modelValue)
/** Data End **/

/**
 * @description 点击确定时返回数据
 */
const handleConfirm = (value: any) => {
    console.log(value.value)
    selectData.value = value
    emit('update:modelValue', selectData.value)
}
/** Methods End **/
</script>
