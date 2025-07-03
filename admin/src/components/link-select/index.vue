<template>
    <popup
        class="link-select"
        title="选择跳转链接"
        width="900px"
        top="20vh"
        ref="popupRef"
        :disabled="disabled"
        :async="true"
        @confirm="onConfirm"
    >
        <!-- Trigger Start -->
        <template #trigger>
            <!-- trigger -->
            <div class="link-select__trigger" slot="trigger">
                <el-input
                    v-model="linkName"
                    placeholder="请选择跳转链接"
                    readonly
                    :disabled="disabled"
                    :class="width"
                >
                    <template #suffix>
                        <el-icon v-if="linkName" class="el-input__icon" @click.stop="handleClear"
                            ><Close
                        /></el-icon>
                        <el-icon v-else class="el-input__icon"><ArrowRight /></el-icon>
                    </template>
                </el-input>
            </div>
        </template>
        <!-- Trigger End -->

        <detail v-model="link" />
    </popup>
</template>

<script lang="ts" setup>
import { ref, computed, watch } from 'vue'
import Popup from '@/components/popup/index.vue'
import { ElMessage } from 'element-plus'
import Detail from './detail.vue'

/** Emit Start **/
const emit = defineEmits(['update:modelValue'])
/** Emit End **/

/** Props Start **/
const props = withDefaults(
    defineProps<{
        modelValue: any
        disabled: boolean
        width: any
    }>(),
    {
        modelValue: {},
        disabled: false,
        width: 'w-[220px]'
    }
)
/** Props End **/

/** Data Start **/
const popupRef = ref()
const link = ref<any>({})
/** Data End **/

/** Computed Start **/
const linkName = computed(() => {
    const { type, name, params } = props.modelValue
    if (!name) {
        return ''
    }
    switch (type) {
        case 'custom':
            return params.url
        default:
            return `${name}${params && params.name ? '(' + params.name + ')' : ''}`
    }
})
/** Computed End **/

/** Watch Start **/
watch(
    () => props.modelValue,
    (value) => {
        link.value = JSON.parse(JSON.stringify(value))
    },
    { immediate: true }
)
/** Watch End **/

/** Methods Start **/
const onConfirm = () => {
    const { type, params, path } = link.value
    switch (type) {
        case 'shop':
            if (!path) {
                ElMessage({ type: 'warning', message: '请选择商城页面' })
                return
            }
            break
        case 'course':
            if (!params.name) {
                ElMessage({ type: 'warning', message: '请选择商品' })
                return
            }
            break
        case 'subject':
            if (!params.name) {
                ElMessage({ type: 'warning', message: '请选择专题活动' })
                return
            }
            break
        case 'category':
            if (!params.name) {
                ElMessage({ type: 'warning', message: '请选择分类' })
                return
            }
            break
        case 'custom':
            if (!params.url) {
                ElMessage({ type: 'warning', message: '请输入自定义链接' })
                return
            }
            break
    }
    popupRef.value.close()
    emit('update:modelValue', link.value)
}

const handleClear = () => {
    emit('update:modelValue', {})
}
/** Methods End **/
</script>

<style scoped lang="scss">
.link-select {
    &__trigger {
        ::v-deep .el-input {
            input {
                cursor: pointer;
            }

            .el-input__icon {
                cursor: pointer;
            }
        }
    }
}
</style>
