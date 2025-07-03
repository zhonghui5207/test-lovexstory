<template>
    <div>
        <div>
            <draggable class="draggable" v-model="navLists" animation="300">
                <template v-slot:item="{ element: item, index }">
                    <del-wrap
                        class="max-w-[400px]"
                        :key="index"
                        @close="handleDelete(index)"
                        :show-close="!topIcon"
                    >
                        <div class="linkTitle form-tips" v-if="topIcon == true">
                            {{ item.linkName }}
                        </div>
                        <div class="bg-fill-light flex items-center w-full p-4 mb-4">
                            <material-picker
                                :excludeDomain="true"
                                v-model="item.image"
                                upload-class="bg-body"
                                size="60px"
                            >
                                <template #upload>
                                    <div class="upload-btn w-[60px] h-[60px]">
                                        <icon name="el-icon-Plus" :size="20" />
                                    </div>
                                </template>
                            </material-picker>
                            <div class="ml-3 flex-1">
                                <div class="flex">
                                    <span class="w-[56px] text-tx-regular flex-none mr-3"
                                        >菜单名称</span
                                    >
                                    <el-input
                                        v-model="item.name"
                                        :show-word-limit="showLimit"
                                        :maxlength="limitLength"
                                        placeholder="请输入名称"
                                    />
                                </div>
                                <div v-if="topIcon == false" class="flex mt-[18px]">
                                    <span class="w-[56px] text-tx-regular flex-none mr-3"
                                        >链接地址</span
                                    >
                                    <!-- <link-picker v-model="item.link" /> -->
                                    <link-select v-model="item.link"></link-select>
                                </div>
                                <div v-if="topIcon == true" class="sectitle flex mt-[18px]">
                                    <span class="w-[56px] text-tx-regular flex-none mr-3"
                                        >副标题</span
                                    >
                                    <el-input v-model="item.secName" placeholder="请输入名称" />
                                </div>
                            </div>
                        </div>
                    </del-wrap>
                </template>
            </draggable>
        </div>
        <div>
            <el-button v-if="topIcon == false" type="primary" @click="handleAdd">添加</el-button>
        </div>
    </div>
</template>
<script lang="ts" setup>
import feedback from '@/utils/feedback'
import type { PropType } from 'vue'
import LinkSelect from '../../../components/link-select/index.vue'
import Draggable from 'vuedraggable'
const props = defineProps({
    modelValue: {
        type: Array as PropType<any[]>,
        default: () => []
    },
    max: {
        type: Number,
        default: 10
    },
    min: {
        type: Number,
        default: 1
    },
    topIcon: {
        type: Boolean,
        default: false
    },
    showLimit: {
        type: Boolean,
        default: false
    },
    limitLength: {
        type: Number,
        default: 4
    }
})
const emit = defineEmits(['update:modelValue'])
const navLists = computed({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value)
    }
})

const handleAdd = () => {
    if (props.modelValue?.length < props.max) {
        navLists.value.push({
            image: '',
            name: '导航名称',
            link: {}
        })
    } else {
        feedback.msgError(`最多添加${props.max}个`)
    }
}
const handleDelete = (index: number) => {
    if (props.modelValue?.length <= props.min) {
        return feedback.msgError(`最少保留${props.min}个`)
    }
    navLists.value.splice(index, 1)
}
</script>

<style lang="scss" scoped></style>
