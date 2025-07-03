<template>
    <!-- Form Start -->
    <div class="flex justify-between mb-4">
        <el-button
            type="primary"
            @click="$router.push('/course/course/imageText/detail?type=1')"
            v-if="type == 1"
            >新建图文课程
        </el-button>
        <el-button
            type="primary"
            @click="$router.push('/course/course/audio/detail?type=2')"
            v-if="type == 2"
            >新建音频课程
        </el-button>
        <el-button
            type="primary"
            @click="$router.push('/course/course/video/detail?type=3')"
            v-if="type == 3"
            >新建视频课程
        </el-button>

        <el-input
            class="ls-input"
            v-model="courseData.name"
            @input="getLists"
            placeholder="请输入课程名称"
        >
            <template #append>
                <el-button icon="search" />
            </template>
        </el-input>
    </div>
    <!-- Form End -->

    <!-- Table Start -->
    <div class="mt-[20px]">
        <el-table
            ref="tableDataRef"
            height="380"
            :data="pager.lists"
            style="width: 100%"
            v-loading="pager.loading"
            @selection-change="handleSelectionChange"
            :row-key="getRowKey"
        >
            <el-table-column reserve-selection type="selection" width="55" />
            <el-table-column label="课程名称" min-width="200">
                <template #default="scope">
                    <div class="flex col-center">
                        <div>
                            <el-image
                                style="width: 80px; height: 60px"
                                :src="scope.row.cover"
                                :fit="'cover'"
                            >
                            </el-image>
                        </div>
                        <div class="ml-[10px]">{{ scope.row.name }}</div>
                    </div>
                </template>
            </el-table-column>
            <el-table-column property="type_desc" label="课程类型" max-width="160" />
            <el-table-column property="teacher_name" label="讲师" max-width="120" />
            <el-table-column property="sell_price" label="价格" max-width="120" />
            <el-table-column property="status" label="课程状态" max-width="140">
                <template #default="scope">
                    <el-tag class="ml-2" v-if="scope.row.status === 1" type="success"
                        >上架中</el-tag
                    >
                    <el-tag class="ml-2" v-if="scope.row.status === 0" type="info">已下架</el-tag>
                </template>
            </el-table-column>
        </el-table>
    </div>
    <!-- Table End -->

    <!-- Footer Pagination Start -->
    <div class="flex justify-end" style="height: 66px">
        <pagination
            v-model="pager"
            @change="changePage"
            layout="total, prev, pager, next, jumper"
        />
    </div>
    <!-- Footer Pagination End -->
</template>

<script lang="ts" setup>
import { apiCourseCommonLists } from '@/api/course/course'
import Pagination from '@/components/pagination/index.vue'
import { usePaging } from '@/hooks/usePaging'
import { withDefaults, ref } from 'vue'
import { ElTable } from 'element-plus'

interface courseObj {
    id?: any
    type: number
    name: string
}

const tableDataRef = ref<InstanceType<typeof ElTable>>()
const props = withDefaults(
    defineProps<{
        modelValue: any
        type: number
        course?: any
    }>(),
    {
        modelValue: [],
        type: 0,
        course: 0
    }
)
const emit = defineEmits(['update:modelValue'])

//请求参数
const courseData = ref<courseObj>({
    type: props.type,
    name: ''
})
//分页组件
const { pager, getLists } = usePaging({
    size: 5,
    fetchFun: apiCourseCommonLists,
    params: courseData.value
})

//获取rowkey
const getRowKey: any = (row: any) => {
    return row.id
}

//多选框选项改变
const handleSelectionChange = (value: any) => {
    emit('update:modelValue', value)
}

//修改页面
const changePage = async () => {
    await getLists()
    getIsSelect()
}
//获取已选项
const getIsSelect = async () => {
    pager.lists.forEach((item: any, index: any) => {
        for (let i = 0; i < props.course.length; i++) {
            if (item.id == props.course[i].id) {
                tableDataRef.value!.toggleRowSelection(pager.lists[index], true)
                break
            } else {
                tableDataRef.value!.toggleRowSelection(pager.lists[index], false)
            }
        }
    })
}

onMounted(async () => {
    await getLists()
    await nextTick()
    getIsSelect()
})

defineExpose({ getIsSelect })
</script>

<style lang="scss" scoped>
.ls-input {
    width: 240px;
}

.content {
    height: 400px;
}
</style>
