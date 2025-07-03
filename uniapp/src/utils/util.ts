import { ClientEnum } from '@/utils/enum'
import { isObject } from '@vue/shared'
import { it } from 'node:test'
import { getToken } from './auth'
import { parseQuery } from 'uniapp-router-next'

/**
 * @description 获取元素节点信息（在组件中的元素必须要传ctx）
 * @param  { String } selector 选择器 '.app' | '#app'
 * @param  { Boolean } all 是否多选
 * @param  { ctx } context 当前组件实例
 */
export const getRect = (selector: string, all = false, context?: any) => {
    return new Promise((resolve, reject) => {
        let qurey = uni.createSelectorQuery()
        if (context) {
            qurey = uni.createSelectorQuery().in(context)
        }
        qurey[all ? 'selectAll' : 'select'](selector)
            .boundingClientRect(function (rect) {
                if (all && Array.isArray(rect) && rect.length) {
                    return resolve(rect)
                }
                if (!all && rect) {
                    return resolve(rect)
                }
                reject('找不到元素')
            })
            .exec()
    })
}

/**
 * @description 获取当前页面实例
 */
export function currentPage() {
    const pages = getCurrentPages()
    const currentPage = pages[pages.length - 1]
    return currentPage || {}
}

/**
 * @description 后台选择链接专用跳转
 */
interface Link {
    path: string
    name?: string
    type: string
    isTab: boolean
    query?: Record<string, any>
    params?: Record<string, any>
}

export enum LinkTypeEnum {
    'SHOP_PAGES' = 'shop',
    'CUSTOM_LINK' = 'custom',
    'MINI_PROGRAM' = 'mini_program'
}

export function navigateTo(link: Link, navigateType: 'navigateTo' | 'reLaunch' = 'navigateTo') {
    // 如果是小程序跳转
    if (link.type === LinkTypeEnum.MINI_PROGRAM) {
        navigateToMiniProgram(link)
        return
    }
    let url = link.path
    if (navigateType == 'reLaunch') {
        return uni.reLaunch({ url })
    } else if (link.type == 'custom') {
        url = `${link.path}?url=${link.params?.url}`
    } else {
        url = link.query
            ? link.path
            : `${link.path}?id=${link.params?.id}&name=${link.params?.name}`
    }
    uni.navigateTo({ url })
}

/**
 * @description 是否为空
 * @param {unknown} value
 * @return {Boolean}
 */
export const isEmpty = (value: unknown) => {
    return value == null && typeof value == 'undefined'
}

/**
 * @description 对象格式化为Query语法
 * @param { Object } params
 * @return {string} Query语法
 */
export function objectToQuery(params: Record<string, any>): string {
    let query = ''
    for (const props of Object.keys(params)) {
        const value = params[props]
        const part = encodeURIComponent(props) + '='
        if (!isEmpty(value)) {
            console.log(encodeURIComponent(props), isObject(value))
            if (isObject(value)) {
                for (const key of Object.keys(value)) {
                    if (!isEmpty(value[key])) {
                        const params = props + '[' + key + ']'
                        const subPart = encodeURIComponent(params) + '='
                        query += subPart + encodeURIComponent(value[key]) + '&'
                    }
                }
            } else {
                query += part + encodeURIComponent(value) + '&'
            }
        }
    }
    return query.slice(0, -1)
}
/**
 * @description 上传图片
 * @param  { String } path 选择的本地地址
 */
export function uploadFile(path: any) {
    return new Promise((resolve, reject) => {
        const token = getToken()
        uni.uploadFile({
            url: `${import.meta.env.VITE_APP_BASE_URL}/api/upload/image`,
            filePath: path,
            name: 'file',
            header: {
                token,
                version: '1.0.0'
            },
            fileType: 'image',
            success: (res) => {
                console.log('uploadFile res ==> ', res)
                const data = JSON.parse(res.data)
                if (data.code == 1) {
                    resolve(data.data)
                } else {
                    reject()
                }
            },
            fail: (err) => {
                console.log(err)
                reject()
            }
        })
    })
}

/**
 * @description 链接、跳转菜单
 * @param  { Object } item 跳转参数
 */
export function menuJump(item: any) {
    const { params, path, type } = item

    if (params) {
        if (type == 'custom') {
            uni.navigateTo({
                url: `${path}?url=${params.url}`
            })
        } else {
            uni.navigateTo({
                url: `${path}?id=${params.id}&name=${params.name}`,
                success: (src) => {
                    console.log(src)
                },
                fail: (error) => {
                    uni.reLaunch({
                        url: path + objectToQuery(params)
                    })
                }
            })
        }
    } else {
        uni.navigateTo({
            url: path
        })
    }
}

/**
 * @description 格式化输出价格
 * @param  { string } price 价格
 * @param  { string } take 小数点操作
 * @param  { string } prec 小数位补
 */
export function formatPrice({ price, take = 'all', prec = undefined }) {
    let [integer, decimals = ''] = (price + '').split('.')

    // 小数位补
    if (prec !== undefined) {
        const LEN = decimals.length
        for (let i = prec - LEN; i > 0; --i) decimals += '0'
        decimals = decimals.substr(0, prec)
    }

    switch (take) {
        case 'int':
            return integer
        case 'dec':
            return decimals
        case 'all':
            return integer + '.' + decimals
    }
}

/**
 * @description 获取当前是什么端
 * @param  { String } selector 选择器 '.app' | '#app'
 * @param  { Boolean } all 是否多选
 * @param  { This } context this
 * @return { Object }
 */
export const getClient: any = () => {
    return handleClientEvent({
        // 微信小程序
        MP_WEIXIN: () => ClientEnum['MP_WEIXIN'],
        // 微信公众号
        OA_WEIXIN: () => ClientEnum['OA_WEIXIN'],
        // H5
        H5: () => ClientEnum['H5'],
        // APP
        APP: () => ClientEnum['APP'],
        MP_TOUTIAO: () => ClientEnum['MP_TOUTIAO'],
        // 其它
        OTHER: () => null
    })
}

// 根据端处理事件
export const handleClientEvent = ({ MP_WEIXIN, OA_WEIXIN, H5, APP, MP_TOUTIAO, OTHER }: any) => {
    // #ifdef MP-WEIXIN
    return MP_WEIXIN()
    // #endif

    // #ifdef H5
    return isWeixinClient() ? OA_WEIXIN() : H5()
    // #endif

    // #ifdef APP-PLUS
    return APP()
    // #endif
    // #ifdef MP-TOUTIAO
    return MP_TOUTIAO()
    // #endif

    return OTHER()
}

/**
 * @description 判断是否为微信环境
 * @return { Boolean }
 */
export const isWeixinClient = () => {
    // #ifdef H5
    return /MicroMessenger/i.test(navigator.userAgent)
    // #endif
}

/**
 * @description 节流
 * @param { Function } func
 * @param { Number } time
 * @return { Function }
 */
export const throttle = (func: (_p: any) => any, time = 1000): any => {
    let previous = new Date(0).getTime()
    return function (...args: []) {
        const now = new Date().getTime()
        if (now - previous > time) {
            previous = now
            return func(args)
        }
    }
}

/**
 * 复制内容
 * @param { String } str
 * @return void
 */
export function copy(str: string) {
    // #ifdef H5
    const aux = document.createElement('input')
    aux.setAttribute('value', str)
    document.body.appendChild(aux)
    aux.select()
    document.execCommand('copy')
    document.body.removeChild(aux)
    uni.showToast({ title: '复制成功' })
    // #endif

    // #ifndef H5
    uni.setClipboardData({
        data: str.toString(),
        success() {
            // #ifdef MP-TOUTIAO
            uni.showToast({ title: '复制成功' })
            // #endif
        }
    })
    // #endif
}

/**
 * @description 显示消息提示框。
 * @param  { String } title 弹出内容
 * @param  { Number } duration 延时多少毫秒
 */
export const toast = (title: string, duration = 1000) => {
    uni.showToast({
        title,
        duration: duration,
        icon: 'none'
    })
}

/**
 * @description 判断输入字符串时候包含数字加字母
 * @param  { String } str 需要判断的字符串
 */

export const isNumAndLetter = (str: string) => {
    const reg = new RegExp(/^(?![^a-zA-Z]+$)(?!\D+$)/)
    return new Promise<void>((resolve, reject) => {
        if (reg.test(str)) {
            resolve()
        } else {
            reject()
        }
    })
}

/**
 * @description 添加单位
 * @param {String | Number} value 值 100
 * @param {String} unit 单位 px em rem
 */
export const addUnit = (value: string | number, unit = 'rpx') => {
    return !Object.is(Number(value), NaN) ? `${value}${unit}` : value
}

/**
 * @description 对象参数转为以？&拼接的字符
 * @param params
 * @returns
 */
export function paramsToStr(params: Record<string, string>) {
    let p = ''
    if (isObject(params)) {
        p = '?'
        for (const props in params) {
            p += `${props}=${params[props]}&`
        }
        p = p.slice(0, -1)
    }
    return p
}

/**
 * 数字转化成字母
 * @param {number|string} index 输入字母序号
 * @returns {string} 相应字母
 */
export const indexToString = (index: string | number) => {
    let charcode
    return index
        .toString(26)
        .split('')
        .map(function (item, i) {
            charcode = item.charCodeAt(0)
            charcode += charcode >= 97 ? 10 : 49
            return String.fromCharCode(charcode)
        })
        .join('')
        .toUpperCase()
}

export const debounce = (fun: () => {}, timeout: 500) => {
    let time: any
    return () => {
        clearTimeout(time)
        time = setTimeout(() => {
            fun()
        }, timeout)
    }
}
/**
 * @description 小程序跳转
 * @param link 跳转信息，由装修数据进行输入
 */
export function navigateToMiniProgram(link: Link) {
    const query = link.query
    // #ifdef H5
    window.open(
        `weixin://dl/business/?appid=${query?.appId}&path=${query?.path}&env_version=${
            query?.env_version
        }&query=${encodeURIComponent(query?.query)}`
    )
    // #endif
    // #ifdef MP
    uni.navigateToMiniProgram({
        appId: query?.appId,
        path: query?.path,
        extraData: parseQuery(query?.query),
        envVersion: query?.env_version
    })
    // #endif
}
