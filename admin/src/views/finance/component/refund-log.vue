<template>
    <div class="code-preview">
        <el-dialog v-model="show" width="760px" title="退款日志">
            <el-table size="large" v-loading="loading" :data="logLists" height="500">
                <el-table-column label="流水单号" prop="sn" min-width="190" />
                <el-table-column label="退款金额" min-width="110">
                    <template #default="{ row }"> ¥{{ row.refundAmount }} </template>
                </el-table-column>
                <el-table-column label="退款状态" prop="" min-width="100">
                    <template #default="{ row }">
                        <el-tag type="warning" v-if="row.refundStatus == 0">
                            {{ row.refundStatusMsg }}
                        </el-tag>
                        <el-tag v-if="row.refundStatus == 1">
                            {{ row.refundStatusMsg }}
                        </el-tag>
                        <el-tag type="danger" v-if="row.refundStatus == 2">
                            {{ row.refundStatusMsg }}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="记录时间" prop="createTime" min-width="180" />
                <el-table-column label="操作人" prop="handler" min-width="120" />
            </el-table>
        </el-dialog>
    </div>
</template>

<script lang="ts" setup>
// import { refundLog } from '@/api/finance'
const loading = ref(false)
const logLists = ref([])
const props = defineProps<{
    modelValue: boolean
    refundId: number
}>()

const emit = defineEmits<{
    (event: 'update:modelValue', value: boolean): void
}>()

const show = computed<boolean>({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value)
    }
})

const getRefundLog = async () => {
    loading.value = true
    logLists.value = []
    try {
        // const res = await refundLog({
        //     id: props.refundId
        // })
        // logLists.value = res
    } catch (error) {}
    loading.value = false
}

watch(show, (value) => {
    if (value) {
        getRefundLog()
    }
})
</script>
