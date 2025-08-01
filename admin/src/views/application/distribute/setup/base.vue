<template>
    <div>
        <el-card class="!border-none" shadow="never">
            <div class="text-lg font-medium">分销功能</div>
            <el-form class="mt-4" label-width="150px">
                <el-form-item label="分销功能">
                    <div>
                        <el-radio-group v-model="formData.is_distribution">
                            <el-radio :label="1">开启</el-radio>
                            <el-radio :label="0">关闭</el-radio>
                        </el-radio-group>
                        <div class="form-tips">
                            关闭分销功能时，不会再产生新的分销佣金，商城分销入口会关闭，不会建立新的分销关系
                        </div>
                    </div>
                </el-form-item>
                <el-form-item label="分销层级">
                    <div>
                        <el-radio-group v-model="formData.distribution_level">
                            <el-radio :label="1">一级分销</el-radio>
                            <el-radio :label="2">二级分销</el-radio>
                        </el-radio-group>
                        <div class="form-tips">
                            允许发放佣金的分销层级，等级默认佣金比例在 分销等级 进行设置
                        </div>
                    </div>
                </el-form-item>
                <el-form-item label="分销自购返佣">
                    <div>
                        <el-radio-group v-model="formData.self_buy">
                            <el-radio :label="1">开启</el-radio>
                            <el-radio :label="0">关闭</el-radio>
                        </el-radio-group>
                        <div class="form-tips">开启后，分销商自购时可以获得自购返佣</div>
                    </div>
                </el-form-item>
                <el-form-item label="商品详情显示佣金">
                    <div>
                        <el-radio-group v-model="formData.goods_detail">
                            <el-radio :label="1">显示</el-radio>
                            <el-radio :label="0">隐藏</el-radio>
                        </el-radio-group>
                        <div class="form-tips">是否在商品详情显示佣金奖励提示</div>
                    </div>
                </el-form-item>
                <el-form-item label="详情页佣金可见用户">
                    <div>
                        <el-radio-group v-model="formData.goods_detail_user">
                            <el-radio :label="1">全部用户</el-radio>
                            <el-radio :label="2">分销商</el-radio>
                        </el-radio-group>
                        <div class="form-tips">
                            选择全部用户，则所有人在商品详情都可以看到佣金奖励提示
                        </div>
                    </div>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none mt-4" shadow="never">
            <div class="text-lg font-medium">分销功能</div>
            <el-form class="mt-4" label-width="150px">
                <el-form-item label="开通分销商条件">
                    <div>
                        <el-radio-group v-model="formData.distribution_apply">
                            <el-radio :label="1">无条件</el-radio>
                            <el-radio :label="2">申请分销</el-radio>
                            <el-radio :label="3">指定分销</el-radio>
                        </el-radio-group>
                        <div class="form-tips" v-if="formData.distribution_apply == '1'">
                            开通分销商条件切换至无条件时，所有用户都将开通分销商资格
                        </div>
                        <div class="form-tips" v-if="formData.distribution_apply == '2'">
                            用户需在前端提交分销申请，后台管理员同意后即可成为分销商
                        </div>
                        <div class="form-tips" v-if="formData.distribution_apply == '3'">
                            指定某个用户成为分销商。在【分销商】-【开通分销商】选择某个用户即可
                        </div>
                    </div>
                </el-form-item>
                <el-form-item label="申请页顶部宣传图">
                    <div>
                        <material-picker v-model="formData.apply_image"></material-picker>
                        <div class="form-tips">不上传则显示系统默认背景图，建议尺寸750*280</div>
                    </div>
                </el-form-item>
                <el-form-item label="申请协议">
                    <div>
                        <el-radio-group v-model="formData.is_apply_protocol">
                            <el-radio :label="1">显示</el-radio>
                            <el-radio :label="0">隐藏</el-radio>
                        </el-radio-group>
                    </div>
                </el-form-item>
                <el-form-item label="申请协议内容">
                    <div class="flex-1">
                        <el-input
                            class="w-[400px]"
                            type="textarea"
                            v-model="formData.apply_protocol_content"
                            :autosize="{ minRows: 4, maxRows: 6 }"
                        ></el-input>
                    </div>
                </el-form-item>
            </el-form>
        </el-card>
        <footer-btns>
            <el-button v-perms="['distribute.setup/base']" @click="submit" type="primary"
                >保存</el-button
            >
        </footer-btns>
    </div>
</template>

<script setup lang="ts">
import { getconfig, Saveconfig } from '@/api/distribution/distribution'
import feedback from '@/utils/feedback'
//表单数据
const formData = ref({
    is_distribution: '1',
    distribution_level: '1', //层级 1-1级 2-2级
    self_buy: '1', //开启自购 1是 0否
    goods_detail: '1', //商品详情展示佣金 1是 0否
    goods_detail_user: '1', //商品详情展示佣金用户 1全部 2分销商
    distribution_apply: '1', //分销商条件 1无条件 2申请 3后台指定
    apply_image: '1', //申请分销顶部宣传图 1-开启 -关闭
    is_apply_protocol: '1', //申请协议展示 1是 0否
    apply_protocol_content: '', //申请协议
    settlementTiming: '',
    settlementTime: ''
})

//获取数据
const getData = async () => {
    formData.value = await getconfig()
}

const submit = async () => {
    try {
        await Saveconfig({ ...formData.value })
        getData()
    } catch (error: any) {
        feedback.msgError(error)
    }
}

onMounted(() => {
    getData()
})
</script>

<style scoped lang="scss"></style>
