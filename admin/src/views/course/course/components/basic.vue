<template>
    <el-form-item label="课程名称:" prop="name">
        <el-input
            class="ls-input"
            v-model="modelValue.name"
            placeholder="请输入课程名称"
            maxlength="64"
            show-word-limit
        ></el-input>
    </el-form-item>
    <el-form-item label="课程分类:" prop="category_id">
        <!-- 选择分类 -->
        <el-cascader
            class="ls-input"
            v-model="modelValue.category_id"
            :options="categoryData"
            :props="props"
            clearable
        />
    </el-form-item>
    <el-form-item label="选择讲师:" prop="teacher_id">
        <!-- 选择讲师 -->
        <el-select v-model="modelValue.teacher_id" class="ls-input" placeholder="请选择">
            <el-option
                :label="item.name"
                :value="item.id"
                v-for="(item, index) in teacherData"
                :key="index"
            >
            </el-option>
        </el-select>
    </el-form-item>
    <el-form-item label="课程简介:">
        <el-input
            class="ls-input"
            v-model="modelValue.synopsis"
            type="textarea"
            rows="5"
            placeholder="请输入课程简介"
        >
        </el-input>
    </el-form-item>
    <el-form-item :label="pageType == 4 ? '专栏封面:' : '课程封面:'" prop="cover">
        <div>
            <material-select v-model="modelValue.cover" :limit="1"></material-select>
            <div class="form-tips">
                是指在商品列表展示的图片，建议尺寸750*454px，JPG、PNG格式， 图片小于2MB
            </div>
        </div>
    </el-form-item>
    <el-form-item label="轮播图:" prop="images">
        <div>
            <material-select v-model="modelValue.images" :limit="10"></material-select>
            <div class="form-tips">
                建议尺寸：750*400，可拖拽改变图片顺序，默认首张图为主图，最多上传10张
            </div>
        </div>
    </el-form-item>
    <el-form-item label="商品标签:">
        <div>
            <el-checkbox
                v-model="modelValue.is_choice"
                :false-label="0"
                :true-label="1"
                label="精选课程"
                size="large"
            />
            <div class="form-tips">是否在首页精选专区显示</div>
        </div>
    </el-form-item>
</template>

<script lang="ts" setup>
import { apiCourseOtherLists } from '@/api/course/course'
import { reactive, ref, inject } from 'vue'
import MaterialSelect from '@/components/material/picker.vue'

/** Interface Start **/
interface Lists {
    name: string
    id: number | string
}
/** Interface End **/

/** Props Start **/
withDefaults(
    defineProps<{
        modelValue?: any
    }>(),
    {
        modelValue: {}
    }
)
/** Props Start **/

/** Data Start **/
// 注入
const pageType = inject<string | number>('pageType')
// 分类数据
const categoryData = ref<Array<Lists> | null>([])
// 讲师数据
const teacherData = ref<Array<Lists> | null>([])
// 分类组件配置数据
const props = reactive({
    multiple: false,
    checkStrictly: false,
    label: 'name',
    value: 'id',
    children: 'sons',
    emitPath: false
})
/** Data End **/

/** Methods Start **/
/**
 * @description 获取分类通用列表
 */
const getCourseOtherLists = async (): Promise<void> => {
    const { category_list, teacher_list } = await apiCourseOtherLists()
    categoryData.value = category_list
    teacherData.value = teacher_list
}
/** Methods End **/

/** LifeCycle Start **/
getCourseOtherLists()
/** LifeCycle End **/
</script>

<style lang="scss"></style>
