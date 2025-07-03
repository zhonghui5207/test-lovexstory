<template>
    <div class="custom">
        <el-form label-width="100px">
            <el-form-item
                label="跳转链接"
                :rules="[
                    {
                        required: true,
                        message: '',
                        trigger: 'blur'
                    }
                ]"
            >
                <el-input
                    style="width: 280px"
                    v-model="params.url"
                    placeholder="请输入跳转链接"
                ></el-input>
            </el-form-item>
            <el-form-item label=" ">
                <div style="line-height: 1.3" class="form-tips">
                    请填写完整的带有“https://”或“http://”的链接地址
                    <br />
                    链接的域名必须在微信公众平台设置业务域名
                </div>
            </el-form-item>
        </el-form>
    </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'

/** Emit Start **/
const emit = defineEmits(['update:modelValue'])
/** Emit End **/

/** Props Start **/
const props = withDefaults(
    defineProps<{
        modelValue: any
    }>(),
    {
        modelValue: ''
    }
)
/** Props End **/

/** Computed Start **/
const params = computed({
    get: () => {
        return props.modelValue
    },
    set: (value) => {
        emit('update:modelValue', value)
    }
})
/** Computed End **/
</script>
<style lang="scss" scoped></style>
