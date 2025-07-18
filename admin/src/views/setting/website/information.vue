<!-- 网站信息 -->
<template>
    <div class="website-information">
        <el-form ref="formRef" :rules="rules" class="ls-form" :model="formData" label-width="120px">
            <el-card shadow="never" class="!border-none">
                <el-form-item label="网站名称" prop="name">
                    <div class="w-80">
                        <el-input
                            v-model="formData.name"
                            placeholder="请输入网站名称"
                            maxlength="30"
                            show-word-limit
                        ></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="网站图标" prop="web_favicon" required>
                    <div>
                        <material-picker v-model="formData.web_favicon" :limit="1" />
                        <div class="form-tips">建议尺寸：100*100像素，支持jpg，jpeg，png格式</div>
                    </div>
                </el-form-item>
                <el-form-item label="网站LOGO" prop="web_logo" required>
                    <div>
                        <material-picker v-model="formData.web_logo" :limit="1" />
                        <div class="form-tips">建议尺寸：100*100像素，支持jpg，jpeg，png格式</div>
                    </div>
                </el-form-item>
                <el-form-item label="登录页广告图" prop="login_image" required>
                    <div>
                        <material-picker v-model="formData.login_image" :limit="1" />
                        <div class="form-tips">建议尺寸：100*100像素，支持jpg，jpeg，png格式</div>
                    </div>
                </el-form-item>
                <el-form-item label="文档信息">
                    <div>
                        <el-radio-group v-model="formData.document_status">
                            <el-radio :label="1">显示</el-radio>
                            <el-radio :label="0">隐藏</el-radio>
                        </el-radio-group>
                        <div class="form-tips">默认开启，控制工作台商城信息的按钮是否显示</div>
                    </div>
                </el-form-item>
            </el-card>
            <el-card shadow="never" class="!border-none mt-4">
                <div class="text-xl font-medium mb-[20px]">前台设置</div>
                <el-form-item label="前台名称" prop="shop_name">
                    <div class="w-80">
                        <el-input
                            v-model="formData.shop_name"
                            placeholder="请输入名称"
                            maxlength="30"
                            show-word-limit
                        ></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="前台LOGO" prop="shop_logo">
                    <div>
                        <material-picker v-model="formData.shop_logo" :limit="1" />
                        <div class="form-tips">建议尺寸：100*100px，支持jpg，jpeg，png格式</div>
                    </div>
                </el-form-item>
            </el-card>
            <el-card shadow="never" class="!border-none mt-4">
                <div class="text-xl font-medium mb-[20px]">平台联系方式</div>
                <el-form-item label="联系人姓名" prop="contact_name">
                    <div class="w-80">
                        <el-input
                            v-model="formData.contact_name"
                            placeholder="请输入联系人姓名"
                        ></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="手机号码" prop="contact_phone">
                    <div class="w-80">
                        <el-input
                            v-model="formData.contact_phone"
                            placeholder="请输入手机号码"
                        ></el-input>
                        <div class="form-tips">
                            平台联系人手机号：有订单产生时，可发送短信至联系人手机
                        </div>
                    </div>
                </el-form-item>
            </el-card>
        </el-form>
        <footer-btns v-perms="['setting.web.web_setting/setWebsite']">
            <el-button type="primary" @click="handleSubmit">保存</el-button>
        </footer-btns>
    </div>
</template>

<script lang="ts" setup name="webInformation">
import { getWebsite, setWebsite } from '@/api/setting/website'
import useAppStore from '@/stores/modules/app'
import type { FormInstance } from 'element-plus'
const formRef = ref<FormInstance>()

const appStore = useAppStore()
// 表单数据
const formData = reactive({
    name: '', // 网站名称
    web_favicon: '', // 网站图标
    web_logo: '', // 网站logo
    login_image: '', // 登录页广告图
    shop_name: '',
    shop_logo: '',
    contact_name: '',
    contact_phone: '',
    document_status: 1
})

// 表单验证
const rules = {
    name: [
        {
            required: true,
            message: '请输入网站名称',
            trigger: ['blur']
        }
    ],
    web_favicon: [
        {
            required: true,
            message: '请选择网站图标',
            trigger: ['change']
        }
    ],
    web_logo: [
        {
            required: true,
            message: '请选择网站logo',
            trigger: ['change']
        }
    ],
    login_image: [
        {
            required: true,
            message: '请选择登录页广告图',
            trigger: ['change']
        }
    ],
    shop_name: [
        {
            required: true,
            message: '请输入店铺/商城名称',
            trigger: ['blur']
        }
    ],
    shop_logo: [
        {
            required: true,
            message: '请选择商城LOGO',
            trigger: ['change']
        }
    ],
    contact_name: [
        {
            required: true,
            message: '请输入联系人姓名',
            trigger: ['blur']
        }
    ],
    contact_phone: [
        {
            required: true,
            message: '请输入联系人手机号码',
            trigger: ['blur']
        }
    ]
}

// 获取备案信息
const getData = async () => {
    const data = await getWebsite()
    for (const key in formData) {
        //@ts-ignore
        formData[key] = data[key]
    }
}

// 设置备案信息
const handleSubmit = async () => {
    await formRef.value?.validate()
    await setWebsite(formData)
    appStore.getConfig()
    getData()
}

getData()
</script>

<style lang="scss" scoped></style>
