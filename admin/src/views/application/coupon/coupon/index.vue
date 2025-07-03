<template>
    <div>
        <el-card shadow="never" class="!border-none">
            <el-alert
                title="1.优惠券在发放时间内只要未关闭未删除符合条件就能领取；2.优惠券已结束不能继续领取，已发放的优惠券在用券时间内能可继续使用；3.已结束的优惠券允许后台删除，优惠券已删除不能继续领取已发放的优惠券不能继续使用"
                type="success"
                :closable="false"
            />
            <el-form class="mt-4" label-width="90px" inline>
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
                <el-form-item label="使用范围">
                    <el-select
                        class="w-[280px]"
                        placeholder="请选择"
                        v-model="queryParams.use_scope"
                    >
                        <el-option label="全部" value></el-option>
                        <el-option
                            v-for="(value, key) in otherList.use_scope"
                            :key="key"
                            :label="value"
                            :value="key"
                        ></el-option>
                        <!-- <el-option label="全场通用" value="1"></el-option>
                        <el-option label="指定课程可用" value="2"></el-option>
                        <el-option label="指定课程不可用" value="3"></el-option> -->
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <div class="ml-[20px]">
                        <el-button
                            type="primary"
                            @click="resetPage"
                            v-perms="['coupon.coupon/lists']"
                            >查询</el-button
                        >
                        <el-button @click="resetParams" v-perms="['coupon.coupon/reset']"
                            >重置</el-button
                        >
                    </div>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card shadow="never" class="!border-none mt-4">
            <el-tabs v-model="activeName" class="demo-tabs" @tab-click="handleClick">
                <el-tab-pane
                    :label="`全部(${pager.extend?.all_num || 0})`"
                    :name="''"
                ></el-tab-pane>
                <el-tab-pane
                    :label="`未开始(${pager.extend?.not_start_num || 0})`"
                    name="1"
                ></el-tab-pane>
                <el-tab-pane
                    :label="`进行中(${pager.extend?.ing_num || 0})`"
                    name="2"
                ></el-tab-pane>
                <el-tab-pane
                    :label="`已结束(${pager.extend?.end_num || 0})`"
                    name="3"
                ></el-tab-pane>
            </el-tabs>
            <div>
                <el-button type="primary" @click="toEdit()" v-perms="['application.coupon/add']"
                    >新增优惠券</el-button
                >
                <el-table class="mt-2" :data="pager.lists">
                    <el-table-column
                        label="优惠券名称"
                        prop="name"
                        min-width="140"
                    ></el-table-column>
                    <el-table-column
                        label="券内容"
                        prop="content"
                        min-width="180"
                    ></el-table-column>
                    <el-table-column label="领取方式" prop="get_type_desc" min-width="92" />
                    <el-table-column label="领券时间" prop="useTimeStr" min-width="178">
                        <template #default="{ row }">
                            <view class="flex flex-col">
                                <view>{{ row.receive_time_start }}</view>
                                <view>{{ row.receive_time_end }}</view>
                            </view>
                        </template>
                    </el-table-column>
                    <el-table-column label="发放数量" prop="send_num_desc" min-width="110" />
                    <el-table-column
                        label="已领取"
                        prop="get_coupon_num"
                        min-width="80"
                    ></el-table-column>
                    <el-table-column
                        label="已使用"
                        prop="use_coupon_num"
                        min-width="80"
                    ></el-table-column>
                    <el-table-column label="使用率" prop="use_rate" min-width="80" />
                    <el-table-column label="优惠券状态" prop="status" min-width="110">
                        <template #default="{ row }">
                            <div>
                                <el-tag class="ml-2" type="danger" v-if="row.status == 1"
                                    >未开始</el-tag
                                >
                                <el-tag class="ml-2" type="success" v-if="row.status == 2"
                                    >进行中</el-tag
                                >
                                <el-tag class="ml-2" type="info" v-if="row.status == 3"
                                    >已结束</el-tag
                                >
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="操作" min-width="180">
                        <template #default="{ row }">
                            <div>
                                <el-button
                                    type="primary"
                                    link
                                    @click="toEdit(row.id, 2)"
                                    v-perms="['coupon.coupon/detail']"
                                    >详情</el-button
                                >
                                <el-button
                                    type="primary"
                                    link
                                    v-if="row.status != 3"
                                    @click="toEdit(row.id, 1, row.status)"
                                    v-perms="['coupon.coupon/edit']"
                                    >编辑</el-button
                                >
                                <el-button
                                    type="danger"
                                    link
                                    v-if="row.status == 3"
                                    @click="delCoupon(row.id, row.name)"
                                    v-perms="['coupon.coupon/del']"
                                    >删除</el-button
                                >
                                <el-button
                                    type="primary"
                                    link
                                    v-if="row.status == 1"
                                    @click="changeState(row.id, 1, row.name)"
                                    v-perms="['coupon.coupon/open']"
                                    >开始发放</el-button
                                >
                                <el-button
                                    type="primary"
                                    link
                                    v-if="row.status == 2"
                                    @click="changeState(row.id, 2, row.name)"
                                    v-perms="['coupon.coupon/close']"
                                    >结束发放</el-button
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
    </div>
</template>

<script setup lang="ts">
import { usePaging } from '@/hooks/usePaging'
import {
    apiCouponList,
    apiChangeCouponState,
    apiDelCoupon,
    apiOtherLists
} from '@/api/application/coupon'
import router from '@/router'
import feedback from '@/utils/feedback'

const activeName = ref('')

const queryParams = ref({
    name: '', //优惠券名称
    get_type: '', //领取方式 1-用户领取 2-系统赠送
    use_scope: '', //使用范围 1-全场可用 2-指定课程可用 3-指定课程不可用
    status: ''
})

//选项列表
const otherList: any = ref({})

//分页组件
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: apiCouponList,
    params: queryParams.value
})

//标签页切换
const handleClick = async () => {
    await nextTick()
    queryParams.value.status = activeName.value
    getLists()
}

//跳转页面
const toEdit = (id: any = null, type: any = 1, status?: any) => {
    //type : edit-1 detail-2
    if (id != null) {
        router.push(`coupon/edit?id=${id}&type=${type}&status=${status}`)
    } else {
        router.push('coupon/edit')
    }
}

//开始/结束发放优惠券
const changeState = async (id: number, type: number, name: string) => {
    //type 开始发放-1 结束发放-2
    try {
        if (type == 1) {
            await feedback.customConfirm('确定开始发放\t', '\t!', name, 'color:red')
            await apiChangeCouponState({ id })
        }
        if (type == 2) {
            await feedback.customConfirm(
                '确定结束发放\t',
                '\t？结束发放的优惠券不能重新开始领取，请谨慎操作。',
                name,
                'color:red'
            )
            await apiChangeCouponState({ id })
        }
        await feedback.msgSuccess('成功！')
        getLists()
    } catch (err) {
        return 0
    }
}

//删除优惠券
const delCoupon = async (id: number, name: string) => {
    await feedback.customConfirm('确定删除\t', '\t!', name, 'color:red')
    await apiDelCoupon({ id })
    getLists()
}

//获取选项数据列表
const getOtherLists = async () => {
    otherList.value = await apiOtherLists()
}

onMounted(async () => {
    await getLists()
    await getOtherLists()
})
</script>

<style scoped lang="scss">
::v-deep .el-table .el-table__cell {
    padding: 18px 0;
}
</style>
