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

namespace app\adminapi\controller;


use app\common\service\UploadService;
use Exception;
use think\response\Json;

/**
 * 上传文件
 * Class UploadController
 * @package app\adminapi\controller
 */
class UploadController extends BaseAdminController
{

    /**
     * @notes 文件上传
     * @return Json
     * @throws Exception
     * @author cjhao
     * @date 2022/6/22 16:48
     */
    public function file()
    {
        $cid = $this->request->post('cid', 0);
        $result = UploadService::file($cid,$this->adminId);
        if(is_array($result)){
            return $this->success('上传成功',$result);
        }
        return $this->fail($result);

    }
    /**
     * @notes 上传图片
     * @return Json
     * @author 张无忌
     * @date 2021/7/28 16:57
     */
    public function image()
    {

        $cid = $this->request->post('cid', 0);
        $result = UploadService::image($cid,$this->adminId);
        if(is_array($result)){
            return $this->success('上传成功',$result);
        }
        return $this->fail($result);
    }

    /**
     * @notes 上传视频
     * @return Json
     * @author 张无忌
     * @date 2021/7/29 14:01
     */
    public function video()
    {
        $cid = $this->request->post('cid', 0);
        $result = UploadService::video($cid,$this->adminId);
        if(is_array($result)){
            return $this->success('上传成功',$result);
        }
        return $this->fail($result);
    }


    /**
     * @notes 音频上传
     * @return Json
     * @author 张无忌
     * @date 2021/7/29 14:01
     */
    public function audio()
    {
        $cid = $this->request->post('cid', 0);
        $result = UploadService::audio($cid,$this->adminId);
        if(is_array($result)){
            return $this->success('上传成功',$result);
        }
        return $this->fail($result);

    }

}