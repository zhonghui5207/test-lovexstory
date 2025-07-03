<template>
    <div>
        <el-card shadow="never" class="!border-none">
            <el-page-header :content="id ? '编辑题库' : '新增题库'" @back="$router.back()" />
        </el-card>
        <el-card shadow="never" class="!border-none mt-4">
            <el-form label-width="90px">
                <el-form-item label="题库名称">
                    <el-input
                        v-model="formData.name"
                        show-word-limit
                        maxlength="20"
                        placeholder="请输入题库名称"
                        class="w-[500px]"
                    ></el-input>
                </el-form-item>
                <el-form-item label="题库分类">
                    <el-select class="w-[500px]" v-model="formData.category_id">
                        <el-option
                            v-for="(item, index) in categorizeList"
                            :key="index"
                            :label="item.name"
                            :value="item.id"
                        />
                    </el-select>
                </el-form-item>
                <el-form-item label="付费方式">
                    <el-radio-group v-model="formData.pay_type">
                        <el-radio :label="1">收费</el-radio>
                        <el-radio :label="2">免费</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="购买价格" v-if="formData.pay_type == 1">
                    <el-input
                        v-model="formData.pay_amount"
                        placeholder="请输入购买价格"
                        class="w-[320px]"
                    ></el-input>
                </el-form-item>
                <el-form-item label="关联课程">
                    <courseSelect @confirm="getSelectCourse" :course-data="isSelectCourse">
                        <el-button type="primary">选择课程</el-button>
                    </courseSelect>
                </el-form-item>
                <el-form-item v-if="isSelectCourse?.length">
                    <el-table :data="isSelectCourse">
                        <el-table-column label="课程信息">
                            <template #default="{ row }">
                                <div class="flex items-center">
                                    <el-image
                                        class="w-[80px] h-[48px] flex-none"
                                        :src="row.cover"
                                    ></el-image>
                                    <div class="ml-2">{{ row.name }}</div>
                                </div>
                            </template>
                        </el-table-column>
                        <el-table-column label="价格" prop="sell_price"></el-table-column>
                        <el-table-column label="课程分类" prop="type_desc"></el-table-column>
                        <el-table-column fixed="right" label="操作">
                            <template #default="{ $index }">
                                <el-button link type="danger" @click="delCourse($index)"
                                    >删除</el-button
                                >
                            </template>
                        </el-table-column>
                    </el-table>
                </el-form-item>
            </el-form>
        </el-card>
        <footer-btns>
            <el-button type="primary" @click="submit">保存</el-button>
        </footer-btns>
    </div>
</template>

<script setup lang="ts">
import courseSelect from '@/components/course-select/course-root.vue'
import {
    addQuestionbank,
    questionbankDetail,
    noPageCategorizeList,
    editQuestionbank
} from '@/api/questionBank/manage'

const route = useRoute()
const router = useRouter()

const id = route.query.id

const categorizeList = ref([])

const isSelectCourse = ref<any[]>()

const formData = ref({
    category_id: '', //分类id
    name: '', //题库名称
    pay_type: 2, //付费方式
    pay_amount: '', //购买价格
    course_id: [] as number[] //课程id
})

//获取选择课程
const getSelectCourse = (value: any) => {
    isSelectCourse.value = value
    formData.value.course_id = isSelectCourse.value?.map((item) => item.id) as number[]
}

//删除课程
const delCourse = (index: number) => {
    isSelectCourse.value?.splice(index, 1)
}

//获取分类列表
const getCategorize = async () => {
    categorizeList.value = await noPageCategorizeList()
}

//获取题库详情
const getDetail = async () => {
    const res = await questionbankDetail({ id })
    Object.keys(formData.value).map((key) => {
        //@ts-ignore
        formData.value[key] = res[key]
    })
    isSelectCourse.value = res.course_lists
}

//保存
const submit = async () => {
    if (id) {
        await editQuestionbank({ ...formData.value, id })
    } else {
        await addQuestionbank({ ...formData.value })
    }
    router.back()
}

onMounted(async () => {
    await getCategorize()
    await nextTick()
    if (id) {
        getDetail()
    }
})
</script>
