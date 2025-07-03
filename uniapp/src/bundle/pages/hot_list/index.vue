<template>
  <view class="hot-list pt-4 bg-white">
    <z-paging
      ref="paging"
      v-model="hotData"
      @query="queryList"
      :fixed="false"
      height="100%"
      use-page-scroll
    >
      <course-list :list="hotData" type="lists" />
    </z-paging>
  </view>
</template>

<script lang="ts" setup>
import { ref, shallowRef } from "vue";
import { apiHotLists } from "@/api/store";
import CourseList from "@/components/course-list/index.vue";

const paging = shallowRef();
const hotData = ref<any>([]);

const queryList = async (pageNo: number, pageSize: number) => {
  try {
    const { lists } = await apiHotLists({
      page_no: pageNo,
      page_size: pageSize,
    });
    console.log(lists);
    paging.value.complete(lists);
  } catch (e) {
    //TODO handle the exception
    paging.value.complete(false);
  }
};
</script>