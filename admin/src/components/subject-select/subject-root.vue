<template>
    <popup
        class="mr-[10px] inline"
        :clickModalClose="false"
        title="选择课程"
        :center="true"
        @confirm="emit('confirm', selectData)"
        width="60vw"
        @open="popOpen"
    >
        <!-- Trigger Start -->
        <template #trigger>
            <!-- trigger -->
            <slot></slot>
        </template>
        <!-- Trigger End -->

        <!-- Table Start -->
        <el-scrollbar height="420px">
            <div class="mt-[20px]">
                <el-table
                    ref="tableDataRef"
                    @selection-change="handleSelectItem"
                    :data="pager.lists"
                    :row-key="getRowKey"
                    border
                    style="width: 100%"
                >
                    <el-table-column type="selection" :reserve-selection="true" width="55" />
                    <el-table-column label="活动名称" min-width="200">
                        <template #default="scope">
                            <div class="flex col-center">
                                <el-image
                                    style="width: 60px; height: 60px"
                                    :src="scope.row.cover"
                                    :fit="'cover'"
                                >
                                </el-image>
                                <div class="m-l-10">{{ scope.row.name }}</div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column property="course_count" label="课程数量" max-width="160" />
                    <el-table-column property="create_time" label="添加时间" max-width="120" />
                </el-table>
            </div>
        </el-scrollbar>
        <!-- Table End -->

        <!-- Footer Pagination Start -->
        <div class="flex justify-end mr-2">
            <pagination
                v-model="pager"
                @change="pagChange"
                :page-sizes="[5]"
                layout="total, prev, pager, next, jumper"
            />
        </div>
        <!-- Footer Pagination End -->
    </popup>
</template>

<script lang="ts" setup>
import { apiSubjectLists } from '@/api/application/application'
import Pagination from '@/components/pagination/index.vue'
import { usePaging } from '@/hooks/usePaging'
import Popup from '@/components/popup/index.vue'
import { ElTable } from 'element-plus'
import { withDefaults, ref, inject } from 'vue'

/** Emit Start **/
const emit = defineEmits(['confirm'])
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
const tableDataRef = ref<InstanceType<typeof ElTable>>()
const selectData: any = ref([])
const courseData = ref({
    name: '' as string,
    status: 1
})
const { pager, getLists } = usePaging({
    size: 5,
    fetchFun: apiSubjectLists,
    params: courseData.value
})
/** Data End **/

const getRowKey = (row: any) => {
    return row.id
}

/** Methods Start **/
/**
 * @description 选择课程
 */
const handleSelectItem = (list: any, row: any): void => {
    console.log(list)
    selectData.value = list
}
/** Methods End **/

//分页改变
const pagChange = async () => {
    await getLists()
    await getSelected()
}
// 已选择课程
const getSelected = () => {
    console.log(props.modelValue)
    setTimeout(() => {
        pager.lists.forEach((row: any) => {
            props.modelValue.forEach((key: any) => {
                if (row.id === key.id) {
                    tableDataRef.value?.toggleRowSelection(row, true)
                }
            })
        })
    }, 0)
}

const popOpen = async () => {
    await getLists()
    await getSelected()
}
</script>

<style lang="scss" scoped>
.ls-input {
    width: 240px;
}

.content {
    height: 400px;
}
</style>
