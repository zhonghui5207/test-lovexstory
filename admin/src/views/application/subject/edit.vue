<template>
    <div>
        <!-- Header Start -->
        <el-card shadow="never" class="!border-none">
            <el-page-header
                :content="id ? '编辑专区活动' : '新增专区活动'"
                @back="$router.back()"
            />
        </el-card>
        <!-- Header End -->

        <!-- Main Start -->
        <el-form
            ref="formRef"
            :model="formData"
            :rules="rules"
            label-width="120px"
            class="formData"
        >
            <el-card shadow="never" class="!border-none mt-4">
                <el-form-item label="专区名称:" prop="name">
                    <el-input
                        class="ls-input"
                        v-model="formData.name"
                        placeholder="请输入"
                    ></el-input>
                </el-form-item>

                <el-form-item label="专区封面:" prop="cover">
                    <div>
                        <material-select v-model="formData.cover" :limit="1"></material-select>
                        <div class="form-tips">用于专栏专区展示的图片，建议尺寸：750*1010px</div>
                    </div>
                </el-form-item>

                <el-form-item label="专区头图:">
                    <div>
                        <material-select v-model="formData.image" :limit="1"></material-select>
                        <div class="form-tips">用于专区内页顶部图片，建议尺寸：750*400px</div>
                    </div>
                </el-form-item>

                <el-form-item label="排序:">
                    <div>
                        <el-input
                            class="ls-input"
                            v-model="formData.sort"
                            placeholder="请输入"
                        ></el-input>
                        <div class="form-tips">默认排序为0，数字越大，排序越靠前</div>
                    </div>
                </el-form-item>

                <el-form-item label="状态:" prop="status">
                    <el-switch
                        v-model="formData.status"
                        :active-text="formData.status ? '启用' : '关闭'"
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
import { apiSubjectAdd, apiSubjectEdit, apiSubjectDetail } from '@/api/application/application'
import { ref, reactive } from 'vue'
import FooterBtns from '@/components/footer-btns/index.vue'
import MaterialSelect from '@/components/material/picker.vue'
import type { ElForm } from 'element-plus'
import feedback from '@/utils/feedback'

/** Interface Start **/
interface FormDataObj {
    name?: string // 专区名称
    cover?: string // 专区封面
    image?: string // 专区头图
    sort: number // 排序
    status: number // 状态
}
/** Interface End **/

/** Data Start **/
type FormInstance = InstanceType<typeof ElForm>
const formRef = ref<FormInstance>()

const route = useRoute()
const router = useRouter()

const id: any = route.query.id
const formData = ref<FormDataObj>({
    name: '',
    cover: '',
    image: '',
    sort: 0,
    status: 1
})

// 表单娇艳规则
const rules = reactive<object>({
    name: [{ required: true, message: '请输入专区名称', trigger: 'blur' }],
    cover: [{ required: true, message: '请选择专区封面', trigger: 'change' }]
})
/** Data End **/

/** Methods Start **/
// 获取详情
const getHomeMenuDetail = async (id: number): Promise<void> => {
    formData.value = await apiSubjectDetail({ id })
}
// 添加专区活动
const handleHomeMenuAdd = async (): Promise<void> => {
    await apiSubjectAdd({ ...formData.value })
    await feedback.msgSuccess('保存成功！')
    router.back()
}
// 编辑专区活动
const handleHomeMenuEdit = async (): Promise<void> => {
    await apiSubjectEdit({ ...formData.value })
    await feedback.msgSuccess('保存成功！')
    router.back()
}
// 提交数据
const onSubmit = (formEl: FormInstance | undefined): void => {
    if (!formEl) return
    formEl.validate((valid): boolean | undefined => {
        if (!valid) return false
        if (!id) handleHomeMenuAdd()
        else handleHomeMenuEdit()
    })
}
/** Methods End **/

/** LifeCycle Start **/
// 请求详情 => 如果是编辑的话
if (id) getHomeMenuDetail(id)
/** LifeCycle End **/
</script>

<style lang="scss" scoped>
.ls-input,
.select {
    width: 340px;
}
</style>
