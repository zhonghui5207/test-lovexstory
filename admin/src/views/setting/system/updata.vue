<!-- 系统更新 -->
<template>
    <div class="system-update">
        <el-card class="!border-none" shadow="never">
            <el-alert class="xxl" type="warning" :closable="false" show-icon>
                <template v-slot:title>
                    <div class="iconSize">温馨提示：</div>
                    <div class="iconSize flex">
                        1.版本更新需要逐个版本更新，
                        <div class="a-key">
                            更新前请备份好系统和数据库，更新成功后需要强制刷新站点；
                        </div>
                    </div>
                    <div class="iconSize">2.系统没有做二次开发，可以直接选择在线更新功能；</div>
                    <div class="iconSize">
                        3.系统已做二次开发，进行了功能修改，请谨慎选择在线更新功能，推荐以更新包的形式手动更新；
                    </div>
                    <!-- <div class="iconSize">
                        4.由于更换新域名原因，1.3.1及以下版本，需修改server/app/adminapi/logic/settings/system/UpgradeLogic.php文件中的BASE_URL为https://server.likeshop.cn
                        否则系统更新列表会报错。1
                    </div> -->
                </template>
            </el-alert>
        </el-card>

        <el-card class="!border-none mt-4" shadow="never" v-loading="pager.loading">
            <div class="ls-card mt-[16rpx]">
                <el-table
                    :data="pager.lists"
                    style="width: 100%"
                    size="mini"
                    v-loading="false"
                    :header-cell-style="{ background: '#f5f8ff' }"
                >
                    <el-table-column prop="publish_time" label="发布日期" />
                    <el-table-column label="版本号">
                        <template #default="scope">
                            <div class="flex">
                                <el-tag
                                    v-if="scope.$index == 0 && pager.page == 1"
                                    size="small"
                                    type="success"
                                    effect="plain"
                                    round
                                >
                                    new</el-tag
                                >
                                <div class="m-l-5">{{ scope.row.version_no }}</div>
                            </div>
                            <div class="">
                                {{ scope.row.version_str }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="" label="版本内容">
                        <template #default="scope">
                            <div v-for="(item, index) in scope.row.add" :key="index">
                                {{ item }}
                            </div>
                            <div v-for="(item, index) in scope.row.optimize" :key="index">
                                {{ item }}
                            </div>
                            <div v-for="(item, index) in scope.row.repair" :key="index">
                                {{ item }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="notice" label="注意事项">
                        <template v-slot="scope">
                            <div class="" v-for="(item, index) in scope.row.notice" :key="index">
                                {{ item }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="操作" min-width="200px">
                        <template v-slot="scope">
                            <div class="operation flex flex-wrap">
                                <el-button
                                    v-if="scope.row.able_update == 1"
                                    class="m-r-10"
                                    type="primary"
                                    size="small"
                                    @click="onOuterVisible(scope.row.id)"
                                    >一键更新
                                </el-button>
                                <el-button
                                    v-if="scope.row.integral_package_link"
                                    class="m-r-10"
                                    type=""
                                    size="small"
                                    @click="toPackage(scope.row.id, 6)"
                                    >下载完整安装包
                                </el-button>
                                <el-button
                                    v-if="scope.row.package_link"
                                    class="m-r-10"
                                    type=""
                                    size="small"
                                    @click="toPackage(scope.row.id, 2)"
                                    >下载服务端更新包</el-button
                                >
                                <el-button
                                    v-if="scope.row.pc_package_link"
                                    class="m-r-10"
                                    type=""
                                    size="small"
                                    @click="toPackage(scope.row.id, 3)"
                                    >下载pc端更新包</el-button
                                >
                                <el-button
                                    v-if="scope.row.uniapp_package_link"
                                    class="m-r-10"
                                    type=""
                                    size="small"
                                    @click="toPackage(scope.row.id, 4)"
                                    >下载uniapp更新包</el-button
                                >
                                <el-button
                                    v-if="scope.row.web_package_link"
                                    class=""
                                    type=""
                                    size="small"
                                    @click="toPackage(scope.row.id, 5)"
                                >
                                    下载后台前端更新包</el-button
                                >
                                <el-button
                                    v-if="scope.row.kefu_package_link"
                                    class=""
                                    type=""
                                    size="small"
                                    @click="toPackage(scope.row.id, 8)"
                                >
                                    下载客服更新包</el-button
                                >
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <!-- 底部分页栏  -->
            <div class="flex mt-4 justify-end">
                <pagination v-model="pager" @change="getSystemUpgradeLists" />
            </div>
        </el-card>

        <!-- 一键更新弹出框 -->
        <el-dialog class="dialog" title="" v-model="outerVisible" width="50%" center>
            <el-dialog class="dialogTwo" width="50%" v-model="innerVisible" append-to-body center>
                <el-dialog
                    class="dialogThree"
                    width="50%"
                    v-model="threeVisible"
                    append-to-body
                    center
                    :close-on-click-modal="false"
                    :close-on-press-escape="false"
                >
                    <div
                        style="height: 200px; text-align: center"
                        v-loading="pager.loading"
                        element-loading-text="切勿关闭窗口或刷新页面"
                    >
                        <div>系统更新中，更新完毕后会自行刷新页面</div>
                    </div>
                </el-dialog>
                <div v-if="isSecondaryDev == false" style="height: 200px">
                    <div class="" style="text-align: center">
                        一键更新导致的系统问题，欢迎前往社区反馈，请做好系统备份！
                    </div>
                    <div class="btn-div flex-col" style="width: 160px; margin: auto">
                        <el-button
                            type="primary"
                            @click="confirmUpdate"
                            size="small"
                            style="margin-top: 15px; margin-left: 0"
                            class="mr-3"
                            >确定更新</el-button
                        >
                        <el-button
                            @click="innerVisible = false"
                            size="small"
                            style="margin-top: 15px; margin-left: 0"
                        >
                            取消更新</el-button
                        >
                    </div>
                </div>
                <div v-if="isSecondaryDev == true" style="height: 200px">
                    <div class="" style="text-align: center">
                        <div>二次开发后请谨慎使用一键更新功能！</div>
                        <div>二次开发后一键更新导致的系统问题，官方无法处理，请做好系统备份！</div>
                    </div>
                    <div class="btn-div flex-col" style="width: 160px; margin: auto">
                        <el-button
                            type="primary"
                            @click="confirmUpdate"
                            size="small"
                            style="margin-top: 15px; margin-left: 0"
                            >确定更新</el-button
                        >
                        <el-button
                            @click="innerVisible = false"
                            size="small"
                            style="margin-top: 15px; margin-left: 0"
                        >
                            下载更新包，手动更新</el-button
                        >
                        <el-button
                            @click="innerVisible = false"
                            size="small"
                            style="margin-top: 15px; margin-left: 0"
                        >
                            取消更新</el-button
                        >
                    </div>
                </div>
            </el-dialog>
            <div style="height: 200px">
                <div class="pb-4" style="text-align: center">系统是否进行二次开发</div>
                <div class="flex-col">
                    <el-button type="primary" @click="NotSecondaryDev" size="small"
                        >未做二次开发，直接更新</el-button
                    >
                    <el-button @click="SecondaryDev" size="small">已做二次开发</el-button>
                    <el-button @click="outerVisible = false" size="small">取消更新</el-button>
                </div>
            </div>
        </el-dialog>
    </div>
</template>

<script lang="ts" setup>
import { usePaging } from '@/hooks/usePaging'
import {
    apiSystemUpgradeLists,
    apiSystemUpgradeDownloadPkg,
    apiSystemUpgrade
} from '@/api/setting/system'

// 外层dialog
const outerVisible = ref(false)
// 第二层dialog
const innerVisible = ref(false)
// 第三层dialog
const threeVisible = ref(false)
// 是否二次开发
const isSecondaryDev = ref(true)
// 点击一键更新按钮的版本id
const updateId = ref(0)

const timer = ref<any>(0)

//系统更新列表
const {
    pager,
    getLists: getSystemUpgradeLists,
    resetParams,
    resetPage
} = usePaging({
    fetchFun: apiSystemUpgradeLists
})

// 非完整安装包下载
const toPackage = (id: any, type: any) => {
    if (timer.value) {
        clearTimeout(timer.value)
    }

    timer.value = setTimeout(() => {
        //
        apiSystemUpgradeDownloadPkg({
            id,
            update_type: type // 2:服务端更新包下载，3:pc端更新包下载，4:uniapp更新包下载,5:后台前端更新包下载, 6:完整包更新
        }).then((res) => {
            //
            //
            if (type == 6) {
                window.open(res.line, '_blank')
            } else {
                window.location.href = res.line
            }
        })
    }, 500)
}

// 一键更新请求
const systemUpgrade = () => {
    apiSystemUpgrade({
        id: updateId.value,
        update_type: 1 // 1:一键更新
    })
        .then((res) => {
            getSystemUpgradeLists()
        })
        .finally(() => {
            // this.loading = false
            threeVisible.value = false
            innerVisible.value = false
            outerVisible.value = false
        })
}

// 点击一键更新按钮
const onOuterVisible = (id: any) => {
    outerVisible.value = true
    updateId.value = id
}

// 未做二次开发
const NotSecondaryDev = () => {
    isSecondaryDev.value = false
    innerVisible.value = true
}
// 已做二次开发
const SecondaryDev = () => {
    isSecondaryDev.value = true
    innerVisible.value = true
}

// 点击确定更新
const confirmUpdate = () => {
    threeVisible.value = true
    systemUpgrade()
}

onMounted(() => {
    getSystemUpgradeLists()
})
</script>

<style lang="scss" scoped>
.system-update {
    .dialog {
        :deep(.el-button) {
            margin: auto;
            width: 160px;
            margin-top: 15px;
        }

        .dialogTwo {
            .btn-div {
                :deep(.el-button) {
                    margin: auto;
                    width: 160px;
                    margin-top: 15px;
                }
            }
        }
    }

    .iconSize {
        padding: 3px 0;

        .a-key {
            color: #f56c6c;
        }
    }

    .operation {
        :deep(.el-button) {
            margin-left: 10px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    }
}
</style>
