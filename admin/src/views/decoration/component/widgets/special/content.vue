<template>
    <div class="news">
        <div class="flex items-center mx-[10px] my-[15px] text-[17px] font-medium">
            {{ content.title }}
        </div>
        <div class="subject--main">
            <div class="m-l-15">
                <div
                    class="course-item mr-[10px]"
                    v-for="subjectItem in content.data"
                    :key="subjectItem"
                >
                    <div class="course-item--content">
                        <el-icon v-if="!subjectItem?.cover" size="22px"><PictureFilled /></el-icon>
                        <el-image
                            v-else
                            :src="subjectItem.cover"
                            style="width: 134px; height: 180px"
                        ></el-image>
                    </div>
                    <div class="form-tips lighter text-center">{{ 9999 }}人在学习</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { PropType } from 'vue'
import type options from './options'
type OptionsType = ReturnType<typeof options>
defineProps({
    content: {
        type: Object as PropType<OptionsType['content']>,
        default: () => ({})
    },
    styles: {
        type: Object as PropType<OptionsType['styles']>,
        default: () => ({})
    }
})
</script>

<style lang="scss" scoped>
.subject {
    margin-top: 10px;
    width: 100%;
    padding-bottom: 20px;
    background-color: #ffffff;

    &--header {
        height: 24px;
        padding: 14px 15px;
        .title {
            font-weight: 500;
            font-size: 18px;
            color: #333333;
        }
    }
    ::-webkit-scrollbar {
        display: none;
        width: 0 !important;
        height: 0 !important;
        -webkit-appearance: none;
        background: transparent;
    }
    &--main {
        -ms-overflow-style: none;
    }
    &--main {
        overflow: -moz-scrollbars-none;
    }
    &--main {
        overflow: auto hidden;
        white-space: nowrap;
        .course-item {
            width: 134px;
            height: 220rpx;
            overflow: hidden;
            display: inline-block;
            border-radius: 5px;
            &--content {
                width: 100%;
                height: 180px;
                line-height: 180px;
                text-align: center;
                background-color: #999999;
            }
            &--text {
                height: 40px;
                line-height: 40px;
                background: #f6f6f6;
            }
        }
    }
}
</style>
