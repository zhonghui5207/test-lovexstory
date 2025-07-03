<!-- 交易设置 -->
<template>
    <div>
        <!-- Main Form Start -->
        <el-form ref="formRef" :model="formData" label-width="140px">
            <el-card shadow="never" class="mt-4 !border-none">
                <el-form-item label="系统取消待付款订单">
                    <div>
                        <div>
                            <el-radio :label="0" v-model="formData.cancel_unpaid_orders"
                                >关闭系统自动取消待付款订单</el-radio
                            >
                        </div>
                        <div>
                            <el-radio :label="1" v-model="formData.cancel_unpaid_orders">
                                <span class="mr-1">订单提交后</span>
                                <el-input
                                    class="ls-input"
                                    v-model="formData.cancel_unpaid_orders_times"
                                ></el-input>
                                <span class="ml-1">分钟内未付款，系统自动取消</span>
                            </el-radio>
                        </div>
                    </div>
                </el-form-item>
            </el-card>
            <el-card shadow="never" class="mt-4 !border-none">
                <div class="text-lg font-bold">购买设置</div>
                <el-form-item label="购买按钮" class="mt-4">
                    <el-radio-group v-model="formData.is_buy_btn">
                        <el-radio :label="1">允许购买</el-radio>
                        <el-radio :label="2">不允许购买</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="按钮文字">
                    <el-input class="w-[240px]" v-model="formData.is_buy_btn_desc"></el-input>
                </el-form-item>
            </el-card>
        </el-form>
        <!-- Main Form End -->

        <!-- Footer Start -->
        <footer-btns>
            <el-button type="primary" @click="onSubmit">保存</el-button>
        </footer-btns>
        <!-- Footer End -->
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { apiOrderConfigGet, apiOrderConfigSet } from '@/api/setting/transaction'
import FooterBtns from '@/components/footer-btns/index.vue'

/** Interface Start **/
interface FormDataObj {
    cancel_unpaid_orders: number //系统取消待付款订单 0-关闭系统自动取消待付款订单 1-订单提交{cancel_unpaid_orders_times}分钟内未付款，系统自动取消
    cancel_unpaid_orders_times: number // 取消未付款订单时间,单位：分钟
    cancel_unshipped_orders: number // 买家取消待发货订单 0-关闭买家取消待发货订单 1-待发货订单{cancel_unshipped_orders_times}分钟内允许买家取消
    cancel_unshipped_orders_times: number // 取消待发货订单时间,单位：分钟
    is_buy_btn: number //1-允许购买 2-不允许购买
    is_buy_btn_desc: string //按钮文字
}
/** Interface End **/

/** Data Start **/
const formData = ref<FormDataObj>({
    cancel_unpaid_orders: 0,
    cancel_unpaid_orders_times: 0,
    cancel_unshipped_orders: 0,
    cancel_unshipped_orders_times: 0,
    is_buy_btn: 1,
    is_buy_btn_desc: ''
})
/** Data End **/

/** Methods Start **/
/**
 * @description 获取交易设置
 */
const initOrderConfig = async (): Promise<void> => {
    try {
        formData.value = await apiOrderConfigGet()
    } catch (error) {
        console.log('获取交易设置', error)
    }
}
/**
 * @description 编辑交易设置
 */
const handleOrderConfigEdit = async (): Promise<void> => {
    try {
        await apiOrderConfigSet({ ...formData.value })
        initOrderConfig()
    } catch (error) {
        console.log('编辑交易设置', error)
    }
}
/**
 * @description 提交数据
 */
const onSubmit = (): void => {
    handleOrderConfigEdit()
}
/** Methods End **/

/** LifeCycle Start **/
initOrderConfig()
/** LifeCycle End **/
</script>

<style lang="scss" scoped>
.ls-input {
    width: 280px;
}
</style>
