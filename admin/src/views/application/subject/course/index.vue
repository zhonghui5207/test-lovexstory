<template>
    <div>
        <!-- Header Start -->
        <el-card shadow="never" class="!border-none">
            <el-page-header content="管理课程" @back="$router.back()" />
        </el-card>
        <!-- Header End -->

        <!-- Main Start -->
        <el-card shadow="never" class="!border-none" style="margin-top: 15px; height: 70vh">
            <el-form ref="formRef" label-width="90px">
                <el-form-item label="专区课程:">
                    <div>
                        <div>
                            <course-select @confirm="handleConfirm" :courseData="courseData">
                                <el-button type="primary">选择课程</el-button>
                            </course-select>
                        </div>
                        <div>
                            <el-table :data="courseData">
                                <el-table-column prop="date" label="课程信息" min-width="330">
                                    <template #default="{ row }">
                                        <div class="flex items-center">
                                            <el-image
                                                style="width: 80px; height: 54px"
                                                :src="row.cover"
                                            ></el-image>
                                            <span class="ml-[10px]">{{
                                                row.course_name || row.name
                                            }}</span>
                                        </div>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                    prop="type_desc"
                                    label="课程类型"
                                    min-width="100"
                                />
                                <el-table-column prop="teacher_name" label="讲师" min-width="150" />
                                <el-table-column prop="sell_price" label="销售价格" min-width="100">
                                </el-table-column>
                                <el-table-column prop="name" label="课程状态" min-width="100">
                                    <template #default="{ row }">
                                        <el-tag class="ml-2" v-if="row.status == 1" type="success"
                                            >上架中</el-tag
                                        >
                                        <el-tag class="ml-2" v-if="row.status == 2" type="info"
                                            >已下架</el-tag
                                        >
                                    </template>
                                </el-table-column>
                                <el-table-column
                                    prop="name"
                                    label="操作"
                                    min-width="180"
                                    fixed="right"
                                >
                                    <template #default="scope">
                                        <el-button
                                            link
                                            type="primary"
                                            @click="handleDelete(scope.$index)"
                                            >移除</el-button
                                        >
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </div>
                </el-form-item>
            </el-form>
        </el-card>
        <!-- Main End -->

        <!-- Footer Start -->
        <footer-btns>
            <el-button type="primary" @click="onSubmit">保存</el-button>
        </footer-btns>
        <!-- Footer End -->
    </div>
</template>

<script lang="ts" setup>
import {
    apiSubjectCourseDetail, // 专区课程详情
    apiSubjectDecorateSave // 保存专区课程
} from '@/api/application/application'
import CourseSelect from '@/components/course-select/course-root.vue'
import FooterBtns from '@/components/footer-btns/index.vue'

const route = useRoute()
// 专区ID
const id: any = route.query.id
// 课程ID
const courseData = ref<any>([])

//获取专区课程详情
const initSubjectCourseDetail = async (): Promise<void> => {
    try {
        const res = await apiSubjectCourseDetail({ subject_id: id })
        res.forEach((item: any) => {
            item.id = item.course_id
        })
        courseData.value = res
    } catch (err) {
        console.log('获取专区课程详情err=>', err)
    }
}

//添加专栏目录课程
const handleConfirm = (course: []) => {
    courseData.value = course
}

// 保存
const onSubmit = async (): Promise<void> => {
    try {
        await apiSubjectDecorateSave({
            course_ids: courseData.value.map((item: any) => item.id),
            subject_id: id
        })
    } catch (err) {
        console.log('添加栏目组件报错err=>', err)
    }
}

//移除目录
const handleDelete = async (index: any) => {
    courseData.value.splice(index, 1)
}

if (id) initSubjectCourseDetail()
</script>

<style scoped lang="scss">
:deep() .el-form-item__content {
    display: block;
}
</style>
