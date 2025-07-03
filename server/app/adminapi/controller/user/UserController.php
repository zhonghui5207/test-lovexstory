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
namespace app\adminapi\controller\user;
use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\user\UserLists;
use app\adminapi\logic\user\UserLogic;
use app\adminapi\validate\user\UserValidate;

/**
 * 用户控制器类
 * Class UserController
 * @package app\adminapi\controller\user
 */
class UserController extends BaseAdminController
{

    /**
     * @notes 用户列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/24 16:06
     */
    public function lists()
    {
        return $this->dataLists(new UserLists());
    }

    /**
     * @notes 获取用户详情
     * @author cjhao
     * @date 2022/5/24 16:15
     */
    public function detail()
    {
        $params = (new UserValidate())->goCheck('detail');
        $detail = (new UserLogic())->detail($params['id']);
        return $this->success('',$detail);
    }

    /**
     * @notes 设置用户信息
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/24 16:41
     */
    public function setInfo()
    {
        $params = (new UserValidate())->post()->goCheck('setInfo');
        (new UserLogic())->setInfo($params);
        return $this->success('设置成功');

    }

    /**
     * @notes 调整用户钱包
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/24 18:21
     */
    public function adjustUserWallet()
    {
        $params = (new UserValidate())->post()->goCheck('adjustWallet');
        $result = (new UserLogic())->adjustUserWallet($params);
        if(true === $result){
            return $this->success('调整成功');
        }
        return $this->fail($result);
    }

    /**
     * @notes 回收课程
     * @author cjhao
     * @date 2022/6/22 10:54
     */
    public function recycleCourse()
    {
        $params = (new UserValidate())->post()->goCheck('recycleCourse');
        $result = (new UserLogic())->recycleCourse($params);
        if(true === $result){
            return $this->success('回收成功',[],1,1);
        }
        return $this->fail($result);
    }


}