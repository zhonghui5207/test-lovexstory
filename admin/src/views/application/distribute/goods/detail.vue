<template>
    <div>
        <el-card class="!border-none" shadow="never">
            <el-page-header :content="$route.meta.title" @back="$router.back()" />
        </el-card>
        <el-form label-width="120px">
            <el-card class="!border-none mt-4" shadow="never">
                <div class="font-medium text-xl">商品信息</div>
                <div class="mt-4">
                    <el-form-item label="商品编号">
                        <div>{{ detailData.id }}</div>
                    </el-form-item>
                    <el-form-item label="商品图片">
                        <el-image :src="detailData.cover" class="w-[58px] h-[58px]"> </el-image>
                    </el-form-item>
                    <el-form-item label="商品名称">
                        <div>{{ detailData.name }}</div>
                    </el-form-item>
                </div>
            </el-card>
            <el-card class="!border-none mt-4" shadow="never">
                <div>
                    <el-form-item label="分销状态">
                        <div>
                            <el-radio-group v-model="detailData.distribution_status">
                                <el-radio :label="1">参与分销</el-radio>
                                <el-radio :label="0">不参与分销</el-radio>
                            </el-radio-group>
                            <div class="form-tips">
                                是否参与分销，选择不参与分销将不产生分销佣金
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="分销状态">
                        <div class="flex-none">
                            <el-radio-group v-model="detailData.distribution_rule">
                                <el-radio :label="1">默认分销等级佣金规则</el-radio>
                                <el-radio :label="2">单独设置佣金比例</el-radio>
                                <!-- <el-radio :label="2">单独设置佣金</el-radio> -->
                            </el-radio-group>
                            <div class="mt-2 flex">
                                <popover-input @confirm="setSelf" :disabled="!isSelectData">
                                    <el-button :disabled="!isSelectData">设置自购佣金</el-button>
                                </popover-input>
                                <popover-input @confirm="setFirst" :disabled="!isSelectData">
                                    <el-button class="ml-2" :disabled="!isSelectData"
                                        >设置一级佣金</el-button
                                    >
                                </popover-input>
                                <popover-input @confirm="setSecond" :disabled="!isSelectData">
                                    <el-button class="ml-2" :disabled="!isSelectData"
                                        >设置二级佣金</el-button
                                    >
                                </popover-input>
                            </div>
                            <el-table
                                :data="detailData.rule"
                                ref="tableRef"
                                class="mt-2"
                                @selection-change="tableSelect"
                            >
                                <el-table-column
                                    :selectable="
                                        () => {
                                            return detailData.distribution_rule == 2
                                        }
                                    "
                                    type="selection"
                                    width="55"
                                />
                                <el-table-column
                                    label="分销等级"
                                    prop="level_name"
                                ></el-table-column>
                                <el-table-column label="价格" prop="sell_price"></el-table-column>
                                <el-table-column :label="`自购佣金比例(%)`" min-width="180">
                                    <template #default="{ row }">
                                        <div>
                                            <div class="flex items-center">
                                                <el-input
                                                    class="w-[80px]"
                                                    type="number"
                                                    v-model="row.self_ratio"
                                                    :disabled="detailData.distribution_rule == 1"
                                                ></el-input>
                                                <div class="ml-2">
                                                    =
                                                    {{
                                                        (
                                                            (row.sell_price * row.self_ratio) /
                                                            100
                                                        ).toFixed(2)
                                                    }}元
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </el-table-column>
                                <el-table-column :label="`一级佣金比例(%)`" min-width="180">
                                    <template #default="{ row }">
                                        <div>
                                            <div class="flex items-center">
                                                <el-input
                                                    class="w-[80px]"
                                                    type="number"
                                                    v-model="row.first_ratio"
                                                    :disabled="detailData.distribution_rule == 1"
                                                ></el-input>
                                                <div class="ml-2">
                                                    =
                                                    {{
                                                        (
                                                            (row.sell_price * row.first_ratio) /
                                                            100
                                                        ).toFixed(2)
                                                    }}元
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </el-table-column>
                                <el-table-column :label="`二级佣金比例(%)`" min-width="180">
                                    <template #default="{ row }">
                                        <div class="flex items-center">
                                            <div class="flex items-center">
                                                <el-input
                                                    class="w-[80px]"
                                                    type="number"
                                                    v-model="row.second_ratio"
                                                    :disabled="detailData.distribution_rule == 1"
                                                ></el-input>
                                                <div class="ml-2">
                                                    =
                                                    {{
                                                        (
                                                            (row.sell_price * row.second_ratio) /
                                                            100
                                                        ).toFixed(2)
                                                    }}元
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </el-form-item>
                </div>
            </el-card>
        </el-form>
        <footer-btns>
            <el-button type="primary" @click="submit">保存</el-button>
        </footer-btns>
    </div>
</template>
<script setup lang="ts">
import { getGoodsDetail, distributionSave } from '@/api/distribution/distribution'
import router from '@/router'
import feedback from '@/utils/feedback'

interface IDetailData {
    code: string
    name: string
    id: number
    cover: string
    distribution_status: number
    rule: any[]

    distribution_rule: number //佣金规则 1-按分销等级比例分佣 2-单独设置分佣比例
}

const route = useRoute()
const id = route.query.id

//表格ref
const tableRef = shallowRef()
//是否选中数据
const isSelectData = ref()
//分销详情数据
const detailData = ref<IDetailData>({
    code: '',
    name: '',
    id: 0,
    cover: '',
    distribution_status: 1,
    rule: [],
    distribution_rule: 1
})

//获取详情数据
const getDetailData = async () => {
    detailData.value = await getGoodsDetail({ id })
}

//变选项变化
const tableSelect = async (selectData: any) => {
    await nextTick()
    isSelectData.value = selectData.length
}

//批量设置佣金
const setSelf = (setValue: any) => {
    tableRef.value.getSelectionRows().map((item: any) => {
        item.self_ratio = setValue
    })
}
const setFirst = (setValue: any) => {
    tableRef.value.getSelectionRows().map((item: any) => {
        item.first_ratio = setValue
    })
}
const setSecond = (setValue: any) => {
    tableRef.value.getSelectionRows().map((item: any) => {
        item.second_ratio = setValue
    })
}

//保存
const submit = async () => {
    await distributionSave({ ...detailData.value })
    router.back()
}

onMounted(async () => {
    await getDetailData()
    // await formatTable()
})
</script>

<style scoped lang="scss">
:deep(.el-form-item__content) {
    display: block;
}
</style>
