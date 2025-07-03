<template>
    <div>
        <!-- Header Form Start -->
        <el-card shadow="never" class="!border-none">
            <template #header>
                <span class="font-extrabold text-lg">充值设置</span>
            </template>
            <el-form :model="formData" label-width="120px">
                <el-form-item label="状态：">
                    <div>
                        <el-radio-group v-model="formData.open" class="ml-4">
                            <el-radio :label="1">开启</el-radio>
                            <el-radio :label="0">关闭</el-radio>
                        </el-radio-group>
                        <div class="form-tips">关闭或开启充值功能，关闭后商城将不显示充值入口</div>
                    </div>
                </el-form-item>
                <el-form-item label="最低充值金额：">
                    <div>
                        <el-input
                            class="ls-input"
                            v-model="formData.min_amount"
                            placeholder="请输入最低充值金额"
                        />
                        <div class="form-tips">
                            最低充值金额要求，不填或填0表示不限制最低充值金额
                        </div>
                    </div>
                </el-form-item>
            </el-form>
        </el-card>
        <!-- Header Form End -->

        <!-- Main Start -->
        <!-- 充值规则 -->
        <!-- <el-card shadow="never" class="mt-4 !border-none">
            <template #header>
                <span class="font-extrabold text-lg">充值规则</span>
            </template>
            <div class="form">
                <div class="form--item" v-for="(item, index) in formData.rule" :key="item.id">
                    <div class="label">规则{{ index + 1 }}</div>
                    <div class="content ls-del-wrap">
                        <div class="flex w-[370px] mt-1">
                            <div class="content--label">单笔充值满</div>
                            <el-input
                                class="content--input ls-input-border"
                                v-model="item.money"
                                placeholder="请输入充值金额"
                            />
                        </div>
                        <div class="flex w-[370px] mt-1">
                            <div class="content--label">赠送金额</div>
                            <el-input
                                class="content--input ls-input-border"
                                v-model="item.award[0].give_money"
                                placeholder="请输入赠送金额"
                            />
                        </div>
                        <el-icon class="ls-icon-del" @click="handleDelete(index)"
                            ><Close
                        /></el-icon>
                    </div>
                </div>
                <div class="form--item">
                    <div class="label"></div>
                    <div>
                        <el-button type="primary" @click="handleAddRule">添加规则</el-button>
                    </div>
                </div>
            </div>
        </el-card> -->
        <!-- Main End -->

        <!-- Footer Start -->
        <footer-btns>
            <el-button type="primary" @click="onSubmit()">保存</el-button>
        </footer-btns>
        <!-- Footer End -->
    </div>
</template>

<script lang="ts" setup>
import { apiRechargeGetRule, apiRechargeSetRule } from '@/api/application/application'
import FooterBtns from '@/components/footer-btns/index.vue'
import { ref } from 'vue'

/** Interface Start **/
interface AwardObj {
    give_money: string // 赠送金额
}
interface RuleObj {
    award: Array<AwardObj>
    id?: string // 充值ID
    money: string // 充值金额
}
interface FormDataObj {
    open: number // 是否开启充值
    min_amount: string // 最低充值金额
    rule: Array<RuleObj> // 充值规则
}
/** Interface End **/

/** Data Start **/
const formData = ref<FormDataObj>({
    open: 1,
    min_amount: '',
    rule: []
})
/** Data End **/

/** Methods Start **/
/**
 * @description 初始化充值数据
 */
const initChargeRule = async (): Promise<void> => {
    try {
        const { rule, set } = await apiRechargeGetRule()
        formData.value.rule = rule
        formData.value.open = set.open
        formData.value.min_amount = set.min_amount
    } catch (err) {
        console.log('初始化充值数据', err)
    }
}
/**
 * @description 处理添加规则
 */
const handleAddRule = () => {
    formData.value.rule.push({
        award: [{ give_money: '' }],
        money: ''
    })
}
/**
 * @description 删除充值规则
 */
const handleDelete = async (index: number) => {
    formData.value.rule.splice(index, 1)
}
/**
 * @description 提交保存充值规则
 */
const onSubmit = async (): Promise<void> => {
    try {
        await apiRechargeSetRule({
            ...formData.value
        })
    } catch (err) {
        console.log('提交保存充值规则', err)
    }
    initChargeRule()
}
/** Methods End **/

/** LifeCycle Start **/
initChargeRule()
/** LifeCycle End **/
</script>

<style lang="scss" scoped>
.ls-input {
    width: 320px;
}
.form {
    &--item:last-child {
        margin: 0;
    }
    &--item {
        display: flex;
        margin-bottom: 20px;
        .label {
            width: 108px;
            font-size: 13px;
            padding-right: 12px;
            line-height: 50px;
            text-align: right;
        }
        .content {
            display: flex;
            flex-wrap: wrap;
            width: 800px;
            padding: 10px;
            background-color: #f7f7f7;
            &--label {
                line-height: 30px;
                margin: 0 10px;
                width: 70px;
            }
            &--input {
                width: 240px;
                margin-right: 40px;
            }
        }
        .content:hover {
            .close-icon {
                display: block;
            }
        }
    }
}
</style>
