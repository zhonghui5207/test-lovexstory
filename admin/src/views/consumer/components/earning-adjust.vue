<template>
    <popup
        ref="popupRef"
        title="可提现余额调整"
        width="500px"
        @confirm="handleConfirm"
        :async="true"
        @close="popupClose"
    >
        <div class="pr-8">
            <el-form ref="formRef" :model="formData" label-width="120px" :rules="formRules">
                <el-form-item label="当前余额">¥ {{ value }} </el-form-item>
                <el-form-item label="余额增减" required prop="action">
                    <el-radio-group v-model="formData.action">
                        <el-radio :label="1">增加余额</el-radio>
                        <el-radio :label="0">扣减余额</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="调整余额" prop="amount">
                    <el-input
                        :model-value="formData.money"
                        placeholder="请输入调整的金额"
                        type="number"
                        @input="numberValidate"
                    />
                </el-form-item>
                <el-form-item label="调整后余额"> ¥ {{ adjustmentMoney.toFixed(2) }} </el-form-item>
                <el-form-item label="备注" prop="remark">
                    <el-input v-model="formData.remark" type="textarea" :rows="4" />
                </el-form-item>
            </el-form>
        </div>
    </popup>
</template>
<script lang="ts" setup>
import Popup from '@/components/popup/index.vue'
import { apiAdjustUserWallet } from '@/api/user/user'
import type { FormInstance, FormRules } from 'element-plus'
import feedback from '@/utils/feedback'
const formRef = shallowRef<FormInstance>()
const props = defineProps({
    value: {
        type: [Number, String],
        required: true
    },
    userId: {
        type: Number,
        default: 0
    }
})
const emit = defineEmits<{
    (event: 'confirm', value: any): void
}>()

const id = ref('')

const formData = reactive({
    action: 1, //变动类型 1-增加 2-减少
    money: '',
    remark: '',
    wallet_type: 2
})
const popupRef = shallowRef<InstanceType<typeof Popup>>()

const adjustmentMoney = computed(() => {
    return Number(props.value) + Number(formData.money) * (formData.action == 1 ? 1 : -1)
})

const formRules: FormRules = {
    money: [
        {
            required: true,
            message: '请输入调整的金额'
        }
    ]
}
const numberValidate = (value: string) => {
    if (value.includes('-')) {
        return feedback.msgError('请输入正整数')
    }
    formData.money = value
}
const handleConfirm = async () => {
    await formRef.value?.validate()
    await apiAdjustUserWallet({ ...formData, id: props.userId })
    emit('confirm', formData)
    popupRef.value?.close()
}

const popupClose = () => {
    formRef.value?.resetFields()
}

watch(adjustmentMoney, (val) => {
    if (val < 0) {
        feedback.msgError('调整后余额需大于0')
        formData.amount = ''
    }
})

const open = (option: any) => {
    id.value = option.id
    popupRef.value?.open()
}

defineExpose({ open })
</script>
