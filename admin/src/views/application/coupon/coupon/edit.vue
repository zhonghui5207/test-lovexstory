<template>
    <div>
        <el-card shadow="never" class="!border-none">
            <el-page-header :content="id ? '编辑优惠券' : '新增优惠券'" @back="$router.back()" />
        </el-card>
        <el-form label-width="120" ref="ruleFormRef" :model="formData" :rules="rules">
            <el-card shadow="never" class="!border-none mt-4">
                <div class="px-[30px] py-[20px]">
                    <div class="font-extrabold text-lg">基本信息</div>
                    <div class="mt-6">
                        <el-form-item label="优惠券名称" prop="name">
                            <el-input
                                placeholder="请输入优惠券名称"
                                class="w-[300px]"
                                v-model="formData.name"
                                :disabled="type == 2"
                            ></el-input>
                        </el-form-item>
                        <el-form-item label="发放数量" prop="send_num">
                            <div>
                                <div class="flex items-center">
                                    <el-input
                                        placeholder="请输入每日发放数量"
                                        class="w-[300px]"
                                        v-model="formData.send_num"
                                        maxlength="8"
                                        :disabled="type == 2 || formData.send_num_type == 1"
                                    ></el-input>
                                    <el-checkbox
                                        class="ml-2"
                                        label="不限数量"
                                        v-model="formData.send_num_type"
                                        :true-label="1"
                                        :false-label="2"
                                        :disabled="type == 2"
                                    ></el-checkbox>
                                </div>
                                <div class="form-tips">
                                    编辑进行中的优惠券，发放总量只能增加不能减少，请谨慎设置。最多1000000张
                                </div>
                            </div>
                        </el-form-item>
                        <el-form-item label="优惠券面额" prop="money">
                            <div>
                                <el-input
                                    class="w-[360px]"
                                    maxlength="8"
                                    placeholder="请输入优惠券面额"
                                    v-model="formData.money"
                                    :disabled="type == 2 || status == 2"
                                ></el-input>
                                <div class="form-tips">面额需大于0元，支持两位小数</div>
                            </div>
                        </el-form-item>

                        <el-form-item label="使用门槛" prop="use_condition">
                            <div>
                                <div>
                                    <el-radio-group
                                        v-model="formData.use_condition"
                                        :disabled="type == 2 || status == 2"
                                    >
                                        <el-radio :label="1" size="large">无门槛</el-radio>
                                        <el-radio :label="2" size="large">订单满减</el-radio>
                                    </el-radio-group>
                                </div>
                                <el-input
                                    v-if="formData.use_condition != 1"
                                    class="w-[240px]"
                                    v-model="formData.condition_money"
                                    :disabled="type == 2 || status == 2"
                                >
                                    <template #append>
                                        <div>元可使用</div>
                                    </template>
                                </el-input>
                            </div>
                        </el-form-item>
                    </div>
                </div>
            </el-card>
            <el-card shadow="never" class="!border-none mt-4">
                <div class="px-[30px] py-[20px]">
                    <div class="font-extrabold text-lg">优惠券设置</div>
                    <div class="mt-6">
                        <el-form-item label="领取方式" prop="get_type">
                            <div>
                                <el-radio-group
                                    v-model="formData.get_type"
                                    :disabled="type == 2 || status == 2"
                                >
                                    <el-radio :label="1" size="large">用户领取</el-radio>
                                    <el-radio :label="2" size="large">系统赠送</el-radio>
                                </el-radio-group>
                                <div class="form-tips">
                                    用户领取：指用户自行领取；系统赠送：指通过后台给某个用户发放
                                </div>
                            </div>
                        </el-form-item>
                        <el-form-item label="领券时间" prop="receive_time_start">
                            <div>
                                <DataPicker
                                    v-model:start_time="formData.receive_time_start"
                                    v-model:end_time="formData.receive_time_end"
                                    :disabled="type == 2 || status == 2"
                                >
                                </DataPicker>
                            </div>
                        </el-form-item>
                        <el-form-item label="领取次数">
                            <div>
                                <div>
                                    <el-radio-group
                                        v-model="formData.get_num_type"
                                        :disabled="type == 2 || status == 2"
                                    >
                                        <el-radio :label="1" size="large">不限领取</el-radio>
                                        <el-radio :label="2" size="large">限制领取</el-radio>
                                        <el-radio :label="3" size="large">每天限制领取</el-radio>
                                    </el-radio-group>
                                </div>
                                <el-input
                                    v-if="formData.get_num_type != 1"
                                    class="w-[240px]"
                                    v-model="formData.get_num"
                                    :disabled="type == 2 || status == 2"
                                >
                                    <template #append>
                                        <div>张</div>
                                    </template>
                                </el-input>
                            </div>
                        </el-form-item>
                        <el-form-item label="使用范围" prop="use_scope">
                            <div>
                                <div>
                                    <el-radio-group
                                        v-model="formData.use_scope"
                                        :disabled="type == 2 || status == 2"
                                    >
                                        <el-radio :label="1" size="large">全场通用</el-radio>
                                        <el-radio :label="2" size="large">指定课程可用</el-radio>
                                        <el-radio :label="3" size="large">指定课程不可用</el-radio>
                                    </el-radio-group>
                                </div>
                                <div v-if="formData.use_scope != 1">
                                    <div class="flex mt-2">
                                        <courseSelect
                                            @confirm="confirm"
                                            :courseData="formData.course_list"
                                        >
                                            <el-button
                                                type="primary"
                                                :disabled="type == 2 || status == 2"
                                                >选择课程</el-button
                                            >
                                        </courseSelect>
                                        <el-button
                                            type="primary"
                                            link
                                            class="ml-2"
                                            @click="courseClean"
                                            :disabled="type == 2 || status == 2"
                                        >
                                            清空</el-button
                                        >
                                    </div>
                                    <div class="mt-2 flex flex-wrap w-[500px]">
                                        <div
                                            class="flex ml-2"
                                            v-for="(item, index) in formData.course_list"
                                            :key="index"
                                        >
                                            <DelWrap
                                                @close="courseDel(index)"
                                                :show-close="type != 2 || status == 2"
                                            >
                                                <el-tooltip :content="item.name" placement="top">
                                                    <el-image
                                                        style="width: 80px; height: 60px"
                                                        :src="item.cover"
                                                    ></el-image>
                                                </el-tooltip>
                                            </DelWrap>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </el-form-item>
                    </div>
                </div>
            </el-card>
            <el-card shadow="never" class="!border-none mt-4">
                <div class="px-[30px] py-[20px]">
                    <div class="font-extrabold text-lg">用券规则</div>
                </div>
                <el-form-item label="使用时间" prop="use_time_type">
                    <div>
                        <el-radio-group
                            v-model="formData.use_time_type"
                            :disabled="type == 2 || status == 2"
                        >
                            <el-radio :label="1" size="large">固定日期</el-radio>
                            <el-radio :label="2" size="large">领券当日起</el-radio>
                            <el-radio :label="3" size="large">领券次日起</el-radio>
                        </el-radio-group>
                        <div>
                            <div v-if="formData.use_time_type == 1">
                                <DataPicker
                                    v-model:start_time="formData.use_time_start"
                                    v-model:end_time="formData.use_time_end"
                                    :disabled="type == 2 || status == 2"
                                >
                                </DataPicker>
                            </div>
                            <div v-if="formData.use_time_type != 1">
                                <el-input
                                    class="w-[240px]"
                                    v-model="formData.use_day"
                                    :disabled="type == 2 || status == 2"
                                >
                                    <template #append>
                                        <div>天内可用</div>
                                    </template>
                                </el-input>
                            </div>
                        </div>
                    </div>
                </el-form-item>
            </el-card>
        </el-form>
        <FooterBtns>
            <el-button type="primary" @click="submitForm(ruleFormRef)" :disabled="type == 2"
                >保存</el-button
            >
        </FooterBtns>
    </div>
</template>
<script lang="ts" setup>
import courseSelect from '@/components/course-select/course-root.vue'
import { apiAddCoupon, apiCouponDetail, apiEditCoupon } from '@/api/application/coupon'
import type { FormInstance, FormRules } from 'element-plus'
import feedback from '@/utils/feedback'

const router = useRouter()
const route = useRoute()
const id = ref()
const type = ref()
const status = ref()

//表单ref
const ruleFormRef = ref<FormInstance>()
//表单数据
const formData = ref<any>({
    name: '', //优惠券名称
    send_num: '', //发放数量
    use_time_type: 1, //使用时间类型 1-固定时间 2-领取当日起 3- 领券次日起
    use_day: '', //相对失效
    use_time_start: '', //固定时效
    use_time_end: '', //固定时效
    money: '', //优惠券面额
    get_type: 1, //领取方式 1-用户领取 2-系统赠送
    use_condition: 1, //使用门槛 1-无门槛 2-订单满多少金额可用
    condition_money: '', //满减金额
    get_num_type: 1, //领取次数类型 1-不限制 2-只能领取n张 3-每天只能领取n张
    get_num: '', //领取数量
    use_scope: 1, //使用范围 1-全场可用 2-指定课程可用 3指定课程不可用
    course_list: [], //课程id列表
    // is_quota: 0 //不限数量 1-限制 0-不限制
    send_num_type: 2, //不限数量 2-限制 1-不限制
    receive_time_start: '', //领券时间
    receive_time_end: '' //领券时间
})

//发放数量校验规则
const checkQuota = (rule: any, value: any, callback: any) => {
    if (value == '' && formData.value.send_num_type == 0) {
        callback(new Error('请输入优惠券发放数量！'))
    } else {
        callback()
    }
}

//使用时间校验规则
const checkValidType = (rule: any, value: any, callback: any) => {
    if (value == 1 && formData.value.use_time_start == '') {
        callback(new Error('请输入优惠券可使用时间！'))
    }
    if (value != 1 && formData.value.use_day == '') {
        callback(new Error('请输入优惠券可使用时间！'))
    }
    if (value != 1 && formData.value.use_day == 0) {
        callback(new Error('使用时间不能为0！'))
    } else {
        callback()
    }
}

//使用门槛校验
const checkType = (rule: any, value: any, callback: any) => {
    if (value == 2 && formData.value.condition_money == '') {
        callback(new Error('请输入订单的使用门槛！'))
    } else {
        callback()
    }
}

//使用范围校验
const checkUsed = (rule: any, value: any, callback: any) => {
    if (value != 1 && !formData.value.course_list.length) {
        callback(new Error('请选择使用范围课程！'))
    } else {
        callback()
    }
}

const rules = reactive<FormRules>({
    name: [{ required: true, message: '请输入优惠券名称！', trigger: 'blur' }],
    send_num: [{ validator: checkQuota, trigger: 'change', required: true }],
    use_time_type: [{ validator: checkValidType, trigger: 'blur', required: true }],
    money: [{ required: true, message: '请输入优惠券面额', trigger: 'blur' }],
    receivingMethod: [{ required: true, message: '请选择领取方式', trigger: 'blur' }],
    use_condition: [{ validator: checkType, trigger: 'change', required: true }],
    use_scope: [{ validator: checkUsed, trigger: 'blur', required: true }],
    receive_time_start: [{ required: true, message: '请选择领券时间', trigger: 'blur' }]
})

//课程弹框确认
const confirm = (value: any) => {
    formData.value.course_list = value
}

//课程删除
const courseDel = (index: any) => {
    formData.value.course_list.splice(index, 1)
}

//清空课程
const courseClean = () => {
    formData.value.course_list = []
}

//提交
const submitForm = async (formEl: FormInstance | undefined) => {
    if (!formEl) return
    await formEl.validate(async (valid, fields) => {
        if (valid) {
            if (id.value) {
                await apiEditCoupon({ ...formData.value, id: id.value })
            } else {
                await apiAddCoupon(formData.value)
            }
            feedback.msgSuccess('保存成功！')
            router.back()
        } else {
            console.log('error submit!', fields)
        }
    })
}

onMounted(async () => {
    await nextTick()
    id.value = route.query.id
    status.value = route.query.status
    type.value = route.query.type //type：edit-1 detial-2
    if (id.value) {
        const res = await apiCouponDetail({ id: id.value })
        for (const key in res) {
            formData.value[key] = res[key]
        }
    }
})
</script>
<style lang="scss" scoped></style>
