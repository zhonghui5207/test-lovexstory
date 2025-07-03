<template>
    <popup
        class="mr-2 inline"
        width="800px"
        :async="true"
        :clickModalClose="false"
        @open="refundlogLists"
        :title="title"
        :confirmButtonText="false"
        :cancelButtonText="false"
    >
        <template #trigger>
            <el-link type="primary" :underline="false">{{ title }}</el-link>
        </template>

        <div style="height: 300px">
            <el-table :data="formData" style="width: 100%">
                <el-table-column label="流水单号" prop="sn" min-width="200" />

                <el-table-column label="退款金额" prop="refund_amount" min-width="100">
                    <template #default="{ row }">
                        <span>{{ `￥${row.refund_amount}` }}</span>
                    </template>
                </el-table-column>

                <el-table-column label="退款状态" prop="refund_status_desc" min-width="100">
                    <template #default="{ row }">
                        <el-tag type="warning" v-if="row.refund_status == 0"> 退款中</el-tag>
                        <el-tag type="success" v-if="row.refund_status == 1">退款成功</el-tag>
                        <el-tag type="danger" v-if="row.refund_status == 2">退款失败</el-tag>
                    </template>
                </el-table-column>

                <el-table-column label="记录时间" prop="create_time" min-width="200">
                    <template #default="{ row }">
                        <span>
                            {{ row.create_time || '-' }}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column label="操作人" width="120" fixed="right">
                    <template #default="{ row }">
                        <span>
                            {{ row.operator_desc || '-' }}
                        </span>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </popup>
</template>

<script lang="ts" setup>
import { apiRefundLogList } from '@/api/finance/finance'
import Popup from '@/components/popup/index.vue'

const formData = ref<any>([])
const props = withDefaults(
    defineProps<{
        id: string | number
        title: string
    }>(),
    {
        id: '',
        title: ''
    }
)

// 获取退款日志详情
const refundlogLists = async (): Promise<void> => {
    const res = await apiRefundLogList({ id: props.id })
    formData.value = []
    res.lists.forEach((item: any) => {
        formData.value.push(item)
    })
}
</script>

<style lang="scss" scoped>
:deep() .el-table .el-table__cell {
    padding: 15px 0;
}
</style>
