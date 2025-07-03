<!-- 支付方式 -->
<template>
    <!-- Header Start -->
    <router-link
        class="mr-[10px]"
        :to="{
            path: 'payment_way/edit'
        }"
    >
        <el-button type="primary" v-perms="['setting.pay.pay_way/setPayWay']"
            >设置支付方式</el-button
        >
    </router-link>
    <!-- Header Emd -->

    <!-- Main Start -->
    <el-card shadow="never" class="mt-4 !border-none">
        <!-- 各平台支付 -->
        <div
            class="ls-card m-t-24"
            style="padding-bottom: 50px"
            v-for="(item, index) in tableData"
            v-loading="tableData.length == 0"
            :key="index"
        >
            <div class="lg mb-[24px] card-title" v-if="index == 1">
                微信小程序
                <span class="form-tips ml-[10px]">在微信小程序中付款的场景</span>
            </div>
            <div class="text-lg mb-[24px] card-title" v-if="index == 2">
                微信公众号
                <span class="form-tips ml-[10px]"
                    >在微信公众号H5页面中付款的场景，公众号类型一般为服务号</span
                >
            </div>
            <div class="lg mb-[24px] card-title" v-if="index == 3">
                H5支付
                <span class="form-tips ml-[10px]">在浏览器H5页面中付款的场景</span>
            </div>
            <div class="lg mb-[24px] card-title" v-if="index == 4">
                PC商城
                <span class="form-tips ml-[10px]">在PC商城页面中付款的场景</span>
            </div>
            <div class="lg mb-[24px] card-title" v-if="index == 5">
                APP支付
                <span class="form-tips ml-[10px]">在APP中付款的场景</span>
            </div>
            <div class="lg mb-[24px] card-title" v-if="index == 7">
                字节小程序
                <span class="form-tips ml-[10px]">在字节小程序中付款的场景</span>
            </div>

            <!-- 支付列表主体 -->
            <el-table :data="item" style="width: 100%">
                <el-table-column prop="icon" label="图标" min-width="150">
                    <template #default="scope">
                        <el-image
                            :src="scope.row.icon"
                            alt="图标"
                            style="width: 34px; height: 34px"
                        />
                    </template>
                </el-table-column>
                <el-table-column
                    prop="pay_way_desc"
                    label="支付方式"
                    min-width="150"
                ></el-table-column>
                <el-table-column prop="is_default" label="默认支付" min-width="150">
                    <template #default="scope">
                        <div>
                            <el-tag v-if="scope.row.is_default == 1">默认</el-tag>
                            <span v-else>-</span>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column prop="status" label="开启状态" min-width="150">
                    <template #default="scope">
                        {{ scope.row.status == 1 ? '开启' : '关闭' }}
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <!-- <layout-footer></layout-footer> -->
    </el-card>
    <!-- Main End -->
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { apiPaymentWayLists } from '@/api/setting/payment'
import LayoutFooter from '@/layout/components/footer.vue'

/** Data Start **/
const tableData = ref<Array<object>>([])
/** Data End **/

/** Methods Start **/
// 获取服务设置
const getPaymentConfig = async (): Promise<void> => {
    ;(tableData.value as object) = await apiPaymentWayLists()
}
/** Methods End **/

/** LifeCycle Start **/
getPaymentConfig()
/** LifeCycle End **/
</script>

<style lang="scss" scoped>
.ls-input {
    width: 280px;
}
</style>
