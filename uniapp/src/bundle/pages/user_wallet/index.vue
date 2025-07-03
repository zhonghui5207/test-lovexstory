<template>
    <view class="user-wallet p-[20rpx]">
        <!-- Header -->
        <view class="p-[24rpx] bg-white header rounded-[14rpx]">
            <view class="bg-primary rounded-[14rpx] text-white text-center py-[50rpx]">
                <view>
                    <view class="pb-[10rpx] text-base">我的余额</view>
                    <!-- 总资产 -->
                    <view class="text-[56rpx] font-medium">
                        {{ wallet.total_money }}
                    </view>
                </view>

                <view class="flex justify-around pt-[20rpx]">
                    <view>
                        <view class="pb-[10rpx] text-base">可用余额(元)</view>
                        <!-- 余额 -->
                        <view class="text-xl font-medium">
                            {{ wallet.user_money }}
                        </view>
                    </view>
                    <view>
                        <view class="pb-[10rpx] text-base">可提现佣金(元)</view>
                        <!-- 佣金 -->
                        <view class="text-xl font-medium">
                            {{ wallet.user_earnings }}
                        </view>
                    </view>
                </view>
            </view>

            <view class="flex w-full mt-[24rpx]">
                <view class="w-[50%] mx-[10rpx]" @click="goPage('/bundle/pages/user_charge/index')">
                    <button class="flex-1 bg-[#ECECEC] text-black">去充值</button>
                </view>
                <view
                    class="flex-1 mx-[10rpx]"
                    @click="goPage('/bundle/pages/withdraw_deposit/index')"
                >
                    <button class="bg-primary flex-1 text-white">提现</button>
                </view>
            </view>
        </view>

        <!-- Main -->
        <view class="flex flex-wrap mt-[20rpx] text-center bg-white rounded-[14rpx] p-[20rpx]">
            <view
                class="w-1/3 py-[20rpx]"
                @click="goPage(`/bundle/pages/balance_detail/index?type=1`)"
            >
                <image
                    class="w-[54rpx] h-[54rpx]"
                    src="@/bundle/static/wallet/icon_balance_detail.png"
                />
                <view class="text">账户明细</view>
            </view>
            <view
                class="w-1/3 py-[20rpx]"
                @click="goPage(`/bundle/pages/balance_detail/index?type=2`)"
            >
                <image
                    class="w-[54rpx] h-[54rpx]"
                    src="@/bundle/static/wallet/icon_commission_detail.png"
                />
                <view class="text">佣金明细</view>
            </view>
            <view
                class="w-1/3 py-[20rpx]"
                @click="goPage('/bundle/pages/user_charge_record/index')"
            >
                <image
                    class="w-[54rpx] h-[54rpx]"
                    src="@/bundle/static/wallet/icon_charge_record.png"
                />
                <view class="text">充值记录</view>
            </view>
            <view class="w-1/3 py-[20rpx]" @click="goPage('/bundle/pages/withdraw_record/index')">
                <image
                    class="w-[54rpx] h-[54rpx]"
                    src="@/bundle/static/wallet/icon_withdraw_record.png"
                />
                <view class="text">提现记录</view>
            </view>
        </view>
    </view>
</template>

<script lang="ts" setup>
import { reactive } from 'vue'
import { walletData } from '@/api/wallet'

import { onShow } from '@dcloudio/uni-app'

const wallet = reactive<any>({
    user_money: '', //可用余额
    user_earnings: '', //可提现佣金
    total_money: '', //总资产
    recharge_open: '' //充值开关 1-开启 0-关闭
})

const initWallet = async (): Promise<void> => {
    try {
        const data = await walletData()
        Reflect.ownKeys(data).map((item: any) => {
            wallet[item] = data[item]
        })
    } catch (e) {
        console.log('初始化钱包请求=>', e)
    }
}

const goPage = (url: string) => {
    uni.$u.route(url)
}

onShow(() => {
    initWallet()
})
</script>

<style lang="scss"></style>
