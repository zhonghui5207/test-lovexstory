<template>
    <div>
        <el-form label-width="70px">
            <el-form-item label="是否启用">
                <el-radio-group v-model="content.enabled">
                    <el-radio :label="1">开启</el-radio>
                    <el-radio :label="0">停用</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-form-item label="图片设置">
                <div class="flex-1">
                    <div class="form-tips">最多添加5张，建议图片尺寸：750px*340px</div>
                    <draggable class="draggable" v-model="content.data" animation="300">
                        <template v-slot:item="{ element: item, index }">
                            <del-wrap
                                :key="index"
                                @close="handleDelete(index)"
                                class="max-w-[400px]"
                            >
                                <div class="bg-fill-light flex items-center w-full p-4 mt-4">
                                    <material-picker
                                        :excludeDomain="true"
                                        v-model="item.image"
                                        upload-class="bg-body"
                                    />
                                    <div class="ml-3 flex-1">
                                        <el-form-item label="图片名称">
                                            <el-input
                                                class="w-[178px]"
                                                v-model="item.name"
                                                placeholder="请输入名称"
                                            />
                                        </el-form-item>
                                        <el-form-item class="mt-[18px]" label="图片链接">
                                            <!-- <link-picker v-model="item.link" /> -->
                                            <link-select
                                                v-model="item.link"
                                                width="w-[178px]"
                                            ></link-select>
                                        </el-form-item>
                                    </div>
                                </div>
                            </del-wrap>
                        </template>
                    </draggable>
                </div>
            </el-form-item>
            <el-form-item v-if="content.data?.length < limit">
                <el-button type="primary" @click="handleAdd">添加图片</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>
<script lang="ts" setup>
import feedback from '@/utils/feedback'
import type { PropType } from 'vue'
import type options from './options'
import Draggable from 'vuedraggable'
const limit = 5
type OptionsType = ReturnType<typeof options>
const props = defineProps({
    content: {
        type: Object as PropType<OptionsType['content']>,
        default: () => ({})
    },
    styles: {
        type: Object as PropType<OptionsType['styles']>,
        default: () => ({})
    }
})

const handleAdd = () => {
    if (props.content.data?.length < limit) {
        props.content.data.push({
            image: '',
            name: '',
            link: {}
        })
    } else {
        feedback.msgError(`最多添加${limit}张图片`)
    }
}
const handleDelete = (index: number) => {
    if (props.content.data?.length <= 1) {
        return feedback.msgError('最少保留一张图片')
    }
    props.content.data.splice(index, 1)
}
</script>

<style lang="scss" scoped></style>
