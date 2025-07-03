<template>
    <div>
        <el-card class="!border-none" shadow="never">
            <!-- <el-alert type="warning" title="分销订单明细。" :closable="false" show-icon /> -->
            <el-form ref="formRef" class="mb-[-16px] mt-4" :model="queryParams" :inline="true">
                <el-form-item label="订单编号">
                    <el-input
                        class="w-[280px]"
                        v-model="queryParams.order_sn"
                        placeholder="请输入订单编号"
                        clearable
                    />
                </el-form-item>
                <el-form-item label="商品搜索">
                    <el-input
                        class="w-[280px]"
                        v-model="queryParams.course_info"
                        placeholder="请输入商品名称"
                        clearable
                    />
                </el-form-item>
                <el-form-item label="买家信息">
                    <el-input
                        class="w-[280px]"
                        v-model="queryParams.user_info"
                        placeholder="请输入买家信息"
                        clearable
                    />
                </el-form-item>
                <el-form-item label="分销商">
                    <el-input
                        class="w-[280px]"
                        v-model="queryParams.distributor_info"
                        placeholder="请输入分销商信息"
                        clearable
                    />
                </el-form-item>
                <el-form-item label="佣金状态">
                    <el-select class="w-[280px]" v-model="queryParams.status">
                        <el-option label="全部" value />
                        <el-option label="待结算" :value="1" />
                        <el-option label="已结算" :value="2" />
                        <el-option label="已失效" :value="3" />
                    </el-select>
                </el-form-item>
                <el-form-item label="下单时间">
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
            <el-table size="large" v-loading="pager.loading" :data="pager.lists">
                <el-table-column label="用户信息" prop="sn" min-width="180">
                    <template #default="{ row }">
                        <div>
                            <el-popover placement="top" trigger="hover">
                                <template #reference>
                                    <div>{{ row.user_nickname }}</div>
                                </template>
                                <div>
                                    <el-form>
                                        <el-form-item label="头像">
                                            <el-image
                                                class="w-[44px] h-[44px] rounded-full"
                                                :src="row.user_avatar"
                                            ></el-image>
                                        </el-form-item>
                                        <el-form-item label="昵称">
                                            <view>{{ row.user_nickname }}</view>
                                        </el-form-item>
                                        <el-form-item label="账号">
                                            <view>{{ row.user_account }}</view>
                                        </el-form-item>
                                    </el-form>
                                </div>
                            </el-popover>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="订单编号" prop="order_sn" min-width="180">
                    <template #default="{ row }">
                        <div>{{ row.order_sn }}</div>
                    </template>
                </el-table-column>
                <el-table-column label="商品信息" min-width="230">
                    <template #default="{ row }">
                        <div class="flex items-center">
                            <el-image
                                class="h-[64px] w-[64px] rounded flex-none"
                                :src="row.course_cover"
                            ></el-image>
                            <div class="ml-2 truncate">{{ row.course_name }}</div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="单价/数量" min-width="120">
                    <template #default="{ row }">
                        <div>¥{{ row.sell_price }}</div>
                        <div>x{{ row.course_num }}</div>
                    </template>
                </el-table-column>
                <el-table-column label="实付金额" min-width="120">
                    <template #default="{ row }">
                        <div>¥{{ row.order_amount }}</div>
                    </template>
                </el-table-column>
                <el-table-column label="当前分销等级" prop="commission_level" min-width="120" />
                <el-table-column label="分销商信息" min-width="150">
                    <template #default="{ row }">
                        <router-link
                            :to="`/app/distribute/distributor/detail?id=${row.distributor_id}`"
                        >
                            <el-button type="primary" link>{{
                                row.distributor_nickname || '-'
                            }}</el-button>
                        </router-link>
                    </template>
                </el-table-column>
                <el-table-column label="当前佣金比例（%）" prop="ratio" min-width="120" />
                <el-table-column label="佣金金额" prop="earnings" min-width="120" />
                <el-table-column label="佣金状态" prop="status_desc" min-width="120" />
                <el-table-column label="结算时间" prop="settlement_time" min-width="120">
                    <template #default="{ row }">
                        <div>{{ row.settlement_time || '-' }}</div>
                    </template>
                </el-table-column>
                <el-table-column label="下单时间" prop="create_time" min-width="120" />
            </el-table>
            <div class="flex justify-end mt-4">
                <pagination v-model="pager" @change="getLists" />
            </div>
        </el-card>
    </div>
</template>
<script setup lang="ts">
import { usePaging } from '@/hooks/usePaging'
import { getorder } from '@/api/distribution/distribution'
const queryParams: any = ref({
    order_sn: '', //订单编号
    user_info: '', //买家信息
    distributor_info: '', //分销商信息
    course_info: '', //商品信息
    status: '', //佣金状态
    start_time: '', //开始时间
    end_time: '' //结束时间
})

//分页组件
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: getorder,
    params: queryParams.value
})

onMounted(() => {
    getLists()
})
</script>
