<template>
    <div class="balance-detail">
        <el-card class="!border-none" shadow="never">
            <el-alert
                type="warning"
                title="温馨提示： 查看会员佣金钱包资金变动记录"
                :closable="false"
                show-icon
            />

            <el-form ref="formRef" class="mb-[-16px] mt-[16px]" :model="queryParams" :inline="true">
                <el-form-item label="用户信息">
                    <el-input
                        v-model="queryParams.user_info"
                        placeholder="请输入用户账号/昵称/手机号"
                        class="w-[320px]"
                        @keyup.enter="resetPage"
                    >
                    </el-input>
                </el-form-item>
                <el-form-item label="变动类型">
                    <el-select class="w-[280px]" v-model="queryParams.change_type">
                        <el-option label="全部" value></el-option>
                        <el-option
                            v-for="(item, key) in otherLists"
                            :key="key"
                            :label="item"
                            :value="key"
                        />
                        <!-- <el-option label="订单结算" value="2000"></el-option>
                        <el-option label="佣金提现" value="2010"></el-option>
                        <el-option label="提现失败" value="2020"></el-option>
                        <el-option label="系统增加佣金" value="2030"></el-option>
                        <el-option label="系统减少佣金" value="2040"></el-option> -->
                    </el-select>
                </el-form-item>
                <el-form-item label="记录时间">
                    <daterange-picker
                        v-model:startTime="queryParams.start_time"
                        v-model:endTime="queryParams.end_time"
                    />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none mt-4" shadow="never">
            <!-- 表格 -->
            <el-table
                ref="tableData"
                :data="pager.lists"
                style="width: 100%"
                v-loading="pager.loading"
            >
                <el-table-column property="sn" label="用户学号" max-width="180" />
                <el-table-column property="nickname" label="用户昵称" max-width="140">
                    <template #default="{ row }">
                        <div class="flex items-center">
                            <el-image class="w-[50px] h-[50px]" :src="row.avatar"></el-image>
                            <div class="ml-2">{{ row.nickname || '-' }}</div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column property="change_amount" label="变动金额" max-width="140">
                    <template #default="scope">
                        <span v-if="scope.row.action === 1" class="red"
                            >+{{ scope.row.change_amount }}</span
                        >
                        <span v-else>-{{ scope.row.change_amount }}</span>
                    </template>
                </el-table-column>
                <el-table-column property="left_amount" label="剩余金额" max-width="140" />
                <el-table-column property="change_type_desc" label="变动类型" max-width="140" />
                <el-table-column property="association_sn" label="来源" max-width="140" />
                <el-table-column property="create_time" label="记录时间" max-width="140" />
            </el-table>
            <div class="flex justify-end mt-4">
                <pagination v-model="pager" @change="getLists" />
            </div>
        </el-card>
    </div>
</template>
<script lang="ts" setup>
// import { commissionDetail } from '@/api/finance/finance'
import { apiAccountLogLists, apiAccountLogOtherLists } from '@/api/finance/finance'
import { usePaging } from '@/hooks/usePaging'

const queryParams = reactive({
    user_info: '', //关键词
    change_type: '',
    start_time: '',
    end_time: '',
    change_object: 2
})

const otherLists = ref({})

const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: apiAccountLogLists,
    params: queryParams
})

const initAccountLogOtherLists = async (): Promise<void> => {
    otherLists.value = await apiAccountLogOtherLists({ change_object: 2 })
}

onMounted(() => {
    initAccountLogOtherLists()
    getLists()
})
</script>
