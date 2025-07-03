<template>
    <!-- Form Start -->
    <el-form label-width="90px" @submit.native.prevent>
        <el-form-item label="课程名称">
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
        </el-form-item>
    </el-form>
    <!-- Form End -->

    <!-- Table Start -->
    <el-scrollbar height="350px" class="mt-[20px]">
        <el-table ref="tableDataRef" :data="pager.lists" style="width: 100%">
            <el-table-column label="选择" width="60">
                <template #default="scope">
                    <div class="flex row-center">
                        <el-checkbox
                            :model-value="getSelectItem(scope.row)"
                            @change="handleSelectItem(scope.row)"
                            label
                            size="large"
                        ></el-checkbox>
                    </div>
                </template>
            </el-table-column>
            <el-table-column label="课程名称" min-width="200">
                <template #default="scope">
                    <div class="flex col-center">
                        <el-image
                            style="width: 80px; height: 60px"
                            :src="scope.row.cover"
                            :fit="'cover'"
                        >
                        </el-image>
                        <div class="ml-[10px]">{{ scope.row.name }}</div>
                    </div>
                </template>
            </el-table-column>
            <el-table-column property="sell_price" label="价格" max-width="120" />
            <el-table-column property="status" label="课程类型" max-width="140">
                <template #default="scope">
                    <el-tag class="ml-2" type="success">{{ scope.row.type_desc }} </el-tag>
                </template>
            </el-table-column>
        </el-table>
    </el-scrollbar>
    <!-- Table End -->

    <!-- Footer Pagination Start -->
    <div class="flex row-right">
        <pagination
            v-model="pager"
            @change="getLists"
            :page-sizes="[5]"
            layout="total, prev, pager, next, jumper"
        />
    </div>
    <!-- Footer Pagination End -->
</template>

<script lang="ts" setup>
import { apiCourseCommonLists } from '@/api/course/course'
import Pagination from '@/components/pagination/index.vue'
import { usePaging } from '@/hooks/usePaging'
import { computed, withDefaults, reactive } from 'vue'

/** Emit Start **/
const emit = defineEmits(['setLink'])
/** Emit End **/

/** Props Start **/
const props = withDefaults(
    defineProps<{
        modelValue: any
    }>(),
    {
        modelValue: []
    }
)
/** Props End **/

/** Data Start **/
const courseData = reactive({
    name: '' as string
})
const { pager, getLists } = usePaging({
    size: 5,
    fetchFun: apiCourseCommonLists,
    params: courseData
})
/** Data End **/

/** Computed Start **/
const params = computed({
    get: () => {
        return props.modelValue
    },
    set: (value) => {
        emit('setLink', value, value.name)
    }
})
// 是否选中当前
const getSelectItem = computed(() => {
    return (event: any) => {
        return params.value?.id == event.id
    }
})
/** Computed End **/

/** Methods Start **/
/**
 * @description 选择课程
 */
const handleSelectItem = (event: any): void => {
    console.log(event)
    if (params.value.id == event.id) {
        params.value = {}
    } else {
        params.value = event
    }
}
/** Methods End **/

/** Life Cycle Start **/
getLists()
/** Life Cycle End **/
</script>

<style lang="scss" scoped>
.ls-input {
    width: 240px;
}
</style>
