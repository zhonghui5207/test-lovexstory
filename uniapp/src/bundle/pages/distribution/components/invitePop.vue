<template>
    <view>
        <!--输入邀请邀请码-->
        <u-popup v-model="PopShow" mode="center" border-radius="14" width="600rpx">
            <view class="p-[70rpx]">
                <view class="flex items-center">
                    <view>邀请码：</view>
                    <input
                        class="flex-1 p-[10rpx] rounded-lg"
                        v-model="inviteCode"
                        style="border: 1px solid #e5e5e5"
                    />
                </view>

                <button @click="confirmVisit" class="rounded-full bg-primary mt-[30rpx] text-white">
                    确定
                </button>
            </view>
        </u-popup>
    </view>
</template>

<script setup lang="ts">
import { postInvite } from '@/api/distribution'
import { onMounted, ref } from 'vue'

const emits = defineEmits(['success'])

//邀请码
const inviteCode = ref('')

//弹框 显示/隐藏
const PopShow = ref(false)

//打开弹框
const open = () => {
    PopShow.value = true
    inviteCode.value = ''
}

//邀请码确定
const confirmVisit = async () => {
    uni.showLoading({
        title: '加载中'
    })
    try {
        await postInvite({ code: inviteCode.value })
        emits('success')
        PopShow.value = false
    } catch (error) {
        uni.$u.toast(error)
        inviteCode.value = ''
    }
    uni.hideLoading()
}

defineExpose({
    open
})
</script>

<style scoped lang="scss"></style>
