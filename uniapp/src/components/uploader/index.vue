<template>
    <view class="uploader-container flex wrap">
        <u-upload
            @on-change="handleCallback"
            :action="action"
            :header="{ token: token, version: versions }"
            :deletable="deletable"
            :max-count="maxUpload"
            :showProgress="showProgress"
            @on-remove="remove"
            :multiple="mutiple"
            :width="previewSize"
            :height="previewSize"
            ref="upload"
        >
            <!-- <view
                slot="addBtn"
                class="uplader-upload"
                hover-class="slot-btn__hover"
                hover-stay-time="150"
            >
                <u-icon size="48" color="#dcdee0" name="camera" />
                <view class="xs m-t-10">
                    {{ fileList.length >= 1 ? fileList.length + '/' + maxUpload : tips }}
                </view>
            </view> -->
        </u-upload>
    </view>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue'
import { useUserStore } from '@/stores/user'
import { version } from '@/config/app'
import { toast } from '@/utils/util'
import UUpload from '../../uni_modules/vk-uview-ui/components/u-upload/u-upload.vue'

const userStore = useUserStore()

const emit = defineEmits(['update:modelValue'])

const props = withDefaults(
    defineProps<{
        mutiple?: boolean // 默认不允许多选图片
        maxUpload?: number // 限制上传文件数量
        previewSize?: number | string // upload显示的大小
        deletable?: boolean // 是否可删除
        tips?: string //提示
        showProgress?: false // 是否显示进度条
    }>(),
    {
        mutiple: false,
        maxUpload: 1,
        previewSize: '160',
        deletable: true,
        tips: '上传图片',
        showProgress: false
    }
)

const action = ref<string | null>('')
const token = ref<string | null>('')
const fileList = ref<string[]>([])
const versions = ref<string | null>(version)

/**
 * @param { params } 上传返回值
 * @return { void }
 * @description 上传，不管成不成功都返回数据｜提示
 */
const handleCallback = (params: any) => {
    try {
        toast(JSON.parse(params.data).msg)

        if (JSON.parse(params.data).code == 1) {
            fileList.value.push(JSON.parse(params.data).data.uri)
            emit('update:modelValue', fileList.value)
        }
    } catch (error) {
        console.log(error)
    }
}

/**
 * @param { event } 索引值
 * @return { void }
 * @description 删除一个图片
 */
const remove = (event: number) => {
    try {
        fileList.value.splice(event, 1)
        emit('update:modelValue', fileList.value)
    } catch (error) {
        console.log(error)
    }
}

onMounted(() => {
    action.value = `${import.meta.env.VITE_APP_BASE_URL}/api/Upload/image`
    token.value = userStore.token
})
</script>

<style lang="scss" scoped>
.uploader-container {
    .uplader-upload {
        position: relative;
        width: 160rpx;
        height: 160rpx;
        padding-top: 30rpx;
        text-align: center;
        margin: 11rpx;
        border: 2px dashed #e5e5e5;
        background-color: #ffffff;

        > view {
            color: #bbb;
        }
    }
}
</style>
