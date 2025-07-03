<template>
    <div>
        <el-card class="!border-none" shadow="never">
            <el-page-header content="下级列表" @back="$router.back()" />
        </el-card>
        <el-card class="!border-none mt-4" shadow="never">
            <div class="text-xl font-medium">分销商信息</div>
            <el-form class="mt-4" label-width="130px">
                <el-form-item label="用户信息">
                    <div>{{ pager.extend.name }}</div>
                </el-form-item>
                <el-form-item label="下级人数">
                    <div>{{ pager.extend.fans }}</div>
                </el-form-item>
                <el-form-item label="下级分销商人数">
                    <div>{{ pager.extend.fans_distribution }}</div>
                </el-form-item>
                <el-form-item label="下一级人数">
                    <div>
                        {{ pager.extend.fans_one }}（分销商：{{
                            pager.extend.fans_one_distribution
                        }}人）
                    </div>
                </el-form-item>
                <el-form-item label="下二级人数">
                    <div>
                        {{ pager.extend.fans_two }}（分销商：{{
                            pager.extend.fans_two_distribution
                        }}人）
                    </div>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none mt-4" shadow="never">
            <div class="text-xl font-medium">分销商信息</div>
            <el-form class="mt-4" inline>
                <el-form-item label="用户信息">
                    <el-input
                        class="w-[240px]"
                        v-model="queryParams.user_info"
                        placeholder="请输入用户昵称/账号/手机号"
                    ></el-input>
                </el-form-item>
                <el-form-item label="分销资格">
                    <!-- <el-input class="w-[240px]" v-model="queryParams.keyword" placeholder="请输入用户昵称/账号/手机号"></el-input> -->
                    <el-select v-model="queryParams.is_distribution">
                        <el-option label="全部" value=""></el-option>
                        <el-option label="未开通" value="0"></el-option>
                        <el-option label="已开通" value="1"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                </el-form-item>
            </el-form>
            <el-tabs v-model="queryParams.level" @tab-change="changeTabs">
                <el-tab-pane label="全部" name="0" />
                <el-tab-pane label="下一级" name="1" />
                <el-tab-pane label="下二级" name="2"
            /></el-tabs>
            <el-table
                ref="tableRef"
                size="large"
                v-loading="pager.loading"
                :data="pager.lists"
                class="mt-4"
            >
                <el-table-column label="用户信息" min-width="180">
                    <template #default="{ row }">
                        <div class="flex items-center">
                            <el-image
                                class="w-[47px] h-[47px] flex-none"
                                :src="row.avatar"
                            ></el-image>
                            <div class="ml-2">{{ row.nickname }}</div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="可提现佣金" min-width="90">
                    <template #default="{ row }">
                        {{ row.user_earnings }}
                    </template>
                </el-table-column>
                <el-table-column
                    label="获得总佣金"
                    min-width="90"
                    prop="total_earnings"
                ></el-table-column>
                <el-table-column label="上级分销商" min-width="90">
                    <template #default="{ row }">
                        {{ row.first_leader_name || '系统' }}
                    </template>
                </el-table-column>
                <el-table-column label="分销资格" min-width="90" prop="isDistributionMsg">
                    <template #default="{ row }">
                        {{ row.is_distribution_desc }}
                        <div>
                            {{ row.distribution_time }}
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="分销状态" min-width="90">
                    <template #default="{ row }">
                        {{ row.is_freeze == 0 ? '正常' : '冻结' }}
                    </template>
                </el-table-column>
                <el-table-column
                    label="注册时间"
                    min-width="90"
                    prop="create_time"
                ></el-table-column>
            </el-table>
        </el-card>
    </div>
</template>
<script setup lang="ts">
import { usePaging } from '@/hooks/usePaging'
import { fansInfo, getLowerList } from '@/api/distribution/distributor'
const route = useRoute()

const detailData: any = ref({})

const queryParams: any = ref({
    user_id: route.query.id,
    level: '0',
    user_info: '',
    is_distribution: ''
})

//分页组件
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: getLowerList,
    params: queryParams.value
})

// const getDetailData = async () => {
//     detailData.value = await fansInfo({ id: route.query.id })
// }

const changeTabs = async () => {
    await nextTick()
    getLists()
}

onMounted(async () => {
    await getLists()
    // await getDetailData()
})
</script>
<style scoped lang="scss"></style>
