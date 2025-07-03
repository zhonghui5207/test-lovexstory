<template>
    <view class="charge">
        <!-- Header Start -->
        <view class="charge-content">
            <view class="lighter text-lg">充值金额</view>
            <view class="input">
                <!-- 充值输入 -->
                <input type="text" @input="handleCheckMoney" v-model="money" placeholder="0.00" />
            </view>
            <view class="mt-[25rpx] text-xs text-muted">
                当前可用余额<text class="primary"> ¥{{ availableMoney }}</text>
            </view>
        </view>
        <!-- Header End -->

        <!-- Main Start -->
        <!-- 充值按钮 -->
        <view class="charge-btn">
            <u-button
                @click="handleCharge()"
                :ripple="true"
                shape="circle"
                :hair-line="false"
                type="primary"
                >立即充值</u-button
            >
        </view>

        <!-- 推荐充值 -->
        <!-- <view class="recommend-charge m-t-25">
            <view class="text-2xl font-medium mb-[40rpx]">推荐充值</view>
            <view
                class="recommend-item flex-col justify-center"
                @click="handleCharge(item.id)"
                v-for="(item, index) in chargeTemplate"
                :key="index"
            >
                <view class="text-2xl font-medium black"
                    >{{ item.money }}<text class="font-md">元</text></view
                >
                <view class="text-xs mt-[10rpx] normal" v-if="item.tips">{{ item.tips }}</view>
            </view>
        </view> -->
        <!-- Main End -->

        <!-- Footer Start -->
        <!-- 充值记录 -->
        <view
            class="record text-muted text-sm flex justify-center"
            @click="goPage('/bundle/pages/user_charge_record/index')"
        >
            充值记录</view
        >
        <!-- Footer End -->

        <u-popup class="pay-popup" v-model="showPopup" round mode="center" borderRadius="10">
            <view class="content bg-white">
                <!-- <image class="img-icon" :src="'../../static/images/recharge_success.png'"></image> -->
                <image class="img-icon" src="@/bundle/static/images/recharge_success.png"></image>
                <view class="text-2xl font-medium m-[30rpx]">充值完成</view>
                <view class="p-[40rpx]">
                    <u-button
                        @click="showPopup = false"
                        :ripple="true"
                        shape="circle"
                        :hair-line="false"
                        type="primary"
                        >好的，谢谢</u-button
                    >
                </view>
            </view>
        </u-popup>
    </view>
</template>

<script lang="ts" setup>
import { ref, reactive, nextTick } from 'vue'
import { onShow } from '@dcloudio/uni-app'
import { apiUserWallet, apiChargeTemplateLists, apiChargeMoney } from '@/api/user.ts'

/** Data Start **/
const money = ref<string>('0')
const availableMoney = ref<string>('0.00')
const chargeTemplate = ref([])
const showPopup = ref<booelan>(false)
/** Data End **/

/** Methods Start **/
/**
 * @return { void }
 * @description 获取钱包数据
 */
const initWalletData = async (): Promise<void> => {
    try {
        const res = await apiUserWallet()
        availableMoney.value = res.user_money
    } catch (err) {
        //TODO handle the exception
        console.log('获取钱包数据报错=>', err)
    }
}
/**
 * @return { void }
 * @description 获取充值模板
 */
const initChargeTemplateLists = async (): Promise<void> => {
    try {
        chargeTemplate.value = await apiChargeTemplateLists()
    } catch (err) {
        //TODO handle the exception
        console.log('充值模版报错=>', err)
    }
}
/**
 * @return { void }
 * @description 处理金额
 */
const handleCheckMoney = async (e: Event) => {
    let val = e.target.value.replace(/(^\s*)|(\s*$)/g, '')
    if (!val) {
        money.value = ''
        return
    }
    const reg = /[^\d.]/g
    // 只能是数字和小数点，不能是其他输入
    val = val.replace(reg, '')
    // // 保证第一位只能是数字，不能是点
    val = val.replace(/^\./g, '')
    // // 小数只能出现1位
    val = val.replace('.', '$#$').replace(/\./g, '').replace('$#$', '.')
    // // 小数点后面保留2位
    val = val.replace(/^(\-)*(\d+)\.(\d\d).*$/, '$1$2.$3')
    await nextTick()
    money.value = val
}
/**
 * @param { money } 充值金额
 * @return { void }
 * @description 点击充值
 */
const handleCharge = async (id: string | number = ''): Promise<void> => {
    try {
        if (id != '') money.value = ''
        const { order_id, from } = await apiChargeMoney({ money: money.value, template_id: id })
        const params = {
            order_id,
            from
        }
        goPage(`/pages/order_buy/index?params=${JSON.stringify(params)}`)
    } catch (e) {
        //TODO handle the exception
        console.log('充值报错=>', err)
    }
}
/**
 * @param { url } 跳转链接
 * @return { void }
 * @description 跳转页面
 */
const goPage = (url: string) => {
    uni.navigateTo({
        url: url
    })
}
/** Methods End **/

/** Life Cycle Start **/
onShow(() => {
    uni.$on('payment', (res) => {
        money.value = ''
        if (res == undefined) showPopup.value = true
    })

    initWalletData()
    initChargeTemplateLists()
})
/** Life Cycle End **/
</script>

<style lang="scss">
.charge {
    padding: 30rpx;

    // 充值内容
    .charge-content {
        width: 100%;
        height: 320rpx;
        padding: 40rpx;
        border-radius: 20rpx;
        background-color: #ffffff;

        .input {
            padding: 24rpx 0;
            font-size: 46rpx;
            border-bottom: 1rpx solid #e5e5e5;

            input {
                padding-left: 10rpx;
                font-size: 66rpx;
                height: 80rpx;
            }
        }
    }

    // 充值按钮
    .charge-btn {
        padding: 40rpx 0 60rpx 0;
    }

    // 推荐充值
    .recommend-item {
        width: 214rpx;
        height: 160rpx;
        padding: 30rpx;
        float: left;
        text-align: center;
        border-radius: 20rpx;
        margin-right: 24rpx;
        margin-bottom: 24rpx;
        background-color: #ffffff;
    }

    .recommend-item:nth-child(3n-2) {
        margin-right: 0;
    }

    // 充值记录
    .record {
        width: 100%;
        left: 0;
        bottom: 80rpx;
        box-sizing: border-box;
        position: absolute;
    }

    .pay-popup {
        .content {
            padding: 40rpx 20rpx;
            padding-bottom: 0;
            text-align: center;
            width: 560rpx;
            border-radius: 20rpx;
        }

        .img-icon {
            width: 168rpx;
            height: 118rpx;
            display: inline-block;
        }
    }
}
</style>
