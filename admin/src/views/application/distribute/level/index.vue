<template>
    <div>
        <el-card class="!border-none" shadow="never">
            <el-alert
                type="warning"
                title="1.管理分销商的等级，系统默认等级不能删除；2.删除分销等级时，会重新调整分销商等级为系统默认等级，请谨慎操作"
                :closable="false"
                show-icon
            />
        </el-card>
        <el-card class="!border-none mt-4" shadow="never">
            <el-button type="primary" v-perms="['distribute.level/add']" @click="toAdd"
                >新增分销等级</el-button
            >
            <el-table class="mt-4" :data="pager.lists" :row-style="{ height: '60px' }">
                <el-table-column label="等级名称" prop="name"></el-table-column>
                <el-table-column label="等级级别" prop="level_desc"></el-table-column>
                <el-table-column label="背景图">
                    <template #default="{ row }">
                        <el-image class="w-[100px] h-[72px]" :src="row.level_bg"></el-image>
                    </template>
                </el-table-column>
                <el-table-column label="自购佣金比例(%)" prop="self_ratio">
                    <template #default="{ row }">
                        {{ row.self_ratio }}
                    </template>
                </el-table-column>
                <el-table-column label="一级佣金比例(%)" prop="first_ratio">
                    <template #default="{ row }">
                        {{ row.first_ratio }}
                    </template>
                </el-table-column>
                <el-table-column label="二级佣金比例(%)" prop="second_ratio">
                    <template #default="{ row }">
                        {{ row.second_ratio }}
                    </template>
                </el-table-column>
                <el-table-column label="分销商数">
                    <template #default="{ row }">
                        <div>{{ row.distributor_num || 0 }}</div>
                    </template>
                </el-table-column>
                <el-table-column label="操作" min-width="180">
                    <template #default="{ row }">
                        <div>
                            <el-button
                                type="primary"
                                @click="toEdit(row.id, 'detail')"
                                link
                                v-perms="['distribute.level/detail']"
                                >详情</el-button
                            >
                            <el-button
                                type="primary"
                                @click="toEdit(row.id, 'edit')"
                                link
                                v-perms="['distribute.level/edit']"
                                >编辑</el-button
                            >
                            <el-button
                                type="primary"
                                @click="del(row.id)"
                                v-if="!row.is_default"
                                link
                                v-perms="['distribute.level/del']"
                                >删除</el-button
                            >
                        </div>
                    </template>
                </el-table-column>
            </el-table>
            <div class="flex justify-end mt-4">
                <pagination v-model="pager" @change="getLists" />
            </div>
        </el-card>
    </div>
</template>

<script setup lang="ts">
import { usePaging } from '@/hooks/usePaging'
import { levelList, levelDel } from '@/api/distribution/level'
import feedback from '@/utils/feedback'

const router = useRouter()
//去添加
const toAdd = () => {
    router.push('/app/distribute/levelEdit')
}
//去编辑
const toEdit = (id: any, type: any) => {
    router.push(`/app/distribute/levelEdit?id=${id}&type=${type}`)
}

const del = async (id: any) => {
    await feedback.confirm('确认删除？')
    await levelDel({ id })
    getLists()
}

//分页组件
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: levelList
})

onMounted(() => {
    getLists()
})
</script>

<style scoped lang="scss"></style>
