<template>
    <div class="center">
        <el-card shadow="never" class="!border-none">
            <div class="ml-4 mt-8 pb-6 flex">
                <div class="flex flex-1">
                    <div class="flex flex-col">
                        <div class="text">{{ '累计退款金额（元）' }}</div>
                        <div class="flex">
                            <div class="amount mt-[6px]">
                                {{ pager.extend.total_refund_amount }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-1">
                    <div class="flex flex-col">
                        <div class="text">{{ '退款中金额（元）' }}</div>
                        <div class="flex">
                            <div class="amount mt-[6px]">
                                {{ pager.extend.refund_ing_amount }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-1">
                    <div class="flex flex-col">
                        <div class="text">{{ '退款成功金额（元）' }}</div>
                        <div class="flex">
                            <div class="amount mt-[6px]">
                                {{ pager.extend.refund_success_amount }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-1">
                    <div class="flex flex-col">
                        <div class="text">{{ '退款失败金额（元）' }}</div>
                        <div class="flex">
                            <div class="amount mt-[6px]">
                                {{ pager.extend.refund_fail_amount }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </el-card>

        <el-card shadow="never" class="!border-none my-4">
            <el-form class="ls-form" :model="formData" inline>
                <el-form-item label="退款编号">
                    <el-input class="ls-input" v-model="formData.refund_sn" placeholder="请输入" />
                </el-form-item>
                <el-form-item label="来源单号">
                    <el-input class="ls-input" v-model="formData.source_sn" placeholder="请输入" />
                </el-form-item>
                <el-form-item label="买家信息">
                    <el-input class="ls-input" v-model="formData.user_info" placeholder="请输入" />
                </el-form-item>
                <el-form-item label="退款类型">
                    <el-select v-model="formData.refund_type" class="ls-input" placeholder="">
                        <el-option label="全部" value></el-option>
                        <el-option
                            v-for="(value, key, index) in dropDownList"
                            :key="index"
                            :label="value"
                            :value="key"
                        ></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="记录时间">
                    <data-picker
                        style="width: 280px"
                        v-model:start_time="formData.start_time"
                        v-model:end_time="formData.end_time"
                    ></data-picker>
                </el-form-item>
                <el-form-item>
                    <div class="flex">
                        <el-button type="primary" @click="resetPage">查询</el-button>
                        <el-button @click="resetParams">重置</el-button>
                    </div>
                </el-form-item>
            </el-form>
        </el-card>

        <el-card class="mt-4 !border-none" shadow="never">
            <el-tabs class="-mt-2" v-model="formData.refund_status" @tab-change="resetPage">
                <el-tab-pane :label="`全部(${pager.extend?.all_count})`" name="0"> </el-tab-pane>
                <el-tab-pane :label="`退款中(${pager.extend?.refund_wait_count})`" name="1">
                </el-tab-pane>
                <el-tab-pane :label="`退款成功(${pager.extend?.refund_success_count})`" name="2">
                </el-tab-pane>
                <el-tab-pane :label="`退款失败(${pager.extend?.refund_fail_count})`" name="3">
                </el-tab-pane>
            </el-tabs>
            <div class="mt-4">
                <el-table :data="pager.lists" size="large" v-loading="pager.loading">
                    <el-table-column label="ID" property="id" min-width="80" />

                    <el-table-column label="退款编号" property="refund_sn" min-width="140" />

                    <el-table-column label="买家信息" min-width="200">
                        <template #default="scope">
                            <div class="flex items-center">
                                <el-image
                                    style="width: 60px; height: 60px"
                                    :src="scope.row.user.avatar"
                                    :preview-src-list="[scope.row.user.avatar]"
                                    :hide-on-click-modal="true"
                                    :preview-teleported="true"
                                    :fit="'cover'"
                                ></el-image>
                                <div class="ml-2">{{ scope.row.user.nickname }}</div>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="来源单号" prop="source_sn" min-width="180" />

                    <el-table-column label="退款金额" min-width="160">
                        <template #default="{ row }">
                            <span>{{ `￥${row.refund_amount}` }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column label="退款类型" prop="type_desc" min-width="160" />

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

                    <el-table-column label="操作" width="200" fixed="right">
                        <template #default="scope">
                            <div class="flex items-center">
                                <logForm
                                    :id="scope.row.id"
                                    :title="'退款日志'"
                                    @refresh="getLists"
                                    v-perms="['order.orderRefund/logLists']"
                                ></logForm>
                                <div v-if="scope.row.refund_status == 2">
                                    <popup
                                        class="-mt-4 inline"
                                        @confirm="handleRefund(scope.row.id)"
                                        content="确定要重新退款吗？"
                                        title="重新退款"
                                    >
                                        <template #trigger>
                                            <el-link type="primary" :underline="false"
                                                >重新退款</el-link
                                            >
                                        </template>
                                    </popup>
                                </div>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </div>

            <div class="flex mt-4 justify-end">
                <pagination v-model="pager" @change="getLists" />
            </div>
        </el-card>
        <!-- <LayoutFooter></LayoutFooter> -->
    </div>
</template>

<script lang="ts" setup>
import { apiRefundList, apiReRefund, apiDropDownList } from '@/api/finance/finance'
import Pagination from '@/components/pagination/index.vue'
import { usePaging } from '@/hooks/usePaging'
import logForm from './components/log-form.vue'
import LayoutFooter from '@/layout/components/footer.vue'

const formData = ref<any>({
    refund_sn: '', // 退款编号
    source_sn: '', // 来源编号
    user_info: '', // 买家信息
    refund_type: '', // 退款类型:1-系统取消订单;2-后台取消订单;3-用户取消订单
    refund_status: '0', // 退款状态:0-全部;1-退款中;2-退款成功;3-退款失败
    start_time: '', // 开始时间
    end_time: '' // 结束时间
})
//下拉列表数据
const dropDownList: any = ref()
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: apiRefundList,
    params: formData.value
})

// 重新退款
const handleRefund = async (id: number) => {
    console.log(id)
    await apiReRefund({ id })
}
//
const getDropDownList = async () => {
    dropDownList.value = await apiDropDownList()
}

onMounted(async () => {
    await getLists()
    await getDropDownList()
})
</script>

<style lang="scss" scoped>
.ls-input {
    width: 280px;
}
.amount {
    font-family: 'PingFang HK';
    font-weight: 400;
    font-size: 32px;
    text-align: left;
    color: #333;
}
.text {
    font-family: 'PingFang HK';
    font-weight: 400;
    font-size: 14px;
    text-align: left;
    color: #666;
}
.el-tabs__nav-wrap::after {
    height: 1px !important;
}
</style>
