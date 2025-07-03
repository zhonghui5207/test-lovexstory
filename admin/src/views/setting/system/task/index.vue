<template>
    <div>
        <el-card shadow="never" class="!border-none">
            <el-button type="primary" @click="goTaskAdd" class="mb-[16px]">+添加</el-button>

            <el-table
                ref="paneTable"
                class="m-t-24"
                :data="pager.lists"
                v-loading="pager.loading"
                style="width: 100%"
            >
                <el-table-column prop="name" label="名称" min-width="200"></el-table-column>
                <el-table-column prop="type_desc" label="类型" min-width="100"></el-table-column>
                <el-table-column prop="command" label="命令" min-width="160"></el-table-column>
                <el-table-column prop="params" label="参数" min-width="100"></el-table-column>
                <el-table-column prop="expression" label="规则" min-width="100"></el-table-column>
                <el-table-column prop="status" label="状态" min-width="80">
                    <template #default="{ row }">
                        <el-tag v-if="row.status == 1" type="success">运行中</el-tag>
                        <el-tag v-if="row.status == 2" type="info">已停止</el-tag>
                        <el-tag v-if="row.status == 3" type="danger">错误</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="error" label="错误原因" min-width="150"></el-table-column>
                <el-table-column
                    prop="last_time"
                    label="最后执行时间"
                    min-width="150"
                ></el-table-column>
                <el-table-column prop="time" label="时长" min-width="100"></el-table-column>
                <el-table-column prop="max_time" label="最大时长" min-width="100"></el-table-column>

                <el-table-column label="操作" min-width="120">
                    <template v-slot="scope">
                        <div class="flex">
                            <el-button
                                type="primary"
                                link
                                @click="goTaskEdit(scope.row.id)"
                                class="mr-4"
                                >编辑</el-button
                            >

                            <!-- 停止/开启 -->
                            <!-- <ls-dialog class="m-l-10 inline" :content="'确定要停止这个定时任务吗？请谨慎操作'" @confirm="onStop(scope.row)">
                                <el-button type="text"  slot="trigger">
                                    {{scope.row.status==1?'停止':'运行'}}
                                </el-button>
                        </ls-dialog>-->

                            <!-- 删除定时任务 -->
                            <ls-dialog
                                class="m-l-10 m-t-20 m-b-20 inline"
                                :content="'确定要停删除个定时任务吗？请谨慎操作'"
                                @confirm="onDel(scope.row.id)"
                            >
                                <template #trigger>
                                    <el-button type="primary" link slot="trigger">删除</el-button>
                                </template>
                            </ls-dialog>
                        </div>
                    </template>
                </el-table-column>
            </el-table>

            <!-- Footer Pagination Start -->
            <div class="flex justify-end mr-4">
                <pagination v-model="pager" @change="getLists" />
            </div>
            <!-- Footer Pagination End -->
        </el-card>
        <!-- <layout-footer></layout-footer> -->
    </div>
</template>

<script lang="ts" setup>
import { apiCrontabLists, apiCrontabDel, apiSrontabOperate } from '@/api/setting/system'
import Pagination from '@/components/pagination/index.vue'
import { PageMode } from '@/utils/enum'
import LsDialog from '@/components/popup/index.vue'
import { usePaging } from '@/hooks/usePaging'
import LayoutFooter from '@/layout/components/footer.vue'

/** Data Start **/
const router = useRouter()

const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: apiCrontabLists,
    params: {}
})
/** Data End **/

/** Methods Start **/
const onStop = async (row: any) => {
    await apiSrontabOperate({
        id: row.id,
        operate: row.status == 1 ? 'stop' : 'start'
    })
    getLists()
}

// 删除这个定时任务
const onDel = async (id: any) => {
    await apiCrontabDel({ id: id })
    getLists()
}

// 新增
const goTaskAdd = () => {
    router.push({
        path: '/setting/system/task/edit',
        query: {
            mode: PageMode['ADD']
        }
    })
}

// 编辑
const goTaskEdit = (id: any) => {
    router.push({
        path: '/setting/system/task/edit',
        query: {
            id: id,
            mode: PageMode['EDIT']
        }
    })
}
/** Methods End **/

/** LifeCycle Start **/
getLists()
/** LifeCycle End **/
</script>

<style lang="scss"></style>
