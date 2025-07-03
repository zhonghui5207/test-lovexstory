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
use app\api\logic\SubjectLogic;

/**
 * 专区控制器类
 * Class SubjectController
 * @package app\api\controller
 */
class SubjectController extends BaseApiController
{

    public array $notNeedLogin = ['lists','detail'];

    /**
     * @notes 专区列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/30 15:58
     */
    public function lists()
    {
        return $this->dataLists();
    }

    /**
     * @notes 专题详情
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/30 16:44
     */
    public function detail()
    {
        $id = $this->request->get('id');
        $result = (new SubjectLogic())->detail($id);
        if(is_array($result)){
            return $this->success('',$result);
        }
        return $this->fail($result);
    }

}
