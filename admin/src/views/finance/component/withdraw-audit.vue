<template>
    <div class="edit-popup">
        <popup
            ref="popupRef"
            title="提现审核"
            :async="true"
            width="550px"
            @confirm="handleSubmit"
            @close="handleClose"
        >
            <el-form ref="formRef" :model="formData" label-width="110px" :rules="formRules">
                <el-form-item label="提现审核" prop="status">
                    <div>
                        <el-radio-group v-model="formData.status">
                            <el-radio :label="1" size="large">通过</el-radio>
                            <el-radio :label="0" size="large">不通过</el-radio>
                        </el-radio-group>

                        <div class="form-tips">审核拒绝后，提现金额会全部退回佣金账户</div>
                    </div>
                </el-form-item>

                <el-form-item label="提现说明" prop="audit_content">
                    <el-input
                        class="w-[280px]"
                        v-model="formData.verify_remark"
                        placeholder="请输入提现说明"
                        type="textarea"
                        :autosize="{ minRows: 4, maxRows: 4 }"
                        :show-word-limit="true"
                        :clearable="true"
                    />
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>
<script lang="ts" setup>
import { apiWithdrawAuditSuccess, apiWithdrawAuditFail } from '@/api/finance/finance'
import Popup from '@/components/popup/index.vue'
import type { FormInstance, FormRules } from 'element-plus'

const emit = defineEmits(['success', 'close'])
const formRef = shallowRef<FormInstance>()
const popupRef = shallowRef<InstanceType<typeof Popup>>()

const formData = reactive<any>({
    id: '',
    status: 1,
    verify_remark: ''
})

const formRules = reactive<FormRules>({
    status: [{ required: true, message: '请选择审核状态', trigger: ['blur'] }]
})

const handleSubmit = async () => {
    await formRef.value?.validate()
    if (formData.status == 1) {
        await apiWithdrawAuditSuccess({ ...formData })
    } else {
        await apiWithdrawAuditFail({ ...formData })
    }
    emit('success')
    popupRef.value?.close()
}

const open = async (id: number) => {
    formData.id = id
    popupRef.value?.open()
}

const handleClose = () => {
    emit('close')
}

defineExpose({ open })
</script>
