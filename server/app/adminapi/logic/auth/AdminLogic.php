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

namespace app\adminapi\logic\auth;

use app\common\cache\AdminAuthCache;
use app\common\enum\YesNoEnum;
use app\common\logic\BaseLogic;
use app\common\model\auth\Admin;
use app\common\model\auth\AdminSession;
use app\common\cache\AdminTokenCache;
use app\common\service\FileService;
use think\facade\Config;
use think\facade\Db;

/**
 * 管理员逻辑
 * Class AdminLogic
 * @package app\adminapi\logic\auth
 */
class AdminLogic extends BaseLogic
{

    /**
     * @notes 添加管理员
     * @param array $params
     * @author 段誉
     * @date 2021/12/29 10:23
     */
    public static function add(array $params)
    {
        $passwordSalt = Config::get('project.unique_identification');
        $password = create_password($params['password'], $passwordSalt);
        $avatar = !empty($params['avatar']) ? FileService::setFileUrl($params['avatar']) : config('project.default_image.admin_avatar');

        Admin::create([
            'name' => $params['name'],
            'account' => $params['account'],
            'avatar' => $avatar,
            'password' => $password,
            'role_id' => $params['role_id'],
            'dept_id' => $params['dept_id'] ?? 0,
            'jobs_id' => $params['jobs_id'] ?? 0,
            'create_time' => time(),
            'disable' => $params['disable'],
            'multipoint_login' => $params['multipoint_login'],
        ]);
    }


    /**
     * @notes 编辑管理员
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2021/12/29 10:43
     */
    public static function edit(array $params) : bool
    {
        Db::startTrans();
        try {
            // 基础信息
            $data = [
                'id' => $params['id'],
                'name' => $params['name'],
                'account' => $params['account'],
                'role_id' => $params['role_id'],
                'dept_id' => $params['dept_id'] ?? 0,
                'jobs_id' => $params['jobs_id'] ?? 0,
                'disable' => $params['disable'],
                'multipoint_login' => $params['multipoint_login']
            ];

            // 头像
            $data['avatar'] = !empty($params['avatar']) ? FileService::setFileUrl($params['avatar']) : '';

            // 密码
            if (!empty($params['password'])) {
                $passwordSalt = Config::get('project.unique_identification');
                $data['password'] = create_password($params['password'], $passwordSalt);
                $password = Admin::where($params['id'])->value('password');
                if($password != $data['password']){
                    $adminSession = AdminSession::where('admin_id', $params['id'])->findOrEmpty();
                    self::expireToken($adminSession['token']);
                }

            }

            // 禁用或更换角色后.设置token过期
            $role_id = Admin::where('id', $params['id'])->value('role_id');
            if ($params['disable'] == 1 || $role_id != $params['role_id']) {
                $tokenArr = AdminSession::where('admin_id', $params['id'])->select()->toArray();
                foreach ($tokenArr as $token) {
                    self::expireToken($token['token']);
                }
            }

            Admin::update($data);
            (new AdminAuthCache($params['id']))->clearAuthCache();

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 删除管理员
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2021/12/29 10:45
     */
    public static function delete(array $params) : bool
    {
        Db::startTrans();
        try {
            $admin = Admin::findOrEmpty($params['id']);
            if ($admin->root == YesNoEnum::YES) {
                throw new \Exception("超级管理员不允许被删除");
            }
            Admin::destroy($params['id']);

            //设置token过期
            $tokenArr = AdminSession::where('admin_id', $params['id'])->select()->toArray();
            foreach ($tokenArr as $token) {
                self::expireToken($token['token']);
            }
            (new AdminAuthCache($params['id']))->clearAuthCache();

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes
     * @param $token
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2021/12/29 10:46
     */
    public static function expireToken($token) : bool
    {
        $adminSession = AdminSession::where('token', '=', $token)
            ->with('admin')
            ->find();

        if (empty($adminSession)) {
            return false;
        }

        $time = time();
        $adminSession->expire_time = $time;
        $adminSession->update_time = $time;
        $adminSession->save();

        return (new AdminTokenCache())->deleteAdminInfo($token);
    }


    /**
     * @notes 查看管理员详情
     * @param $params
     * @return array
     * @author 段誉
     * @date 2021/12/29 11:07
     */
    public static function detail($params, $action = 'detail') : array
    {
        $admin = Admin::field([
            'id','account', 'name', 'role_id', 'disable', 'root',
            'multipoint_login', 'avatar', 'dept_id', 'jobs_id'
        ])->findOrEmpty($params['id'])->toArray();

        if ($action == 'detail') {
            return $admin;
        }

        $result['user'] = $admin;
        // 当前管理员角色拥有的菜单
        $result['menu'] = MenuLogic::getMenuByAdminId($params['id']);
        // 当前管理员橘色拥有的按钮权限
        $result['permissions'] = AuthLogic::getBtnAuthByRoleId($admin);
        return $result;
    }



    /**
     * @notes 编辑超级管理员
     * @param $params
     * @return Admin
     * @author 段誉
     * @date 2022/4/8 17:54
     */
    public static function editSelf($params)
    {
        $data = [
            'id' => $params['admin_id'],
            'name' => $params['name'],
            'avatar' => FileService::setFileUrl($params['avatar']),
        ];

        if (!empty($params['password'])) {
            $passwordSalt = Config::get('project.unique_identification');
            $data['password'] = create_password($params['password'], $passwordSalt);
        }

        return Admin::update($data);
    }

}