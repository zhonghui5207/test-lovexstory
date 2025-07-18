<template>
    <div>
        <el-card class="!border-none" shadow="never">
            <el-page-header content="用户详情" @back="$router.back()" />
        </el-card>
        <el-card class="mt-4 !border-none" header="基本资料" shadow="never">
            <el-form ref="formRef" class="ls-form" :model="formData" label-width="140px">
                <el-row class="py-5 pl-20 mb-10 bg-page">
                    <el-col :span="4">
                        <div class="mb-3 text-tx-regular">用户头像</div>
                        <el-avatar :src="formData.avatar" :size="58" />
                    </el-col>
                    <el-col :span="5">
                        <div class="mb-3 font-medium text-mian">分销商等级</div>
                        <div class="mt-5">
                            <!-- <span class="text-[18px]" v-if="formData.distribution_level?.is_default">默认等级</span> -->
                            <span class="text-[18px]">{{ formData.level_name }}</span>
                            <el-button
                                :disabled="formData.isClose"
                                class="ml-2"
                                type="primary"
                                link
                                @click="adjustLevel"
                                v-perms="['distributor.detail/levelAdjust']"
                                >等级调整</el-button
                            >
                        </div>
                    </el-col>
                    <el-col :span="5">
                        <div class="mb-3 font-medium text-mian">已入账佣金</div>
                        <div class="mt-5">
                            <span class="text-[18px]"> ¥{{ formData.earnings || 0 }} </span>
                        </div>
                    </el-col>
                    <el-col :span="5">
                        <div class="mb-3 font-medium text-mian">待结算佣金</div>
                        <div class="mt-5">
                            <span class="text-[18px]"> ¥{{ formData.wait_earnings || 0 }} </span>
                        </div>
                    </el-col>
                    <el-col :span="5">
                        <div class="mb-3 font-medium text-mian">分销订单</div>
                        <div class="mt-5">
                            <span class="text-[18px]">
                                {{ formData.distribution_order_num || 0 }}
                            </span>
                        </div>
                    </el-col>
                </el-row>
                <el-form-item label="用户信息：">
                    {{ formData.nickname }}（{{ formData.user_sn }}）
                </el-form-item>
                <el-form-item label="真实姓名：">
                    {{ formData.real_name || '-' }}
                </el-form-item>
                <el-form-item label="上级分销商：">
                    {{ formData.first_leader_name || '系统' }}
                    <el-button
                        type="primary"
                        link
                        v-perms="['distributor.detail/leaderAdjust']"
                        :disabled="formData.isClose"
                        @click="adjustSuperior"
                    >
                        <icon name="el-icon-EditPen" />
                    </el-button>
                </el-form-item>
                <el-form-item label="下级人数：">
                    <div class="flex">
                        <div>
                            {{ formData.fans }}
                        </div>
                        <el-button
                            :disabled="formData.isClose"
                            class="ml-2"
                            type="primary"
                            link
                            @click="toLowerList"
                            v-perms="['distributor.detail/lowerList']"
                            >查看下级列表</el-button
                        >
                    </div>
                </el-form-item>
                <el-form-item label="下级分销商人数：">
                    {{ formData.fans_distribution }}
                </el-form-item>
                <el-form-item label="下一级人数：">
                    {{ formData.fans_one }} (分销商：{{ formData.fans_one_distribution }}人)
                </el-form-item>
                <el-form-item label="下二级人数：">
                    {{ formData.fans_two }} (分销商：{{
                        formData.fans_two_distribution
                    }}人)</el-form-item
                >
                <el-form-item label="分销状态：">
                    {{ formData.is_freeze == 0 ? '正常' : '冻结' }}
                </el-form-item>
                <el-form-item label="成为分销商时间：">
                    {{ formData.distribution_time }}
                </el-form-item>
            </el-form>
            <el-button
                v-if="formData.is_freeze == 0"
                @click="changeStatus(0)"
                v-perms="['distributor.detail/status']"
                >冻结资格</el-button
            >
            <el-button
                type="primary"
                v-if="formData.is_freeze == 1"
                @click="changeStatus(1)"
                v-perms="['distributor.detail/status']"
                >恢复资格</el-button
            >
        </el-card>
        <AdjustLevelPop ref="adjustLevelPopRef" @close="getDetails"></AdjustLevelPop>
        <SuperiorAdjustPop ref="superiorAdjustPopRef" @close="getDetails"></SuperiorAdjustPop>
    </div>
</template>

<script lang="ts" setup name="consumerDetail">
// import type { FormInstance } from 'element-plus'
import { getDetail, closeDistributionStatus } from '@/api/distribution/distributor'
// import { isEmpty } from '@/utils/util'
import AdjustLevelPop from './components/adjustLevelPop.vue'
import SuperiorAdjustPop from './components/superiorAdjustPop.vue'
import feedback from '@/utils/feedback'

const router = useRouter()
const route = useRoute()
const formData: any = ref({})
// //等级调整弹框ref
const adjustLevelPopRef = shallowRef()
const superiorAdjustPopRef = shallowRef()
// const formRef = shallowRef<FormInstance>()

const getDetails = async () => {
    formData.value = await getDetail({
        id: route.query.id
    })
}
// //调整等级
const adjustLevel = () => {
    adjustLevelPopRef.value.open(formData.value)
}
// //调整上级分销商
const adjustSuperior = () => {
    superiorAdjustPopRef.value.open(formData.value)
}

// //修改分销商状态
const changeStatus = async (distribution_status: any) => {
    if (distribution_status == 0) {
        await feedback.customConfirm(
            '确定冻结：',
            '？请谨慎处理',
            formData.value.nickname,
            'color: red ',
            '冻结会员'
        )
    }
    if (distribution_status == 1) {
        await feedback.customConfirm(
            '确定恢复：',
            '？请谨慎处理',
            formData.value.nickname,
            ' color: red ',
            '恢复会员'
        )
    }
    await closeDistributionStatus({ id: formData.value.id })
    getDetails()
}

// //跳转至下级列表
const toLowerList = () => {
    router.push(`/app/distribute/distributor/lowerList?id=${formData.value.user_id}`)
}

getDetails()
</script>
