<template>
    <div class="substance_edit-help">
        <el-card class="!border-none mt-4" shadow="never">
            <el-page-header
                :content="mode == 'add' ? '新增定时任务' : '编辑定时任务'"
                @back="$router.go(-1)"
            />
        </el-card>

        <el-card shadow="never" class="!border-none mt-4">
            <el-form :rules="rules" ref="formRef" :model="formData" label-width="120px">
                <!-- 标题输入框 -->
                <el-form-item label="名称" prop="name">
                    <el-input
                        v-model="formData.name"
                        show-word-limit
                        placeholder="请输入名称"
                        class="max-w-[300px]"
                    ></el-input>
                </el-form-item>
                <el-form-item label="类型">
                    <el-radio v-model="formData.type" :label="1">定时任务</el-radio>
                </el-form-item>
                <el-form-item label="命令" prop="command">
                    <el-input
                        v-model="formData.command"
                        class="max-w-[300px]"
                        placeholder="请输入thinkphp命令，如vresion"
                    ></el-input>
                </el-form-item>
                <el-form-item label="参数">
                    <el-input
                        v-model="formData.params"
                        class="max-w-[300px]"
                        placeholder="请输入参数，例:--id 8 --name 测试"
                    ></el-input>
                </el-form-item>
                <el-form-item label="状态">
                    <el-switch v-model="formData.status" :active-value="1" :inactive-value="2" />
                </el-form-item>
                <el-form-item label="规则" prop="expression">
                    <el-input
                        @blur="blur"
                        v-model="formData.expression"
                        class="max-w-[300px]"
                        placeholder="请输入crontab规则，例：59 * * *"
                    ></el-input>
                </el-form-item>
                <el-form-item>
                    <el-table ref="paneTable" :data="lists" style="max-width: 500px">
                        <el-table-column prop="time" label="序号" min-width="80"></el-table-column>
                        <el-table-column
                            prop="date"
                            label="执行时间"
                            min-width="180"
                        ></el-table-column>
                    </el-table>
                </el-form-item>
                <el-form-item label="备注">
                    <el-input
                        type="textarea"
                        class="max-w-[300px]"
                        v-model="formData.remark"
                        placeholder="请输入备注"
                    ></el-input>
                </el-form-item>
            </el-form>
        </el-card>

        <!-- Footer Start -->
        <footer-btns>
            <el-button type="primary" @click="onSubmit(formRef)">保存</el-button>
        </footer-btns>
        <!-- Footer End -->
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue'
import {
    apiCrontabAdd,
    apiCrontabEdit,
    apiCrontabDetail,
    apiCrontabExpression
} from '@/api/setting/system'
import { PageMode } from '@/utils/enum'
import FooterBtns from '@/components/footer-btns/index.vue'
import type { ElForm } from 'element-plus'

/** Interface Start **/
interface formDataObj {
    id: number | string
    status_desc?: number | string
    type_desc?: number | string
    name: string //	是	string	名称
    type: number //	是	integer	类型
    command: string //	是	string	命令
    expression: string //	是	string	运行规则
    status: number //	是	integer	状态
    remark: string //	否	string	备注
    params: string //	否	string	参数
}
/** Interface End **/

/** Data Start **/
type FormInstance = InstanceType<typeof ElForm>
const formRef = ref<FormInstance>()
const route = useRoute()
const router = useRouter()
const mode = ref<string>(PageMode['ADD']) // 当前页面: add-添加角色 edit-编辑角色
// 表
const lists = ref<Array<any>>([])
// 表单数据
const formData = ref<formDataObj>({
    id: '', // id
    name: '', //	是	string	名称
    type: 1, //	是	integer	类型
    command: '', //	是	string	命令
    expression: '', //	是	string	运行规则
    status: 1, //	是	integer	状态
    remark: '', //	否	string	备注
    params: '' //	否	string	参数
})

// 表单校验
const rules = reactive<object>({
    name: [{ required: true, message: '请输入名称', trigger: 'blur' }],
    command: [{ required: true, message: '请输入命令', trigger: 'blur' }],
    params: [{ required: true, message: '请输入参数', trigger: 'blur' }],
    expression: [{ required: true, message: '请输入规则', trigger: 'blur' }]
})

/** Data End **/

/** Methods Start **/
// 提交表单
const onSubmit = (formEl: FormInstance | undefined): void => {
    if (!formEl) return
    formEl.validate((valid): boolean | undefined => {
        if (valid) {
            switch (mode.value) {
                case PageMode['ADD']:
                    handleNoticeAdd()
                    return
                case PageMode['EDIT']:
                    handleNoticeEdit()
                    return
            }
        }
    })
}

const blur = () => {
    if (formData.value.expression != '') {
        getExpressionFun()
    }
}

const getExpressionFun = async (): Promise<void> => {
    const res: any = await apiCrontabExpression({
        expression: formData.value.expression
    })
    lists.value = res
}

// 添加定时任务
const handleNoticeAdd = async (): Promise<void> => {
    await apiCrontabAdd(formData.value)
    setTimeout(() => router.go(-1), 500)
}

// 编辑帮助文章
const handleNoticeEdit = async (): Promise<void> => {
    delete formData.value.status_desc
    delete formData.value.type_desc
    await apiCrontabEdit(formData.value)
    setTimeout(() => router.go(-1), 500)
}

// 初始化表单数据
const initFormDataForNoticeEdit = async (): Promise<void> => {
    const res: any = await apiCrontabDetail({
        id: formData.value.id
    })
    formData.value = res
    getExpressionFun()
}

/** Methods End **/

/** Life Cycle End **/
const query: any = route.query
if (query.mode) mode.value = query.mode

if (mode.value === PageMode['EDIT']) {
    formData.value.id = query.id * 1
    initFormDataForNoticeEdit()
}
/** Life Cycle End **/
</script>

<style lang="scss" scoped>
// .form-container {
//     display: flex;

//     .form-left {
//         width: 500px;
//     }

//     .form-right {
//         width: 800px;
//     }
// }
</style>
