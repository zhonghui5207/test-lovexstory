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
use app\adminapi\lists\questionbank\QuestionbankCategoryLists;
use app\adminapi\logic\questionbank\QuestionbankCategoryLogic;
use app\adminapi\validate\questionbank\QuestionbankCategoryValidate;
use app\common\service\JsonService;

class QuestionbankCategoryController extends BaseAdminController
{
    /**
     * @notes 题库分类列表
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/26 5:43 下午
     */
    public function lists()
    {
        return $this->dataLists(new QuestionbankCategoryLists());
    }

    /**
     * @notes 添加题库分类
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/26 5:52 下午
     */
    public function add()
    {
        $params = (new QuestionbankCategoryValidate())->post()->goCheck('add');
        (new QuestionbankCategoryLogic())->add($params);
        return JsonService::success('操作成功');
    }

    /**
     * @notes 题库分类详情
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/27 10:12 上午
     */
    public function detail()
    {
        $params = (new QuestionbankCategoryValidate())->get()->goCheck('detail');
        $result = (new QuestionbankCategoryLogic())->detail($params);
        return JsonService::success('',$result);
    }

    /**
     * @notes 编辑题库分类
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/26 5:55 下午
     */
    public function edit()
    {
        $params = (new QuestionbankCategoryValidate())->post()->goCheck('edit');
        (new QuestionbankCategoryLogic())->edit($params);
        return JsonService::success('操作成功');
    }

    /**
     * @notes 删除题库分类
     * @return \think\response\Json
     * @author ljj
     * @date 2023/12/26 6:00 下午
     */
    public function del()
    {
        $params = (new QuestionbankCategoryValidate())->post()->goCheck('del');
        (new QuestionbankCategoryLogic())->del($params);
        return JsonService::success('操作成功');
    }

    /**
     * @notes 其他列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/12/27 5:11 下午
     */
    public function otherLists()
    {
        $params = $this->request->get();
        $result = (new QuestionbankCategoryLogic())->otherLists($params);
        return JsonService::success('',$result);
    }
}