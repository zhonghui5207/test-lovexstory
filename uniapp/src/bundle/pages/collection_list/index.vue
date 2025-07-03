<template>
    <view class="collection mt-4">
        <z-paging
            ref="paging"
            v-model="collectData"
            @query="queryList"
            :fixed="false"
            height="100%"
            use-page-scroll
        >
            <u-swipe-action
                :show="item.show"
                :index="index"
                v-for="(item, index) in collectData"
                :key="item.id"
                @click="handleCollect"
                :options="options"
            >
                <navigator :url="`/pages/course_detail/index?id=${item.id}`" hover-class="none">
                    <course-list :list="item" type="card" />
                </navigator>
            </u-swipe-action>
        </z-paging>
    </view>
</template>

<script lang="ts" setup>
import { ref, reactive, shallowRef, unref } from 'vue'
import { apiCollectLists, apiCourseCollection } from '@/api/user'
import CourseList from '@/components/course-list/index.vue'

const paging = shallowRef()
const collectData = ref<any>([])
const options = reactive([
    {
        text: '删除',
        style: {
            backgroundColor: '#dd524d'
        }
    }
])

const queryList = async (pageNo: number, pageSize: number) => {
    const { lists } = await apiCollectLists({
        page_no: pageNo,
        page_size: pageSize
    })
    lists.forEach((item: any) => {
        item.show = false
    })
    paging.value.complete(lists)
}

// 取消收藏
const handleCollect = async (index: number): Promise<void> => {
    try {
        await apiCourseCollection({ id: unref(collectData)[index].id, collect: 0 })
        paging.value.reload()
    } catch (err) {
        //TODO handle the exception
        console.log('收藏报错=>', err)
    }
}
</script>
