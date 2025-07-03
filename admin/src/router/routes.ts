/**
 * Note: 路由配置项
 *
 * path: '/path'                    // 路由路径
 * name:'router-name'               // 设定路由的名字，一定要填写不然使用<keep-alive>时会出现各种问题
 * meta : {
    title: 'title'                  // 设置该路由在侧边栏的名字
    icon: 'icon-name'                // 设置该路由的图标
    activeMenu: '/system/user'      // 当路由设置了该属性，则会高亮相对应的侧边栏。
    query: '{"id": 1}'             // 访问路由的默认传递参数
    hidden: true                   // 当设置 true 的时候该路由不会在侧边栏出现 
    hideTab: true                   //当设置 true 的时候该路由不会在多标签tab栏出现
  }
 */

import type { RouteRecordRaw } from 'vue-router'
import { PageEnum } from '@/enums/pageEnum'
import Layout from '@/layout/default/index.vue'

export const LAYOUT = () => Promise.resolve(Layout)

export const INDEX_ROUTE_NAME = Symbol()

export const constantRoutes: Array<RouteRecordRaw> = [
    {
        path: '/:pathMatch(.*)*',
        component: () => import('@/views/error/404.vue')
    },
    {
        path: PageEnum.ERROR_403,
        component: () => import('@/views/error/403.vue')
    },
    {
        path: PageEnum.LOGIN,
        component: () => import('@/views/account/login.vue')
    },
    {
        path: '/user',
        component: LAYOUT,
        children: [
            {
                path: 'setting',
                component: () => import('@/views/user/setting.vue'),
                meta: {
                    title: '个人设置'
                }
            }
        ]
    },
    {
        path: '/course',
        component: LAYOUT,
        children: [
            {
                path: 'course',
                children: [
                    {
                        path: 'imageText/detail',
                        component: () => import('@/views/course/course/edit.vue'),
                        meta: {
                            title: '新增课程',
                            activeMenu: '/course/course/imageText'
                        }
                    },
                    {
                        path: 'audio/detail',
                        component: () => import('@/views/course/course/edit.vue'),
                        meta: {
                            title: '新增课程',
                            activeMenu: '/course/course/audio'
                        }
                    },
                    {
                        path: 'video/detail',
                        component: () => import('@/views/course/course/edit.vue'),
                        meta: {
                            title: '新增课程',
                            activeMenu: '/course/course/video'
                        }
                    },
                    {
                        path: 'imageText/material',
                        component: () => import('@/views/course/course/course_material.vue'),
                        meta: {
                            title: '课件资料',
                            activeMenu: '/course/course/imageText'
                        }
                    },
                    {
                        path: 'audio/material',
                        component: () => import('@/views/course/course/course_material.vue'),
                        meta: {
                            title: '课件资料',
                            activeMenu: '/course/course/audio'
                        }
                    },
                    {
                        path: 'video/material',
                        component: () => import('@/views/course/course/course_material.vue'),
                        meta: {
                            title: '课件资料',
                            activeMenu: '/course/course/video'
                        }
                    },
                    {
                        path: 'imageText/catalogue',
                        component: () => import('@/views/course/course/catalogue/index.vue'),
                        meta: {
                            title: '课程目录',
                            activeMenu: '/course/course/imageText'
                        }
                    },
                    {
                        path: 'audio/catalogue',
                        component: () => import('@/views/course/course/catalogue/index.vue'),
                        meta: {
                            title: '课程目录',
                            activeMenu: '/course/course/audio'
                        }
                    },
                    {
                        path: 'video/catalogue',
                        component: () => import('@/views/course/course/catalogue/index.vue'),
                        meta: {
                            title: '课程目录',
                            activeMenu: '/course/course/video'
                        }
                    },
                    {
                        path: 'imageText/catalogue/detail',
                        component: () => import('@/views/course/course/catalogue/edit.vue'),
                        meta: {
                            title: '课程目录',
                            activeMenu: '/course/course/imageText'
                        }
                    },
                    {
                        path: 'audio/catalogue/detail',
                        component: () => import('@/views/course/course/catalogue/edit.vue'),
                        meta: {
                            title: '课程目录',
                            activeMenu: '/course/course/audio'
                        }
                    },
                    {
                        path: 'video/catalogue/detail',
                        component: () => import('@/views/course/course/catalogue/edit.vue'),
                        meta: {
                            title: '课程目录',
                            activeMenu: '/course/course/video'
                        }
                    }
                ]
            },
            {
                path: 'column/detail',
                component: () => import('@/views/course/course/edit.vue'),
                meta: {
                    title: '新增专栏',
                    activeMenu: '/course/column'
                }
            },
            {
                path: 'column/material',
                component: () => import('@/views/course/course/course_material.vue'),
                meta: {
                    title: '专栏资料',
                    activeMenu: '/course/column'
                }
            },
            {
                path: 'column/catalogue',
                component: () => import('@/views/course/course/columnist/index.vue'),
                meta: {
                    title: '课程目录',
                    activeMenu: '/course/column'
                }
            },
            {
                path: 'category/detail',
                component: () => import('@/views/course/category/edit.vue'),
                meta: {
                    title: '新增分类',
                    activeMenu: '/course/category'
                }
            }
        ]
    },
    {
        path: '/consumer',
        component: LAYOUT,
        children: [
            {
                path: 'detail',
                component: () => import('@/views/consumer/lists/detail.vue'),
                meta: {
                    title: '用户详情',
                    activeMenu: '/consumer/lists'
                }
            }
        ]
    },
    {
        path: '/teacher',
        component: LAYOUT,
        children: [
            {
                path: 'detail',
                component: () => import('@/views/teacher/lists/edit.vue'),
                meta: {
                    title: '讲师详情',
                    activeMenu: '/teacher/index'
                }
            }
        ]
    },
    {
        path: '/app',
        component: LAYOUT,
        children: [
            {
                path: 'subject/detail',
                component: () => import('@/views/application/subject/edit.vue'),
                meta: {
                    title: '专区详情',
                    activeMenu: '/application/subject'
                }
            },
            {
                path: 'subject_course',
                component: () => import('@/views/application/subject/course/index.vue'),
                meta: {
                    title: '专区课程',
                    activeMenu: '/application/subject'
                }
            },
            {
                path: 'coupon/coupon/edit',
                component: () => import('@/views/application/coupon/coupon/edit.vue'),
                meta: {
                    title: '优惠券',
                    activeMenu: '/application/coupon/coupon'
                }
            }
        ]
    },
    {
        path: '/order',
        component: LAYOUT,
        children: [
            {
                path: 'detail',
                component: () => import('@/views/order/lists/detail.vue'),
                meta: {
                    title: '订单详情',
                    activeMenu: '/order/index'
                }
            }
        ]
    },
    {
        path: '/setting/payment',
        component: LAYOUT,
        children: [
            {
                path: 'payment_way/edit',
                component: () => import('@/views/setting/payment/payment_way/edit.vue'),
                meta: {
                    title: '设置支付方式',
                    activeMenu: '/payment/way'
                }
            }
        ]
    },
    {
        path: '/setting/system',
        component: LAYOUT,
        children: [
            {
                path: 'task/edit',
                component: () => import('@/views/setting/system/task/edit.vue'),
                meta: {
                    title: '任务设置',
                    activeMenu: '/setting/system/task'
                }
            }
        ]
    }
    // {
    //     path: '/dev_tools',
    //     component: LAYOUT,
    //     children: [
    //         {
    //             path: 'code/edit',
    //             component: () => import('@/views/dev_tools/code/edit.vue'),
    //             meta: {
    //                 title: '编辑数据表',
    //                 activeMenu: '/dev_tools/code'
    //             }
    //         }
    //     ]
    // },
    // {
    //     path: '/setting',
    //     component: LAYOUT,
    //     children: [
    //         {
    //             path: 'dict/data',
    //             component: () => import('@/views/setting/dict/data/index.vue'),
    //             meta: {
    //                 title: '数据管理',
    //                 activeMenu: '/setting/dict'
    //             }
    //         }
    //     ]
    // }
]

export const INDEX_ROUTE: RouteRecordRaw = {
    path: PageEnum.INDEX,
    component: LAYOUT,
    name: INDEX_ROUTE_NAME
}
