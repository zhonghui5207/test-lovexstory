<template>
    <div class="balance-statement">
        <el-card shadow="never" class="!border-none">
            <el-alert
                title="温馨提示： 会员账户余额变动记录"
                type="success"
                :closable="false"
                show-icon
            />

            <!-- Header Form Start -->
            <el-form :model="formData" label-width="90px" inline class="mt-4">
                <!-- <el-form-item label="用户学号">
                    <el-input
                        class="w-[280px]"
                        v-model="formData.sn"
                        placeholder="请输入用户学号"
                    />
                </el-form-item>
                <el-form-item label="用户昵称">
                    <el-input
                        class="w-[280px]"
                        v-model="formData.nickname"
                        placeholder="请输入用户昵称"
                    />
                </el-form-item> -->
                <el-form-item label="用户信息">
                    <el-input
                        v-model="formData.user_info"
                        placeholder="请输入用户账号/昵称/手机号"
                        class="w-[320px]"
                        @keyup.enter="resetPage"
                    >
                    </el-input>
                </el-form-item>

                <el-form-item label="变动类型">
                    <el-select
                        v-model="formData.change_type"
                        class="w-[280px]"
                        placeholder="请选择"
                    >
                        <el-option label="全部" value></el-option>
                        <el-option
                            v-for="(changeItem, changeIndex) in otherLists"
                            :key="changeIndex"
                            :label="changeItem"
                            :value="changeIndex"
                        >
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="记录时间">
                    <data-picker
                        v-model:start_time="formData.start_time"
                        v-model:end_time="formData.end_time"
                    >
                    </data-picker>
                </el-form-item>
                <el-form-item>
                    <div class="m-l-20">
                        <el-button type="primary" @click="resetPage">查询</el-button>
                        <el-button @click="resetParams">重置</el-button>
                    </div>
                </el-form-item>
            </el-form>
            <!-- Header Form End -->
        </el-card>

        <el-card shadow="never" class="mt-4 !border-none">
            <!-- Main TableData Start -->
            <div class="m-t-20">
                <el-table
                    ref="tableData"
                    :data="pager.lists"
                    style="width: 100%"
                    v-loading="pager.loading"
                >
                    <el-table-column property="sn" label="用户学号" max-width="180" />
                    <el-table-column property="nickname" label="用户昵称" max-width="140">
                        <template #default="{ row }">
                            <div class="flex items-center">
                                <el-image class="w-[50px] h-[50px]" :src="row.avatar"></el-image>
                                <div class="ml-2">{{ row.nickname || '-' }}</div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column property="change_amount" label="变动金额" max-width="140">
                        <template #default="scope">
                            <span v-if="scope.row.action === 1" class="red"
                                >+{{ scope.row.change_amount }}</span
                            >
                            <span v-else>-{{ scope.row.change_amount }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column property="left_amount" label="剩余金额" max-width="140" />
                    <el-table-column property="change_type_desc" label="变动类型" max-width="140" />
                    <el-table-column property="association_sn" label="来源" max-width="140" />
                    <el-table-column property="create_time" label="记录时间" max-width="140" />
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
import { apiAccountLogLists, apiAccountLogOtherLists } from '@/api/finance/finance'
import { ref } from 'vue'
import Pagination from '@/components/pagination/index.vue'
import { usePaging } from '@/hooks/usePaging'
import DataPicker from '@/components/data-picker/index.vue'
import LayoutFooter from '@/layout/components/footer.vue'

/** Interface Start **/
interface FormDataObj {
    // sn: string // 学号
    // nickname: string // 昵称搜索
    user_info: string
    mobile: string // 讲师名称
    change_type: number | string // 变动类型
    start_time: string // 记录开始时间
    end_time?: string // 记录结束时间
    change_object: number
}
/** Interface End **/

/** Data Start **/
const otherLists = ref({})
// 表单数据
const formData = ref<FormDataObj>({
    // sn: '',
    // nickname: '',
    user_info: '',
    mobile: '',
    change_type: '',
    start_time: '',
    end_time: '',
    change_object: 1
})
const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: apiAccountLogLists,
    params: formData.value
})
/** Data End **/

/** Methods Start **/
const initAccountLogOtherLists = async (): Promise<void> => {
    otherLists.value = await apiAccountLogOtherLists({ change_object: 1 })
}
/** Methods End **/

/** LifeCycle Start **/
getLists()
initAccountLogOtherLists()
/** LifeCycle End **/
</script>

<style lang="scss" scoped>
.ls-input {
    width: 170px;
}

.red {
    color: red;
}
.el-alert--success.is-light {
    background-color: #edefff;
    color: #4a5dff;
}
</style>
