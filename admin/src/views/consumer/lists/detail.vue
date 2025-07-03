<template>
    <div>
        <el-card shadow="never" class="!border-none">
            <el-page-header content="用户详情" @back="$router.back()" />
        </el-card>

        <el-card
            shadow="never"
            class="mt-[20px] card !border-none"
            :body-style="{ padding: '20px 0px' }"
        >
            <template #header>
                <span class="font-22 black f-w-500">基本信息</span>
            </template>

            <div class="header flex">
                <div>
                    <div class="mb-[10px] ml-[15px] text-tx-regular">用户头像</div>
                    <el-image
                        style="width: 80px; height: 80px; border-radius: 50%"
                        :src="userInfo.avatar"
                        :preview-src-list="[userInfo.avatar]"
                        :fit="'cover'"
                    >
                    </el-image>
                </div>
                <div class="header--item">
                    <div class="text-tx-regular">账户余额</div>
                    <div class="mt-[24px] flex items-center">
                        <span class="mr-[10px]">{{ userInfo.total_funds }}</span>
                    </div>
                </div>
                <div class="header--item">
                    <div class="text-tx-regular">可用余额</div>
                    <div class="mt-[24px] flex items-center">
                        <span class="mr-[10px]">{{ userInfo.user_money }}</span>
                        <ls-user-change
                            title="可用余额调整"
                            :value="userInfo.user_money"
                            :userId="userInfo.id"
                            @refresh="initUserInfoFunc"
                            v-perms="['user.detail/distributeMoney']"
                        >
                            <template #trigger>
                                <el-button link type="primary">调整</el-button>
                            </template>
                        </ls-user-change>
                    </div>
                </div>
                <div class="header--item">
                    <div class="text-tx-regular">优惠券</div>
                    <div class="mt-[24px] flex items-center">
                        <span class="mr-[10px]">{{ userInfo.coupon_num }}</span>

                        <el-button
                            link
                            type="primary"
                            @click="adjustCoupon(userInfo.id)"
                            v-perms="['user.detail/distributeCoupons']"
                            >发放优惠券</el-button
                        >
                    </div>
                </div>
                <div class="header--item">
                    <div class="text-tx-regular">可提现金额</div>
                    <div class="mt-[24px] flex items-center">
                        <span class="mr-[10px]">{{ userInfo.user_earnings }}</span>

                        <el-button
                            link
                            type="primary"
                            @click="adjustWithdraw(userInfo.id)"
                            v-perms="['user.detail/withDraw']"
                            >调整</el-button
                        >
                    </div>
                </div>
            </div>

            <el-form label-position="right" label-width="100px" :model="userInfo" class="mt-[20px]">
                <el-form-item label="用户编号:">
                    {{ userInfo.sn || '-' }}
                </el-form-item>
                <el-form-item label="用户昵称:">
                    {{ userInfo.nickname || '-' }}
                </el-form-item>
                <el-form-item label="账号:">
                    <div class="flex">
                        {{ userInfo.account || '-' }}
                        <popover-input
                            @confirm="handleUserInfoEdit($event, 'account')"
                            v-model="userInfo.account"
                            type="text"
                            v-perms="['user.user/edit']"
                        >
                            <el-icon
                                style="vertical-align: middle"
                                color="#4A5DFF"
                                class="ml-[20px]"
                            >
                                <edit-pen />
                            </el-icon>
                        </popover-input>
                    </div>
                </el-form-item>
                <el-form-item label="真实姓名:">
                    <div class="flex">
                        {{ userInfo.real_name || '-' }}
                        <popover-input
                            @confirm="handleUserInfoEdit($event, 'real_name')"
                            v-model="userInfo.real_name"
                            type="text"
                            v-perms="['user.user/edit']"
                        >
                            <el-icon
                                style="vertical-align: middle"
                                color="#4A5DFF"
                                class="ml-[20px]"
                            >
                                <edit-pen />
                            </el-icon>
                        </popover-input>
                    </div>
                </el-form-item>
                <el-form-item label="性别:">
                    <div class="flex">
                        <!-- {{ userInfo.sex === 1 ? '男' : '女' || '-' }} -->
                        {{ userInfo.sex === 1 ? '男' : userInfo.sex == 2 ? '女' : '-' }}
                        <popover-user
                            @confirm="handleUserInfoEdit($event, 'sex')"
                            changeType="sex"
                            v-model="userInfo.sex"
                            v-perms="['user.user/edit']"
                        >
                            <el-icon
                                style="vertical-align: middle"
                                color="#4A5DFF"
                                class="ml-[10px]"
                            >
                                <edit-pen />
                            </el-icon>
                        </popover-user>
                    </div>
                </el-form-item>
                <el-form-item label="联系电话:">
                    <div class="flex">
                        {{ userInfo.mobile || '-' }}
                        <popover-input
                            @confirm="handleUserInfoEdit($event, 'mobile')"
                            v-model="userInfo.mobile"
                            type="text"
                            v-perms="['user.user/edit']"
                        >
                            <el-icon
                                style="vertical-align: middle"
                                color="#4A5DFF"
                                class="ml-[10px]"
                            >
                                <edit-pen />
                            </el-icon>
                        </popover-input>
                    </div>
                </el-form-item>
                <el-form-item label="上级分销商" @click="adjustSuperior">
                    {{ userInfo.first_leader_name }}

                    <el-button
                        :disabled="userInfo.isClose"
                        type="primary"
                        link
                        v-perms="['user.user/edit']"
                    >
                        <icon name="el-icon-EditPen" />
                    </el-button>
                </el-form-item>
                <el-form-item label="注册来源:">
                    {{ userInfo.channel || '-' }}
                </el-form-item>
                <el-form-item label="注册时间:">
                    {{ userInfo.create_time || '-' }}
                </el-form-item>
                <el-form-item label="最近登录时间:">
                    {{ userInfo.login_time || '-' }}
                </el-form-item>
            </el-form>
        </el-card>

        <el-card shadow="never" class="mt-[15px] !border-none" style="padding: 0 20px">
            <template #header>
                <span class="f-w-500 nr">商品信息</span>
            </template>
            <el-table :data="userInfo.study_course_lists" style="width: 100%">
                <el-table-column property="course_id" label="id" width="120" />
                <el-table-column label="课程名称" min-width="240">
                    <template #default="scope">
                        <div class="goods-box flex items-center">
                            <div>
                                <el-image
                                    style="width: 60px; height: 60px"
                                    :src="scope.row.cover"
                                    :preview-src-list="[scope.row.cover]"
                                    :hide-on-click-modal="true"
                                    :preview-teleported="true"
                                    :fit="'contain'"
                                ></el-image>
                            </div>
                            <div
                                class="goods-name text_hidden"
                                @click="jumpCourse(scope.row.course_id, scope.row.type)"
                                style="cursor: pointer"
                            >
                                {{ scope.row.name }}
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="是否免费" min-width="100">
                    <template #default="scope">
                        <div>
                            <el-tag class="ml-2" v-if="scope.row.free" type="warning">收费</el-tag>
                            <el-tag class="ml-2" v-else type="success">免费</el-tag>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column property="create_time" label="下单时间" min-width="100" />
                <el-table-column label="操作" min-width="200">
                    <template #default>
                        <div>{{ '-' }}</div>
                    </template>
                </el-table-column>
            </el-table>
        </el-card>
    </div>
    <ls-user-coupon
        v-if="popShow"
        ref="couponRef"
        @close="
            () => {
                initUserInfoFunc()
                popShow = false
            }
        "
    ></ls-user-coupon>
    <LsUserWithdraw
        :value="userInfo.user_earnings"
        :userId="userInfo.id"
        @confirm="
            () => {
                initUserInfoFunc()
                popShow = false
            }
        "
        v-if="popShow"
        ref="withdrawRef"
    ></LsUserWithdraw>
    <superiorAdjustPop
        ref="superiorAdjustPopRef"
        @close="
            () => {
                initUserInfoFunc()
                popShow = false
            }
        "
    ></superiorAdjustPop>
</template>

<script lang="ts" setup>
import { apiUserDetail, apiSetUserInfo, apiRecycleCourse } from '@/api/user/user'
import PopoverUser from '../components/popover-user.vue'
import LsUserChange from '../components/user-change.vue'
import LsUserCoupon from '../components/user-coupon.vue'
import LsUserWithdraw from '../components/earning-adjust.vue'
import superiorAdjustPop from '../components/superiorAdjustPop.vue'
import feedback from '@/utils/feedback'

interface UserInfoObj {
    id: number //	int	id
    sn: string //	string	用户编码
    nickname: string //	string	用户昵称
    account: string //账号
    avatar: string //	string	用户头像
    mobile: string //	string	手机号码
    user_money: string //  string  用户余额
    sex: number //	int	用户性别
    real_name: string //	string	真实姓名
    login_time: string //	string	最近登录时间
    register_source: number //	int	注册来源
    source_desc: string //	string	来源描述
    create_time: string //	string	注册时间
    study_course_lists: any //  用户已加入｜｜购买的课程
    coupon_num: number
    user_earnings: number
    first_leader_name: string
}
//弹框ref
const couponRef = shallowRef()
const withdrawRef = shallowRef()
const superiorAdjustPopRef = shallowRef()
const popShow = ref(false)

const route = useRoute()
const router = useRouter()
const userInfo = ref<UserInfoObj>({
    id: 0,
    sn: '',
    nickname: '',
    account: '',
    avatar: '',
    mobile: '',
    user_money: '',
    sex: 0,
    real_name: '',
    login_time: '',
    register_source: 0,
    source_desc: '',
    create_time: '',
    study_course_lists: [1],
    coupon_num: 0,
    user_earnings: 0,
    first_leader_name: ''
})

//跳转课程目录页面
const jumpCourse = (id: number, type: number) => {
    let path = '/course/course/imageText/catalogue'
    if (type == 2) {
        path = '/course/course/audio/catalogue'
    }
    if (type == 3) {
        path = '/course/course/video/catalogue'
    }
    router.push({ path, query: { id, type } })
}

//初始化用户数据
const initUserInfoFunc = async (): Promise<void> => {
    const res: any = await apiUserDetail({ id: route.query.id })
    userInfo.value = res
}

//编辑用户信息
const handleUserInfoEdit = async (event: string, type: string): Promise<void> => {
    await apiSetUserInfo({
        id: userInfo.value.id,
        field: type,
        value: event
    })
    initUserInfoFunc()
}

//调整优惠券
const adjustCoupon = async (id: any) => {
    popShow.value = true
    await nextTick()
    couponRef.value.open(id)
}

//调整提现金额
const adjustWithdraw = async (id: any) => {
    popShow.value = true
    await nextTick()
    withdrawRef.value.open({ id })
}

//调整上级
const adjustSuperior = async () => {
    popShow.value = true
    await nextTick()
    superiorAdjustPopRef.value.open(userInfo.value)
}

onMounted(() => {
    initUserInfoFunc()
})
</script>

<style lang="scss">
.card {
    padding: 0 20px;

    .header {
        height: 160px;
        padding: 30px 70px;
        box-sizing: border-box;
        background-color: var(--el-bg-color-page);

        &--item {
            padding: 10px 0;
            margin-left: 160px;
        }
    }
}
</style>
