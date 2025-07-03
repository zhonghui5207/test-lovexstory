<template>
    <view class="container">
        <!-- Header Start -->
        <template v-if="menuData.sons.length">
            <scroll-view class="menu" scroll-x="true" >
                <block v-for="(item) in menuData.sons" :key="item.id">
                    <view class="menu--item" :class="{'active': categoryId === item.id}" @click="changeTabs(item)">
                        <view class="flex row-center">
                            <u-image :src="item.image" width="56" height="56"></u-image>
                        </view>
                        <view class="black f-w-500 m-t-14">{{ item.name }}</view>
                    </view>
                </block>
            </scroll-view>
        </template>
        <!-- Header End -->
        
        <!-- Main Start -->
        <view class="main">
            <view class="content">
                <refresh @up="upCallback" :ref="initRefFunc">
                    <goods-card :goodsList="goodsData"></goods-card>
                 </refresh>
            </view>
        </view>
        <!-- Main End -->
               
    </view>
</template>

<script lang="ts" setup>
    import { ref, reactive } from "vue"
    import { onLoad, onShow, onReady } from "@dcloudio/uni-app";
    import { apiGoodsCategoryLists, apiGoodsLists } from "@/api/store.ts"
    import refresh from "@/components/refresh/index.vue"
    import GoodsCard from "@/components/goods-card/index.vue"
    
    /** Data Start **/
    let current = ref<number>(0)
    let categoryId = ref<number>(0)
    
    let menuData = ref<any>({
        info: {},
        sons: []
    })
    let goodsData = ref<any>([])
    // 下拉组件的Ref
    const refreshRef = ref<InstanceType<typeof refresh> | null>(null)
    /** Data End **/
    
    
    /** Methods Start **/
    /**
     * @param { Object } event
     * @description 切换菜单
     */
    const changeTabs = (event: Event) => {
        // 如果点击同一个2次的话那就是取消当前选择然后选择一级分类的商品
        categoryId.value = event.id === categoryId.value ? menuData.value.info.id : event.id;
        refreshRef.value?.resetParams()
        refreshRef.value?.resetUpScroll()
    }
    /**
     * @param { Object } params
     * @return { Promise } 
     * @description 获取分类列表
     */
    const initGoodsCategoryLists = async (): Promise<void> => {
        try {
            const res: any = await apiGoodsCategoryLists({ id: categoryId.value })
            uni.setNavigationBarTitle({
                title: res.info.name
            })
            menuData.value = res;
        } catch (err) {
            console.log(err)
        }
    }
    /**
     * @param { Object } params
     * @return { Promise } 
     * @description 获取商品列表
     */
    const upCallback = async (param: any): Promise<void> => {
        try{
            const res: any = await apiGoodsLists({
                page_no: param.num,
                page_size: param.size,
                category_id: categoryId.value,
            })
            if (param.num === 1) goodsData.value = []
            goodsData.value = [...goodsData.value, ...res.lists]
            refreshRef.value.endSuccess(res.count)
        }catch(e){
            console.log('下拉加载', e)
            refreshRef.value.endErr()
        }
    }
    /**
     * @param { Object } 下拉组件实例
     * @return { void } 
     * @description 获取下拉组件实例数据
     */
    const initRefFunc = (el: Event) => {
        if ( el == null ) return
        refreshRef.value = el
    }
    /** Methods End **/
    
    
    /** Life Cycle Start **/
    onLoad((options) => {
        categoryId.value = options?.id * 1 || 0
        initGoodsCategoryLists()
    })
    /** Life Cycle End **/
</script>

<style lang="scss">
    .container {
        display: flex;
        height: 100vh;
        overflow: hidden;
        flex-direction: column;
        
        .menu {
            height: 188rpx;
            white-space: nowrap;
            box-sizing: border-box;
            padding: 20rpx 0 20rpx 24rpx;
            
            &--item {
                width: 160rpx;
                height: 148rpx;
                padding: 20rpx 0;
                margin-right: 20rpx;
                display: inline-block;
                text-align: center;
                font-size: $font-size-sm;
                border-radius: $border-radius-large;
                background-color: #FFFFFF;
            }
            .active {
                color: $color-primary;
            }
        }
        
        .main {
            flex: 1;
            min-height: 0;
            overflow: scroll;
            .content {
                height: 100%;
                padding: 0 24rpx;
            }
        }
    }
    
</style>
