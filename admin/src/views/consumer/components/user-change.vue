<template>
    <div class="ls-dialog__trigger" @click="onTrigger">
        <!-- 触发弹窗 -->
        <slot name="trigger"></slot>
    </div>
    <el-dialog
        coustom-class="ls-dialog__content"
        :title="title"
        v-model="visible"
        :width="width"
        :modal-append-to-body="false"
        center
        :before-close="close"
        :close-on-click-modal="false"
        @close="close"
    >
        <!-- 弹窗主要内容-->
        <div class="">
            <el-form ref="formRef" :model="formData" label-width="120px">
                <el-form-item :label="'当前余额'">
                    <div>¥ {{ value }}</div>
                </el-form-item>
                <el-form-item :label="'余额增减'">
                    <!-- 单选按钮 -->
                    <el-radio-group class="m-r-16" v-model="formData.action">
                        <el-radio :label="1">增加余额</el-radio>
                        <el-radio :label="0">扣减余额</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item :label="'调整余额'" prop="num">
                    <div>
                        <el-input
                            class="ls-input"
                            v-model="formData.money"
                            placeholder="请输入调整的金额"
                            style="width: 300px"
                        >
                            <template #append>元</template>
                        </el-input>
                    </div>
                </el-form-item>
                <el-form-item :label="'调整后余额'">
                    <div v-if="type == 3">{{ lastValue }}</div>
                    <div v-else>¥ {{ lastValue.toFixed(2) }}</div>
                </el-form-item>
                <el-form-item label="备注">
                    <el-input
                        class="ls-input"
                        type="textarea"
                        :rows="3"
                        placeholder="请输入备注"
                        v-model="formData.remark"
                        style="width: 300px"
                    >
                    </el-input>
                </el-form-item>
            </el-form>
        </div>

        <!-- 底部弹窗页脚 -->
        <template #footer>
            <div class="dialog-footer">
                <el-button size="small" @click="close">取消</el-button>
                <el-button size="small" @click="updateUserAdjustUserWallet" type="primary"
                    >确认</el-button
                >
            </div>
        </template>
    </el-dialog>
</template>

<script lang="ts" setup>
import { ref, reactive, computed } from 'vue'
import { apiAdjustUserWallet } from '@/api/user/user'
import { ElMessage } from 'element-plus'
import type { FormInstance } from 'element-plus'

const emit = defineEmits(['refresh'])

const props = withDefaults(
    defineProps<{
        value?: number | string // 金额
        type: number // 变动类型：1-用户金额；2-可提现金额；3-积分
        title: string // 弹窗标题
        width: string | number // 弹窗的宽度
        userId: number // 用户ID
    }>(),
    {
        value: '',
        type: 1,
        title: '660px',
        width: '570px'
    }
)

const visible = ref<boolean>(false)
const formRef = ref<FormInstance>()
const formData = reactive({
    money: 0, // 金额
    action: 1, // 调整类型：0-减少；1-增加
    remark: '', // 备注
    wallet_type: 1
})
console.log(formData, props)

//修改后的总金额
const lastValue = computed(() => {
    let total: any = props.value
    if (formData.action == 1) {
        total = Number(formData.money) + total * 1
    } else {
        total = total * 1 - Number(formData.money)
    }
    return total
})

//调整用户钱包
const updateUserAdjustUserWallet = async (): Promise<void> => {
    const num = formData.money * 1
    if (num <= 0) {
        ElMessage({ type: 'error', message: '请输入大于0的数字' })
        return
    }
    try {
        await apiAdjustUserWallet({ ...formData, id: props.userId })
        emit('refresh')
        visible.value = false
    } catch (error) {
        console.log(error)
    }
}

//触发弹窗
const onTrigger = () => {
    visible.value = true
}

//关闭弹窗
const close = () => {
    visible.value = false
    // 重制表单内容
    formData.money = 0
    formData.remark = ''
}
</script>
