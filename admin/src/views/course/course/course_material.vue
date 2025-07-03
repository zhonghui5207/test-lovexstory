<template>
    <popup ref="popRef" width="700px" :title="title" @confirm="onSubmit(formRef)" :async="true">
        <div class="edit">
            <!-- Main Start -->
            <el-form ref="formRef" :model="formData" :rules="rules" label-width="120px">
                <el-card shadow="never" style="margin-top: 15px" class="!border-none mt-4">
                    <el-form-item label="上传:" prop="content">
                        <div>
                            <el-tooltip
                                class="box-item"
                                effect="dark"
                                :content="fileName"
                                placement="top-start"
                                :disabled="!formData.content"
                            >
                                <material-select
                                    v-model="formData.content"
                                    :type="'file'"
                                    :limit="1"
                                    @change="getFileName"
                                ></material-select>
                            </el-tooltip>
                            <div class="form-tips">支持zip、rar格式的文件，不能超过200M</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="网盘链接:" prop="link">
                        <el-input
                            class="ls-input"
                            v-model="formData.link"
                            placeholder="由于IOS微信端不支持资料下载，请通过网盘链接上传、下载资料"
                        >
                        </el-input>
                    </el-form-item>
                    <el-form-item label="网盘密码:" prop="code">
                        <el-input
                            class="ls-input"
                            v-model="formData.code"
                            placeholder="请输入网盘文件获取密码"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="强制网盘下载:">
                        <el-radio-group v-model="formData.is_link">
                            <el-radio :label="1">是</el-radio>
                        </el-radio-group>
                    </el-form-item>
                </el-card>
            </el-form>

            <!-- Main End -->
        </div>
    </popup>
</template>

<script lang="ts" setup>
import { apiSetCourseMaterial, apiGetCourseMaterial } from '@/api/course/course'
import { reactive, ref } from 'vue'
import materialSelect from '@/components/material/picker.vue'
import type { ElForm } from 'element-plus'
import feedback from '@/utils/feedback'

/** Interface Start **/
interface FormDataObj {
    id: number | string // 资料ID
    course_id: string | number // 课程ID
    link: string // 课程名称
    code: number | string // 密码
    content?: string // 上传的资料
    is_link: number // 是否强制下载：1-是
}
type FormInstance = InstanceType<typeof ElForm>
const formRef = ref<FormInstance>()
/** Interface End **/

/** Data Start **/
const popRef = shallowRef()
const id: any = ref()
const title = ref('')
const fileName = ref('')
const course_id: any = ref()
const pageType: any = ref()
// 表单数据
const formData = ref<FormDataObj>({
    id: '',
    course_id: id,
    content: '',
    link: '',
    code: '',
    is_link: 1
})
// 表单规则
const rules = reactive({
    content: [{ required: true, message: '请上传课件资料', trigger: 'change' }],
    link: [{ required: true, message: '请输入网盘链接', trigger: 'blur' }],
    code: [{ required: true, message: '请输入网盘密码', trigger: 'blur' }]
})
/** Data End **/

/** Methods Start **/
/**
 * @description 获取课程资料
 */
const initCourseMaterialDetail = async (): Promise<void> => {
    const res = await apiGetCourseMaterial({ course_id: id.value })
    console.log(res)
    fileName.value = res.content_name
    if (res.length != 0) {
        formData.value = res
    } else {
        formData.value = {
            id: '',
            course_id: id,
            content: '',
            link: '',
            code: '',
            is_link: 1
        }
    }
}

/**
 * @description 设置课程资料
 */
const handleCourseMaterialSet = async (): Promise<void> => {
    await apiSetCourseMaterial({
        ...formData.value,
        course_id: id.value
    })
}
/**
 * @description 获取文件名
 */
const getFileName = (value: any, completeFile: any) => {
    fileName.value = completeFile[0].name
}

/**
 * @description 提交数据
 */
const onSubmit = (formEl: FormInstance | undefined): void => {
    if (!formEl) feedback.alertError('修改失败！')
    formEl.validate((valid): boolean | undefined => {
        if (!valid) return false
        else handleCourseMaterialSet()
        popRef.value.visible = false
    })
}

//打开弹框
const openPop = (idValue: any, typeValue: any) => {
    popRef.value.open()
    id.value = idValue
    pageType.value = typeValue
    switch (pageType.value) {
        case 1:
            title.value = '图文课件资料'
            break
        case 2:
            title.value = '音频课件资料'
            break
        case 3:
            title.value = '视频课件资料'
            break
    }
    initCourseMaterialDetail()
}

/** Methods End **/

defineExpose({ openPop })
</script>

<style lang="scss">
.edit {
    .ls-input {
        width: 460px;
    }
}
</style>
