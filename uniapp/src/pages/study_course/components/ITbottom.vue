<template>
    <view class="flex h-[150rpx] items-center" style="border-top: 1px solid #e2e2e2">
        <view class="w-1/3 text-center" @click="previous">
            <u-icon name="arrow-left" color="#2979ff" size="28"></u-icon>
            <text>上一章</text>
        </view>
        <view class="w-1/3 text-center mid" @click.stop="showPop">目录</view>
        <view class="w-1/3 text-center" @click="next">
            <text>下一章</text>
            <u-icon name="arrow-right" color="#2979ff" size="28"></u-icon>
        </view>
    </view>
    <u-popup
        v-model="popShow"
        safe-area-inset-bottom
        mode="bottom"
        height="800rpx"
        border-radius="20"
        closeable
    >
        <view>
            <view class="h-[100rpx] text-center leading-[100rpx] text-2xl font-medium">目录</view>
            <view class="h-[600rpx]">
                <scroll-view scroll-y class="h-full">
                    <!-- <course-catalogue
                        :list="courseDetailInfo.courseCatalogues"
                        :type="courseDetailInfo.type"
                        :status="courseDetailInfo.purchased"
                        :cateId="cateId"
                        @refresh="handleRefresh"
                    >
                    </course-catalogue> -->
                    <course-catalogue
                        :list="courseDetailInfo.catalogue_list"
                        :type="courseDetailInfo.type"
                        :status="courseDetailInfo.course_status"
                        :cateId="cateId"
                        @refresh="handleRefresh"
                    >
                    </course-catalogue>
                </scroll-view>
            </view>
        </view>
    </u-popup>
</template>

<script setup lang="ts">
import CourseCatalogue from '@/components/catalogue/index.vue'
import { computed, ref } from 'vue'

const emits = defineEmits(['refresh'])

const props = defineProps({
    courseDetailInfo: {
        type: Object,
        default: () => {}
    },
    cateId: {
        type: [Number, String],
        default: 0
    }
})

//弹出层 显示/隐藏
const popShow = ref(false)

//打开目录
const showPop = () => {
    popShow.value = true
}

//目录数据
const catelogue = computed(() => {
    return props.courseDetailInfo.catalogue_list as any[]
})

//切换目录
const handleRefresh = (event: any) => {
    emits('refresh', event)
    popShow.value = false
}

//获取当前章节索引
const getNowIndex = () => {
    // const catelogue: any[] = props.courseDetailInfo.courseCatalogues
    const nowIndex: number = catelogue.value.findIndex((item: any) => {
        return item.id == props.cateId
    })
    return nowIndex
}

//上一章
const previous = () => {
    const nowIndex = getNowIndex()
    if (nowIndex - 1 < 0) {
        uni.$u.toast('已经是第一章！')
        return
    }
    handleRefresh(catelogue.value[nowIndex - 1])
}
//下一章
const next = () => {
    const nowIndex = getNowIndex()

    if (nowIndex + 1 == catelogue.value.length) {
        uni.$u.toast('已经是最后一章！')
        return
    }
    handleRefresh(catelogue.value[nowIndex + 1])
}
</script>

<style scoped lang="scss">
.mid {
    border-left: 1px solid #e2e2e2;
    border-right: 1px solid #e2e2e2;
}
</style>
