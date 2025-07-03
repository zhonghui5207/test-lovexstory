<template>
    <div class="edit">
        <!-- Header Start -->
        <el-card shadow="never" class="!border-none">
            <el-page-header :content="id ? '编辑课程' : '新增课程'" @back="$router.back()" />
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
            <el-card shadow="never" body-style="padding: 0" class="!border-none mt-4">
                <el-tabs v-model="activeName">
                    <!-- 基础设置 -->
                    <el-tab-pane label="基础设置" name="basic">
                        <basic v-model="formData"></basic>
                    </el-tab-pane>

                    <!-- 课程介绍 -->
                    <el-tab-pane :label="pageType == 4 ? '专栏介绍' : '课程介绍'" name="introduce">
                        <div class="xl:flex">
                            <!-- Left Editor -->
                            <el-form-item prop="content" label-width="0">
                                <div class="w-[640px]">
                                    <editorVue v-model="formData.content" height="664"></editorVue>
                                </div>
                            </el-form-item>
                            <!-- Right Result -->
                            <div class="detail ml-[60px] hidden xl:block">
                                <div class="title">
                                    - {{ pageType == 4 ? '专栏介绍' : '课程介绍' }} -
                                </div>
                                <div class="p-[20px]" v-html="formData.content"></div>
                            </div>
                        </div>
                    </el-tab-pane>

                    <!-- 销售设置 -->
                    <el-tab-pane label="销售设置" name="sales">
                        <sales v-model="formData" :type="id ? 1 : 0"></sales>
                    </el-tab-pane>
                </el-tabs>
            </el-card>
        </el-form>
        <!-- Main End -->

        <!-- Footer Start -->
        <footer-btns>
            <el-button type="primary" v-if="activeName != 'basic'" @click="onNextStep(false)"
                >上一步</el-button
            >
            <el-button type="primary" v-if="activeName == 'sales'" @click="onSubmit(formRef)"
                >保存</el-button
            >
            <el-button type="primary" v-if="activeName != 'sales'" @click="onNextStep"
                >下一步</el-button
            >
        </footer-btns>
        <!-- Footer End -->
    </div>
</template>

<script lang="ts" setup>
import { apiCourseAdd, apiCourseEdit, apiCourseDetail } from '@/api/course/course'
import { reactive, ref, provide } from 'vue'
import basic from './components/basic.vue'
import sales from './components/sales.vue'
import editorVue from '@/components/editor/index.vue'
import FooterBtns from '@/components/footer-btns/index.vue'
import type { ElForm } from 'element-plus'
import { ElMessage } from 'element-plus'
import feedback from '@/utils/feedback'
// import { useAdmin } from '@/core/hooks/app'

/** Interface Start **/
interface FormDataObj {
    name: string // 课程名称
    synopsis: string // 课程简介
    category_id: string | number // 分类ID
    teacher_id: string | number // 讲师ID
    images: Array<string> | string // 轮播图
    cover: string // 封面图
    is_choice: number // 是否精选
    sell_price?: string // 销售价格
    line_price?: string // 划线价
    fee_type: number // 是否免费
    status: number // 状态
    content: string // 内容
    sort: number // 排序
}
type FormInstance = InstanceType<typeof ElForm>
const formRef = ref<FormInstance>()
/** Interface End **/

/** Data Start **/
const route = useRoute()
const router = useRouter()

const id: any = route.query.id
const pageType: any = route.query.type
provide('pageType', pageType)
// 切换索引
const activeName = ref<string>('basic')
// 表单数据
const formData = ref<FormDataObj>({
    name: '',
    synopsis: '',
    category_id: '',
    teacher_id: '',
    images: '',
    cover: '',
    is_choice: 0,
    sell_price: '',
    line_price: '',
    fee_type: 1,
    status: 0,
    content: '',
    sort: 0
})
// 表单校验规则
const rules = reactive({
    name: [{ required: true, message: '请输入课程名称', trigger: 'blur' }],
    category_id: [{ required: true, message: '请选择分类', trigger: 'change' }],
    teacher_id: [{ required: true, message: '请选择讲师', trigger: 'change' }],
    cover: [{ required: true, message: '请上传封面图', trigger: 'change' }],
    images: [{ required: true, message: '请上传轮播图', trigger: 'change' }],
    content: [{ required: true, message: '请输入课程介绍', trigger: 'change' }],
    sell_price: [{ required: true, message: '请输入销售价格', trigger: 'blur' }],
    status: [{ required: true, message: '请选择商品状态', trigger: 'change' }]
})
/** Data End **/

/** Methods Start **/
/**
 * @description 获取课程详情
 */
const getCourseDetail = async (): Promise<void> => {
    formData.value = await apiCourseDetail({ id })
}

/**
 * @description 上一步||下一步||保存
 */
const onNextStep = (isNext = true) => {
    console.log(activeName.value)
    switch (activeName.value) {
        case 'basic':
            activeName.value = 'introduce'
            break
        case 'introduce':
            activeName.value = isNext ? 'sales' : 'basic'
            break
        case 'sales':
            activeName.value = 'introduce'
            break
    }
}

/**
 * @description 添加课程
 */
const handleCourseAdd = async (): Promise<void> => {
    await apiCourseAdd({ ...formData.value, type: pageType })
    await feedback.msgSuccess('保存成功')
    router.back()
}

/**
 * @description 编辑课程
 */
const handleCourseEdit = async (): Promise<void> => {
    await apiCourseEdit({ ...formData.value, type: pageType })
    await feedback.msgSuccess('保存成功')
    router.back()
}

/**
 * @description 提交数据
 */
const onSubmit = (formEl: FormInstance | undefined): void => {
    if (!formEl) return
    formEl.validate((valid, requiredFields): void => {
        if (valid) {
            if (!id) handleCourseAdd()
            else handleCourseEdit()
        } else {
            // 基础信息必填字段
            const basicFields = ['name', 'category_id', 'synopsis', 'cover', 'images']
            // 课程介绍必填字段
            const introduceFields = ['content']
            // 销售设置必填字段
            const salesFields = ['sell_price', 'line_price', 'status']
            for (const key in requiredFields) {
                if (basicFields.includes(key)) activeName.value = 'basic'
                else if (salesFields.includes(key)) activeName.value = 'sales'
                else if (introduceFields.includes(key)) activeName.value = 'introduce'
                ElMessage({ type: 'error', message: requiredFields[key][0].message })
                break
            }
        }
    })
}
/** Methods End **/

/** LifeCycle Start **/
if (id) getCourseDetail()
/** LifeCycle End **/
</script>

<style lang="scss">
.edit {
    .el-tabs__header {
        padding: 0 20px;
        border-bottom: 1px solid #e5e5e5;
    }

    .el-tabs__content {
        padding: 20px;
    }

    .ls-input {
        width: 460px;
    }

    .detail {
        width: 375px;
        height: 662px;
        overflow: auto;
        border: 1px solid #e5e5e5;

        .title {
            text-align: center;
            height: 40px;
            line-height: 40px;
            border-bottom: 1px solid #e5e5e5;
        }
    }
}
</style>
