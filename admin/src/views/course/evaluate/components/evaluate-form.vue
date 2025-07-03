<template>
    <popup
        class="inline"
        width="45vw"
        :center="true"
        @confirm="handleReply"
        @cancel="hanleCancel"
        :title="title"
    >
        <template #trigger>
            <el-button type="primary" link>{{ title }}</el-button>
        </template>
        <div style="height: 300px">
            <el-form ref="commentFormRef" :model="commentForm" label-width="120px">
                <el-form-item label="回复评价:">
                    <el-input
                        class="ls-input"
                        type="textarea"
                        v-model="commentForm.reply"
                        placeholder="请输入"
                        :rows="10"
                        show-word-limit
                        maxlength="200"
                    >
                    </el-input>
                </el-form-item>
            </el-form>
        </div>
    </popup>
</template>

<script lang="ts" setup>
import { apiCommentReply } from '@/api/course/course'
import Popup from '@/components/popup/index.vue'
import { ref, defineEmits, watch } from 'vue'

/** Emit Start **/
const emit = defineEmits(['refresh'])
/** Emit End **/

/** Props Start **/
const props = withDefaults(
    defineProps<{
        id: string | number // 评价ID
        title: string // 弹窗标题
        reply: string // 评价
    }>(),
    {
        id: '',
        title: '',
        reply: ''
    }
)
/** Props End **/

/** Data Start **/
const commentForm = ref({
    reply: '' as string
})
/** Data End **/

/** Data Start **/
watch(
    () => props.reply,
    (value) => {
        commentForm.value.reply = value
    },
    { immediate: true }
)
/** Data End **/

/** Methods Start **/
/**
 * @description 回复评价
 */
const handleReply = async (): Promise<void> => {
    try {
        await apiCommentReply({ id: props.id, content: commentForm.value.reply })
        emit('refresh')
    } catch (error) {
        console.log('回复评价=>', error)
    }
}
/** Methods End **/

//点击取消
const hanleCancel = () => {
    commentForm.value.reply = ''
}
</script>

<style lang="scss" scoped>
.ls-input {
    width: 30vw;
}
</style>
