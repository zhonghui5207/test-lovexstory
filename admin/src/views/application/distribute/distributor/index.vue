<template>
    <el-card class="!border-none" shadow="never">
        <!-- <el-alert type="warning" title="设置商品的分销佣金比例" :closable="false" show-icon /> -->
        <el-form ref="formRef" class="mb-[-16px] mt-4" :model="queryParams" :inline="true">
            <el-form-item label="用户信息">
                <el-input
                    class="w-[280px]"
                    v-model="queryParams.user_info"
                    placeholder="请输入昵称/编号"
                    clearable
                />
            </el-form-item>
            <el-form-item label="分销等级">
                <el-select class="w-[280px]" v-model="queryParams.level_id">
                    <el-option label="全部" value />
                    <el-option
                        v-for="(item, index) in dropDownList"
                        :key="index"
                        :label="item.name"
                        :value="item.id"
                    ></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="分销状态">
                <el-select class="w-[280px]" v-model="queryParams.is_freeze">
                    <el-option label="全部" value />
                    <el-option label="正常" value="0" />
                    <el-option label="冻结" value="1" />
                </el-select>
            </el-form-item>
            <el-form-item label="成为分销商时间">
                <daterange-picker
                    v-model:startTime="queryParams.start_time"
                    v-model:endTime="queryParams.end_time"
                />
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetParams">重置</el-button>
            </el-form-item>
        </el-form>
    </el-card>
    <el-card class="!border-none mt-4" shadow="never">
        <el-button type="primary" v-perms="['distribute.distributor/add']" @click="addDistributor"
            >开通分销商</el-button
        >
        <div>
            <el-table
                ref="tableRef"
                size="large"
                v-loading="pager.loading"
                :data="pager.lists"
                class="mt-4"
            >
                <el-table-column label="用户信息" min-width="220">
                    <template #default="{ row }">
                        <div class="flex items-center">
                            <el-image
                                class="w-[47px] h-[47px] flex-none"
                                :src="row.avatar"
                            ></el-image>
                            <div class="ml-2">
                                {{ row.nickname }}
                                <span v-if="row.isClose" class="ml-2 text-xs text-danger"
                                    >(已注销)</span
                                >
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="分销等级" min-width="90">
                    <template #default="{ row }">
                        {{ row?.level_name || '-' }}
                    </template>
                </el-table-column>
                <el-table-column
                    label="已入账佣金"
                    min-width="90"
                    prop="earnings"
                ></el-table-column>
                <el-table-column label="待结算佣金" min-width="90">
                    <template #default="{ row }">
                        {{ row.wait_earnings || 0 }}
                    </template>
                </el-table-column>
                <el-table-column label="上级推荐人" min-width="90">
                    <template #default="{ row }">
                        {{ row.first_leader_name || '系统' }}
                    </template>
                </el-table-column>
                <el-table-column label="分销状态" min-width="90">
                    <template #default="{ row }">
                        {{ row.is_freeze_desc }}
                    </template>
                </el-table-column>
                <el-table-column
                    label="成为分销商时间"
                    min-width="90"
                    prop="distribution_time"
                ></el-table-column>
                <el-table-column label="操作" min-width="180">
                    <template #default="{ row }">
                        <div>
                            <el-button
                                type="primary"
                                @click="toDetial(row.id)"
                                link
                                v-perms="['distribute.distributor/detail']"
                                >详情</el-button
                            >
                            <el-button
                                :disabled="row.isClose"
                                type="primary"
                                link
                                @click="adjustLevel(row)"
                                v-perms="['distribute.distributor/leveladjust']"
                                >等级调整</el-button
                            >
                            <el-button
                                type="primary"
                                v-if="row.is_freeze == 0"
                                link
                                :disabled="row.isClose"
                                @click="changeStatus(row, 0)"
                                v-perms="['distribute.distributor/status']"
                                >冻结资格</el-button
                            >
                            <el-button
                                type="primary"
                                v-if="row.is_freeze == 1"
                                link
                                :disabled="row.isClose"
                                @click="changeStatus(row, 1)"
                                v-perms="['distribute.distributor/status']"
                                >恢复资格</el-button
                            >
                        </div>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <div class="flex justify-end mt-4">
            <pagination v-model="pager" @change="getLists" />
        </div>
    </el-card>
    <addDistributorPopVue
        v-if="showPop"
        ref="popRef"
        @close="showPop = false"
        @confirm="getLists"
    ></addDistributorPopVue>
    <AdjustLevelPop
        ref="adjustLevelPop"
        @close="showPop = false"
        @confirm="getLists"
        v-if="showPop"
    ></AdjustLevelPop>
</template>

<script setup lang="ts">
import { usePaging } from '@/hooks/usePaging'
import {
    getDistributorList,
    closeDistributionStatus,
    getOtherLists
} from '@/api/distribution/distributor'
import addDistributorPopVue from './components/addDistributorPop.vue'
import feedback from '@/utils/feedback'
import AdjustLevelPop from './components/adjustLevelPop.vue'
import router from '@/router'

//弹框ref
const popRef = shallowRef()
const adjustLevelPop = shallowRef()
const showPop = ref(false)
//搜索参数
const queryParams = ref({
    user_info: '', //关键字搜索
    level_id: '', //分销等级id搜索
    is_freeze: '', //分销状态 1-正常 0-冻结
    start_time: '', //开始时间
    end_time: '' //结束时间
})
//下拉列表
const dropDownList: any = ref([])

//分页组件
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: getDistributorList,
    params: queryParams.value
})
//获取下拉列表
const getDropDownList = async () => {
    dropDownList.value = await getOtherLists()
    // dropDownList.value = lists
}
//开通分销商
const addDistributor = async () => {
    showPop.value = true
    await nextTick()
    popRef.value.open()
}
//修改分销商状态
const changeStatus = async (row: any, distribution_status: any) => {
    if (distribution_status == 0) {
        await feedback.customConfirm(
            '确定冻结：',
            '？请谨慎处理',
            row.nickname,
            'color: red ',
            '冻结会员'
        )
    }
    if (distribution_status == 1) {
        await feedback.customConfirm(
            '确定恢复：',
            '？请谨慎处理',
            row.nickname,
            ' color: red ',
            '恢复会员'
        )
    }
    await closeDistributionStatus({ id: row.id })

    getLists()
}

//等级调整
const adjustLevel = async (row: any) => {
    showPop.value = true
    await nextTick()
    await adjustLevelPop.value.open(row)
}

//跳转至详情页
const toDetial = (id: any) => {
    router.push(`/app/distribute/distributor/detail?id=${id}`)
}

onMounted(async () => {
    await getLists()
    await getDropDownList()
})
</script>

<style scoped lang="scss"></style>
