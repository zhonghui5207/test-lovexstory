<template>
    <div class="workbench">
        <div class="lg:flex">
            <el-card class="!border-none mb-4 lg:mr-4 lg:w-[400px]" shadow="never">
                <template #header>
                    <span class="card-title">版本信息</span>
                </template>
                <div>
                    <div class="flex leading-9">
                        <div class="w-20">平台信息</div>
                        <span> {{ workbenchData.platform.name }}</span>
                    </div>
                    <div class="flex leading-9">
                        <div class="w-20">系统版本</div>
                        <span> {{ workbenchData.platform.version }}</span>
                        <a
                            v-if="workbenchData.platform.newVersion?.result == true"
                            class="text-primary ml-2 flex items-center"
                            ><el-icon><Top /></el-icon>有新版本</a
                        >
                    </div>
                    <div class="flex leading-9">
                        <div class="flex" v-if="appStore.config.document_status == 1">
                            <div>
                                <is-copyright />
                            </div>
                            <a class="ml-3" href="https://www.likeshop.cn/" target="_blank">
                                <el-button type="success" size="small">官网</el-button>
                            </a>
                            <a class="ml-3" href="https://www.likeshop.cn/doc" target="_blank">
                                <el-button type="danger" size="small">开发文档</el-button>
                            </a>
                        </div>
                        <!-- <span> {{ workbenchData.platform.website }}</span> -->
                    </div>
                </div>
            </el-card>
            <el-card class="!border-none mb-4 flex-1" shadow="never">
                <template #header>
                    <div>
                        <span class="card-title">今日数据</span>
                        <span class="text-tx-secondary text-xs ml-4">
                            更新时间：{{ workbenchData.today.now }}
                        </span>
                    </div>
                </template>

                <div class="flex flex-wrap">
                    <div class="w-1/2 md:w-1/4">
                        <div class="leading-10">课程收入</div>
                        <div class="text-6xl">
                            {{ workbenchData.today.today_order_amount }}
                        </div>
                        <div class="text-tx-secondary text-xs">
                            总收入：{{ workbenchData.today.total_order_amount }}
                        </div>
                    </div>
                    <div class="w-1/2 md:w-1/4">
                        <div class="leading-10">课程订单</div>
                        <div class="text-6xl">
                            {{ workbenchData.today.today_order_num }}
                        </div>
                        <div class="text-tx-secondary text-xs">
                            总订单：{{ workbenchData.today.total_order_num }}
                        </div>
                    </div>
                    <div class="w-1/2 md:w-1/4">
                        <div class="leading-10">新增学员</div>
                        <div class="text-6xl">{{ workbenchData.today.today_student }}</div>
                        <div class="text-tx-secondary text-xs">
                            总学员量：{{ workbenchData.today.total_student }}
                        </div>
                    </div>
                    <div class="w-1/2 md:w-1/4">
                        <div class="leading-10">用户访问量</div>
                        <div class="text-6xl">{{ workbenchData.today.today_visitor }}</div>
                        <div class="text-tx-secondary text-xs">
                            总访问量：{{ workbenchData.today.total_visitor }}
                        </div>
                    </div>
                </div>
            </el-card>
        </div>
        <div class="function mb-4">
            <el-card class="flex-1 !border-none" shadow="never">
                <template #header>
                    <span>常用功能</span>
                </template>
                <div class="flex flex-wrap">
                    <div
                        v-for="item in workbenchData.menu"
                        class="md:w-[12.5%] w-1/4 flex flex-col items-center"
                        :key="item"
                    >
                        <router-link :to="item.url" class="mb-3 flex flex-col items-center">
                            <image-contain width="40px" height="40px" :src="item?.image" />
                            <div class="mt-2">{{ item.name }}</div>
                        </router-link>
                    </div>
                </div>
            </el-card>
        </div>
        <div class="md:flex">
            <el-card class="flex-1 !border-none md:mr-4 mb-4" shadow="never">
                <template #header>
                    <span>近7天营业额</span>
                </template>
                <div>
                    <v-charts
                        style="height: 350px"
                        :option="workbenchData.businessOption"
                        :autoresize="true"
                    />
                </div>
            </el-card>
            <el-card class="flex-1 !border-none md:mr-4 mb-4" shadow="never">
                <template #header>
                    <span>近7天访客数</span>
                </template>
                <div>
                    <v-charts
                        style="height: 350px"
                        :option="workbenchData.visitorOption"
                        :autoresize="true"
                    />
                </div>
            </el-card>
        </div>
    </div>
    <LayoutFooter></LayoutFooter>
</template>

<script lang="ts" setup name="workbench">
import { apiWorkbench, checkVersion } from '@/api/app'
import LayoutFooter from '@/layout/components/footer.vue'
import useAppStore from '@/stores/modules/app'

import vCharts from 'vue-echarts'
const appStore = useAppStore()

// 表单数据
const workbenchData: any = reactive({
    platform: {
        version: '', // 版本号
        website: '', // 官网
        newVersion: { result: false, version: '' }
    },
    today: {}, // 今日数据
    menu: [], // 常用功能
    visitor: [], // 访问量
    article: [], // 文章阅读量

    visitorOption: {
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: ['访问量']
        },
        toolbox: {
            show: false,
            feature: {
                mark: { show: true },
                dataView: { show: true, readOnly: false },
                magicType: { show: true, type: ['line', 'bar', 'stack', 'tiled'] },
                restore: { show: true },
                saveAsImage: { show: true }
            }
        },
        calculable: true,
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
        },

        yAxis: [
            {
                type: 'value'
            }
        ],
        series: [
            {
                name: '访问量',
                type: 'line',
                smooth: true,
                itemStyle: { normal: { areaStyle: { type: 'default' } } },
                data: [0]
            }
        ]
    },

    businessOption: {
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: ['销售额']
        },
        toolbox: {
            show: false,
            feature: {
                mark: { show: true },
                dataView: { show: true, readOnly: false },
                magicType: { show: true, type: ['line', 'bar', 'stack', 'tiled'] },
                restore: { show: true },
                saveAsImage: { show: true }
            }
        },
        calculable: true,
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
        },

        yAxis: [
            {
                type: 'value'
            }
        ],
        series: [
            {
                name: '销售额',
                type: 'line',
                smooth: true,
                itemStyle: { normal: { areaStyle: { type: 'default' } } },
                data: [0]
            }
        ]
    }
})

// 获取工作台主页数据
const getWorkbench = () => {
    apiWorkbench()
        .then((res: any) => {
            console.log('res', res)
            workbenchData.platform = res.platform
            workbenchData.today = res.today
            workbenchData.menu = res.menu
            workbenchData.visitor = res.visitor
            workbenchData.article = res.article

            // 清空echarts 数据
            workbenchData.visitorOption.xAxis.data = []
            workbenchData.visitorOption.series[0].data = []

            // 清空echarts 数据
            workbenchData.businessOption.xAxis.data = []
            workbenchData.businessOption.series[0].data = []

            // 写入从后台拿来的数据
            const visitor = res.chart.visitor
            for (const key in visitor) {
                workbenchData.visitorOption.xAxis.data.push(key)
                workbenchData.visitorOption.series[0].data.push(visitor[key])
            }

            // 写入从后台拿来的数据
            const turnover = res.chart.turnover
            for (const key in turnover) {
                workbenchData.businessOption.xAxis.data.push(key)
                workbenchData.businessOption.series[0].data.push(turnover[key])
            }
        })
        .catch((err: any) => {
            console.log('err', err)
        })
}

const checkNewVersion = async () => {
    const res = await checkVersion(null)
    workbenchData.platform.newVersion = res
}

onMounted(async () => {
    getWorkbench()
    checkNewVersion()
})
</script>

<style lang="scss" scoped></style>
