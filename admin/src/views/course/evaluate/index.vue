<template>
    <el-card shadow="never" class="!border-none">
        <!-- Header Form Start -->
        <el-form class="ls-form" :model="formData" label-width="80px" inline>
            <el-form-item label="课程名称">
                <el-input class="ls-input" v-model="formData.name" placeholder="请输入课程名称" />
            </el-form-item>
            <el-form-item label="用户昵称">
                <el-input class="ls-input" v-model="formData.keyword" placeholder="昵称/手机号" />
            </el-form-item>
            <el-form-item label="回复状态">
                <el-select v-model="formData.status" class="ls-input" placeholder="请选择">
                    <el-option label="全部" value></el-option>
                    <el-option label="待回复" value="0"></el-option>
                    <el-option label="已回复" value="1"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="评价等级">
                <el-select v-model="formData.score" class="ls-input" placeholder="请选择">
                    <el-option label="全部" value></el-option>
                    <el-option label="好评" value="1"></el-option>
                    <el-option label="中评" value="2"></el-option>
                    <el-option label="差评" value="3"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="评价时间">
                <data-picker
                    v-model:start_time="formData.start_time"
                    v-model:end_time="formData.end_time"
                >
                </data-picker>
            </el-form-item>
            <el-form-item>
                <div class="ml-[20px]">
                    <el-button
                        type="primary"
                        v-perms="['order.courseComment/lists']"
                        @click="resetPage"
                        >查询</el-button
                    >
                    <el-button v-perms="['order.courseComment/reset']" @click="resetParams"
                        >重置</el-button
                    >
                </div>
            </el-form-item>
        </el-form>
        <!-- Header Form End -->
    </el-card>

    <el-card shadow="never" class="!border-none mt-4">
        <!-- Main TableData Start -->
        <el-table
            ref="tableDataRef"
            :data="pager.lists"
            style="width: 100%"
            v-loading="pager.loading"
        >
            <el-table-column label="课程名称" min-width="250">
                <template #default="scope">
                    <div class="flex items-center">
                        <el-image
                            style="width: 80px; height: 60px"
                            :src="scope.row.course_snap.cover"
                            :preview-src-list="[scope.row.course_snap.cover]"
                            :hide-on-click-modal="true"
                            :preview-teleported="true"
                            fit="cover"
                        ></el-image>
                        <el-tooltip
                            class="box-item"
                            effect="dark"
                            :content="scope.row.course_snap.name"
                            placement="top-start"
                        >
                            <div class="ml-[10px] flex-1 text_hidden">
                                {{ scope.row.course_snap.name }}
                            </div>
                        </el-tooltip>
                    </div>
                </template>
            </el-table-column>
            <el-table-column property="nickname" label="用户信息" min-width="160">
                <template #default="scope">
                    <el-popover placement="top-start" width="200px" trigger="hover">
                        <div class="flex">
                            <span class="flex-none mr-[20px]">头像：</span>
                            <el-image
                                :src="scope.row.avatar"
                                style="width: 80px; height: 80px; border-radius: 50%"
                                class="flex-none"
                            >
                            </el-image>
                        </div>
                        <div class="flex mt-[20px] col-top">
                            <span class="flex-none mr-[20px]">昵称：</span>
                            <span>{{ scope.row.nickname }}</span>
                        </div>
                        <div class="flex mt-[20px] col-top">
                            <span class="flex-none mr-[20px]">ID：</span>
                            <span>{{ scope.row.sn }}</span>
                        </div>
                        <template #reference>
                            <div class="pointer">
                                {{ scope.row.nickname }}
                            </div>
                        </template>
                    </el-popover>
                </template>
            </el-table-column>

            <el-table-column label="评价等级" min-width="180">
                <template #default="scope">
                    <el-rate
                        v-model="scope.row.course_score"
                        disabled
                        :texts="['差评', '差评', '中评', '好评', '好评']"
                        show-text
                    >
                    </el-rate>
                </template>
            </el-table-column>
            <el-table-column label="评价内容" min-width="200">
                <template #default="scope">
                    <div class="ml-[10px]">{{ scope.row.comment }}</div>
                    <div class="mt-[10px]">
                        <div
                            v-for="(item, index) in scope.row.comment_image"
                            :key="item.id"
                            class="inline mr-[10px]"
                        >
                            <el-image
                                style="width: 60px; height: 60px"
                                :src="item.uri"
                                :preview-src-list="[item.uri]"
                                :hide-on-click-modal="true"
                                :preview-teleported="true"
                                :fit="'cover'"
                            ></el-image>
                        </div>
                    </div>
                </template>
            </el-table-column>
            <el-table-column property="reply" label="回复内容" min-width="180">
                <template #default="scope">
                    {{ scope.row.reply || '-' }}
                </template>
            </el-table-column>
            <el-table-column property="status_desc" label="回复状态" width="100">
                <template #default="scope">
                    <span :style="{ color: scope.row.status ? 'black' : 'red' }">
                        {{ scope.row.status ? '已回复' : '待回复' }}
                    </span>
                </template>
            </el-table-column>
            <el-table-column property="create_time" label="评价时间" width="180" />
            <el-table-column fixed="right" label="操作" min-width="180">
                <template #default="scope">
                    <div class="flex items-center">
                        <evaluate-form
                            v-perms="['order.courseComment/reply']"
                            :id="scope.row.id"
                            :title="scope.row.status ? '编辑回复' : '回复评价'"
                            :reply="scope.row.reply"
                            @refresh="getLists"
                        >
                        </evaluate-form>

                        <el-button
                            class="mt-[1px]"
                            type="danger"
                            link
                            @click="handleDelete(scope.row.id)"
                            v-perms="['order.courseComment/del']"
                            >删除</el-button
                        >
                    </div>
                </template>
            </el-table-column>
        </el-table>
        <!-- Main TableData End -->

        <!-- Footer Pagination Start -->
        <div class="flex justify-end mt-4">
            <pagination v-model="pager" @change="getLists" />
        </div>
        <!-- Footer Pagination End -->
    </el-card>
    <!-- <LayoutFooter></LayoutFooter> -->
</template>

<script lang="ts" setup name="evaluateLists">
import { apiCommentLists, apiCommentDel } from '@/api/course/course'
import { reactive } from 'vue'
import Pagination from '@/components/pagination/index.vue'
import LayoutFooter from '@/layout/components/footer.vue'
import DataPicker from '@/components/data-picker/index.vue'
import evaluateForm from './components/evaluate-form.vue'
import feedback from '@/utils/feedback'
import { usePaging } from '@/hooks/usePaging'

/** Interface Start **/
interface FormDataObj {
    name?: string // 课程名称
    keyword?: string // 用户昵称
    status?: string // 评价状态
    start_time: string // 开始时间
    end_time: string // 结束时间
    score: string //评价
}
/** Interface End **/

/** Data Start **/
const formData = reactive<FormDataObj>({
    name: '',
    keyword: '',
    status: '',
    score: '',
    start_time: '',
    end_time: ''
})
const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: apiCommentLists,
    params: formData
})
/** Data End **/

/** Methods Start **/
// 删除广告位
const handleDelete = async (id: number): Promise<void> => {
    await feedback.confirm('确认要删除？')
    await feedback.msgSuccess('删除成功')
    await apiCommentDel({ id })
    getLists()
}
/** Methods End **/

//删除评论
const del = () => {
    feedback.alertWarning('是否删除')
}

/** LifeCycle Start **/
getLists()
/** LifeCycle End **/
</script>

<style lang="scss" scoped>
.ls-input {
    width: 240px;
}

.el-rate__item {
    line-height: 12px;

    i {
        margin-right: 2px !important;
    }
}
</style>
