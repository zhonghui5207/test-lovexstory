import { routes } from '@/router/routes'
import { useAppStore } from '@/stores/app'
import wechatOa from './wechat'
import { paramsToStr } from '@/utils/util'
import { useUserStore } from '@/stores/user'
import { SHARE_CODE } from '@/enums/cacheEnums'
import { nextTick } from 'vue'

export default {
    async onShareAppMessage(res: any) {
        const { getWebsiteConfig } = useAppStore()
        if (res.from === 'button') {
            return {
                title: getWebsiteConfig.shop_name,
                path: await getSharePath()
            }
        }
        if (res.from === 'menu') {
            return {
                title: getWebsiteConfig.shop_name,
                path: await getSharePath()
            }
        }
    },
    // 分享到朋友圈
    onShareTimeline() {
        const { getWebsiteConfig } = useAppStore()
        return {
            title: getWebsiteConfig.shop_name,
            path: '/pages/index/index'
        }
    },
    async onShow() {
        const menuList = [
            'menuItem:share:appMessage',
            'menuItem:share:timeline',
            'menuItem:share:qq',
            'menuItem:share:weiboApp',
            'menuItem:share:QZone'
        ]
        await nextTick()
        const pageList = getCurrentPages()
        const currentPage = pageList[pageList.length - 1]?.route
        const pageMate = routes.find((item) => {
            return item.path == `/${currentPage}`
        })
        if (pageMate?.unshare == true) {
            // #ifdef MP-WEIXIN
            uni.hideShareMenu({
                hideShareItems: []
            })
            // #endif
            // #ifdef H5
            wechatOa.hideMenuItems(menuList)
            // #endif
        }
    },
    async onLoad(option: any) {
        query = option
    }
}
let query: any = {}

async function createShareOptions(config: any) {
    uni.showLoading({
        title: '请稍后...'
    })
    const { share_title, share_image, share_content, shop_name, share_page } = config
    const path = await getSharePath()
    uni.hideLoading()
    return {
        title: share_title || shop_name,
        link: path,
        img_url: share_image,
        desc: share_content
    }
}

export function initShareEvent() {
    // #ifdef H5
    document.addEventListener(
        'WeixinJSBridgeReady',
        async () => {
            const appStore = useAppStore()
            //@ts-ignore
            WeixinJSBridge.on('menu:share:appmessage', async function () {
                const options = await createShareOptions({
                    // ...appStore.getShareConfig,
                    ...appStore.getWebsiteConfig
                })
                //@ts-ignore
                WeixinJSBridge.invoke('sendAppMessage', options)
            })
            //@ts-ignore
            WeixinJSBridge.on('menu:share:timeline', async function () {
                const options = await createShareOptions({
                    // ...appStore.getShareConfig,
                    ...appStore.getWebsiteConfig
                })
                //@ts-ignore
                WeixinJSBridge.invoke('shareTimeline', options)
            })
        },
        false
    )
    // #endif
}

const getSharePath = async () => {
    const pageList = getCurrentPages()
    const currentPage = pageList[pageList.length - 1].route
    let origin = ''
    //#ifdef H5
    origin = `${window.location.origin}/mobile`
    //#endif
    const path = `${origin}/${currentPage}`

    try {
        const userStore = useUserStore()
        if (!userStore?.userInfo?.code) {
            await userStore.getUser()
        }
        if (userStore?.userInfo?.code) {
            query[SHARE_CODE] = userStore?.userInfo?.code
        }
    } catch (error) {
        console.log(error)
    }
    console.log(`${path}${paramsToStr(query)}`)

    return `${path}${paramsToStr(query)}`
}
