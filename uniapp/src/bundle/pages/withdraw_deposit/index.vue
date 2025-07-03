<template>
    <view class="user-withdraw">
        <view class="balance-withdrawal-card">
            <tabs
                :current="withdrawCurrent"
                @change="withdrawChange"
                height="120"
                bar-width="80"
                :is-scroll="true"
            >
                <tab v-for="(item, index) in withdrawConfig.type" :key="index" :name="item.name">
                </tab>
            </tabs>
        </view>

        <!-- 钱包余额 / 微信钱包 -->
        <view class="wallet-balance-card">
            <view class="wallet-balance-input flex">
                <text class="text-[46rpx] leading-[100rpx]">¥</text>
                <input class="flex-1" placeholder="0.00" type="digit" v-model="formData.money" />

                <view class="withdrawal-text">
                    <view
                        class="all-withdrawal"
                        @click="formData.money = withdrawConfig.user_earnings"
                    >
                        全部提现
                    </view>
                    <view class="can-withdrawal">
                        可提现余额 ¥ {{ withdrawConfig.user_earnings }}
                    </view>
                </view>
            </view>

            <view class="wallet-balance-tips" v-if="withdrawCurrent != 0">
                提示：提现需要扣除服务费{{ withdrawConfig.percentage }}%
            </view>
        </view>

        <!-- 微信收款码 -->
        <view class="payment-code-card" v-show="formData.type == 4">
            <u-form :model="formData" ref="uForm" label-width="150rpx">
                <u-form-item label="微信账号">
                    <u-input v-model="formData.account" placeholder="请输入微信账号" />
                </u-form-item>

                <u-form-item label="真实姓名">
                    <u-input v-model="formData.real_name" placeholder="请输入真实姓名" />
                </u-form-item>

                <u-form-item label="备注">
                    <u-input v-model="formData.apply_remark" placeholder="请输入备注(选填)" />
                </u-form-item>
            </u-form>

            <view class="mt-[20rpx] flex">
                <view> 微信收款码 </view>
                <uploader v-model="formData.money_qr_code" :maxUpload="1" image-fit="aspectFill" />
            </view>
        </view>

        <!-- 支付宝收款码 -->
        <view class="payment-code-card" v-show="formData.type == 5">
            <u-form :model="formData" ref="uForm" label-width="150rpx">
                <u-form-item label="支付宝账号">
                    <u-input v-model="formData.account" placeholder="请输入支付宝账号" />
                </u-form-item>

                <u-form-item label="真实姓名">
                    <u-input v-model="formData.real_name" placeholder="请输入真实姓名" />
                </u-form-item>

                <u-form-item label="备注">
                    <u-input v-model="formData.apply_remark" placeholder="请输入备注(选填)" />
                </u-form-item>
            </u-form>

            <view class="mt-[20rpx] flex">
                <view class=""> 支付宝收款码 </view>
                <uploader
                    v-model="formData.money_qr_code"
                    :maxUpload="1"
                    deletable
                    image-fit="aspectFill"
                />
            </view>
        </view>

        <!-- 银行卡 -->
        <view class="payment-code-card" v-show="formData.type == 3">
            <u-form :model="formData" ref="uForm" label-width="150rpx">
                <u-form-item label="银行卡账号">
                    <u-input v-model="formData.account" placeholder="请输入银行卡账号" />
                </u-form-item>

                <u-form-item label="持卡人姓名">
                    <u-input v-model="formData.real_name" placeholder="请输入持卡人姓名" />
                </u-form-item>

                <u-form-item label="提现银行">
                    <u-input v-model="formData.bank" placeholder="请输入提现银行" />
                </u-form-item>

                <u-form-item label="银行支行">
                    <u-input v-model="formData.subbank" placeholder="如：荔湾支行" />
                </u-form-item>

                <u-form-item label="备注">
                    <u-input v-model="formData.apply_remark" placeholder="请输入备注(选填)" />
                </u-form-item>
            </u-form>
        </view>

        <view class="mt-[30rpx]">
            <u-button
                @click="onConfirmWithdraw()"
                :ripple="true"
                :hair-line="false"
                shape="circle"
                type="primary"
                hover-class="none"
            >
                确认提现
            </u-button>

            <view class="withdrawal-record" @click="goPage('/bundle/pages/withdraw_record/index')">
                提现记录
            </view>
        </view>
    </view>
</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue'
import { onShow } from '@dcloudio/uni-app'
import { userWithdrawConfig, userWithdrawApply } from '@/api/wallet'
import uploader from '@/components/uploader/index.vue'

const withdrawCurrent = ref<any>(0)
// 提现配置数据
const withdrawConfig = ref<any>({
    user_earnings: '',
    max_withdraw: '',
    min_withdraw: '',
    percentage: '',
    type: []
})

// 提现申请数据
const formData = reactive<any>({
    type: 1, // 是 类型 1钱包余额 2微信零钱 3银行卡 4微信收款码 5支付宝收款码
    money: '', // 是 提现金额
    bank: '', // 否 type=3时需要 银行
    subbank: '', // 否 type=3时需要 支行
    account: '', // 否 type=3时需要 账号
    real_name: '', // 否 type=3时需要 持卡人姓名
    money_qr_code: [],
    apply_remark: '' // 否 提现备注
})

const withdrawChange = (index: number) => {
    withdrawCurrent.value = index
    formData.type = withdrawConfig.value.type[index].value
}

// 获取提现配置
const getWithdrawConfig = async () => {
    const data = await userWithdrawConfig()
    withdrawConfig.value = data
}

// 提现
const onConfirmWithdraw = async () => {
    try {
        if (formData.money == '') {
            uni.$u.toast('请输入提现金额')
            return
        }

        if (formData.type == 4 || formData.type == 5) {
            formData.money_qr_code = formData.money_qr_code[0]
        }
        // if (formData.type == 5) {
        //   formData.moneyQrCode = formData.moneyQrCode?.toString();
        // }
        const { id } = await userWithdrawApply(formData)
        uni.$u.toast('提交成功')
        setTimeout(() => {
            formData.money = ''
            goPage(`/bundle/pages/withdraw_record/index`)
        }, 1000)
    } catch (error) {
        console.log('申请提现失败', error)
    }
}

// 跳转页面
const goPage = (url: any) => {
    uni.$u.route(url)
}

onShow(() => {
    getWithdrawConfig()
})
</script>

<style lang="scss" scoped>
.user-withdraw {
    padding: 15rpx 30rpx;

    // tab栏
    .balance-withdrawal-card {
        background-color: #fff;
        border-radius: 20rpx;
        padding: 10rpx;
    }

    // 收款码/银行卡
    .payment-code-card {
        background-color: #fff;
        border-radius: 20rpx;
        margin-top: 20rpx;
        padding: 0 36rpx 30rpx 36rpx;

        .u-list-item.data-v-f8c23944 {
            background-color: #fff !important;
            margin: 0;
            border: 1rpx dashed #ccc;
            margin-top: 20rpx;
            padding-top: 20rpx;
        }
    }

    // 钱包余额 / 微信钱包
    .wallet-balance-card {
        background-color: #fff;
        border-radius: 20rpx;
        margin-top: 20rpx;
        padding: 35rpx 0 66rpx 66rpx;

        .wallet-balance-input {
            margin-top: 20rpx;
            margin-right: 66rpx;

            input {
                height: 94rpx;
                text-align: left;
                font-size: 66rpx;
                margin-left: 30rpx;
            }

            border-bottom: 1rpx solid #ebebeb;

            .withdrawal-text {
                font-size: 24rpx;

                .all-withdrawal {
                    color: $u-type-primary;
                    display: subgrid;
                    display: flex;
                    justify-content: flex-end;
                    padding-bottom: 10rpx;
                }

                .can-withdrawal {
                    color: #999;
                }
            }
        }

        .wallet-balance-tips {
            margin-top: 30rpx;
            font-size: 24rpx;
            color: #999;
            font-weight: 400;
        }
    }

    .withdrawal-record {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 28rpx;
        color: #666;
        margin-top: 40rpx;
    }
}
</style>
