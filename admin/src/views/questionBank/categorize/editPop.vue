<template>
    <Popup
        ref="popRef"
        :title="id ? '编辑分类' : '新增分类'"
        width="500px"
        async
        @confirm="submit"
        @close="$emit('close')"
    >
        <div>
            <el-form label-width="90px">
                <el-form-item label="分类名称">
                    <div>
                        <el-input
                            placeholder="请输入分类名称"
                            v-model="formData.name"
                            class="w-[270px]"
                        ></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="排序">
                    <div>
                        <el-input v-model="formData.sort" class="w-[270px]"></el-input>
                    </div>
                </el-form-item>
            </el-form>
        </div>
    </Popup>
</template>

<script setup lang="ts">
import { addCategorize, editCategorize, getCategorizeDetail } from '@/api/questionBank/categorize'

const emit = defineEmits(['success', 'close'])

const popRef = shallowRef()

const id = ref<number | null>(null)

const formData = ref({
    name: '',
    sort: '0'
})

const open = (option: { id: number }) => {
    popRef.value.open()
    if (option.id) {
        id.value = option.id
        getDetail()
    }
}

//获取详情
const getDetail = async () => {
    const { name, sort } = await getCategorizeDetail({ id: id.value })
    ;[formData.value.name, formData.value.sort] = [name, sort]
}

//提交
const submit = async () => {
    if (id.value) {
        await editCategorize({ ...formData.value, id: id.value })
    } else {
        await addCategorize({ ...formData.value })
    }
    emit('success')
}

defineExpose({ open })
</script>
