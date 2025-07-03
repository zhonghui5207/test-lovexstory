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


use app\common\enum\FileEnum;
use app\common\service\UploadService;
use Exception;
use think\response\Json;

class UploadController extends BaseApiController
{
    /**
     * @notes 用户图片上传
     * @return Json
     * @author 张无忌
     * @date 2021/8/25 19:55
     */
    public function image()
    {
        try {
            $result = UploadService::image(0, $this->userId,FileEnum::SOURCE_USER);
            return $this->success('上传成功', $result);
        } catch (Exception $e) {
            return $this->fail($e->getMessage());
        }
    }
}