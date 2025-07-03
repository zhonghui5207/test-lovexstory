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
use app\adminapi\lists\questionbank\QuestionbankLists;
use app\adminapi\logic\questionbank\QuestionbankLogic;
use app\adminapi\validate\questionbank\QuestionbankValidate;
use app\common\service\JsonService;

class QuestionbankController extends BaseAdminController
{
    /**
     * @notes 题库列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/26 6:49 下午
     */
    public function lists()
    {
        return $this->dataLists(new QuestionbankLists());
    }

    /**
     * @notes 添加题库
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/27 9:55 上午
     */
    public function add()
    {
        $params = (new QuestionbankValidate())->post()->goCheck('add');
        (new QuestionbankLogic())->add($params);
        return JsonService::success('操作成功');
    }

    /**
     * @notes 题库详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/12/27 10:24 上午
     */
    public function detail()
    {
        $params = (new QuestionbankValidate())->get()->goCheck('detail');
        $result = (new QuestionbankLogic())->detail($params);
        return JsonService::success('',$result);
    }

    /**
     * @notes 编辑题库
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/27 10:31 上午
     */
    public function edit()
    {
        $params = (new QuestionbankValidate())->post()->goCheck('edit');
        (new QuestionbankLogic())->edit($params);
        return JsonService::success('操作成功');
    }

    /**
     * @notes 发布
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/27 10:41 上午
     */
    public function publish()
    {
        $params = (new QuestionbankValidate())->post()->goCheck('publish');
        (new QuestionbankLogic())->publish($params);
        return JsonService::success('操作成功');
    }

    /**
     * @notes 下架
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/27 11:10 上午
     */
    public function off()
    {
        $params = (new QuestionbankValidate())->post()->goCheck('off');
        (new QuestionbankLogic())->off($params);
        return JsonService::success('操作成功');
    }

    /**
     * @notes 删除题库
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/27 11:12 上午
     */
    public function del()
    {
        $params = (new QuestionbankValidate())->post()->goCheck('del');
        (new QuestionbankLogic())->del($params);
        return JsonService::success('操作成功');
    }
}