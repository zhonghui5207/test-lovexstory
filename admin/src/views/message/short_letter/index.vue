<template>
    <div>
        <el-card class="!border-none" shadow="never" v-loading="state.loading">
            <el-table size="large" :data="state.lists">
                <el-table-column label="短信渠道" prop="name" min-width="120" />
                <el-table-column label="状态" min-width="120">
                    <template #default="{ row }">
                        <el-tag v-if="row.status == 1">开启</el-tag>
                        <el-tag type="danger" v-else>关闭</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="操作" min-width="120" fixed="right">
                    <template #default="{ row }">
                        <el-button
                            v-perms="['notice.sms_config/setConfig']"
                            type="primary"
                            link
                            @click="handleSet(row.name)"
                        >
                            设置
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-card>
        <edit-popup ref="editRef" @success="getLists" />
        <!-- <layout-footer></layout-footer> -->
    </div>
</template>
<script lang="ts" setup name="shortLetter">
import { smsLists } from '@/api/message'
import EditPopup from './edit.vue'
import LayoutFooter from '@/layout/components/footer.vue'

const editRef = shallowRef<InstanceType<typeof EditPopup>>()

// 列表数据
const state: any = reactive({
    loading: false,
    lists: []
})

// 获取存储引擎列表数据
const getLists = async () => {
    try {
        state.loading = true
        const res = await smsLists()
        state.lists = [{ ...res.ali }, { ...res.tencent }]
        console.log(res[0])
        state.loading = false
    } catch (error) {
        state.loading = false
    }
}

const handleSet = (alias: string) => {
    let type = ''
    if (alias == '阿里云短信') {
        type = 'ali'
    } else if (alias == '腾讯云短信') {
        type = 'tencent'
    }
    editRef.value?.open(type)
}

getLists()
</script>
