<template>
	<view class="container">
		<view class="main">
			<z-paging ref="paging" v-model="indexData" @query="getData" :fixed="false" height="100%">
				<block v-for="(item, index) in indexData" :key="index">
					<template v-if="item.name == 'search'">
						<w-search :content="item.content" :styles="item.styles" />
					</template>
					<template v-if="item.name == 'banner'">
						<w-banner :content="item.content" :styles="item.styles" />
					</template>
					<template v-if="item.name == 'nav'">
						<w-nav :content="item.content" :styles="item.styles" />
					</template>
					<template v-if="item.name == 'special' && item.content.enabled != 2">
						<special-zone :content="item.content" />
					</template>
				</block>

				<template v-if="1">
					<hot-list :content="hotList" />
				</template>

				<template v-if="choiceList">
					<choice :content="choiceList" />
				</template>
			</z-paging>
		</view>

		<!-- 底部导航栏 -->
		<tabbar active="0"></tabbar>

		<!-- 骨架屏 -->
		<skeleton v-show="loading" />
		<!-- #ifdef APP-PLUS -->
		<lyg-popup title="用户使用及隐私保护政策提示" @confirm="handlecomfirm"> </lyg-popup>
		<!-- #endif -->
	</view>
</template>

<script setup lang="ts">
	import { ref, shallowRef } from 'vue'
	import { onLoad, onShow } from '@dcloudio/uni-app'
	import SpecialZone from './component/special-zone.vue'
	import HotList from './component/hot.vue'
	import Choice from './component/choice.vue'
	import lygPopup from '@/components/lyg-popup/lyg-popup.vue'
	import Skeleton from './component/skeleton.vue'
	import { getIndex } from '@/api/shop'
	import { apiChoiceCourseLists, apiIndexHotCourseLists } from '@/api/store'
	import { useAppStore } from '@/stores/app'

	const appStore = useAppStore()

	const paging = shallowRef()
	const indexData = ref<any>({})
	const hotList = ref([])
	const choiceList = ref([])
	const loading = ref(true)

	const getData = async () => {
		try {
			const data = await getIndex()
			indexData.value = data?.page?.data
			loading.value = false
			paging.value.complete(indexData.value)
		} catch (error) {
			loading.value = false
			console.log(error)
			paging.value.complete(false)
		}
	}

	const getChoiceCourse = async () => {
		try {
			const { lists } = await apiChoiceCourseLists({ is_choice: 2 })
			choiceList.value = lists
		} catch (error) {
			//TODO handle the exception
			console.log(error)
		}
	}

	const getHotCourse = async () => {
		try {
			const lists = await apiIndexHotCourseLists()
			hotList.value = lists
			console.log(hotList.value)
		} catch (error) {
			console.log(error)
		}
	}

	const goPage = (url : string) => {
		uni.navigateTo({ url: url })
	}
	//处理ios首次加载异常
	const handlecomfirm = async () => {
		getData()
		getChoiceCourse()
		getHotCourse()
		await appStore.getConfig()
	}
	onShow(() => {
		getData()
		getChoiceCourse()
		getHotCourse()
	})
</script>

<style lang="scss">
	.container {
		display: flex;
		height: 100vh;
		overflow: hidden;
		flex-direction: column;

		.main {
			flex: 1;
			min-height: 0;
			overflow: scroll;
		}
	}
</style>