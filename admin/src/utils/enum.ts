
// 页面模式
export enum PageMode {
    'ADD' = 'add',    // 添加
    'EDIT' = 'edit',   // 编辑
}

export enum ServiceMode {
    'all_count' = '全部',     //	int	全部
    'SHELVE' = '销售中', //销售中
    'UNSHELVE' = '仓库中'//仓库中
}

export enum OrderMode {
    'all_num' = '全部',         //	int	全部
    'waitpay_num' = '待支付',   //	int	待支付
    'complete_num' = '已完成',  //	int	已完成
    'close_num' = '已关闭',     //	int	已关闭
}