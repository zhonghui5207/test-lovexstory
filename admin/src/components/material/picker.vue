<template>
    <div class="material-select">
        <popup
            ref="popupRef"
            width="830px"
            custom-class="body-padding"
            :title="`选择${tipsText}`"
            @confirm="handleConfirm"
            @close="handleClose"
        >
            <template v-if="!hiddenUpload" #trigger>
                <div class="material-select__trigger clearfix" @click.stop>
                    <draggable class="draggable" :list="fileList" animation="300" item-key="id">
                        <template v-slot:item="{ element, index }">
                            <div
                                class="material-preview"
                                :class="{
                                    'is-disabled': disabled,
                                    'is-one': limit == 1
                                }"
                                @click="showPopup(index)"
                            >
                                <del-wrap @close="deleteImg(index)">
                                    <file-item
                                        :uri="excludeDomain ? getImageUrl(element) : element"
                                        :file-size="size"
                                        :type="type"
                                    ></file-item>
                                </del-wrap>
                                <div
                                    v-if="type != 'file'"
                                    class="operation-btns text-xs text-center"
                                >
                                    <span>修改</span>
                                    |
                                    <span @click.stop="handlePreview(element)">查看</span>
                                </div>
                            </div>
                        </template>
                    </draggable>
                    <div
                        class="material-upload"
                        @click="showPopup(-1)"
                        v-show="showUpload"
                        :class="{
                            'is-disabled': disabled,
                            'is-one': limit == 1,
                            [uploadClass]: true
                        }"
                    >
                        <slot name="upload">
                            <div
                                class="upload-btn"
                                :style="{
                                    width: size,
                                    height: size
                                }"
                            >
                                <icon :size="25" name="el-icon-Plus" />
                                <span>添加</span>
                            </div>
                        </slot>
                    </div>
                </div>
            </template>
            <el-scrollbar>
                <div class="material-wrap">
                    <material
                        ref="materialRef"
                        :type="type"
                        :file-size="fileSize"
                        :limit="meterialLimit"
                        @change="selectChange"
                    />
                </div>
            </el-scrollbar>
        </popup>
        <preview v-model="showPreview" :url="previewUrl" :type="type" />
    </div>
</template>

<script lang="ts">
import Draggable from 'vuedraggable'
import Popup from '@/components/popup/index.vue'
import FileItem from './file.vue'
import Material from './index.vue'
import Preview from './preview.vue'
import useAppStore from '@/stores/modules/app'
export default defineComponent({
    components: {
        Popup,
        Draggable,
        FileItem,
        Material,
        Preview
    },
    props: {
        modelValue: {
            type: [String, Array],
            default: () => []
        },
        // 文件类型
        type: {
            type: String,
            default: 'image'
        },
        // 选择器尺寸
        size: {
            type: String,
            default: '100px'
        },
        // 文件尺寸
        fileSize: {
            type: String,
            default: '100px'
        },
        // 选择数量限制
        limit: {
            type: Number,
            default: 1
        },
        // 禁用选择
        disabled: {
            type: Boolean,
            default: false
        },
        // 隐藏上传框*(目前在富文本中使用到)
        hiddenUpload: {
            type: Boolean,
            default: false
        },
        uploadClass: {
            type: String,
            default: ''
        },
        //选择的url排出域名
        excludeDomain: {
            type: Boolean,
            default: false
        },
        getName: {
            type: Boolean,
            default: false
        }
    },

    emits: ['change', 'update:modelValue'],
    setup(props, { emit }) {
        const popupRef = ref<InstanceType<typeof Popup>>()
        const materialRef = ref<InstanceType<typeof Material>>()
        const previewUrl = ref('')
        const showPreview = ref(false)
        const fileList = ref<any[]>([])
        const completeFileList = ref<any[]>([])
        const select = ref<any[]>([])
        const isAdd = ref(true)
        const currentIndex = ref(-1)
        const { disabled, limit, modelValue } = toRefs(props)
        const { getImageUrl } = useAppStore()
        const tipsText = computed(() => {
            switch (props.type) {
                case 'image':
                    return '图片'
                case 'video':
                    return '视频'
                case 'audio':
                    return '音频'
                case 'file':
                    return '压缩文件'
                default:
                    return ''
            }
        })

        const showUpload = computed(() => {
            return props.limit - fileList.value.length > 0
        })
        const meterialLimit: any = computed(() => {
            if (!isAdd.value) {
                return 1
            }
            if (limit.value == -1) return null
            return limit.value - fileList.value.length
        })
        const handleConfirm = () => {
            const selectUri = select.value.map((item) =>
                props.excludeDomain ? item.url : item.uri
            )
            completeFileList.value = select.value
            if (!isAdd.value) {
                fileList.value.splice(currentIndex.value, 1, selectUri.shift())
            } else {
                fileList.value = [...fileList.value, ...selectUri]
            }
            handleChange()
        }
        const showPopup = (index: number) => {
            if (disabled.value) return
            if (index >= 0) {
                isAdd.value = false
                currentIndex.value = index
            } else {
                isAdd.value = true
            }
            popupRef.value?.open()
        }

        const selectChange = (val: any[]) => {
            select.value = val
        }
        const handleChange = () => {
            const valueImg = limit.value != 1 ? fileList.value : fileList.value[0] || ''
            //导出文件路径
            emit('update:modelValue', valueImg)
            //导出文件完成信息
            emit('change', valueImg, completeFileList.value)
            handleClose()
        }

        const deleteImg = (index: number) => {
            fileList.value.splice(index, 1)
            handleChange()
        }

        const handlePreview = (url: string) => {
            url = props.excludeDomain ? getImageUrl(url) : url
            previewUrl.value = url
            showPreview.value = true
        }

        const handleClose = () => {
            nextTick(() => {
                if (props.hiddenUpload) fileList.value = []
                materialRef.value?.clearSelect()
            })
        }

        watch(
            modelValue,
            (val: any[] | string) => {
                fileList.value = Array.isArray(val) ? val : val == '' ? [] : [val]
            },
            {
                immediate: true
            }
        )
        provide('limit', props.limit)
        provide('hiddenUpload', props.hiddenUpload)
        return {
            popupRef,
            materialRef,
            fileList,
            tipsText,
            handleConfirm,
            meterialLimit,
            showUpload,
            showPopup,
            selectChange,
            deleteImg,
            previewUrl,
            showPreview,
            handlePreview,
            handleClose,
            getImageUrl
        }
    }
})
</script>

<style scoped lang="scss">
.material-select {
    .material-upload,
    .material-preview {
        position: relative;
        border-radius: 4px;
        cursor: pointer;
        margin-right: 8px;
        margin-bottom: 8px;
        box-sizing: border-box;
        float: left;
        &.is-disabled {
            cursor: not-allowed;
        }
        &.is-one {
            margin-bottom: 0;
        }
        &:hover {
            .operation-btns {
                display: block;
            }
        }
        .operation-btns {
            display: none;
            position: absolute;
            bottom: 0;
            border-radius: 4px;
            width: 100%;
            line-height: 2;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.3);
        }
    }
    .material-upload {
        :deep(.upload-btn) {
            @apply text-tx-secondary box-border rounded border-br border-dashed border flex flex-col justify-center items-center;
        }
    }
}
.material-wrap {
    min-width: 720px;
    height: 430px;
    @apply border-t border-b border-br;
}
</style>
