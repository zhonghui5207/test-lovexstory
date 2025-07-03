export default () => ({
    title: '顶部图标',
    name: 'top-icon',
    content: {
        enabled: 1,
        title: '我的服务',
        data: [
            {
                name: '我的课程',
                secName: '我的课程',
                linkName: '我的课程',
                link: '/pages/study/index',
                image: ''
            },
            {
                name: '我的订单',
                secName: '我的订单',
                linkName: '我的订单',
                link: '/bundle/pages/order_list/index',
                image: ''
            },
            {
                name: '钱包余额',
                secName: '钱包余额',
                linkName: '钱包余额',
                link: '/bundle/pages/user_wallet/index',
                image: ''
            }
        ]
    },
    styles: {}
})
