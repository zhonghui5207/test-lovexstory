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

namespace app\api\controller;


use app\api\lists\QuestionbankLists;
use app\api\logic\QuestionbankLogic;
use app\api\validate\QuestionbankValidate;
use app\common\service\JsonService;

class QuestionbankController extends BaseApiController
{
    public array $notNeedLogin = ['categoryLists','lists'];


    /**
     * @notes 题库分类列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/12/28 2:45 下午
     */
    public function categoryLists()
    {
        $result = (new QuestionbankLogic())->categoryLists();
        return JsonService::data($result);
    }

    /**
     * @notes 题库列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/28 3:20 下午
     */
    public function lists()
    {
        return $this->dataLists(new QuestionbankLists());
    }

    /**
     * @notes 题目列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/12/28 5:23 下午
     */
    public function topicLists()
    {
        $params = (new QuestionbankValidate())->get()->goCheck('topicLists');
        $params['user_id'] = $this->userId;
        $result = (new QuestionbankLogic())->topicLists($params);
        return JsonService::success('',$result);
    }

    /**
     * @notes 提交答案
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/28 5:53 下午
     */
    public function answer()
    {
        $params = (new QuestionbankValidate())->post()->goCheck('answer');
        $params['user_id'] = $this->userId;
        $result = (new QuestionbankLogic())->answer($params);
        if (true !== $result) {
            return JsonService::fail($result);
        }
        return JsonService::success('操作成功',[],1,0);
    }

    /**
     * @notes 交卷
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/28 6:13 下午
     */
    public function submit()
    {
        $params = (new QuestionbankValidate())->post()->goCheck('submit');
        $params['user_id'] = $this->userId;
        $result = (new QuestionbankLogic())->submit($params);
        if (true !== $result) {
            return JsonService::fail($result);
        }
        return JsonService::success('操作成功');
    }

    /**
     * @notes 刷题报告
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/12/28 6:36 下午
     */
    public function report()
    {
        $params = (new QuestionbankValidate())->get()->goCheck('report');
        $params['user_id'] = $this->userId;
        $result = (new QuestionbankLogic())->report($params);
        return JsonService::success('',$result);
    }

    /**
     * @notes 我的题库
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/29 2:38 下午
     */
    public function userQuestionbankLists()
    {
        $params = $this->request->get();
        $params['user_id'] = $this->userId;
        $result = (new QuestionbankLogic())->userQuestionbankLists($params);
        return JsonService::success('',$result);
    }

    /**
     * @notes 重新做题
     * @return \think\response\Json
     * @author ljj
     * @date 2024/1/4 11:22 上午
     */
    public function again()
    {
        $params = (new QuestionbankValidate())->post()->goCheck('again');
        $params['user_id'] = $this->userId;
        $result = (new QuestionbankLogic())->again($params);
        if (true !== $result) {
            return JsonService::fail($result);
        }
        return JsonService::success('操作成功');
    }

    /**
     * @notes 购买题库
     * @return \think\response\Json
     * @author ljj
     * @date 2024/1/4 3:52 下午
     */
    public function buy()
    {
        $params = (new QuestionbankValidate())->post()->goCheck('buy',['user_id'=>$this->userId]);
        $result = (new QuestionbankLogic())->buy($params,$this->userInfo);
        if(is_array($result)){
            return $this->success('订单提交成功',$result);
        }
        return $this->fail($result);

    }
}