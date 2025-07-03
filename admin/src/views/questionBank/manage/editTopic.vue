<template>
    <div>
        <el-card shadow="never" class="!border-none">
            <el-page-header :content="`${id ? '编辑题目' : '新增题目'}`" @back="$router.back()" />
        </el-card>
        <el-card shadow="never" class="!border-none mt-4">
            <el-form
                :disabled="quetionbankStatus != 1"
                label-width="90px"
                :model="formData"
                ref="formRef"
                :rules="rules"
            >
                <el-form-item label="题库">
                    <div>
                        <el-input :value="title" disabled class="w-[500px]"></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="题型">
                    <el-radio-group v-model="formData.type">
                        <el-radio :label="1">单选题</el-radio>
                        <el-radio :label="2">多选题</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="题干" prop="stem">
                    <div>
                        <el-input
                            placeholder="请输入题干"
                            show-word-limit
                            maxlength="1000"
                            v-model="formData.stem"
                            type="textarea"
                            rows="5"
                            class="w-[500px]"
                        ></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="题干插图">
                    <div>
                        <MaterialPicker v-model="formData.illustration"></MaterialPicker>
                        <div class="form-tips">尺寸：630*440</div>
                    </div>
                </el-form-item>
                <div>
                    <el-form-item
                        v-for="(item, index) in formData.option"
                        :key="index"
                        :label="`选项${indexToString(index)}`"
                        class="is-required"
                    >
                        <div>
                            <el-input
                                placeholder="请输入答案"
                                show-word-limit
                                maxlength="100"
                                class="w-[500px]"
                                v-model="formData.option[index]"
                            ></el-input>
                            <el-button @click="delSelection(index)" link type="danger" class="ml-2"
                                >删除</el-button
                            >
                        </div>
                    </el-form-item>
                </div>
                <el-form-item prop="option">
                    <el-button type="primary" @click="addSelection">新增选项</el-button>
                    <span class="form-tips ml-2">最多添加10个选项</span>
                </el-form-item>
                <el-form-item label="正确答案" prop="answer">
                    <el-radio-group v-model="radioAnswer" v-if="formData.type == 1">
                        <el-radio
                            v-for="(item, index) in formData.option"
                            :key="index"
                            :label="index"
                            >{{ indexToString(index) }}</el-radio
                        >
                    </el-radio-group>
                    <el-checkbox-group v-model="checkAnswer" v-if="formData.type == 2">
                        <el-checkbox
                            v-for="(item, index) in formData.option"
                            :key="index"
                            :label="index"
                            >{{ indexToString(index) }}</el-checkbox
                        >
                    </el-checkbox-group>
                </el-form-item>
                <el-form-item label="题目解析" prop="analysis">
                    <div class="h-[500px] w-[1000px]">
                        <Editor v-model="formData.analysis"></Editor>
                    </div>
                </el-form-item>
                <el-form-item label="题目难度">
                    <el-radio-group v-model="formData.difficulty">
                        <el-radio :label="1">简单</el-radio>
                        <el-radio :label="2">中等</el-radio>
                        <el-radio :label="3">困难</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="题目考点">
                    <el-input
                        placeholder="一行输入一个考点，回车换行"
                        v-model="formData.points"
                        type="textarea"
                        rows="5"
                        class="w-[500px]"
                    ></el-input>
                </el-form-item>
            </el-form>
        </el-card>
        <FooterBtns>
            <el-button :disabled="quetionbankStatus != 1" @click="submit" type="primary"
                >保存</el-button
            >
        </FooterBtns>
    </div>
</template>
<script setup lang="ts">
import feedback from '@/utils/feedback'
import { indexToString } from '@/utils/util'
import { topicDetail, tipicAdd, tipicEdit } from '@/api/questionBank/manage'
import type { FormRules, ElForm } from 'element-plus'

//表单ref
const formRef = ref<InstanceType<typeof ElForm>>()

const route = useRoute()
const router = useRouter()

//题库id
const questionbank_id = route.query.questionbank_id
//题库状态
const quetionbankStatus: any = route.query.status || '1'
//题目id
const id = route.query.id
//题目title
const title = route.query.title

//单选答案
const radioAnswer = ref('')
//多选答案
const checkAnswer = ref([])

//表单数据
const formData = ref({
    type: 1, //题型：1-单选题 2-多选题
    stem: '', //题干
    illustration: '', //题干插图
    option: ['', '', ''], //选项
    answer: [] as string[], //答案
    analysis: '', //题目解析
    difficulty: 1, //题目难度： 1-简单，2-中等，3-困难
    points: '' //考题考点
})

//富文本校验
const richtextCheck = (rule: any, value: any, callback: any) => {
    if (value == '<p><br></p>' || value == '<p>&nbsp;</p>') {
        callback(new Error('请输入题目解析！'))
    } else {
        callback()
    }
}

//选项校验
const optionCheck = (rule: any, value: any, callback: any) => {
    value.forEach((item: string) => {
        if (item == '') {
            callback(new Error('选项不能为空！'))
        }
    })
    callback()
}

//正确答案校验
const answerCheck = (rule: any, value: any, callback: any) => {
    if (formData.value.type == 1 && radioAnswer.value === '') {
        callback(new Error('请选择正确答案'))
    } else if (formData.value.type == 2 && checkAnswer.value.length == 0) {
        callback(new Error('请选择正确答案'))
    }
    callback()
}

//表单校验规则
const rules = reactive<FormRules>({
    stem: [{ required: true, trigger: ['blur', 'change'], message: '请输入题干' }],
    answer: [{ required: true, validator: answerCheck, trigger: ['blur'] }],
    analysis: [{ required: true, validator: richtextCheck, trigger: ['blur', 'change'] }],
    option: [{ validator: optionCheck, trigger: ['blur', 'change'] }]
})

//添加选项
const addSelection = () => {
    if (formData.value.option.length == 10) {
        feedback.msgWarning('最多添加10个选项！')
        return
    }
    formData.value.option.push('')
}

//删除选项
const delSelection = (index: number) => {
    formData.value.option.splice(index, 1)
}

//获取详情
const getDetail = async () => {
    const res: any = await topicDetail({ id })
    Object.keys(formData.value).map((item) => {
        //@ts-ignore
        formData.value[item] = res[item]
        if (res.type == 1) {
            radioAnswer.value = res.answer[0]
        } else {
            checkAnswer.value = res.answer
        }
    })
}

//保存
const submit = async () => {
    await formRef.value?.validate()
    formData.value.answer = []
    if (formData.value.type == 1) {
        formData.value.answer.push(radioAnswer.value)
    } else {
        // formData.value.answer.concat(checkAnswer.value)
        formData.value.answer = checkAnswer.value
    }
    if (id) {
        await tipicEdit({ ...formData.value, questionbank_id, id })
    } else {
        await tipicAdd({ ...formData.value, questionbank_id })
    }
    router.back()
}

onMounted(async () => {
    if (id) {
        await getDetail()
    }
})
</script>
