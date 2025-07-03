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
namespace app\adminapi\controller\decorate;
use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\decorate\SubjectCourseLogic;
use app\adminapi\validate\decorate\SubjectCourseValidate;
use app\adminapi\validate\decorate\SubjectValidate;

/**
 * 专区专题课程控制器类
 * Class SubjectController
 * @package app\adminapi\controller\decorate
 */
class SubjectCourseController extends BaseAdminController
{

    /**
     * @notes 专题专区列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/5/31 17:28
     */
    public function lists()
    {
        return $this->dataLists();
    }

    /**
     * @notes 添加课程专区
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/1 14:42
     */
    public function save()
    {
        $params = (new SubjectCourseValidate())->post()->goCheck('save');
        $result = (new SubjectCourseLogic())->save($params);
        if(true === $result){
            return $this->success('保存成功',[],1,1);
        }
        return $this->fail($result);
    }

    /**
     * @notes 获取详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/6/2 11:17
     */
    public function detail()
    {
        $params = (new SubjectCourseValidate())->goCheck('detail');
        $result = (new SubjectCourseLogic())->detail($params['subject_id']);
        return $this->success('',$result);
    }

    /**
     * @notes 删除
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/1 14:45
     */
    public function del()
    {
        $params = (new SubjectCourseValidate())->post()->goCheck('del');
        (new SubjectCourseLogic())->del($params['id']);
        return $this->success('删除成功');
    }

}