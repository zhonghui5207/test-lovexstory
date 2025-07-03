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

namespace app\adminapi\validate\ad;


use app\common\enum\AdEnum;
use app\common\enum\DefaultEnum;
use app\common\enum\MenuEnum;
use app\common\model\ad\Ad;
use app\common\model\ad\AdPosition;
use app\common\validate\BaseValidate;

/**
 * 广告验证
 * Class AdValidate
 * @package app\adminapi\validate\ad
 */
class AdValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|checkId',
        'name' => 'require|checkName',
        'pid' => 'require|checkPid',
        'image' => 'require',
        'link_type' => 'require|in:1,2,3',
        'link_address' => 'require|checkAddresss',
        'status' => 'require|in:0,1',
        'sort' => 'number|max:5',
    ];

    protected $message = [
        'id.require' => '参数错误',
        'name.require' => '请输入广告名称',
        'pid.require' => '请选择广告位',
        'image.require' => '请选择广告图片',
        'link_type.require' => '请选择链接类型',
        'link_type.in' => '连接类型取值范围在[1,2,3]',
        'link_address.require' => '请选择链接地址',
        'sort.number' => '排序必须为纯数字',
        'sort.max' => '排序最大值不能超过五位数',
        'status.require' => '请选择菜单状态',
        'status.in' => '菜单状态取值范围在[0,1]',
    ];

    public function sceneAdd()
    {
        return $this->only(['name','pid','image','link_type','link_address','status','sort']);
    }

    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    public function sceneEdit()
    {
        return $this->only(['id','name','pid','image','link_type','link_address','status','sort']);
    }

    public function sceneDel()
    {
        return $this->only(['id']);
    }

    public function sceneStatus()
    {
        return $this->only(['id','status']);
    }


    /**
     * @notes 检验广告id
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2022/2/14 6:51 下午
     */
    public function checkId($value,$rule,$data)
    {
        $result = Ad::where('id',$value)->findOrEmpty();
        if ($result->isEmpty()) {
            return '广告不存在';
        }
        return true;
    }

    /**
     * @notes 检验广告名称
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2022/2/14 6:52 下午
     */
    public function checkName($value,$rule,$data)
    {
        $where[] = ['name','=',$value];
        if (isset($data['id'])) {
            $where[] = ['id','<>',$data['id']];
        }
        $result = Ad::where($where)->findOrEmpty();
        if (!$result->isEmpty()) {
            return '广告名称已存在，请重新输入';
        }
        return true;
    }

    /**
     * @notes 检验广告位id
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2022/2/14 6:55 下午
     */
    public function checkPid($value,$rule,$data)
    {
        $result = AdPosition::where('id',$value)->findOrEmpty();
        if ($result->isEmpty()) {
            return '广告位不存在';
        }
        if ($result['status'] == DefaultEnum::HIDE) {
            return '广告位已停用';
        }
        return true;
    }

    /**
     * @notes 检验链接地址
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2022/2/14 6:55 下午
     */
    public function checkAddresss($value,$rule,$data)
    {
        if ($data['link_type'] == AdEnum::LINK_SHOP) {
            $shop_page = array_column(MenuEnum::SHOP_PAGE,NULL,'index');
            if (!isset($shop_page[$data['link_address']])) {
                return '商城页面不存在该链接，请重新选择';
            }
        }

        return true;
    }
}