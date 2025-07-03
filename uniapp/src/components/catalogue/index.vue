<template>
    <!-- <template v-if="type == 4">
        <view
            class="collapse"
            v-for="(collapseItem, collapseIndex) in cataList"
            :key="collapseItem.id"
        >
            <view
                class="collapse--header flex justify-between"
                @click="handleCollapseItem(collapseIndex)"
            >
                <view class="collapse--header--title flex-1 truncate">{{ collapseItem.name }}</view>
                <u-icon
                    name="arrow-up"
                    color="#888888"
                    size="24"
                    :style="{
                        transition: 'all 0.3s',
                        transform: collapseItem.open ? 'rotate(0)' : 'rotate(180deg)'
                    }"
                ></u-icon>
            </view>

            <view
                class="catalogue collapse--body"
                :style="{
                    height: collapseItem.open ? collapseItem.catalogue.length * 98 + 'rpx' : '0px'
                }"
            >
                <view
                    class="catalogue-item"
                    :class="{ grayscale: cataItem.is_study }"
                    v-for="cataItem in collapseItem.catalogue"
                    :key="cataItem.id"
                    @click="refresh({ ...cataItem, type: collapseItem.type })"
                >
                    <image :src="getCourseType(collapseItem.type)"></image>
                    <view
                        class="catalogue-title truncate"
                        :class="{ 'catalogue-title-s': cataItem.id == cateId }"
                        >{{ cataItem.name }}</view
                    >
                    <view class="catalogue-item-last flex row-end" v-if="status == 2">
                        <text v-if="!cataItem.fee_type">免费</text>
                        <u-icon v-else name="lock" color="#888888" size="28"></u-icon>
                    </view>
                </view>
            </view>
        </view>
    </template> -->

    <!-- <template v-else> -->
    <view class="catalogue">
        <view
            class="catalogue-item items-center w-full"
            v-for="cataItem in cataList"
            :key="cataItem.id"
            @click="refresh(cataItem)"
            :class="{ grayscale: cataItem.is_study }"
        >
            <image :src="getCourseType(type)"></image>
            <view class="ml-[14px] !min-w-0">
                <view
                    class="catalogue-title"
                    :class="{
                        'catalogue-title-s': cataItem.id == cateId
                    }"
                    >{{ cataItem.name }}</view
                >
                <view
                    class="text-info text-sm"
                    :class="{ 'catalogue-title-s': cataItem.id == cateId }"
                    v-if="type !== 1"
                >
                    <text>{{ cataItem.catelogue_time }}时长</text>
                    <text>·</text>
                    <text v-if="cataItem.id != cateId">学习进度{{ cataItem.study_progress }}</text>
                    <text v-if="cataItem.id == cateId">学习中</text>
                </view>
            </view>
            <view class="catalogue-item-last ml-auto flex-none" v-if="status == 2">
                <text v-if="!cataItem.fee_type">免费</text>
                <u-icon v-else name="lock" color="#888888" size="28"></u-icon>
            </view>
        </view>
    </view>
    <!-- </template> -->
</template>

<script lang="ts" setup>
import { ref, computed, watch } from 'vue'
import { useUserStore } from '@/stores/user'

/** Emit Start **/
const emit = defineEmits(['refresh'])
/** Emit End **/

/** Props Start **/
const props = withDefaults(
    defineProps<{
        list: any // 目录列表
        type: number // 课程类型
        status: number // 课程状态 1-加入课程 2-购买课程 3-已购买
        cateId?: number // 目录的ID
    }>(),
    {
        list: [],
        type: 0,
        status: 1,
        cateId: -1
    }
)
/** Props End **/

/** Computed Start **/
const cataList: any = ref([])
/** Computed End **/

/** Computed Start **/
const userStore = useUserStore()
const isLogin = computed(() => userStore.token)
/**
 * @description 获取课程类型
 */
const getCourseType = computed(() => {
    return (type: any) => {
        switch (type) {
            case 1:
                return '/static/images/icon_image_text.png'
            case 2:
                return '/static/images/icon_audio.png'
            case 3:
                return '/static/images/icon_video.png'
        }
    }
})
/** Computed End **/

/** Watch Start **/
watch(
    () => props.list,
    (value) => {
        if (props.type == 4) {
            value.forEach((el) => {
                el.open = true
            })
        }
        cataList.value = value
    },
    { immediate: true }
)
/** Watch End **/

/** Methods Start **/
/**
 * @param { number } index
 * @description 打开某个手风琴
 */
const handleCollapseItem = (index: number) => {
    cataList.value[index].open = !cataList.value[index].open
}
/**
 * @param { course_id } 课程ID
 * @description 跳转页面方法
 */
const refresh = (cateItem: any) => {
    if (!isLogin.value) return goPage('/pages/login/login')
    emit('refresh', cateItem)
}
/**
 * @param { string } url
 * @return { void }
 * @description 跳转页面方法
 */
const goPage = (url: string) => {
    uni.navigateTo({ url: url })
}
/** Methods End **/
</script>

<style lang="scss" scoped>
.collapse {
    &--header {
        padding: 30rpx;
        background-color: $color-white;
        border-bottom: 1px solid $color-border-light;
        &--title {
            color: $color-text-deep;
            font-size: $font-size-lg;
            font-weight: 500;
        }
    }

    &--body {
        overflow: hidden;
        transition: all 0.3s;
    }
}

.catalogue {
    // 已读
    .grayscale {
        color: #999;
    }
    .catalogue-item {
        display: flex;
        padding: 30rpx;
        background-color: $color-white;
        border-bottom: 1px solid $color-border-light;
        image {
            width: 34rpx;
            height: 34rpx;
            flex: none;
        }
        .catalogue-title {
            flex: auto;
            font-size: $font-size-md;
        }
        .catalogue-title-s {
            color: #2073f4;
        }

        .catalogue-item-last {
            width: 100rpx;
            text-align: center;
            text {
                color: #fa8919;
                padding: 4rpx 15rpx;
                font-size: $font-size-xs;
                background: #fff3e7;
            }
        }
    }
}
</style>
