<template>
    <div class="charge-record">
        <el-card shadow="never" class="!border-none">
            <el-alert
                title="温馨提示： 会员账户余额变动记录"
                type="success"
                :closable="false"
                show-icon
            />

            <!-- Header Form Start -->
            <el-form :model="formData" label-width="90px" inline class="mt-[16px]">
                <el-form-item label="充值单号">
                    <el-input
                        class="w-[280px]"
                        v-model="formData.sn"
                        placeholder="请输入充值单号"
                    />
                </el-form-item>
                <el-form-item label="用户信息">
                    <el-input
                        class="w-[280px]"
                        v-model="formData.keyword"
                        placeholder="请输入用户信息"
                    />
                </el-form-item>
                <el-form-item label="支付方式">
                    <el-select v-model="formData.pay_way" class="w-[280px]" placeholder="请选择">
                        <el-option label="全部" value></el-option>
                        <el-option label="微信支付" :value="1"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="支付状态">
                    <el-select v-model="formData.pay_status" class="w-[280px]" placeholder="请选择">
                        <el-option label="全部" value></el-option>
                        <el-option label="未支付" :value="0"></el-option>
                        <el-option label="已支付" :value="1"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="下单时间">
                    <data-picker
                        v-model:start_time="formData.start_time"
                        v-model:end_time="formData.end_time"
                    >
                    </data-picker>
                </el-form-item>
                <el-form-item>
                    <div class="ml-[20px]">
                        <el-button type="primary" @click="resetPage">查询</el-button>
                        <el-button @click="resetParams">重置</el-button>
                    </div>
                </el-form-item>
            </el-form>
            <!-- Header Form End -->
        </el-card>

        <el-card shadow="never" class="!border-none mt-4">
            <!-- Main TableData Start -->
            <div class="m-t-20">
                <el-table
                    ref="tableData"
                    :data="pager.lists"
                    style="width: 100%"
                    v-loading="pager.loading"
                >
                    <!-- <el-table-column label="ID" prop="id"></el-table-column> -->
                    <el-table-column label="用户信息" min-width="180">
                        <template #default="{ row }">
                            <div class="flex items-center">
                                <el-image class="w-[50px] h-[50px]" :src="row.avatar"></el-image>
                                <div class="ml-2">{{ row.nickname || '-' }}</div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column property="sn" label="订单编号" min-width="140" />
                    <el-table-column property="order_amount" label="充值金额" max-width="140" />
                    <el-table-column property="pay_way" label="支付方式" max-width="140" />
                    <el-table-column property="pay_status" label="支付状态" max-width="140">
                        <template #default="{ row }">
                            <div>
                                <div
                                    v-if="row.pay_status == '未支付'"
                                    :class="{ 'text-danger': row.pay_status == '未支付' }"
                                >
                                    未支付
                                </div>
                                <div v-else>已支付</div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column property="create_time" label="下单时间" min-width="140" />
                    <el-table-column property="pay_time" label="支付时间" min-width="140" />
                </el-table>
            </div>
            <!-- Main TableData End -->

            <!-- Footer Pagination Start -->
            <div class="flex justify-end mr-2">
                <pagination v-model="pager" @change="getLists" />
            </div>
            <!-- Footer Pagination End -->
        </el-card>
    </div>
    <!-- <layout-footer></layout-footer> -->
</template>

<script lang="ts" setup>
import { apiRechargeRecord } from '@/api/finance/finance'
import { ref } from 'vue'
import Pagination from '@/components/pagination/index.vue'
import { usePaging } from '@/hooks/usePaging'
import DataPicker from '@/components/data-picker/index.vue'
import LayoutFooter from '@/layout/components/footer.vue'

/** Interface Start **/
interface FormDataObj {
    sn: number | any // 充值订单编号
    keyword?: string // 用户信息
    pay_way: number | string // 支付方式
    pay_status?: number | string // 支付状态：0-待支付 1-已支付
    start_time: string // 开始下单时间
    end_time: string // 结束下单时间
}
/** Interface End **/

/** Data Start **/
// 表单数据
const formData = ref<FormDataObj>({
    sn: '',
    keyword: '',
    pay_way: 1,
    pay_status: '',
    start_time: '',
    end_time: ''
})
const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: apiRechargeRecord,
    params: formData.value
})

/** Data End **/

/** LifeCycle Start **/
getLists()
/** LifeCycle End **/
</script>

<style lang="scss" scoped>
.ls-input {
    width: 170px;
}
.el-alert--success.is-light {
    background-color: #edefff;
    color: #4a5dff;
}
</style>
