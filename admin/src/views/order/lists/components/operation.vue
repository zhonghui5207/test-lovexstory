<template>
    <!-- 列表里的操作 -->
    <div v-if="btnStyle === 'text'" class="inline">
        <!-- 下拉 -->
        <el-dropdown>
            <el-button class="mt-[3px]" type="primary" link>
                更多
                <el-icon class="el-icon--right">
                    <arrow-down />
                </el-icon>
            </el-button>
            <template #dropdown>
                <el-dropdown-menu>
                    <!-- 删除订单 -->
                    <el-dropdown-item v-if="del_btn">
                        <popup
                            class="mr-[10px] inline"
                            @confirm="handleDelete"
                            v-perms="['order.order/del']"
                        >
                            <template #trigger>
                                <el-button :type="btnStyle" link class="order_btn">删除</el-button>
                            </template>
                        </popup>
                    </el-dropdown-item>

                    <!-- 商家备注 -->
                    <el-dropdown-item>
                        <popup
                            class="mr-[10px] inline"
                            width="45vw"
                            :center="true"
                            @confirm="handleRemark"
                            v-perms="['order.order/remark']"
                        >
                            <template #trigger>
                                <el-button :type="btnStyle" link class="order_btn"
                                    >商家备注</el-button
                                >
                            </template>
                            <div style="height: 300px">
                                <el-form ref="orderFormRef" :model="orderForm" label-width="120px">
                                    <el-form-item label="商家备注:">
                                        <el-input
                                            class="ls-input"
                                            type="textarea"
                                            v-model="orderForm.shop_remark"
                                            placeholder="请输入"
                                            :rows="10"
                                        ></el-input>
                                    </el-form-item>
                                </el-form>
                            </div>
                        </popup>
                    </el-dropdown-item>
                </el-dropdown-menu>
            </template>
        </el-dropdown>
    </div>

    <!-- 详情里的操作 -->
    <div v-else class="mt-4 flex">
        <popup class="mr-[10px] inline" @confirm="handleCancel" v-if="cancel_btn">
            <template #trigger>
                <el-button type="danger">取消订单</el-button>
            </template>
            确认要取消订单吗？
        </popup>
        <!-- 商家备注 -->
        <popup class="mr-[10px] inline" width="45vw" :center="true" @confirm="handleRemark">
            <template #trigger>
                <el-button>商家备注</el-button>
            </template>
            <div style="height: 300px">
                <el-form ref="orderFormRef" :model="orderForm" label-width="120px">
                    <el-form-item label="商家备注:">
                        <el-input
                            class="ls-input"
                            type="textarea"
                            v-model="orderForm.shop_remark"
                            placeholder="请输入"
                            :rows="10"
                        ></el-input>
                    </el-form-item>
                </el-form>
            </div>
        </popup>
        <!-- 退款按钮 -->

        <el-button v-if="refund_btn" type="primary" @click="handleRefund">售后退款</el-button>
    </div>
</template>

<script lang="ts" setup>
import { apiOrderDel, apiOrderRefund, apiOrderCancel, apiOrderRemark } from '@/api/order/order'
import Popup from '@/components/popup/index.vue'
import feedback from '@/utils/feedback'
import { reactive, watch } from 'vue'

/** Emit Start **/
const emit = defineEmits(['refresh'])
/** Emit End **/

/** Props Start **/
const props = withDefaults(
    defineProps<{
        id: string | number // 订单ID
        btnStyle: string // 按钮样式
        cancel_btn: number // 取消订单
        refund_btn: number // 退款按钮
        del_btn: number // 删除订单
        shop_remark: string // 商家备注
    }>(),
    {
        id: '',
        btnStyle: 'primary',
        cancel_btn: 0,
        refund_btn: 0,
        del_btn: 0,
        shop_remark: ''
    }
)
/** Props End **/

/** Data Start **/
const orderForm = reactive({
    shop_remark: '' as string
})
/** Data End **/

/** Methods Start **/
/**
 * @description 取消订单
 */
const handleCancel = async (): Promise<void> => {
    try {
        await apiOrderCancel({ id: props.id })
        emit('refresh')
    } catch (error) {
        console.log('取消订单=>', error)
    }
}
/**
 * @description 退款订单
 */
const handleRefund = async (): Promise<void> => {
    await feedback.confirm('售后退款成功后，系统将自动回收该订单的课程，请谨慎操作！')
    await apiOrderRefund({ id: props.id })
    emit('refresh')
}
/**
 * @description 删除订单
 */
const handleDelete = async (): Promise<void> => {
    try {
        await apiOrderDel({ id: props.id })
        emit('refresh')
    } catch (error) {
        console.log('删除订单=>', error)
    }
}
/**
 * @description 提交商家备注
 */
const handleRemark = async (): Promise<void> => {
    try {
        await apiOrderRemark({ id: props.id, remark: orderForm.shop_remark })
        emit('refresh')
    } catch (error) {
        console.log('提交商家备注=>', error)
    }
}
/** Methods End **/

/** Data Start **/
watch(
    () => props.shop_remark,
    (value) => {
        orderForm.shop_remark = value
    },
    { immediate: true }
)
/** Data End **/
</script>

<style lang="scss" scoped>
.ls-input {
    width: 30vw;
}

.order_btn {
    color: #101010;
}
.order_btn:hover {
    color: #4a5dff;
}
</style>
