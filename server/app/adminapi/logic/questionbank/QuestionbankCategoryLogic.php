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

namespace app\adminapi\logic\questionbank;


use app\common\logic\BaseLogic;
use app\common\model\questionbank\QuestionbankCategory;

class QuestionbankCategoryLogic extends BaseLogic
{
    /**
     * @notes 添加题库分类
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/12/26 5:52 下午
     */
    public function add($params)
    {
        QuestionbankCategory::create([
            'name' => $params['name'],
            'sort' => $params['sort']
        ]);
        return true;
    }

    /**
     * @notes 题库分类详情
     * @param $params
     * @return array
     * @author ljj
     * @date 2023/12/27 10:12 上午
     */
    public function detail($params)
    {
        $result = QuestionbankCategory::where(['id'=>$params['id']])->findOrEmpty()->toArray();
        return $result;
    }

    /**
     * @notes 编辑题库分类
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/12/26 5:54 下午
     */
    public function edit($params)
    {
        QuestionbankCategory::update([
            'name' => $params['name'],
            'sort' => $params['sort']
        ],['id'=>$params['id']]);
        return true;
    }

    /**
     * @notes 删除题库分类
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/12/26 5:58 下午
     */
    public function del($params)
    {
        QuestionbankCategory::destroy($params['id']);
        return true;
    }

    /**
     * @notes 其他列表
     * @param $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2023/12/27 5:11 下午
     */
    public function otherLists($params)
    {
        $where = [];
        if(isset($params['name']) && $params['name']){
            $where[] = ['name','like','%'.$params['name'].'%'];
        }
        $lists = QuestionbankCategory::field('id,name,sort,create_time')
            ->append(['questionbank_num'])
            ->where($where)
            ->order(['sort'=>'desc','id'=>'desc'])
            ->select()
            ->toArray();
        return $lists;
    }
}