<template>
    <div class="summarize">
        <!-- 经营概况 Start -->
        <el-card shadow="never" class="!border-none">
            <template #header>
                <div class="header">经营概况</div>
            </template>
            <div class="pb-[16px] pt-[16px]">
                <el-row :gutter="20">
                    <el-col :md="6" :span="12" class="flex mt-1">
                        <div class="flex">
                            <img
                                style="width: 60px; height: 60px"
                                src="@/assets/images/finance/course_total_amount.png"
                            />
                            <div class="flex-col items-center pt-[4px] ml-[10px]">
                                <div class="text-4xl font-medium">
                                    {{ financeData.order_sum || '0.00' }}
                                </div>
                                <div class="text-tx-regular text-xs">课程总收入</div>
                            </div>
                        </div>
                    </el-col>
                    <el-col :md="6" :span="12" class="flex mt-1">
                        <div class="flex">
                            <img
                                style="width: 60px; height: 60px"
                                src="@/assets/images/finance/course_order_total_num.png"
                            />
                            <div class="flex-col items-center pt-[4px] ml-[10px]">
                                <div class="text-4xl font-medium">
                                    {{ financeData.order_num || '0.00' }}
                                </div>
                                <div class="text-tx-regular text-xs">课程订单总数</div>
                            </div>
                        </div>
                    </el-col>
                    <el-col :md="6" :span="12" class="flex mt-[20px] md:mt-1">
                        <div class="flex">
                            <img
                                style="width: 60px; height: 60px"
                                src="@/assets/images/finance/refund_order_amount.png"
                            />
                            <div class="flex-col items-center pt-[4px] ml-[10px]">
                                <div class="text-4xl font-medium">
                                    {{ financeData.refund_sum || '0.00' }}
                                </div>
                                <div class="text-tx-regular text-xs">退款订单总额</div>
                            </div>
                        </div>
                    </el-col>
                    <el-col :md="6" :span="12" class="flex mt-[20px] md:mt-1">
                        <div class="flex">
                            <img
                                style="width: 60px; height: 60px"
                                src="@/assets/images/finance/refund_order_num.png"
                            />
                            <div class="flex-col items-center pt-[4px] ml-[10px]">
                                <div class="text-4xl font-medium">
                                    {{ financeData.refund_num || '0.00' }}
                                </div>
                                <div class="text-tx-regular text-xs">退款订单总数</div>
                            </div>
                        </div>
                    </el-col>
                </el-row>
            </div>
        </el-card>
        <!-- 经营概况 Start -->

        <!-- 用户概况 Start -->
        <el-card shadow="never" class="mt-4 !border-none">
            <template #header>
                <div class="header">用户概况</div>
            </template>
            <div class="pb-[16px] pt-[16px]">
                <el-row :gutter="20">
                    <el-col :md="6" :span="12" class="flex">
                        <div class="flex">
                            <img
                                style="width: 60px; height: 60px"
                                src="@/assets/images/finance/user_total_amount.png"
                            />
                            <div class="flex-col items-center pt-[4px] ml-[10px]">
                                <div class="text-4xl font-medium">
                                    {{ financeData.user_money_sum || '0.00' }}
                                </div>
                                <div class="text-tx-regular text-xs">用户总资产（元）</div>
                            </div>
                        </div>
                    </el-col>
                    <el-col :md="6" :span="12" class="flex">
                        <div class="flex">
                            <img
                                style="width: 60px; height: 60px"
                                src="@/assets/images/finance/user_total_peopel.png"
                            />
                            <div class="flex-col items-center pt-[4px] ml-[10px]">
                                <div class="text-4xl font-medium">
                                    {{ financeData.user_num || '0.00' }}
                                </div>
                                <div class="text-tx-regular text-xs">用户总人数</div>
                            </div>
                        </div>
                    </el-col>
                    <el-col :md="6" :span="12" class="flex mt-[20px] md:mt-1">
                        <div class="flex">
                            <img
                                style="width: 60px; height: 60px"
                                src="@/assets/images/finance/total_visit.png"
                            />
                            <div class="flex-col items-center pt-[4px] ml-[10px]">
                                <div class="text-4xl font-medium">
                                    {{ financeData.visitor || '0.00' }}
                                </div>
                                <div class="text-tx-regular text-xs">总访问量</div>
                            </div>
                        </div>
                    </el-col>
                </el-row>
            </div>
        </el-card>
        <!-- 用户概况 End -->
        <!-- 分销概况 Start -->
        <el-card shadow="never" class="mt-4 !border-none">
            <template #header>
                <div class="header">分销概况</div>
            </template>
            <div class="pb-[16px] pt-[16px]">
                <el-row :gutter="20">
                    <el-col :md="6" :span="12" class="flex">
                        <div class="flex">
                            <img
                                style="width: 60px; height: 60px"
                                src="@/assets/images/finance/user_total_amount.png"
                            />
                            <div class="flex-col items-center pt-[4px] ml-[10px]">
                                <div class="text-4xl font-medium">
                                    {{ financeData.today_earnings || '0.00' }}
                                </div>
                                <div class="text-tx-regular text-xs">今日入账佣金</div>
                            </div>
                        </div>
                    </el-col>
                    <el-col :md="6" :span="12" class="flex">
                        <div class="flex">
                            <img
                                style="width: 60px; height: 60px"
                                src="@/assets/images/finance/user_total_peopel.png"
                            />
                            <div class="flex-col items-center pt-[4px] ml-[10px]">
                                <div class="text-4xl font-medium">
                                    {{ financeData.wait_earnings || '0.00' }}
                                </div>
                                <div class="text-tx-regular text-xs">待结算佣金</div>
                            </div>
                        </div>
                    </el-col>
                    <el-col :md="6" :span="12" class="flex mt-[20px] md:mt-1">
                        <div class="flex">
                            <img
                                style="width: 60px; height: 60px"
                                src="@/assets/images/finance/total_visit.png"
                            />
                            <div class="flex-col items-center pt-[4px] ml-[10px]">
                                <div class="text-4xl font-medium">
                                    {{ financeData.today_wait_earnings || '0.00' }}
                                </div>
                                <div class="text-tx-regular text-xs">今日新增待结算佣金</div>
                            </div>
                        </div>
                    </el-col>
                    <el-col :md="6" :span="12" class="flex mt-[20px] md:mt-1">
                        <div class="flex">
                            <img
                                style="width: 60px; height: 60px"
                                src="@/assets/images/finance/total_visit.png"
                            />
                            <div class="flex-col items-center pt-[4px] ml-[10px]">
                                <div class="text-4xl font-medium">
                                    {{ financeData.total_earnings || '0.00' }}
                                </div>
                                <div class="text-tx-regular text-xs">累计已入账佣金</div>
                            </div>
                        </div>
                    </el-col>
                </el-row>
            </div>
        </el-card>
        <!-- 分销概况 End -->

        <!-- 充值概括 Start -->
        <el-card shadow="never" class="mt-4 !border-none">
            <template #header>
                <div class="header">充值概括</div>
            </template>
            <div class="pb-[16px] pt-[16px]">
                <el-row :gutter="20">
                    <el-col :md="6" :span="12" class="flex mt-1">
                        <div class="flex">
                            <img
                                style="width: 60px; height: 60px"
                                src="@/assets/images/finance/total_charge_amount.png"
                            />
                            <div class="flex-col items-center pt-[4px] ml-[10px]">
                                <div class="text-4xl font-medium">
                                    {{ financeData.recharge_sum || '0.00' }}
                                </div>
                                <div class="text-tx-regular text-xs">累计充值金额</div>
                            </div>
                        </div>
                    </el-col>
                    <el-col :md="6" :span="12" class="flex mt-1">
                        <div class="flex">
                            <img
                                style="width: 60px; height: 60px"
                                src="@/assets/images/finance/total_charge_num.png"
                            />
                            <div class="flex-col items-center pt-[4px] ml-[10px]">
                                <div class="text-4xl font-medium">
                                    {{ financeData.recharge_num || '0.00' }}
                                </div>
                                <div class="text-tx-regular text-xs">累计充值次数</div>
                            </div>
                        </div>
                    </el-col>
                </el-row>
            </div>
        </el-card>
        <!-- <layout-footer></layout-footer> -->
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { apiFinanceDataCenter } from '@/api/finance/finance'
import { number } from 'echarts/core'
import LayoutFooter from '@/layout/components/footer.vue'

/** Interface Start **/
interface FinanceObj {
    order_sum: number //	float	课程总收入
    order_num: number //	int	课程总订单数
    refund_sum: number //	float	课程总收入
    refund_num: number //	int	退款订单总数
    user_money_sum: number //	float	用户总资产
    user_num: number //	int	用户总人数
    visitor: number //	int	总访问量
    recharge_sum: number //	float	累计充值金额
    recharge_num: number //	int	累计充值次数
    award_sum: number //	float	累计赠送金额
    today_earnings: number //今日入账佣金
    wait_earnings: number //待结算佣金
    today_wait_earnings: number //今日新增待结算佣金
    total_earnings: number //累计已入账佣金
}
/** Interface End **/

/** Data Start **/
const financeData = ref<FinanceObj>({
    order_sum: 0,
    order_num: 0,
    refund_sum: 0,
    refund_num: 0,
    user_money_sum: 0,
    user_num: 0,
    visitor: 0,
    recharge_sum: 0,
    recharge_num: 0,
    award_sum: 0,
    today_earnings: 0,
    wait_earnings: 0,
    today_wait_earnings: 0,
    total_earnings: 0
})
/** Data End **/

/** Methods Start **/
const initFinanceDataCenter = async (): Promise<void> => {
    try {
        financeData.value = await apiFinanceDataCenter()
    } catch (error) {
        console.log('初始化财务概括=>', error)
    }
}
/** Methods End **/

/** Life Cycle Start **/
initFinanceDataCenter()
/** Life Cycle End **/
</script>

<style lang="scss" scoped>
.header {
    font-size: 14px;
    font-weight: 500;
    padding-left: 10px;
    border-left: 3px solid #4a5dff;
}
</style>
