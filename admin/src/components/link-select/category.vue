<template>
    <div class="category">
        <el-form label-width="100px">
            <el-form-item
                label="选择分类"
                :rules="[
                    {
                        required: true,
                        message: '',
                        trigger: 'blur'
                    }
                ]"
            >
                <el-cascader
                    ref="cascaderRef"
                    :value="params"
                    :options="cateLists"
                    :props="{
                        checkStrictly: true,
                        label: 'name',
                        value: 'id',
                        children: 'sons',
                        emitPath: false
                    }"
                    clearable
                    filterable
                    @change="onSelect"
                ></el-cascader>
            </el-form-item>
        </el-form>
    </div>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import { apiCategoryLists } from '@/api/course/course'

/** Emit Start **/
const emit = defineEmits(['setLink'])
/** Emit End **/

/** Props Start **/
const props = withDefaults(
    defineProps<{
        modelValue: any
        type: string
    }>(),
    {
        modelValue: '',
        type: ''
    }
)
/** Props End **/

/** Data Start **/
const cateLists = ref<any>([])
const cascaderRef = ref()
/** Data End **/

/** Computed Start **/
const params = computed(() => {
    return props.modelValue
})
/** Computed End **/

/** Methods Start **/
const initCategoryLists = async (): Promise<void> => {
    try {
        const { lists } = await apiCategoryLists()
        cateLists.value = lists
    } catch (error) {
        console.log('获取分类列表=>', error)
    }
}

const onSelect = () => {
    const node = cascaderRef.value.getCheckedNodes()
    console.log(node)
    emit('setLink', node[0].data, node[0].text)
}
/** Methods End **/

/** Life Cycle Start **/
initCategoryLists()
/** Life Cycle End **/
</script>
