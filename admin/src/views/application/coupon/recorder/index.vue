<template>
    <div>
        <el-card shadow="never" class="!border-none">
            <el-alert
                title="1.查看优惠券领取记录；2.未使用的优惠券可以作废。"
                type="success"
                :closable="false"
            />
            <el-form class="mt-4" label-width="90px" inline>
                <el-form-item label="用户信息">
                    <el-input
                        class="w-[280px]"
                        placeholder="请输入用户学号/昵称"
                        v-model="queryParams.keyword"
                    ></el-input>
                </el-form-item>
                <el-form-item label="优惠券名称">
                    <el-input
                        class="w-[280px]"
                        placeholder="请输入"
                        v-model="queryParams.name"
                    ></el-input>
                </el-form-item>
                <el-form-item label="领取方式">
                    <el-select
                        class="w-[280px]"
                        placeholder="请选择"
                        v-model="queryParams.get_type"
                    >
                        <el-option label="全部" value></el-option>
                        <el-option
                            v-for="(value, key) in otherList.get_type"
                            :key="key"
                            :label="value"
                            :value="key"
                        ></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="发放人">
                    <el-select
                        class="w-[280px]"
                        placeholder="请选择"
                        v-model="queryParams.admin_id"
                    >
                        <el-option label="全部" value></el-option>
                        <el-option
                            v-for="(value, key) in otherList.admin_list"
                            :key="key"
                            :label="value"
                            :value="key"
                        ></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="领取时间">
                    <DataPicker
                        v-model:start_time="queryParams.start_time"
                        v-model:end_time="queryParams.end_time"
                    >
                    </DataPicker>
                </el-form-item>
                <el-form-item>
                    <div class="ml-[20px]">
                        <el-button
                            type="primary"
                            @click="resetPage"
                            v-perms="['coupon.record/lists']"
                            >查询</el-button
                        >
                        <el-button @click="resetParams" v-perms="['coupon.record/reset']"
                            >重置</el-button
                        >
                    </div>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card shadow="never" class="!border-none mt-4">
            <el-tabs v-model="activeName" class="demo-tabs" @tab-click="handleClick">
                <el-tab-pane
                    :label="`全部(${pager.extend.all_count || 0})`"
                    :name="''"
                ></el-tab-pane>
                <el-tab-pane
                    :label="`未使用(${pager.extend.not_use_count || 0})`"
                    name="1"
                ></el-tab-pane>
                <el-tab-pane
                    :label="`已使用(${pager.extend.use_count || 0})`"
                    name="2"
                ></el-tab-pane>
                <el-tab-pane
                    :label="`已过期(${pager.extend.overtime_count || 0})`"
                    name="3"
                ></el-tab-pane>
                <el-tab-pane
                    :label="`已作废(${pager.extend.cancel_count || 0})`"
                    name="4"
                ></el-tab-pane>
            </el-tabs>
            <div>
                <el-button @click="batchCancal()" :disabled="ids.length == 0">批量作废</el-button>
                <el-table
                    class="mt-2"
                    :data="pager.lists"
                    :row-key="getRowKey"
                    @selection-change="tableSelect"
                >
                    <el-table-column
                        :selectable="
                            (row, index) => {
                                return row.status == 1
                            }
                        "
                        type="selection"
                        width="55"
                    />
                    <el-table-column label="用户昵称" min-width="160">
                        <template #default="{ row }">
                            <div class="flex items-center">
                                <div>
                                    <el-avatar
                                        shape="square"
                                        :size="40"
                                        :src="appStore.getImageUrl(row.avatar)"
                                    />
                                </div>
                                <div class="ml-2">{{ row.nickname }}</div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="优惠券名称" min-width="160" prop="name" />
                    <el-table-column label="领取方式" prop="channel_desc" min-width="92" />
                    <el-table-column label="发放人" min-width="100" prop="admin_name" />
                    <el-table-column label="领取时间" min-width="160" prop="create_time" />
                    <el-table-column label="过期时间" min-width="160" prop="invalid_time_desc" />
                    <el-table-column label="使用状态" min-width="120">
                        <template #default="{ row }">
                            <div>
                                <el-tag v-if="row.status == 2" class="ml-2" type="success"
                                    >已使用</el-tag
                                >
                                <el-tag v-if="row.status == 1" class="ml-2" type="danger"
                                    >未使用</el-tag
                                >
                                <el-tag v-if="row.status == 3" class="ml-2" type="info"
                                    >已过期</el-tag
                                >
                                <el-tag v-if="row.status == 4" class="ml-2" type="info"
                                    >已作废</el-tag
                                >
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="使用时间" min-width="150" prop="use_time_desc" />
                    <el-table-column label="操作" min-width="110" prop="usedTime">
                        <template #default="{ row }">
                            <div>
                                <el-button
                                    v-if="row.status == 1"
                                    type="primary"
                                    link
                                    @click="cancelCoupon(row.id)"
                                    >作废</el-button
                                >
                                <div v-else>{{ '-' }}</div>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <div class="flex justify-end mt-4">
                <pagination v-model="pager" @change="getLists" />
            </div>
        </el-card>
    </div>
</template>
<script setup lang="ts">
import {
    apiCouponGetRecord,
    apiCancelCoupon,
    apiGetRecordOtherLists
} from '@/api/application/coupon'
import { usePaging } from '@/hooks/usePaging'
import useAppStore from '@/stores/modules/app'
import feedback from '@/utils/feedback'
//表单数据接口
interface IquerParams {
    keyword: string
    name: string
    get_type: number | string
    admin_id: number | string
    start_time: string
    end_time: string
    status: number | string
}
//表单数据
const queryParams = ref<IquerParams>({
    keyword: '', //用户信息
    name: '',
    get_type: '',
    admin_id: '',
    start_time: '',
    end_time: '',
    status: ''
})

const appStore = useAppStore()

//active标签页
const activeName = ref<any>('')

//已领取优惠券列表
const ids = ref<number[]>([])

//选项数据
const otherList: any = ref({})

//分页组件
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: apiCouponGetRecord,
    params: queryParams.value
})

//tabs页改变
const handleClick = async () => {
    await nextTick()
    queryParams.value.status = activeName
    getLists()
}

//优惠券作废
const cancelCoupon = async (id: number) => {
    await feedback.confirm('确定作废？')
    await apiCancelCoupon({ id })
    await feedback.msgSuccess('作废成功！')
    getLists()
}

//获取row-key
const getRowKey = (row: any) => {
    return row.id
}

//table多选
const tableSelect = (value: any) => {
    ids.value = []
    value.map((item: any) => {
        ids.value.push(item.id)
    })
}

//获取选项数据
const getOtherList = async () => {
    otherList.value = await apiGetRecordOtherLists()
}

//批量作废
const batchCancal = async () => {
    const idsString = ids.value.join()
    await feedback.confirm('确定批量作废？')
    await apiCancelCoupon({ id: idsString })
    await feedback.msgSuccess('作废成功！')
    getLists()
}

onMounted(async () => {
    await getLists()
    await getOtherList()
})
</script>
<style lang="scss" scoped></style>
