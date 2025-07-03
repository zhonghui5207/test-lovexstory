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

namespace app\common\service;


use app\common\enum\FileEnum;
use app\common\model\file\File;
use app\common\service\storage\Driver as StorageDriver;
use app\common\validate\UploadValidate;
use Exception;

/**
 * 文件上传服务类
 * Class UploadService
 * @package app\common\service
 */
class UploadService
{

    public static function file($cid, $source_id = 0, $source = FileEnum::SOURCE_ADMIN, $save_dir = 'uploads/file')
    {
        (new UploadValidate())->goCheck(null,['file'=>request()->file(),'type'=>'file']);
        try {
            $config = [
                'default' => ConfigService::get('storage', 'default', 'local'),
                'engine' => ConfigService::get('storage') ?? ['local' => []],
            ];
            // 2、执行文件上传
            $StorageDriver = new StorageDriver($config);
            $StorageDriver->setUploadFile('file');
            if (!$StorageDriver->upload($save_dir)) {
                throw new Exception($StorageDriver->getError());
            }

            $fileName = $StorageDriver->getFileName();
            $fileInfo = $StorageDriver->getFileInfo();

            // 3、处理文件名称
            if (mb_strlen($fileInfo['name']) > 128) {
                $file_name = mb_substr($fileInfo['name'], 0, 123);
                $file_end = mb_substr($fileInfo['name'], mb_strlen($fileInfo['name']) - 5, mb_strlen($fileInfo['name']));
                $fileInfo['name'] = $file_name . $file_end;
            }

            // 4、写入数据库中
            $file = File::create([
                'cid' => $cid,
                'type' => FileEnum::FILE_TYPE,
                'name' => $fileInfo['name'],
                'uri' => $save_dir . '/' . str_replace("\\", "/", $fileName),
                'source' => $source,
                'source_id' => $source_id,
                'create_time' => time(),
            ]);

            // 5、返回结果
            return [
                'id' => $file['id'],
                'cid' => $file['cid'],
                'type' => $file['type'],
                'name' => $file['name'],
                'uri' => FileService::getFileUrl($file['uri']),
                'url' => $file['uri']
            ];

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 上传图片
     * @notes
     * @param $cid
     * @param int $source_id 来源id
     * @param int $source 来源
     * @param string $save_dir
     * @return array
     * @throws Exception
     * @author 张无忌
     * @date 2021/7/28 16:48
     */
    public static function image($cid, $source_id = 0, $source = FileEnum::SOURCE_ADMIN, $save_dir = 'uploads/images')
    {
        (new UploadValidate())->goCheck(null,['file'=>request()->file(),'type'=>'image']);
        try {

            $config = [
                'default' => ConfigService::get('storage', 'default', 'local'),
                'engine' => ConfigService::get('storage') ?? ['local' => []],
            ];
            // 2、执行文件上传
            $StorageDriver = new StorageDriver($config);
            $StorageDriver->setUploadFile('file');
            if (!$StorageDriver->upload($save_dir)) {
                throw new Exception($StorageDriver->getError());
            }

            $fileName = $StorageDriver->getFileName();
            $fileInfo = $StorageDriver->getFileInfo();

            // 3、处理文件名称
            if (mb_strlen($fileInfo['name']) > 128) {
                $file_name = mb_substr($fileInfo['name'], 0, 123);
                $file_end = mb_substr($fileInfo['name'], mb_strlen($fileInfo['name']) - 5, mb_strlen($fileInfo['name']));
                $fileInfo['name'] = $file_name . $file_end;
            }

            // 4、写入数据库中
            $file = File::create([
                'cid' => $cid,
                'type' => FileEnum::IMAGE_TYPE,
                'name' => $fileInfo['name'],
                'uri' => $save_dir . '/' . str_replace("\\", "/", $fileName),
                'source' => $source,
                'source_id' => $source_id,
                'create_time' => time(),
                'ip'          => request()->ip(),
            ]);

            // 5、返回结果
            return [
                'id' => $file['id'],
                'cid' => $file['cid'],
                'type' => $file['type'],
                'name' => $file['name'],
                'uri' => FileService::getFileUrl($file['uri']),
                'url' => $file['uri']
            ];

        } catch (Exception $e) {
            return $e->getMessage();

        }
    }

    /**
     * @notes 视频上传
     * @param $cid
     * @param int $user_id
     * @param string $save_dir
     * @return array
     * @throws Exception
     * @author 张无忌
     * @date 2021/7/29 9:41
     */

    public static function video($cid, $source_id = 0, $source = FileEnum::SOURCE_ADMIN, $save_dir = 'uploads/video')
    {
        (new UploadValidate())->goCheck(null,['file'=>request()->file(),'type'=>'video']);
        try {
            $config = [
                'default' => ConfigService::get('storage', 'default', 'local'),
                'engine' => ConfigService::get('storage') ?? ['local' => []],
            ];

            // 2、执行文件上传
            $StorageDriver = new StorageDriver($config);
            $StorageDriver->setUploadFile('file');
            if (!$StorageDriver->upload($save_dir)) {
                throw new Exception($StorageDriver->getError());
            }

            $fileName = $StorageDriver->getFileName();
            $fileInfo = $StorageDriver->getFileInfo();

            // 3、处理文件名称
            if (mb_strlen($fileInfo['name']) > 128) {
                $file_name = mb_substr($fileInfo['name'], 0, 123);
                $file_end = mb_substr($fileInfo['name'], mb_strlen($fileInfo['name']) - 5, mb_strlen($fileInfo['name']));
                $fileInfo['name'] = $file_name . $file_end;
            }

            // 4、写入数据库中
            $file = File::create([
                'cid' => $cid,
                'type' => FileEnum::VIDEO_TYPE,
                'name' => $fileInfo['name'],
                'uri' => $save_dir . '/' . str_replace("\\", "/", $fileName),
                'source' => $source,
                'source_id' => $source_id,
                'create_time' => time(),
            ]);

            // 5、返回结果
            return [
                'id' => $file['id'],
                'cid' => $file['cid'],
                'type' => $file['type'],
                'name' => $file['name'],
                'uri' => FileService::getFileUrl($file['uri']),
                'url' => $file['uri']
            ];

        } catch (Exception $e) {
            return $e->getMessage();

        }
    }


    /**
     * @notes 音频上传
     * @param $cid
     * @param int $source_id
     * @param int $source
     * @param string $save_dir
     * @return array|string
     * @author cjhao
     * @date 2022/12/2 15:25
     */
    public static function audio($cid, $source_id = 0, $source = FileEnum::SOURCE_ADMIN, $save_dir = 'uploads/audio')
    {
        (new UploadValidate())->goCheck(null,['file'=>request()->file(),'type'=>'audio']);
        try {
            $config = [
                'default' => ConfigService::get('storage', 'default', 'local'),
                'engine' => ConfigService::get('storage') ?? ['local' => []],
            ];

            // 2、执行文件上传
            $StorageDriver = new StorageDriver($config);
            $StorageDriver->setUploadFile('file');
            if (!$StorageDriver->upload($save_dir)) {
                throw new Exception($StorageDriver->getError());
            }

            $fileName = $StorageDriver->getFileName();
            $fileInfo = $StorageDriver->getFileInfo();

            // 3、处理文件名称
            if (mb_strlen($fileInfo['name']) > 128) {
                $file_name = mb_substr($fileInfo['name'], 0, 123);
                $file_end = mb_substr($fileInfo['name'], mb_strlen($fileInfo['name']) - 5, mb_strlen($fileInfo['name']));
                $fileInfo['name'] = $file_name . $file_end;
            }

            // 4、写入数据库中
            $file = File::create([
                'cid' => $cid,
                'type' => FileEnum::AUDIO_TYPE,
                'name' => $fileInfo['name'],
                'uri' => $save_dir . '/' . str_replace("\\", "/", $fileName),
                'source' => $source,
                'source_id' => $source_id,
                'create_time' => time(),
            ]);

            // 5、返回结果
            return [
                'id' => $file['id'],
                'cid' => $file['cid'],
                'type' => $file['type'],
                'name' => $file['name'],
                'uri' => FileService::getFileUrl($file['uri']),
                'url' => $file['uri']
            ];

        } catch (Exception $e) {
            return $e->getMessage();

        }
    }
}