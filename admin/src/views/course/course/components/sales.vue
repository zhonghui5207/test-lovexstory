<template>
    <el-form-item label="收费方式:" prop="fee_type">
        <el-radio v-model="modelValue.fee_type" :disabled="type == 1" :label="1">收费</el-radio>
        <el-radio v-model="modelValue.fee_type" :disabled="type == 1" :label="0">免费</el-radio>
    </el-form-item>
    <el-form-item label="价格设置:" prop="sell_price" v-if="modelValue.fee_type === 1">
        <el-input
            class="ls-input"
            v-model="modelValue.sell_price"
            placeholder="请输入销售价格"
        ></el-input>
    </el-form-item>
    <el-form-item label="划线价:" v-if="modelValue.fee_type === 1">
        <el-input
            class="ls-input"
            v-model="modelValue.line_price"
            placeholder="请输入划线价"
        ></el-input>
    </el-form-item>
    <el-form-item label="虚拟学习人数:">
        <div>
            <el-input class="ls-input" v-model="modelValue.virtual_study_num"></el-input>
            <div class="form-tips">
                课程上架后的原始学习人数，原始数据只有用户可以看到，不参与数据分析
            </div>
        </div>
    </el-form-item>
    <el-form-item label="排序:">
        <el-input class="ls-input" v-model="modelValue.sort"></el-input>
    </el-form-item>
    <el-form-item label="课程状态:" prop="status">
        <el-radio v-model="modelValue.status" :label="1">立即上架</el-radio>
        <el-radio v-model="modelValue.status" :label="0">暂不上架</el-radio>
    </el-form-item>
</template>

<script lang="ts" setup>
/** Props Start **/
withDefaults(
    defineProps<{
        modelValue?: any
        type: any
    }>(),
    {
        modelValue: {},
        type: 0 //0-新增，1-编辑
    }
)
/** Props Start **/
</script>
