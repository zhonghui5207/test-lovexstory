<template>
    <el-card shadow="never" class="!border-none">
        <!-- Header Form Start -->
        <el-form :model="formData" label-width="90px" inline>
            <el-form-item label="订单编号">
                <el-input class="w-[280px]" v-model="formData.sn" placeholder="请输入订单编号" />
            </el-form-item>
            <el-form-item label="用户信息">
                <el-input
                    class="w-[280px]"
                    v-model="formData.keyword"
                    placeholder="请输入用户信息"
                />
            </el-form-item>
            <el-form-item label="支付状态">
                <el-select v-model="formData.pay_status" class="w-[280px]" placeholder="请选择">
                    <el-option label="全部" value></el-option>
                    <el-option
                        v-for="(item, index) in dropDownData.pay_status"
                        :key="index"
                        :label="item"
                        :value="index"
                    ></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="退款状态">
                <el-select v-model="formData.refund_status" class="w-[280px]" placeholder="请选择">
                    <el-option label="全部" value></el-option>
                    <el-option
                        v-for="(item, index) in dropDownData.refund_status"
                        :key="index"
                        :label="item"
                        :value="index"
                    ></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="下单时间">
                <!-- <data-picker
                    v-model:start_time="formData.start_time"
                    v-model:end_time="formData.end_time"
                >
                </data-picker> -->
                <daterange-picker
                    v-model:startTime="formData.start_time"
                    v-model:endTime="formData.end_time"
                >
                </daterange-picker>
            </el-form-item>
            <el-form-item label="课程类型">
                <el-select v-model="formData.course_type" class="w-[280px]" placeholder="请选择">
                    <el-option label="全部" value></el-option>
                    <el-option
                        v-for="(item, index) in dropDownData.couser_type"
                        :key="index"
                        :label="item"
                        :value="index"
                    ></el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <div class="ml-[20px]">
                    <el-button type="primary" @click="resetPage" v-perms="['order.order/lists']"
                        >查询</el-button
                    >
                    <el-button @click="resetParams" v-perms="['order.order/reset']">重置</el-button>
                </div>
            </el-form-item>
        </el-form>
        <!-- Header Form End -->
    </el-card>

    <el-card shadow="never" class="mt-4 !border-none">
        <!-- Header Tabs Start -->
        <el-tabs v-model="formData.type" @tab-click="tabChange">
            <el-tab-pane
                class="radio-group"
                v-for="(item, name, index) in extend"
                :key="index"
                :label="orderMode[name] + '(' + item + ')'"
                :name="index"
            ></el-tab-pane>
        </el-tabs>
        <!-- Header Tabs End -->

        <!-- Main TableData Start -->
        <div class="mt-4">
            <el-table
                ref="tableData"
                :data="pager.lists"
                style="width: 100%"
                v-loading="pager.loading"
            >
                <el-table-column property="sn" label="订单编号" min-width="180" />
                <el-table-column property="nickname" label="用户信息" min-width="160">
                    <template #default="scope">
                        <el-popover placement="top-start" width="200px" trigger="hover">
                            <div class="flex">
                                <span class="flex-none mr-[20px]">头像：</span>
                                <el-image
                                    :src="scope.row.avatar"
                                    style="width: 40px; height: 40px; border-radius: 50%"
                                >
                                </el-image>
                            </div>
                            <div class="flex mt-[20px]">
                                <span class="flex-none mr-[20px]">昵称：</span>
                                <span>{{ scope.row.nickname }}</span>
                            </div>
                            <div class="flex mt-[20px]">
                                <span class="flex-none mr-[20px]">编号：</span>
                                <span>{{ scope.row.user_sn }}</span>
                            </div>
                            <template #reference>
                                <router-link :to="`/consumer/detail?id=${scope.row.user_id}`">
                                    <div class="pointer normal">
                                        {{ scope.row.nickname }}
                                    </div>
                                </router-link>
                            </template>
                        </el-popover>
                    </template>
                </el-table-column>
                <el-table-column label="课程信息" min-width="250">
                    <template #default="scope">
                        <div
                            class="goods-box flex items-center"
                            v-for="(item2, index2) in scope.row.order_course"
                            :key="index2"
                        >
                            <div>
                                <el-image
                                    style="width: 80px; height: 60px"
                                    :src="item2.course_snap?.cover"
                                    :preview-src-list="[item2.course_snap?.cover]"
                                    :hide-on-click-modal="true"
                                    :preview-teleported="true"
                                    :fit="'contain'"
                                ></el-image>
                            </div>
                            <div class="ml-[10px]">
                                <el-tooltip
                                    class="box-item"
                                    effect="dark"
                                    :content="item2.course_snap?.name"
                                    placement="top-start"
                                >
                                    <div class="goods-name text_hidden">
                                        {{ item2.course_snap?.name }}
                                    </div>
                                </el-tooltip>
                                <div class="muted">¥{{ item2.course_snap?.sell_price }}</div>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column property="order_amount" label="实付金额" min-width="120">
                    <template #default="scope"> ¥{{ scope.row.order_amount }} </template>
                </el-table-column>
                <el-table-column property="pay_status_desc" label="支付状态" min-width="125" />
                <el-table-column label="订单状态" min-width="125">
                    <template v-slot="{ row }">
                        <span :class="{ error: row.order_status_desc === '待支付' }">{{
                            row.order_status_desc
                        }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="退款状态" min-width="125">
                    <template v-slot="{ row }">
                        <span :class="{ error: row.refund_status_desc === '已退款' }">{{
                            row.refund_status_desc
                        }}</span>
                    </template>
                </el-table-column>
                <el-table-column fixed="right" property="address" label="操作" min-width="150">
                    <template #default="scope">
                        <div class="flex items-center">
                            <router-link
                                :to="{
                                    path: 'detail',
                                    query: {
                                        id: scope.row.id
                                    }
                                }"
                            >
                                <el-button
                                    v-perms="['order.order/detail']"
                                    type="primary"
                                    link
                                    class="mr-[10px]"
                                    >详情</el-button
                                >
                            </router-link>
                            <!-- 操作 -->
                            <operation
                                btnStyle="text"
                                :id="scope.row.id"
                                :cancel_btn="scope.row.cancel_btn"
                                :del_btn="scope.row.del_btn"
                                :shop_remark="scope.row.shop_remark"
                                @refresh="resetPage"
                            />
                        </div>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <!-- Main TableData End -->

        <!-- Footer Pagination Start -->
        <div class="flex justify-end mr-2">
            <pagination v-model="pager" @change="getLists" />
        </div>
        <!-- Footer Pagination End -->
    </el-card>
    <!-- <layout-footer></layout-footer> -->
</template>

<script lang="ts" setup>
import { apiOrderLists, apiOrderOtherLists } from '@/api/order/order'
import { reactive } from 'vue'
import { OrderMode } from '@/utils/enum'
import Operation from './components/operation.vue'
import Pagination from '@/components/pagination/index.vue'
import DataPicker from '@/components/data-picker/index.vue'
import LayoutFooter from '@/layout/components/footer.vue'
import { usePaging } from '@/hooks/usePaging'

/** Interface Start **/
interface FormDataObj {
    sn: string //否	string	订单编号
    keyword: string //否	string	用户信息
    pay_status: string | number //否	int	支付状态;0-待支付;1-已支付;
    refund_status: string | number //是	string	退款状态（参数调用otherlist接口）
    start_time: string //否	string	开始时间
    end_time: string //否	string	结束时间
    type: string | number //否	int	订单状态;0-待支付;1-预约中;2-服务中;3-已完成;4-已关闭
    course_type: string | number //课程类型
}
/** Interface End **/

/** Data Start **/
const orderMode: any = reactive<any>(OrderMode)
const dropDownData: any = ref({})
const formData = reactive<FormDataObj>({
    sn: '',
    keyword: '',
    pay_status: '',
    refund_status: '',
    start_time: '',
    end_time: '',
    type: 0,
    course_type: ''
})
const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: apiOrderLists,
    params: formData
})
const extend = reactive<any>({
    all_num: '',
    waitpay_num: '',
    complete_num: '',
    close_num: ''
})
/** Data End **/

/** Methods Start **/
/**
 * @description 获取订单其它列表
 */
const getOtherLists = async (): Promise<void> => {
    const res = await apiOrderOtherLists()
    dropDownData.value = res
    Object.keys(extend).map((item) => {
        extend[item] = res[item]
    })
}
/** Methods End **/

/** Methods Start **/
/**
 * @description tab栏改变
 */
const tabChange = () => {
    setTimeout(() => {
        getLists()
    }, 0)
}
/** Methods End **/

/** LifeCycle Start **/
getLists()
getOtherLists()
/** LifeCycle End **/
</script>

<style lang="scss" scoped>
.ls-input {
    width: 240px;
}

.error {
    color: #db2828;
}

.goods-box {
    .goods-name {
        color: #333333;
    }
}
.text_hidden {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
    overflow: hidden;
}
</style>
