<template>
  <popup
    ref="popupRef"
    width="700px"
    :title="id ? '编辑分类' : '新增分类'"
    @confirm="onSubmit(formRef)"
  >
    <div>
      <!-- Main Start -->
      <el-form
        ref="formRef"
        :model="formData"
        :rules="rules"
        label-width="120px"
        class="demo-formData"
      >
        <el-card shadow="never" class="!border-none mt-4">
          <el-form-item label="分类名称:" prop="name">
            <el-input
              class="ls-input"
              v-model="formData.name"
              placeholder="请输入"
              show-word-limit
              maxlength="10"
            ></el-input>
          </el-form-item>
          <el-form-item label="父级分类:" prop="pid">
            <el-radio v-model="isParent" :label="0">无父级分类</el-radio>
            <el-radio v-model="isParent" :label="1">有父级分类</el-radio>

            <div class="mt-[15px]" v-show="isParent">
              <el-cascader
                class="select m-r-10"
                v-model="formData.pid"
                :options="categoryData"
                :props="props"
                clearable
              />
            </div>
          </el-form-item>
          <el-form-item label="分类图片:">
            <div>
              <material-select
                v-model="formData.image"
                :limit="1"
              ></material-select>
              <div class="form-tips">
                建议尺寸：宽200像素*高200像素的jpg，jpeg，png图片
              </div>
            </div>
          </el-form-item>
          <el-form-item label="排序:">
            <div>
              <el-input class="ls-input" v-model="formData.sort"></el-input>
              <div class="form-tips">数字越大，排序越靠前，默认排序号为0</div>
            </div>
          </el-form-item>
          <el-form-item label="状态:" prop="status">
            <el-switch
              v-model="formData.status"
              :active-text="formData.status ? '显示' : '关闭'"
              :active-value="1"
              :inactive-value="0"
            />
          </el-form-item>
        </el-card>
      </el-form>
      <!-- Main End -->
    </div>
  </popup>
</template>

<script lang="ts" setup>
import {
  apiCategoryAdd,
  apiCategoryEdit,
  apiCategoryCommonLists,
  apiCategoryDetail,
} from "@/api/course/course";
import { ref, reactive, watchEffect } from "vue";
import MaterialSelect from "@/components/material/picker.vue";
import type { ElForm } from "element-plus";

/** Interface Start **/
interface FormDataObj {
  name?: string | Array<object>;
  pid?: number | string | Array<object>;
  image?: string | Array<object>;
  sort?: number;
  status?: number | Array<object>;
}
interface Lists {
  name: string;
  id: number | string;
}
type FormInstance = InstanceType<typeof ElForm>;

/** Interface End **/

const emits = defineEmits(["refresh"]);

/** Data Start **/
const formRef = ref<FormInstance>();
const id: any = ref();
const popupRef = shallowRef();
/**
 * @description 是否有父级分类
 */
const isParent = ref<string | number | undefined>(0);
const categoryData = ref<Array<Lists> | null>([]);
const formData = ref<FormDataObj>({
  name: "",
  pid: 0,
  image: "",
  sort: 0,
  status: 1,
});
// 表单校验规则
const rules = reactive<FormDataObj>({
  name: [{ required: true, message: "请输入分类名称", trigger: "blur" }],
  pid: [
    { required: true, message: "请选父级分类", trigger: ["blur", "change"] },
  ] as any[],
  status: [{ required: true, message: "是否显示", trigger: "change" }],
});
const props = reactive({
  multiple: false,
  checkStrictly: true,
  label: "name",
  value: "id",
  emitPath: false,
});
/** Data End **/

/** Methods Start **/
/**
 * @description 获取分类详情
 */
const getCategoryDetail = async (id: number): Promise<void> => {
  formData.value = await apiCategoryDetail({ id });
  isParent.value = formData.value.pid === 0 ? 0 : 1;
};
/**
 * @description 获取分类通用列表
 */
const getCategoryLists = async (): Promise<void> => {
  categoryData.value = await apiCategoryCommonLists();
};
/**
 * @description 添加分类
 */
const handleCategoryAdd = async (): Promise<void> => {
  await apiCategoryAdd({ ...formData.value });
  emits("refresh");
};
/**
 * @description 编辑分类
 */
const handleCategoryEdit = async (): Promise<void> => {
  await apiCategoryEdit({ ...formData.value });
  emits("refresh");
};
/**
 * @description 提交分类数据
 */
const onSubmit = (formEl: FormInstance | undefined): void => {
  if (!isParent.value) {
    rules.pid = [];
    formData.value.pid = 0;
  } else {
    rules.pid = [
      { required: true, message: "请选父级分类", trigger: ["blur", "change"] },
    ];
  }
  if (!formEl) return;
  formEl.validate((valid): boolean | undefined => {
    if (!valid) return false;
    if (!id.value) handleCategoryAdd();
    else handleCategoryEdit();
  });
};

//打开弹框
const open = (value: any) => {
  popupRef.value.open();
  id.value = value;
  getCategoryLists();
  formData.value = {
    name: "",
    pid: 0,
    image: "",
    sort: 0,
    status: 1,
  };
  if (id.value) getCategoryDetail(id.value);
};
defineExpose({ open });
/** Methods End **/

/** Watch Start **/
watchEffect(() => {
  if (!isParent.value) {
    formRef?.value?.clearValidate(["pid"]);
  }
});
/** Watch Start **/
</script>

<style lang="scss" scoped>
.ls-input,
.select {
  width: 340px;
}
</style>
