<template>
    <block v-for="goodsItem in goodsList" :key="goodsItem.id">
        <view class="goods-item bg-white" @click="goPage(goodsItem.id)">
            <u-image :src="goodsItem.image" width="340" height="340"></u-image>
            <view class="goods-name line-1 p-l-20 p-t-20">
                {{ goodsItem.name }}
            </view>
            <view class="p-l-20 m-t-10">
                <price
                    :price="goodsItem?.price"
                    :desc="goodsItem.unit_desc || goodsItem?.unit_name"
                ></price>
            </view>
        </view>
    </block>
</template>

<script lang="ts" setup>
import Price from '@/components/price/index.vue'

/** Props Start **/
const props = withDefaults(
    defineProps<{
        goodsList?: any //商品数据
    }>(),
    {
        goodsList: []
    }
)
/** Props End **/

/** Methods Start **/
/**
 * @param { string } url
 * @return { void }
 * @description 跳转到商品
 */
const goPage = (id: number | string) => {
    uni.navigateTo({
        url: `/pages/goods/index?id=${id}`
    })
}
/** Methods End **/
</script>

<style lang="scss" scoped>
.goods-item {
    width: 340rpx;
    height: 480rpx;
    display: inline-block;
    overflow: hidden;
    border-radius: 14rpx;
    margin-top: 20rpx;
    margin-right: 20rpx;

    .goods-name {
        width: 300rpx;
        font-size: $font-size-md;
        color: $color-text-deep;
    }
}
.goods-item:nth-child(2n) {
    margin-right: 0;
}
</style>
