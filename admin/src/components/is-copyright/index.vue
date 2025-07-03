<template>
    <div class="full-screen h-full cursor-pointer flex items-center" @click="openDialog">
        <el-tag effect="dark">免费更新</el-tag>
    </div>

    <popup
        ref="popupRef"
        width="510px"
        @confirm="goPage"
        :confirmButtonText="!checking ? '前往官网' : false"
        :cancelButtonText="!checking ? '关闭' : false"
        title="免费更新"
    >
        <div
            v-if="checking"
            v-loading="checking"
            element-loading-text="正在检测中"
            style="height: 200px"
        ></div>

        <div v-else>
            <div v-if="flag">
                <div class="text-center flex justify-center">
                    <img
                        style="width: 48px; height: 48px"
                        :src="success"
                        :fit="'cover'"
                        class="mb-5"
                    />
                </div>
                <div class="mb-[60px] pl-[45px] pr-[45px]">
                    恭喜您，系统检测到您的域名
                    <span style="color: #4073fa">已授权</span>
                    ，可前往likeshop.cn官网享受所有正版权益
                </div>
            </div>
            <div v-else>
                <div class="text-center flex justify-center">
                    <img
                        style="width: 48px; height: 48px"
                        :src="error"
                        :fit="'cover'"
                        class="mb-5"
                    />
                </div>
                <div class="mb-[60px] pl-[45px] pr-[45px]">
                    很遗憾，系统检测到您的域名
                    <span style="color: #4073fa">未授权</span>
                    ，请前往likeshop.cn官网【个人中心】-【产品授权】登记域名授权，否则将视为盗版行为!
                </div>
            </div>
        </div>
    </popup>
</template>

<script lang="ts" setup>
import Popup from '@/components/popup/index.vue'
import { apiCheckLegal } from '@/api/app'
import feedback from '@/utils/feedback'
import success from '@/assets/images/success.png'
import error from '@/assets/images/error.png'
const popupRef = shallowRef<InstanceType<typeof Popup>>()

const checking = ref(true)
const flag = ref(false)
const openDialog = async () => {
    popupRef.value?.open()
    apiCheckLegal(null)
        .then((res) => {
            flag.value = res.result
        })
        .catch((err) => {
            feedback.msgError(err)
        })
        .finally(() => {
            setTimeout(() => (checking.value = false), 1500)
        })
}

const goPage = () => {
    window.open('https://www.likeshop.cn/', '_blank')
}
</script>
