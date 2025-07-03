<template>
    <div>
        <el-card class="!border-none" shadow="never">
            <el-form ref="formRef" class="mb-[-16px]" :model="queryParams" :inline="true">
                <el-form-item label="用户信息">
                    <el-input
                        class="w-[280px]"
                        v-model="queryParams.keyword"
                        placeholder="用户编号/昵称/手机号码"
                        clearable
                        @keyup.enter="resetPage"
                    />
                </el-form-item>
                <el-form-item label="注册时间">
                    <daterange-picker
                        v-model:startTime="queryParams.create_time"
                        v-model:endTime="queryParams.end_time"
                    />
                </el-form-item>
                <el-form-item label="注册来源">
                    <el-select class="w-[280px]" v-model="queryParams.channel">
                        <el-option
                            v-for="(item, key) in ClientMap"
                            :key="key"
                            :label="item"
                            :value="key"
                        />
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" v-perms="['user.user/lists']" @click="resetPage"
                        >查询</el-button
                    >
                    <el-button v-perms="['user.user/reset']" @click="resetParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none mt-4" shadow="never">
            <el-table size="large" v-loading="pager.loading" :data="pager.lists">
                <el-table-column label="用户编号" prop="sn" min-width="120" />
                <el-table-column label="头像" min-width="100">
                    <template #default="{ row }">
                        <el-avatar :src="row.avatar" :size="50" />
                    </template>
                </el-table-column>
                <el-table-column label="昵称" prop="nickname" min-width="100" />
                <el-table-column label="账号" prop="account" min-width="100" />
                <el-table-column label="手机号码" prop="mobile" min-width="100" />
                <el-table-column label="注册来源" prop="channel" min-width="100" />
                <el-table-column label="注册时间" prop="create_time" min-width="120" />
                <el-table-column label="操作" width="120" fixed="right">
                    <template #default="{ row }">
                        <el-button v-perms="['user.user/detail']" type="primary" link>
                            <router-link class="primary" :to="`/consumer/detail?id=${row.id}`"
                                >详情</router-link
                            >
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div class="flex justify-end mt-4">
                <pagination v-model="pager" @change="getLists" />
            </div>
        </el-card>
        <!-- <layout-footer></layout-footer> -->
    </div>
</template>
<script lang="ts" setup name="consumerLists">
import { usePaging } from '@/hooks/usePaging'
import { apiUserLists } from '@/api/user/user'
import { ClientMap } from '@/enums/appEnums'
import LayoutFooter from '@/layout/components/footer.vue'
const queryParams = reactive({
    keyword: '',
    channel: '',
    create_time: '',
    end_time: ''
})

const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: apiUserLists,
    params: queryParams
})
onActivated(() => {
    getLists()
})

getLists()
</script>
