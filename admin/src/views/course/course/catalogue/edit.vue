<template>
    <div class="edit">
        <!-- Header Start -->
        <el-card shadow="never" class="!border-none">
            <el-page-header
                v-if="pageType == 1"
                :content="id ? '编辑图文目录' : '新增图文目录'"
                @back="$router.back()"
            />
            <el-page-header
                v-if="pageType == 2"
                :content="id ? '编辑音频目录' : '新增音频目录'"
                @back="$router.back()"
            />
            <el-page-header
                v-if="pageType == 3"
                :content="id ? '编辑视频目录' : '新增视频目录'"
                @back="$router.back()"
            />
            <el-page-header
                v-if="pageType == 4"
                :content="id ? '编辑专栏目录' : '新增专栏目录'"
                @back="$router.back()"
            />
        </el-card>
        <!-- Header End -->

        <!-- Main Start -->
        <el-form ref="formRef" :model="formData" :rules="rules" label-width="120px">
            <el-card shadow="never" class="!border-none" style="margin-top: 15px">
                <el-form-item label="目录名称:" prop="name">
                    <el-input
                        class="ls-input"
                        v-model="formData.name"
                        placeholder="请输入目录名称"
                        show-word-limit
                        maxlength="64"
                    ></el-input>
                </el-form-item>
                <el-form-item
                    label="上传音频:"
                    prop="content"
                    :rules="[
                        {
                            required: true,
                            message: '请选择上传音频',
                            trigger: 'change'
                        }
                    ]"
                    v-if="pageType == 2"
                >
                    <div>
                        <material-select
                            v-model="formData.content"
                            :type="'audio'"
                            :limit="1"
                            @change="materialChange"
                        ></material-select>
                        <div class="form-tips">{{ fileName }}</div>
                    </div>
                </el-form-item>
                <el-form-item
                    label="上传视频:"
                    prop="content"
                    :rules="[
                        {
                            required: true,
                            message: '请选择上传视频',
                            trigger: 'change'
                        }
                    ]"
                    v-if="pageType == 3"
                >
                    <div>
                        <material-select
                            v-model="formData.content"
                            :type="'video'"
                            :limit="1"
                            @change="materialChange"
                        ></material-select>
                        <div class="form-tips">{{ fileName }}</div>
                    </div>
                </el-form-item>
                <el-form-item label="视频封面:" v-if="pageType == 3">
                    <div>
                        <material-select
                            excludeDomain
                            v-model="formData.cover"
                            :type="'image'"
                            :limit="1"
                        ></material-select>
                        <div class="form-tips">
                            封面是指进入视频未播放时展示的图片，建议尺寸750*400px
                        </div>
                    </div>
                </el-form-item>
                <el-form-item v-if="fee_type == 1" :label="pageType == 2 ? '免费试听' : '免费试看'">
                    <div>
                        <el-radio-group v-model="formData.fee_type">
                            <el-radio :label="0">是</el-radio>
                            <el-radio :label="1">否</el-radio>
                        </el-radio-group>
                        <div class="form-tips">默认为否，选择是，则用户可免费观看本章目录内容</div>
                    </div>
                </el-form-item>

                <el-form-item label="内容:" v-if="pageType == 1">
                    <editor v-model="formData.content" width="800" height="664"></editor>
                </el-form-item>
                <el-form-item label="排序:">
                    <div class="form-tips">
                        <el-input class="ls-input" v-model="formData.sort"></el-input>
                        <div>默认为0，数值越大越排前面</div>
                    </div>
                </el-form-item>
                <el-form-item label="状态:">
                    <div>
                        <el-switch
                            v-model="formData.status"
                            :active-value="1"
                            :inactive-value="0"
                        />
                        <span class="ml-2">{{ formData.status == 1 ? '显示' : '隐藏' }}</span>
                    </div>
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
import {
    apiCourseFeeType,
    apiCourseCatalogueAdd,
    apiCourseCatalogueEdit,
    apiCourseCatalogueDetail
} from '@/api/course/course'
import { reactive, ref } from 'vue'
import editor from '@/components/editor/index.vue'
import FooterBtns from '@/components/footer-btns/index.vue'
import materialSelect from '@/components/material/picker.vue'
import type { ElForm } from 'element-plus'
// import { useAdmin } from '@/core/hooks/app'

/** Interface Start **/
interface FormDataObj {
    id: number // 目录ID
    course_id?: string | number // 课程ID
    name?: string // 课程名称
    fee_type: number | string // 是否免费
    content?: string // 内容 - 图文课程
    cover?: string // 视频课程 封面
    sort?: string | number // 排序
    status?: number
    content_name?: any
}
type FormInstance = InstanceType<typeof ElForm>
const formRef = ref<FormInstance>()
/** Interface End **/

/** Data Start **/
const router = useRouter()
const route = useRoute()

const id: any = route.query.id
const course_id: any = route.query.course_id
const pageType: any = route.query.type
const fee_type = ref<boolean | number>(true)
const fileName = ref<string>('')
// 表单数据
const formData = ref<FormDataObj>({
    id: id,
    course_id: course_id,
    name: '',
    content: '',
    cover: '',
    fee_type: 1,
    sort: 0,
    status: 1
})
// 表单规则
const rules = reactive({
    name: [{ required: true, message: '请输入目录名称', trigger: 'blur' }]
})
/** Data End **/

/** Methods Start **/

/**
 * @description 获取课程收费类型
 */
const initCourseFeeType = async (): Promise<void> => {
    const res = await apiCourseFeeType({ id: course_id })
    fee_type.value = res.fee_type
    if (!fee_type.value) {
        formData.value.fee_type = 0
    }
}

/**
 * @description 获取课程目录详情
 */
const initCourseCatalogueDetail = async (): Promise<void> => {
    formData.value = await apiCourseCatalogueDetail({ id })
    fileName.value = formData.value.content_name
}

/**
 * @description 添加课程目录
 */
const handleCourseCatalogueAdd = async (): Promise<void> => {
    await apiCourseCatalogueAdd({ ...formData.value })
    router.back()
}

/**
 * @description 编辑课程目录
 */
const handleCourseCatalogueEdit = async (): Promise<void> => {
    await apiCourseCatalogueEdit({ ...formData.value })
    router.back()
}

/**
 * @description 提交数据
 */
const onSubmit = (formEl: FormInstance | undefined): void => {
    if (!formEl) return
    formEl.validate((valid, requiredFields): void => {
        if (valid) {
            if (!id) handleCourseCatalogueAdd()
            else handleCourseCatalogueEdit()
        }
    })
}

const materialChange = (value: any, completeFileList: any) => {
    console.log(completeFileList)
    fileName.value = completeFileList[0]?.name
}
/** Methods End **/

/** LifeCycle Start **/
onMounted(async () => {
    await initCourseFeeType()
    if (id) {
        await initCourseCatalogueDetail()
    }
})

/** LifeCycle End **/
</script>

<style lang="scss">
.edit {
    .ls-input {
        width: 460px;
    }
}
</style>
