<template>
    <div>
        <!-- Header Start -->
        <el-card shadow="never" class="!border-none">
            <el-page-header content="设置支付方式" @back="$router.back()" />
        </el-card>
        <!-- Header End -->

        <!-- Main Start -->
        <el-card shadow="never" class="!border-none mt-4">
            <div
                class="m-t-24"
                style="padding-bottom: 50px"
                v-for="(item, index) in tableData"
                :key="index"
            >
                <div class="mb-[24px]" v-if="index == 1">
                    <span class="text-lg font-medium">微信小程序</span>
                    <span class="text-xs ml-[10px] form-tips">在微信小程序中付款的场景</span>
                </div>
                <div class="text-lg mb-[24px]" v-if="index == 2">
                    微信公众号
                    <span class="text-xs muted ml-[10px]"
                        >在微信公众号H5页面中付款的场景，公众号类型一般为服务号</span
                    >
                </div>
                <!-- 支付列表主体 -->
                <el-table :data="item" style="width: 100%">
                    <el-table-column prop="icon" label="图标" width="150">
                        <template #default="scope">
                            <!-- {{scope.row}} -->
                            <el-image :src="scope.row.icon" style="width: 34px; height: 34px" />
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="pay_way_desc"
                        label="支付方式"
                        min-width="150"
                    ></el-table-column>
                    <el-table-column label="默认支付" min-width="150">
                        <template #default="scope">
                            <el-radio
                                v-model="scope.row.is_default"
                                :label="1"
                                :name="1"
                                @change="changeRadioPaymentSet(scope.$index, index)"
                                >设为默认</el-radio
                            >
                        </template>
                    </el-table-column>
                    <el-table-column prop="status" label="开启状态" min-width="150">
                        <template #default="scope">
                            <el-switch
                                v-model="scope.row.status"
                                :active-value="1"
                                :inactive-value="0"
                            />
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </el-card>
        <!-- Main End -->

        <!-- Footer Start -->
        <footer-btns>
            <el-button type="primary" @click="onSubmit">保存</el-button>
        </footer-btns>
        <!-- Footer End -->
    </div>
</template>

<script lang="ts" setup>
import { apiPaymentWaySet, apiPaymentWayLists } from '@/api/setting/payment'
import { ref } from 'vue'
import FooterBtns from '@/components/footer-btns/index.vue'

/** Data Start **/
const router = useRouter()
const tableData = ref<Array<any>>([])
/** Data End **/

/** Methods Start **/
/**
 * @description 切换默认支付方式,去除其它选项
 */
const changeRadioPaymentSet = (cIndex: number, index: number): void => {
    tableData.value[index].forEach((item: any, i: number) => {
        tableData.value[index][i].is_default = 0
    })
    tableData.value[index][cIndex].is_default = 1
}
/**
 * @description 初始化支付方式
 */
const initPaymentWay = async (): Promise<void> => {
    tableData.value = await apiPaymentWayLists()
}
/**
 * @description 编辑支付方式
 */
const handlePaymentWayEdit = async (): Promise<void> => {
    await apiPaymentWaySet({ ...tableData.value })
    router.back()
}
/**
 * @description 提交娇艳
 */
const onSubmit = (): void => {
    handlePaymentWayEdit()
}
/** Methods End **/

/** LifeCycle Start **/
initPaymentWay()
/** LifeCycle End **/
</script>
