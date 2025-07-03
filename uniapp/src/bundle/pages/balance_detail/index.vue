<template>
    <tabs :current="current" @change="handleChange" height="100" bar-width="60" :is-scroll="false">
        <tab v-for="(item, index) in tabList" :key="index" :name="item.name">
            <account-log-list
                :changeObj="changeObj"
                :type="item.type"
                :index="current"
                :i="index"
            ></account-log-list>
        </tab>
    </tabs>
</template>

<script setup lang="ts">
import AccountLogList from './component/account-log-list'
import { ref } from 'vue'
import { onLoad } from '@dcloudio/uni-app'

const changeObj = ref<number>(0)
const tabList = ref([
    {
        name: '全部',
        type: ''
    },
    {
        name: '收入',
        type: 1
    },
    {
        name: '支出',
        type: 0
    }
])
const current = ref<number>(0)

const handleChange = (index: number) => {
    current.value = Number(index)
}

// onLoad(({ type }: any) => {
//     if (type == 1) {
//         uni.setNavigationBarTitle({
//             title: '账户明细'
//         })
//         changeObj.value = 0
//     } else if (type == 2) {
//         uni.setNavigationBarTitle({
//             title: '佣金明细'
//         })
//         changeObj.value = 1
//     }
// })
</script>
