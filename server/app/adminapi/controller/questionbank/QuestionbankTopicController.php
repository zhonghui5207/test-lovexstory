<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\adminapi\controller\questionbank;


use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\questionbank\QuestionbankTopicLists;
use app\adminapi\logic\questionbank\QuestionbankTopicLogic;
use app\adminapi\validate\questionbank\QuestionbankTopicValidate;
use app\common\service\JsonService;

class QuestionbankTopicController extends BaseAdminController
{
    /**
     * @notes 题目列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/27 2:13 下午
     */
    public function lists()
    {
        return $this->dataLists(new QuestionbankTopicLists());
    }

    /**
     * @notes 添加题目
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/27 2:41 下午
     */
    public function add()
    {
        $params = (new QuestionbankTopicValidate())->post()->goCheck('add');
        (new QuestionbankTopicLogic())->add($params);
        return JsonService::success('操作成功');
    }

    /**
     * @notes 题目详情
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/27 3:01 下午
     */
    public function detail()
    {
        $params = (new QuestionbankTopicValidate())->get()->goCheck('detail');
        $result = (new QuestionbankTopicLogic())->detail($params);
        return JsonService::success('',$result);
    }

    /**
     * @notes 编辑题目
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/27 3:09 下午
     */
    public function edit()
    {
        $params = (new QuestionbankTopicValidate())->post()->goCheck('edit');
        (new QuestionbankTopicLogic())->edit($params);
        return JsonService::success('操作成功');
    }

    /**
     * @notes 删除题目
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/27 3:18 下午
     */
    public function del()
    {
        $params = (new QuestionbankTopicValidate())->post()->goCheck('del');
        (new QuestionbankTopicLogic())->del($params);
        return JsonService::success('操作成功');
    }
}