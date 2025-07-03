<script setup lang="ts">
import { onLaunch, onShow } from '@dcloudio/uni-app'
import { useAppStore } from './stores/app'
import { useUserStore } from './stores/user'
import cache from './utils/cache'
import { SHARE_CODE } from './enums/cacheEnums'

const appStore = useAppStore()
const { getUser } = useUserStore()
const userStore = useUserStore()

const cacheInvite = (code: any) => {
    if (code) {
        cache.set(SHARE_CODE, code)
    }
}

onLaunch(async () => {
    await appStore.getConfig()
    // #ifdef H5
    const { status, page_status, page_url } = appStore.getH5Config
    if (status == 0) {
        if (page_status == 1) return (location.href = page_url)
        uni.reLaunch({ url: '/pages/empty/empty' })
    }
    // #endif

    //ios音频静音模式播放
    // #ifdef MP-WEIXIN
    uni.setInnerAudioOption({
        obeyMuteSwitch: false
    })
    // #endif
    if (userStore.isLogin) {
        await getUser()
    }
    const updateManager = uni.getUpdateManager()
    updateManager.onCheckForUpdate(function (res) {
        // 请求完新版本信息的回调
        console.log(res.hasUpdate ? '有新版本更新！' : '无新版本更新。')
    })
    updateManager.onUpdateReady(function (res) {
        uni.showModal({
            title: '更新提示',
            content: '新版本已经准备好，是否重启应用？',
            success(res) {
                if (res.confirm) {
                    // 新的版本已经下载好，调用 applyUpdate 应用新版本并重启
                    updateManager.applyUpdate()
                }
            }
        })
    })
})
onShow(async (option: any) => {
    cacheInvite(option?.query[SHARE_CODE])
})
</script>
<style lang="scss">
//
</style>
