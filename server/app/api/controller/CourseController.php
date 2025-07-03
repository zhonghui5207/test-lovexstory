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
use app\api\lists\HostLists;
use app\api\logic\CourseLogic;
use app\api\validate\CourseHandleValidate;

/**
 * 课程控制器类
 * Class CourseController
 * @package app\api\controller
 */
class CourseController extends BaseApiController
{
    public array $notNeedLogin = ['hostLists','indexHost','lists','detail'];


    /**
     * @notes 热门课程
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/10/19 10:50
     */
    public function indexHost()
    {
        $lists = (new CourseLogic())->indexHost();
        return $this->success('',$lists);

    }


    /**
     * @notes 热门课程
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/11/29 5:36 下午
     */
    public function hostLists()
    {
        return $this->dataLists(new HostLists());

    }

    /**
     * @notes 课程列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/7 17:04
     */
    public function lists()
    {
        return $this->dataLists();
    }


    /**
     * @notes 课程详情
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/6 14:48
     */
    public function detail()
    {
        $courseId = $this->request->get('id');
        $result = (new CourseLogic())->detail($courseId,$this->userId);
        if(is_array($result)){
            return $this->success('',$result);
        }
        return $this->fail($result);
    }

    /**
     * @notes 获取目录列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/17 14:51
     */
    public function catalogueLists()
    {
        $courseId = $this->request->get('id');
        $detail = (new CourseLogic())->catalogueLists($courseId,$this->userId);
        return $this->success('',$detail);

    }


    /**
     * @notes 获取目录内容
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/8 9:18
     */
    public function catalogue()
    {

        $params = $this->request->get();
        $result = (new CourseLogic())->catalogue($params,$this->userId);
        if(is_array($result)){
            return $this->success('',$result);
        }
        return $this->fail($result);
    }




    /**
     * @notes 课程加入学习(免费课程)
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/8 16:40
     */
    public function study()
    {
        $params = (new CourseHandleValidate())->post()->goCheck('study',['type'=>'study','user_id'=>$this->userId]);
        $params['user_id'] = $this->userId;
        $result = (new CourseLogic())->study($params);
        if(true === $result){
            return $this->success('加入学习成功');
        }
        return $this->fail($result);
    }

    /**
     * @notes 学习课程列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/6/17 18:45
     */
    public function studyCourseLists()
    {
        $lists = (new CourseLogic())->studyCourseLists($this->userId);
        return $this->success('',$lists);

    }

    /**
     * @notes 更新学习进度
     * @return \think\response\Json
     * @author ljj
     * @date 2023/9/19 10:22 上午
     */
    public function updateSchedule()
    {
        $params = (new CourseHandleValidate())->post()->goCheck('updateSchedule',['user_id'=>$this->userId]);
        (new CourseLogic())->updateSchedule($params);
        return $this->success('操作成功');
    }
}