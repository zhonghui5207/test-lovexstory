<template>
    <div>
        <el-form label-width="70px">
            <el-form-item label="是否启用">
                <el-radio-group v-model="content.enabled">
                    <el-radio :label="1">开启</el-radio>
                    <el-radio :label="0">停用</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-form-item label="菜单设置">
                <addNav v-model="content.data" :topIcon="true" :show-limit="true"></addNav>
            </el-form-item>
        </el-form>
    </div>
</template>

<script lang="ts" setup>
import type { PropType } from 'vue'
import type options from './options'
import addNav from '../../add-nav.vue'
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
