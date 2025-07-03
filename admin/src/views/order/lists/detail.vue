<template>
    <!-- Header Start -->
    <el-card shadow="never" class="!border-none">
        <el-page-header content="订单详情" @back="$router.back()" />
    </el-card>
    <!-- Header End -->

    <div class="order-detail-main">
        <!-- 订单信息 Start -->
        <el-card shadow="never" class="mt-4 !border-none" style="padding: 0 20px">
            <template #header>
                <div class="card-header">
                    <span class="font-medium">订单信息</span>
                </div>
            </template>
            <!-- 订单信息 -->
            <el-form :inline="true" :model="formData" label-width="120px">
                <el-form-item label="订单状态: ">
                    <div class="content">{{ formData.order_status_desc || '-' }}</div>
                </el-form-item>
                <el-form-item label="支付状态: ">
                    <div class="content">{{ formData.pay_status_desc || '-' }}</div>
                </el-form-item>
                <el-form-item label="订单编号: ">
                    <div class="content">{{ formData.sn || '-' }}</div>
                </el-form-item>
                <el-form-item label="支付方式: ">
                    <div class="content">{{ formData.pay_way_desc || '-' }}</div>
                </el-form-item>
                <el-form-item label="下单时间: ">
                    <div class="content">{{ formData.create_time || '-' }}</div>
                </el-form-item>
                <el-form-item label="支付时间: ">
                    <div class="content">{{ formData.pay_time || '-' }}</div>
                </el-form-item>
                <el-form-item label="退款状态: ">
                    <div class="content">{{ formData.refund_status_desc || '-' }}</div>
                </el-form-item>
                <el-form-item label="商家备注: ">
                    <div class="content">{{ formData.shop_remark || '-' }}</div>
                </el-form-item>
            </el-form>
            <!-- Button Group Start -->
            <div class="button-group">
                <operation
                    btnStyle="primary"
                    :id="id"
                    :cancel_btn="formData.cancel_btn"
                    :refund_btn="formData.refund_btn"
                    :shop_remark="formData.shop_remark"
                    @refresh="getOrderDetail"
                />
            </div>
            <!-- Button Group End -->
        </el-card>
        <!-- 订单信息 End -->

        <!-- 用户信息 Start -->
        <el-card shadow="never" class="mt-4 !border-none" style="padding: 0 20px">
            <template #header>
                <span class="font-medium">用户信息</span>
            </template>
            <!-- 用户信息 -->
            <el-form :inline="true" :model="formData" label-width="120px">
                <el-form-item label="用户昵称: ">
                    <div class="content">{{ formData?.user?.nickname || '-' }}</div>
                </el-form-item>
                <el-form-item label="用户学号: ">
                    <div class="content">{{ formData?.user?.sn || '-' }}</div>
                </el-form-item>
            </el-form>
        </el-card>
        <!-- 用户信息 End -->

        <!-- 商品信息 Start -->
        <el-card shadow="never" class="mt-4 !border-none" style="padding: 0 20px">
            <template #header>
                <span class="font-medium">商品信息</span>
            </template>
            <!-- 商品信息 -->
            <el-table :data="formData.order_course" style="width: 100%">
                <el-table-column label="课程信息" min-width="240">
                    <template #default="scope">
                        <div class="goods-box flex items-center">
                            <div>
                                <el-image
                                    style="width: 60px; height: 60px"
                                    :src="scope.row.course_snap?.cover"
                                    :preview-src-list="[scope.row.course_snap?.cover]"
                                    :hide-on-click-modal="true"
                                    :preview-teleported="true"
                                    :fit="'contain'"
                                ></el-image>
                            </div>
                            <div class="goods-name text-xs ml-[10px] text_ellipsis">
                                {{ scope.row.course_snap?.name }}
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="价格" min-width="240">
                    <template #default="scope"> ¥{{ scope.row.course_snap.sell_price }} </template>
                </el-table-column>
                <el-table-column property="order_amount" label="优惠价格" min-width="240">
                    <template #default="scope"> ¥{{ formData.discount_amount }} </template>
                </el-table-column>
                <el-table-column property="order_amount" label="数量" min-width="240">
                    <template #default="scope"> 1 </template>
                </el-table-column>
                <el-table-column property="order_amount" label="实付金额" min-width="240">
                    <template #default="scope"> ¥{{ formData.order_amount }} </template>
                </el-table-column>
            </el-table>
        </el-card>
        <!-- 商品信息 End -->

        <!-- 订单日志 Start -->
        <el-card shadow="never" class="mt-4 !border-none" style="padding: 0 20px">
            <template #header>
                <span class="font-medium">订单日志</span>
            </template>
            <!-- 订单日志 -->
            <el-table ref="tableDataRef" :data="formData.order_log" style="width: 100%">
                <el-table-column property="operator" label="操作人" max-width="300" />
                <el-table-column property="channel_desc" label="操作事件" max-width="300" />
                <el-table-column property="create_time" label="创建时间" max-width="300" />
            </el-table>
        </el-card>
        <!-- 订单日志 End -->
    </div>
</template>

<script lang="ts" setup>
import { apiOrderDetail } from '@/api/order/order'
import { ref } from 'vue'
import Operation from './components/operation.vue'

/** Props Start **/
const route = useRoute()
/** Props End **/

/** Data Start **/
const id = ref<any>(route.query.id)
const formData = ref<any>({})
/** Data End **/

/** Methods Start **/
/**
 * @description 商家备注详情
 */
const getOrderDetail = async (): Promise<void> => {
    formData.value = await apiOrderDetail({ id: id.value })
}
/** Methods End **/

/** Life Cycle Start **/
if (id.value) getOrderDetail()
/** Life Cycle End **/
</script>

<style lang="scss">
.order-detail-main .el-card__header,
.order-detail-main .el-card__body {
    padding: calc(var(--el-card-padding) - 2px) 0;
}

.content {
    width: 24vw;
}

.button-group {
    padding-left: 40px;
    border-top: 1px solid #f2f2f2;
}

.goods-box {
    .goods-name {
        color: #333333;
    }
}
.text_ellipsis {
    overflow: hidden;

    text-overflow: ellipsis;

    display: -webkit-box;

    -webkit-box-orient: vertical;

    -webkit-line-clamp: 2;
}
</style>
