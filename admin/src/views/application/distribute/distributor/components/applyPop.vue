<template>
    <Popup ref="popRef" title="分销审核" width="700px" @confirm="submit" async>
        <div>
            <!-- <div class="text-xl font-medium">基本信息</div> -->
            <el-form class="mt-4" inline v-loading="!userData">
                <!-- <el-form-item label="审核状态">
                    <div class="w-[230px] text-success" v-if="">{{ userData?.statusMsg || '-' }}</div>
                </el-form-item> -->
                <el-form-item label="用户信息">
                    <div class="w-[230px] text-success">{{ userData?.nickname || '-' }}</div>
                </el-form-item>

                <el-form-item label="联系手机">
                    <div class="w-[230px]">{{ userData.mobile || '-' }}</div>
                </el-form-item>
                <el-form-item label="真实姓名">
                    <div class="w-[230px]">
                        {{ userData.real_name || '-' }}
                    </div>
                </el-form-item>
                <el-form-item label="现住省份">
                    <div class="w-[230px]">
                        {{
                            `${userData.province_desc}/${userData.city_desc}/${userData.district_desc}`
                        }}
                    </div>
                </el-form-item>
                <el-form-item label="申请原因">
                    <el-tooltip
                        :disabled="!userData.reason"
                        :content="userData.reason"
                        raw-content
                        placement="top"
                    >
                        <div class="w-[230px] truncate">
                            {{ userData.reason || '-' }}
                        </div>
                    </el-tooltip>
                </el-form-item>
                <el-form-item label="申请时间">
                    <div class="w-[230px]">{{ userData.create_time || '-' }}</div>
                </el-form-item>
                <el-form-item label="审核时间" v-if="userData.status != 0">
                    <div class="w-[230px]">{{ userData.audit_time || '-' }}</div>
                </el-form-item>
                <el-form-item label="审核状态">
                    <div class="w-[230px]" v-if="type == 2">
                        <el-radio-group v-model="submitData.status">
                            <el-radio :label="1">通过</el-radio>
                            <el-radio :label="2">拒绝</el-radio>
                        </el-radio-group>
                        <el-input
                            v-model="submitData.audit_remark"
                            v-if="submitData.status == 2"
                            type="textarea"
                        ></el-input>
                    </div>
                    <div class="w-[230px]" v-if="type == 1">
                        <div>{{ userData.status_desc }}</div>
                        <div class="text-danger" v-if="userData.audit_remark">
                            原因：{{ userData.audit_remark }}
                        </div>
                    </div>
                </el-form-item>
                <!-- <el-form-item label="审核时间">
                    <div class="w-[230px]">
                        {{
                            userData.update_time == userData.create_time
                                ? '-'
                                : userData.update_time
                        }}
                    </div>
                </el-form-item> -->
            </el-form>
        </div>
    </Popup>
</template>

<script setup lang="ts">
import { postExamine, emamineDetail } from '@/api/distribution/distributor'

const emit = defineEmits(['close'])
//弹框ref
const popRef = shallowRef()

//id
const id = ref()
const type = ref()
const userData: any = ref({})

const submitData: any = ref({
    status: 1, //是否同意 1-是 2-否
    audit_remark: '' //审核说明
})

//获取详情
const getDetail = async () => {
    userData.value = await emamineDetail({ id: id.value })
}

//提交审核
const submit = async () => {
    if (type.value == 1) {
        popRef.value.close()
        emit('close')
    } else if (type.value == 2) {
        await postExamine({ ...submitData.value, id: id.value })
        popRef.value.close()
        emit('close')
    }
}

//打开弹框
const open = (option: { id: number; type: number }) => {
    //type 1-详情 2-审核
    popRef.value.open()
    id.value = option.id
    type.value = option.type
    getDetail()
}

defineExpose({ open })
</script>

<style scoped lang="scss">
:deep(.el-form--inline .el-form-item) {
    vertical-align: top;
}
</style>
