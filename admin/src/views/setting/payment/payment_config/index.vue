<!-- 支付配置 -->
<template>
    <div>
        <!-- Header Alert Start -->
        <el-card shadow="never" class="!border-none">
            <el-alert
                title="温馨提示：设置系统支持的支付方式"
                type="success"
                show-icon
                :closable="false"
            />
        </el-card>
        <!-- Header Alert End -->

        <!-- Main Start -->
        <el-card shadow="never" class="mt-4 !border-none">
            <el-table :data="tableData" v-loading="tableData.length == 0" style="width: 100%">
                <el-table-column prop="image" label="图标" min-width="150">
                    <template #default="scope">
                        <el-image
                            :src="scope.row.image"
                            alt="图标"
                            style="width: 34px; height: 34px"
                        />
                    </template>
                </el-table-column>
                <el-table-column prop="name" label="支付方式" min-width="150" />
                <el-table-column prop="sort" label="排序" min-width="150" />
                <el-table-column label="操作" min-width="150">
                    <!-- 操作 -->
                    <template #default="scope">
                        <el-button
                            type="text"
                            v-perms="['setting.pay.pay_config/edit']"
                            @click="openEdit(scope.row.id)"
                            >编辑</el-button
                        >
                    </template>
                </el-table-column>
            </el-table>
        </el-card>
        <!-- Main End -->
        <edit-pop ref="editPopRef" @hendSubmit="initPaymentConfig()"></edit-pop>
        <!-- <layout-footer></layout-footer> -->
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { apiPaymentConfigLists } from '@/api/setting/payment'
import editPop from './edit.vue'
import LayoutFooter from '@/layout/components/footer.vue'

/** Data Start **/
const tableData = ref<Array<object>>([])
const editPopRef = ref()
/** Data End **/

/** Methods Start **/
/**
 * @description 初始化支付配置
 */
const initPaymentConfig = async (): Promise<void> => {
    try {
        tableData.value = await apiPaymentConfigLists()
    } catch (err) {
        console.log('初始化支付配置', err)
    }
}

//打开编辑弹框
const openEdit = (id: any) => {
    editPopRef.value?.open(id)
}

/** Methods End **/

/** LifeCycle Start **/
initPaymentConfig()
/** LifeCycle End **/
</script>

<style lang="scss" scoped>
.ls-input {
    width: 280px;
}
</style>
