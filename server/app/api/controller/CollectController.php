<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------
namespace app\api\controller;
use app\api\logic\CollectLogic;
use app\api\validate\CourseHandleValidate;

/**
 * 收藏控制器类
 * Class CollectController
 * @package app\api\controller
 */
class CollectController extends BaseApiController
{

    /**
     * @notes
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/10 11:38
     */
    public function lists()
    {
        return $this->dataLists();
    }

    /**
     * @notes 课程收藏
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/7 15:50
     */
    public function collect()
    {
        $params = (new CourseHandleValidate())->post()->goCheck('collect');
        $params['user_id'] = $this->userId;
        (new CollectLogic())->collect($params);
        return $this->success('操作成功');
    }

}