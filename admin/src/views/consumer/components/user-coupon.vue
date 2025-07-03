<template>
    <Popup ref="popRef" title="发放优惠券" width="700px" @confirm="confirm" @close="$emit('close')">
        <div>
            <el-input
                placeholder="请输入优惠券名称"
                class="w-[240px]"
                v-model="queryParams.name"
                @input="getLists"
            >
                <template #append>
                    <el-button :icon="Search" />
                </template>
            </el-input>
            <el-table
                ref="tableRef"
                class="mt-2"
                :data="pager.lists"
                @selection-change="tableSelect"
                :row-key="getRowKey"
            >
                <el-table-column type="selection" :reserve-selection="true" width="55" />
                <el-table-column label="优惠券名称" min-width="180" prop="name" />
                <el-table-column label="优惠券内容" min-width="180" prop="content" />
                <el-table-column label="发放方式" min-width="180">
                    <template #default="{ row }">
                        <div>{{ row.receivingMethod == 1 ? '用户领取' : '系统赠送' }}</div>
                    </template>
                </el-table-column>
            </el-table>
            <div class="flex justify-end mt-4">
                <pagination
                    v-model="pager"
                    @change="getLists"
                    layout="total, prev, pager, next, jumper"
                />
            </div>
        </div>
    </Popup>
</template>
<script setup lang="ts">
import { Search } from '@element-plus/icons-vue'
import { usePaging } from '@/hooks/usePaging'
import { apiCouponList, provideCoupon } from '@/api/application/coupon'
import { map } from 'lodash'
import feedback from '@/utils/feedback'
const popRef = shallowRef()
const emits = defineEmits(['close'])
//搜索
const queryParams = ref({ name: '', get_type: 2, status: 2 })
//多选选中数据
const isSelectIds: any = ref([])
//用户id
const userId: any = ref()

const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: apiCouponList,
    params: queryParams.value,
    size: 5
})

//表单多选选择
const tableSelect = (value: any) => {
    isSelectIds.value = value.map((item: any) => String(item.id))
}

//获取rowkey
const getRowKey = (item: any) => {
    return item.id
}

//确定
const confirm = async () => {
    await provideCoupon({ send_user: [userId.value], id: isSelectIds.value, send_user_num: 1 })
    // feedback.msgSuccess('发放成功！')
}

const open = async (id: any) => {
    getLists()
    userId.value = id
    popRef.value.open()
}

defineExpose({ open })
</script>
<style lang="scss" scoped></style>
