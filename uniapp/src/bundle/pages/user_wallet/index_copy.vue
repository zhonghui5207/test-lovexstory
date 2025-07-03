<template>
    <view class="container">
        <!-- Header Start -->
        <view class="header bg-white">
            <view class="card text-white flex justify-between items-center">
                <view class="pl-[30rpx]">
                    <text class="text-xs">我的余额</text>
                    <!-- 我的余额 -->
                    <view class="text-5xl pt-[20rpx]">{{ walletInfo.user_money || '0.00' }}</view>
                </view>
                <!-- 充值按钮 -->
                <navigator url="/bundle/pages/user_charge/index" hover-class="none">
                    <view v-if="walletInfo.recharge_open" class="charge-btn text-lg font-medium"
                        >去充值</view
                    >
                </navigator>
            </view>
        </view>
        <!-- Header End -->

        <!-- Main Start -->
        <tabs
            :current="current"
            @change="handleChange"
            height="100"
            bar-width="60"
            :is-scroll="false"
        >
            <tab v-for="(item, index) in tabList" :key="index" :name="item.name">
                <account-log-list :type="item.type"></account-log-list>
            </tab>
        </tabs>
        <!-- Main End -->
    </view>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { apiUserWallet } from '@/api/user'
import { AccountLogEnum } from '@/utils/enum'
import AccountLogList from './components/account-log-list.vue'

/** Data Start **/
interface WalletObj {
    user_money: string
    recharge_open: number
}
/** Data End **/

/** Data Start **/
const walletInfo = ref<WalletObj>({
    user_money: '',
    recharge_open: 0
})
const tabList = ref([
    {
        name: '全部',
        type: AccountLogEnum.ALL
    },
    {
        name: '收入',
        type: AccountLogEnum.INCOME
    },
    {
        name: '支出',
        type: AccountLogEnum.EXPEND
    }
])
const current = ref<number>(0)
/** Data End **/

/** Methods Start **/
/**
 * @return { void } Promise
 * @description 初始化钱包
 */
const initWallet = async (): Promise<void> => {
    try {
        walletInfo.value = await apiUserWallet()
    } catch (e) {
        //TODO handle the exception
        console.log('初始化钱包请求=>', e)
    }
}
/**
 * @description 切换标签
 */
const handleChange = (index: number) => {
    current.value = Number(index)
}
/** Methods End **/

/** Life Cycle Start **/
initWallet()
/** Life Cycle End **/
</script>

<style lang="scss">
.container {
    display: flex;
    height: 100vh;
    overflow: hidden;
    flex-direction: column;
}

.header {
    padding: 48rpx 30rpx 0 30rpx;
    .card {
        height: 240rpx;
        margin-bottom: 12rpx;
        border-radius: 14rpx;
        background-color: $color-primary;
        // 充值按钮
        .charge-btn {
            padding: 16rpx 42rpx;
            color: $color-primary;
            background-color: #e9f1fe;
            border-radius: 37rpx 0 0 37rpx;
        }
    }
}
</style>
