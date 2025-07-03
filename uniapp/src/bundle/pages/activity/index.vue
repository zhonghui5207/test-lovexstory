<template>
    <view class="container">
        <!-- Header Search Start -->
        <view class="search-box flex bg-white">
            <view class="search flex">
                <!-- 左侧图标 -->
                <u-icon name="search" size="34" color="#888888"></u-icon>
                <!-- 输入 -->
                <input v-model="keyword" type="text" class="search--input" placeholder="请输入关键词搜索" @focus="showClear=true" @blur="showClear=false" />
                <!-- 清空图标 -->
                <image src="../../../static/images/icon_clear.png" class="clear" v-show="showClear" @click="keyword = ''"></image>
            </view>
            <view v-show="searchStatus === false" class="flex-1 normal text-right font-md" @click="handleSearch(true)">
                搜索
            </view>
            <view v-show="searchStatus === true" class="flex-1 normal text-right font-md" @click="handleSearch(false)">
                取消
            </view>
        </view>
        <!-- Header Search End -->
        
        <view class="main bg-white m-t-20">
            <refresh @up="upCallback" :ref="(el: Event | any) => refreshRef = el">
                    
                <block v-for="(item) in masterWorkData" :key="item.id">                                
                    <!-- 订单卡片 -->
                    <view class="master_worker_item flex col-start" @click="toMasterWorkerDetail(item.id)">
                        <u-image :src="item.user_image" width="100" height="100" border-radius="50%"></u-image>
                        <view class="m-l-20">
                            <view class="normal f-w-500 font-lg">{{ item.name }}</view>
                            <view class="lighter font-sm m-t-10">所在地区：{{ item.city }} - {{ item.district }}</view>
                            <view class="lighter font-sm m-t-10">服务项目：{{ item.goods_name }}</view>
                        </view>
                    </view>
                </block>
                
            </refresh>
        </view>
    </view>
</template>

<script lang="ts" setup>
    import { ref, nextTick } from "vue"
    import { onLoad, onShow } from "@dcloudio/uni-app";
    import { apiStaffLists } from "@/api/store.ts"
    import refresh from "@/components/refresh/index.vue"
    
    
    /** Data Start **/
    // 页面状态
    let searchStatus = ref<boolean>(false)
    // 搜索关键字
    let keyword = ref<string | number>('')
    // 清空输入框
    let showClear = ref<boolean>(false)
    // 师傅列表数据
    let masterWorkData = ref< any >([])
    // 下拉组件的Ref
    const refreshRef = ref<InstanceType<typeof refresh> | null>(null)
    /** Data End **/
    
    
    /** Methods Start **/     
    /**
     * @param { Object } 是否搜索
     * @description 处理搜索
     */
    const handleSearch = (flag: boolean) => {
        if ( flag ) {
            if ( keyword.value !== '' ) 
                searchStatus.value = true
        } else {
            keyword.value = ''
            searchStatus.value = false
        }
        refreshRef.value.resetUpScroll()
    }
    /**
     * @param { Object } param
     * @return { Promise } 
     * @description 初始化请求师傅列表
     */
    const upCallback = async (param: any): Promise<void> => {
        try{
            const res: any = await apiStaffLists({
                page_no: param.num,
                page_size: param.size,
                name: keyword.value
            })
            if (param.num === 1) masterWorkData.value = []
            masterWorkData.value = [...masterWorkData.value, ...res.lists]
            refreshRef.value.endSuccess(res.count)
        }catch(e){
            console.log('下拉加载', e)
            refreshRef.value.endErr()
        }
    }
    /**
     * @param { number } id
     * @return { void } 
     * @description 去师傅主页
     */
    const toMasterWorkerDetail = (id: number | string) => {
        uni.navigateTo({
            url: `/bundle/pages/master_worker_detail/index?id=${ id }`
        })
    }
    /** Methods End **/
    
    
    /** Life Cycle Start **/
    onLoad((options) => {
        // current.value = options?.type || 0
    })
    onShow(() => {
        nextTick(() => {
            refreshRef.value?.resetUpScroll()
        })
    })
    /** Life Cycle End **/
</script>

<style lang="scss">
    .container {
        display: flex;
        height: 100vh;
        overflow: hidden;
        flex-direction: column;
        
        // 头部搜索
        .search-box {
            width: 100%;
            height: 100rpx;
            padding: 15rpx 24rpx;
            .search {
                width: 620rpx;
                height: 100%;
                padding: 15rpx 30rpx;
                border-radius: $border-radius-large;
                background-color: #F6F6F6;
                // 输入框
                &--input {
                    width: 84%;
                    padding-left: 20rpx;
                }
                // 清空
                .clear {
                    width: 34rpx;
                    height: 34rpx;
                    padding-left: 20rpx;
                }
            }
        }
        
        .main {
            flex: 1;
            min-height: 0;
            overflow: scroll;
            
            // 师傅
            .master_worker_item {
                padding-bottom: 28rpx;
                margin: 30rpx 24rpx 0 24rpx;
                border-bottom: 1px solid $color-bg;
            }
        }
        
    }    
</style>
