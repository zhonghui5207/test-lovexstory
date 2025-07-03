<template>
    <div class="detail">
        <div class="content flex">
            <div class="left">
                <el-scrollbar class="ls-scrollbar" style="height: 100%">
                    <el-collapse class="p-t-20" v-model="activeName">
                        <el-collapse-item
                            :title="item.name"
                            :name="item.key"
                            v-for="(item, index) in lists"
                            :key="index"
                        >
                            <div class="nav-list">
                                <div
                                    class="nav-item"
                                    :class="{ active: linkType == citem.type }"
                                    v-for="(citem, cindex) in item.children"
                                    :key="cindex"
                                    @click="linkType = citem.type"
                                >
                                    <div>{{ citem.name }}</div>
                                </div>
                            </div>
                        </el-collapse-item>
                    </el-collapse>
                </el-scrollbar>
            </div>
            <div class="right flex-1">
                <div class="right-content">
                    <shop
                        :key="linkType"
                        :type="linkType"
                        v-if="linkType == 'shop' || linkType == 'marking'"
                        v-model="link"
                    />
                    <course
                        v-if="linkType == 'course'"
                        :modelValue="link.params"
                        @setLink="setLinkParams"
                    />
                    <subject
                        v-if="linkType == 'subject'"
                        :modelValue="link.params"
                        @setLink="setLinkParams"
                    />
                    <category
                        v-if="linkType == 'category'"
                        :modelValue="link.params"
                        @setLink="setLinkParams"
                    />
                    <!-- <draw v-if="linkType == 'draw'" :value="link.params" @input="setLinkParams" />
                    <page v-if="linkType == 'page'" :value="link.params" @input="setLinkParams" /> -->
                    <custom v-if="linkType == 'custom'" v-model="link.params" />
                    <mini-program v-model="link" v-if="linkType == 'mini_program'" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, computed, watch } from 'vue'
import Course from './course.vue'
import Subject from './subject.vue'
import Shop from './shop.vue'
import Category from './category.vue'
import MiniProgram from './mini-program.vue'

// import Page from './page.vue'
import Custom from './custom.vue'
// import Draw from './draw.vue'

/** Emit Start **/
const emit = defineEmits(['update:modelValue'])
/** Emit End **/

/** Props Start **/
const props = withDefaults(
    defineProps<{
        modelValue: any
    }>(),
    {
        modelValue: ''
    }
)
/** Props End **/

/** Data Start **/
const activeName = ref<any>(['shop', 'course', 'category', 'marking', 'other'])
// const activeName = ref<string[]>(['shop'])
const currentLink = ref<any>({})
const linkType = ref<string>('shop')
// 菜单列表
const lists = ref([
    {
        name: '商城页面',
        key: 'shop',
        children: [
            {
                name: '基础页面',
                type: 'shop',
                link: {
                    path: '',
                    name: '',
                    params: {},
                    type: 'shop'
                }
            }
        ]
    },
    {
        name: '课程列表',
        key: 'course',
        children: [
            {
                name: '课程列表',
                type: 'course',
                link: {
                    path: '/pages/course_detail/index',
                    name: '普通商品',
                    params: {},
                    type: 'course'
                }
            }
        ]
    },
    {
        name: '课程分类',
        key: 'category',
        children: [
            {
                name: '课程分类',
                type: 'category',
                link: {
                    path: '/bundle/pages/search/index',
                    name: '课程分类',
                    params: {},
                    type: 'category'
                }
            }
        ]
    },
    {
        name: '营销活动',
        key: 'marking',
        children: [
            {
                name: '专题活动',
                type: 'subject',
                link: {
                    path: '/bundle/pages/subject_detail/index',
                    name: '专题活动',
                    params: {},
                    type: 'subject'
                }
            }
        ]
    },
    {
        name: '其他',
        key: 'other',
        children: [
            {
                name: '自定义链接',
                type: 'custom',
                link: {
                    path: '/pages/webview/index',
                    name: '自定义链接',
                    params: {
                        url: ''
                    },
                    type: 'custom'
                }
            },
            {
                name: '跳转小程序',
                type: 'mini_program',
                link: {
                    type: 'mini_program',
                    name: '跳转小程序',
                    query: {
                        appId: '',
                        path: '',
                        query: '',
                        env_version: ''
                    }
                }
            }
        ]
    }
])
/** Data End **/

/** Computed Start **/
const link = computed({
    get: () => {
        const linktype = linkType.value
        let itemLink: any = {}
        lists.value.forEach((item) => {
            const citem = item.children.find((citem) => citem.type == linktype)
            citem && (itemLink = citem)
        })
        return itemLink?.link
    },
    set: (value) => {
        lists.value.forEach((item) => {
            item.children.forEach((citem) => {
                if (citem.type == linkType.value) {
                    value && (citem.link = value)
                    if (!activeName.value.includes(item.key)) {
                        activeName.value.push(item.key)
                    }
                }
            })
        })
    }
})
/** Computed End **/

/** Watch Start **/
watch(
    () => props.modelValue,
    (value) => {
        if (!value) {
            return
        }
        linkType.value = value.type || 'shop'
        link.value = value
        // currentLink.value = value
    },
    { immediate: true }
)

watch(
    () => link.value,
    (value) => {
        emit('update:modelValue', value)
    },
    { deep: true }
)
/** Watch End **/

/** Methods Start **/
const setLinkParams = (item: any, param?: any) => {
    if (!item.id) {
        return (link.value.params = {})
    }
    link.value &&
        (link.value = {
            ...link.value,
            params: { id: item.id, name: param }
        })
}
/** Methods End **/
</script>

<style scoped lang="scss">
.detail {
    .content {
        .left,
        .right {
            width: 160px;
            height: 500px;
            border-radius: 4px;
            border: 1px solid #e5e5e5;
            box-sizing: border-box;
        }

        .left {
            ::v-deep .el-collapse {
                border: none;

                &-item__header {
                    height: 40px;
                    line-height: 40px;
                    padding-left: 16px;
                    border-radius: 4px;
                    border: none;
                }

                &-item__wrap {
                    border: none;
                    overflow: unset;
                }

                &-item__content {
                    padding-bottom: 10px;
                }
            }

            .nav-list {
                .nav-item {
                    cursor: pointer;
                    padding: 8px 15px;

                    &.active {
                        border-right-width: 2px;
                        border-color: var(--el-color-primary);
                        color: var(--el-color-primary);
                        background-color: var(--el-color-primary-light-9);
                    }
                }
            }
        }

        .right {
            .right-content {
                padding: 20px 24px;
            }
        }
    }
}
</style>
