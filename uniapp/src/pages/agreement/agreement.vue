<template>
    <view class="p-[20rpx]">
        <u-parse :html="agreementContent" v-if="agreementType != 'distribution'"></u-parse>
        <div v-else>{{ agreementContent }}</div>
    </view>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { onLoad } from '@dcloudio/uni-app'
import { getPolicy } from '@/api/app'

let agreementType = ref('') // 协议类型
const agreementContent = ref('') // 协议内容

const getData = async (type) => {
    const res = await getPolicy({ type })
    agreementContent.value = res.content
    uni.setNavigationBarTitle({
        title: String(res.title)
    })
}

onLoad((options: any) => {
    if (options.type) {
        agreementType = options.type
        getData(agreementType)
    }
})
</script>

<style lang="scss" scoped></style>
