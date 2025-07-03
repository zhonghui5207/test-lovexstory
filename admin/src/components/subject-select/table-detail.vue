<template>
    <div class="mt-[10px]" v-if="selectData.length">
        <el-table ref="tableData" :data="selectData" style="width: 100%" class="mt-[16px]">
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
            <el-table-column property="status" label="显示状态" max-width="100">
                <template #default="scope">
                    <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="0" />
                </template>
            </el-table-column>
            <el-table-column property="sort" label="排序" max-width="100">
                <template #default="scope">
                    <el-input v-model="scope.row.sort" />
                </template>
            </el-table-column>
            <el-table-column property="course_num" label="课程数量" max-width="100" />
            <el-table-column property="address" label="操作" max-width="100">
                <template #default="scope">
                    <el-button type="text" @click="handleDeleteItem(scope.$index)">删除 </el-button>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script lang="ts" setup>
import { computed, withDefaults } from 'vue'

const emit = defineEmits(['del'])
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

/** Computed Start **/
const selectData: any = computed(() => {
    return props.modelValue || []
})
/** Computed End **/

/** Methods Start **/
/**
 * @description 删除某项
 */
const handleDeleteItem = async (index: number) => {
    await selectData.value.splice(index, 1)
    emit('del', selectData.value)
}
/** Methods End **/
</script>
