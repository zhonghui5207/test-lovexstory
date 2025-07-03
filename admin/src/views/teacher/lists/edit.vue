<template>
    <div class="techaer-detail">
        <!-- Header Start -->
        <el-card shadow="never" class="!border-none">
            <el-page-header :content="id ? '编辑讲师' : '新增讲师'" @back="$router.back()" />
        </el-card>
        <!-- Header End -->

        <!-- Main Start -->
        <el-form
            ref="formRef"
            :model="formData"
            :rules="rules"
            label-width="120px"
            class="demo-formData"
        >
            <el-card shadow="never" class="!border-none mt-4">
                <el-form-item label="讲师姓名:" prop="name">
                    <el-input
                        class="ls-input"
                        v-model="formData.name"
                        placeholder="请输入"
                    ></el-input>
                </el-form-item>
                <el-form-item label="讲师头像:" prop="avatar">
                    <div>
                        <material-select v-model="formData.avatar" :limit="1"></material-select>
                        <div class="form-tips">
                            建议尺寸：宽200像素*高200像素的jpg，jpeg，png图片，图片小于2MB
                        </div>
                    </div>
                </el-form-item>
                <el-form-item label="讲师性别:">
                    <el-radio-group v-model="formData.gender">
                        <el-radio :label="1">男</el-radio>
                        <el-radio :label="2">女</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="讲师简介:">
                    <el-input class="ls-input" v-model="formData.synopsis"></el-input>
                </el-form-item>
                <el-form-item label="讲师介绍:">
                    <editor v-model="formData.introduce" width="800" height="664"></editor>
                </el-form-item>
                <el-form-item label="讲师排序:">
                    <el-input
                        class="ls-input"
                        v-model="formData.sort"
                        placeholder="请输入"
                    ></el-input>
                </el-form-item>
                <el-form-item label="状态:" prop="status">
                    <el-switch
                        v-model="formData.status"
                        :active-text="formData.status ? '正常' : '停用'"
                        :active-value="1"
                        :inactive-value="0"
                    />
                </el-form-item>
            </el-card>
        </el-form>
        <!-- Main End -->

        <!-- Footer Start -->
        <footer-btns>
            <el-button type="primary" @click="onSubmit(formRef)">保存</el-button>
        </footer-btns>
        <!-- Footer End -->
    </div>
</template>

<script lang="ts" setup>
import { apiTeacherAdd, apiTeacherEdit, apiTeacherDetail } from '@/api/teacher/teacher'
import Editor from '@/components/editor/index.vue'
import FooterBtns from '@/components/footer-btns/index.vue'
import MaterialSelect from '@/components/material/picker.vue'
import type { ElForm } from 'element-plus'

/** Interface Start **/
interface FormDataObj {
    name: string // 讲师名称
    avatar: string | string[] // 讲师头像
    synopsis: string // 讲师介绍
    gender: number // 讲师性别
    introduce: string // 讲师简介
    sort: number // 讲师排序
    status: number // 讲师状态
}
type FormInstance = InstanceType<typeof ElForm>
const formRef = ref<FormInstance>()
/** Interface End **/

/** Data Start **/
const route = useRoute()
const router = useRouter()
const id: any = route.query.id
const formData = ref<FormDataObj>({
    name: '',
    avatar: '',
    synopsis: '',
    gender: 1,
    introduce: '',
    sort: 0,
    status: 1
})
// 表单校验规则
const rules = reactive({
    name: [{ required: true, message: '请输入讲师名称', trigger: 'blur' }],
    avatar: [{ required: true, message: '请上传讲师图片', trigger: 'change' }],
    status: [{ required: true, message: '是否显示', trigger: 'change' }]
})
/** Data End **/

/** Methods Start **/
/**
 * @description 获取讲师详情
 */
const getTeacherDetail = async (id: number): Promise<void> => {
    formData.value = await apiTeacherDetail({ id })
}

/**
 * @description 添加讲师
 */
const handleTeacherAdd = async (): Promise<void> => {
    await apiTeacherAdd({ ...formData.value })
    router.back()
}

/**
 * @description 编辑讲师
 */
const handleTeacherEdit = async (): Promise<void> => {
    await apiTeacherEdit({ ...formData.value })
    router.back()
}

/**
 * @description 提交讲师数据
 */
const onSubmit = (formEl: FormInstance | undefined): void => {
    if (!formEl) return
    formEl.validate((valid): boolean | undefined => {
        if (!valid) return false
        if (!id) handleTeacherAdd()
        else handleTeacherEdit()
    })
}
/** Methods End **/

/** LifeCycle Start **/
// 请求详情 => 如果是编辑的话
if (id) getTeacherDetail(id)
/** LifeCycle End **/
</script>

<style lang="scss" scoped>
.ls-input,
.select {
    width: 340px;
}
</style>
