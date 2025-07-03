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
namespace app\api\logic;
use app\common\enum\CourseEnum;
use app\common\enum\OrderEnum;
use app\common\model\course\Course;
use app\common\model\course\CourseCatalogue;
use app\common\model\course\CourseCollect;
use app\common\model\course\CourseColumn;
use app\common\model\course\StudyCourse;
use app\common\model\course\StudyCourseCatalogue;
use app\common\model\distribution\Distribution;
use app\common\model\distribution\DistributionLevel;
use app\common\model\order\Order;
use app\common\service\ConfigService;
use app\common\service\FileService;
use think\Exception;

/**
 * 课程逻辑类
 * Class CourseLogic
 * @package app\adminapi\logic
 */
class CourseLogic
{

    /**
     * @notes 课程详情
     * @param int $courseId
     * @param int $userId
     * @return array
     * @author cjhao
     * @date 2022/6/6 14:41
     */
    public function detail(int $courseId,int $userId)
    {
        try {
            $course = Course::with(['teacher' => function ($query) {
                $query->field('id,number,name,avatar,gender,synopsis');
            }, 'meterial', 'course_image', 'comment' => ['nickname', 'comment_image']])
                ->field('id,name,type,category_id,teacher_id,synopsis,status,cover,content,fee_type,sell_price,line_price,(virtual_study_num+study_num) as study_num,create_time,distribution_status')
                ->findOrEmpty($courseId);

            if ($course->isEmpty()) {
                throw new Exception('课程已被删除');
            }

            if(0 == $course->status){
                if(empty($userId)){
                    throw new Exception('课程已下架');
                }
                $studyCourse = StudyCourse::where(['user_id'=>$userId,'course_id'=>$course->id])
                            ->where('source_id','>',0)
                            ->findOrEmpty();
                //如果是已购买了课程的，下架仍然可以看
                if($studyCourse->isEmpty()){
                    throw new Exception('课程已下架');
                }
            }

            $studyCatalogue = [];
            $distribution = [];
            $course->is_collect = false;
            $course->course_status = 2; //course_status:1-加入课程；2-购买课程；3-已购买
            if (CourseEnum::FEE_TYPE_FREE == $course->fee_type) {
                $course->course_status = 1;
            }
            if ($userId) {
                //标记已学习的目录
                $studyCatalogue = StudyCourseCatalogue::where(['user_id' => $userId])->column('catalogue_id');
                //标记是否收藏
                $collect = CourseCollect::where(['course_id' => $courseId, 'user_id' => $userId])->findOrEmpty();
                $course->is_collect = !$collect->isEmpty();
                //标记是否已购买
                $study = StudyCourse::where(['course_id' => $courseId, 'user_id' => $userId])->findOrEmpty();
                //已经购买的，直接标记已购买
                if (!$study->isEmpty()) {
                    $course->course_status = 3;
                }


                //分销佣金
                $distribution['is_show'] = 0;//是否显示分销
                $distribution['ratio'] = 0;
                $distribution['earnings'] = 0;
                $is_distribution = ConfigService::get('distribution_config', 'is_distribution', config('project.distribution_config.is_distribution'));
                $goods_detail = ConfigService::get('distribution_config', 'goods_detail', config('project.distribution_config.goods_detail'));
                if ($is_distribution && $goods_detail && $course->distribution_status) {
                    $goods_detail_user = ConfigService::get('distribution_config', 'goods_detail_user', config('project.distribution_config.goods_detail_user'));
                    $distributor = Distribution::where(['user_id'=>$userId,'is_distribution'=>1])->findOrEmpty();
                    if (!$distributor->isEmpty()) {
                        $level = DistributionLevel::where(['id'=>$distributor->level_id])->findOrEmpty()->toArray();
                        $distribution['ratio'] = $level['first_ratio'];
                    } else {
                        $default_distribution_level = DistributionLevel::where(['is_default'=>1])->findOrEmpty()->toArray();
                        $distribution['ratio'] = $default_distribution_level['first_ratio'];
                    }
                    $distribution['earnings'] = round(($distribution['ratio'] * $course->sell_price / 100),2);
                    if (CourseEnum::FEE_TYPE_FEE == $course->fee_type) {
                        if ($goods_detail_user == 2 && !$distributor->isEmpty()) {
                            $distribution['is_show'] = 1;
                        }
                        if ($goods_detail_user == 1) {
                            $distribution['is_show'] = 1;
                        }
                    }
                }
            }
            $course->distribution = $distribution;

            //专栏数据特殊处理
            $study_catalogue_arr = StudyCourseCatalogue::where(['user_id'=>$userId,'course_id' => $courseId])->column('schedule','catalogue_id');
            if (CourseEnum::COURSE_TYPE_COLUMN == $course->type) {
                $courseColumn = array_column($course->course->toArray(), null, 'relation_id');
                $relationId = array_keys($courseColumn);
                $courseLists = Course::where(['id' => $relationId])
                    ->field('id,name,type,fee_type')
                    ->with(['catalogue','study_course_catalogue'])
                    ->select()->toArray();
                $courseNum = 0;
                foreach ($courseLists as $coursesKey => $courses) {
                    $courseNum += count($courses['catalogue']);
                    $courses['fee_type'] = $courseColumn[$courses['id']]['fee_type'];
                    foreach ($courses['catalogue'] as $cataloguesKey => $catalogues) {
                        $courses['catalogue'][$cataloguesKey]['is_study'] = in_array($catalogues['id'], $studyCatalogue);
                        $courses['catalogue'][$cataloguesKey]['catelogue_time'] = changeTimeType($catalogues['duration'] ?? 0);
                        $courses['catalogue'][$cataloguesKey]['study_progress'] = '0%';
                        if ($catalogues['duration'] > 0) {
                            $courses['catalogue'][$cataloguesKey]['study_progress'] = (round(($study_catalogue_arr[$catalogues['id']] ?? 0) / $catalogues['duration'],2) * 100).'%';
                        }
                    }
                    $courseLists[$coursesKey] = $courses;
                }
                $course->catalogue_list = $courseLists;

            } else {

                $catalogues = $course->catalogue;
                $courseNum = count($catalogues);
                //标记目录是否已学习
                foreach ($catalogues as $catalogue) {
                    $catalogue->is_study = in_array($catalogue->id, $studyCatalogue);
                    $catalogue->catelogue_time = changeTimeType($catalogue->duration ?? 0);
                    $catalogue->study_progress = '0%';
                    if ($catalogue->duration > 0) {
                        $catalogue->study_progress = (round(($study_catalogue_arr[$catalogue->id] ?? 0) / $catalogue->duration,2) * 100).'%';
                    }
                }
                $course->catalogue_list = $catalogues;
            }
            foreach ($course->comment as $comment) {
                $comment->nickname = hide_substr($comment->nickname ?? '');
                $comment->comment = $comment->comment ?: '此用户没有填写评论';
            }
            $course->course_num = $courseNum;

            //购买按钮
            $course->is_buy_btn = ConfigService::get('transaction', 'is_buy_btn',1);
            $course->is_buy_btn_desc = ConfigService::get('transaction', 'is_buy_btn_desc','立即购买');

            return $course->hidden(['course', 'catalogue'])->toArray();

        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }


    /**
     * @notes 目录列表
     * @param int $courseId
     * @param int $userId
     * @return array
     * @author cjhao
     * @date 2022/6/17 15:18
     */
    public function catalogueLists(int $courseId,int $userId):array
    {

        $course = Course::where(['status'=>1])
            ->field('id,name,type,cover,fee_type')
            ->findOrEmpty($courseId);
        if($course->isEmpty()){
            return [];
        }
        $studyCatalogue = [];
        $course->course_status = 1;
        if(CourseEnum::FEE_TYPE_FEE == $course->fee_type){
            $course->course_status = 2;
        }

        if($userId){
            //标记已学习的目录
            $studyCatalogue = StudyCourseCatalogue::where(['user_id'=>$userId])->column('catalogue_id');
            //标记是否已购买
            $study = StudyCourse::where(['course_id'=>$courseId,'user_id'=>$userId])->findOrEmpty();
            //已经购买的，直接标记已购买
            if(!$study->isEmpty()){
                $course->course_status = 3;
            }elseif (CourseEnum::FEE_TYPE_FEE == $course->fee_type){
                $course->course_status = 2;//去购买
            }

        }
        //专栏数据特殊处理
        if(CourseEnum::COURSE_TYPE_COLUMN == $course->type) {
            $courseColumn = array_column($course->course->toArray(),null,'relation_id');
            $relationId = array_keys($courseColumn);
            $courseLists = Course::where(['id'=>$relationId])
                ->field('id,name,type,fee_type')
                ->with(['catalogue'])
                ->select()->toArray();
            $courseNum = 0;
            foreach ($courseLists as $coursesKey =>  $courses){
                $courses[$coursesKey]['fee_type'] = $courseColumn[$courses['id']]['fee_type'];
                $courseNum+= count($course['catalogue']);
                foreach ($course['catalogue'] as $cataloguesKey => $catalogues){
                    $courseLists[$coursesKey]['catalogue'][$cataloguesKey]['is_study'] = in_array($catalogues['id'],$studyCatalogue);
                }
            }
            $course->catalogue_list = $courseLists;

        }else{

            $catalogues = $course->catalogue;
            $courseNum = count($catalogues);
            //标记目录是否已学习
            foreach ($catalogues as $catalogue){
                $catalogue->is_study = in_array($catalogue->id,$studyCatalogue);
            }
            $course->catalogue_list = $catalogues;
        }
        $course->course_num = $courseNum;
        return $course->hidden(['course','catalogue'])->toArray();
    }

    /**
     * @notes 获取课程目录内容
     * @param $id
     * @param $userId
     * @return array|string
     * @author cjhao
     * @date 2022/6/8 11:37
     */
    public function catalogue(array $params,int $userId)
    {
        try{
            $catalogue = CourseCatalogue::withoutField('create_time,update_time,delete_time')->findOrEmpty($params['id'])->toArray();
            if(!$catalogue){
                throw new Exception('目录不存在');
            }
            //目录收费时，看当前课程是否已购买
            if(CourseEnum::FEE_TYPE_FEE == $catalogue['fee_type']){
                $studyCourse = StudyCourse::where(['user_id'=>$userId,'course_id'=>$params['course_id']])->findOrEmpty();
                if($studyCourse->isEmpty()){
                    throw new Exception('付费课程请先购买');
                }
            }
            $studyCatalogue = StudyCourseCatalogue::where(['user_id'=>$userId,'course_id'=>$params['course_id'],'catalogue_id'=>$catalogue['id']])->findOrEmpty();
            if($studyCatalogue->isEmpty()){
                //记录已学习
                $studyCatalogue = StudyCourseCatalogue::create([
                    'user_id'       => $userId,
                    'course_id'     => $params['course_id'],
                    'catalogue_id'  => $catalogue['id'],
                ]);
            }
            $courseType = Course::where(['id'=>$catalogue['course_id']])->value('type');
            if(CourseEnum::COURSE_TYPE_VOICE == $courseType || CourseEnum::COURSE_TYPE_VIDEO == $courseType){
                $catalogue['content'] = FileService::getFileUrl($catalogue['content']);
            }

            //获取课程学习进度
            $catalogue['schedule'] = $studyCatalogue->schedule;

            return $catalogue;
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }


    /**
     * @notes 学习课程
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2022/6/8 18:16
     */
    public function study(array $params)
    {
        try{
            \app\common\logic\CourseLogic::courseStatistics($params['user_id'],$params['id']);
            return true;
        }catch (Exception $e){
            return $e->getMessage();
        }

    }

    /**
     * @notes 课程学习
     * @param int $userId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/6/17 18:55
     */
    public function studyCourseLists(int $userId):array
    {
        $lists = Course::alias('C')
                ->where(['SC.user_id'=>$userId])
                ->join('study_course SC','SC.course_id = C.id and SC.delete_time is null')
                ->field('C.id,C.name,C.type,C.cover,SC.source_id')
                ->append(['type_desc'])
                ->group('id')
                ->select();
        $courseIds = array_column($lists->toArray(),'id');
        if($courseIds){
            //课程目录数量
            $catalogueCounts = CourseCatalogue::where(['course_id'=>$courseIds])
                            ->group('course_id')
                            ->column('count(id)','course_id');
            $studyCourseCataglogue = StudyCourseCatalogue::where(['course_id'=>$courseIds])
                        ->order('id asc')
                        ->field('course_id,catalogue_id')
                        ->column('catalogue_id','course_id');
            $studyCourseCataglogueIds = array_values($studyCourseCataglogue);
            //学习到的课程目录名
            $studyCatelogueInfo = CourseCatalogue::where(['id'=>$studyCourseCataglogueIds])
                                ->column('name,duration','course_id');

            //已学习的目录数量
            $studyCatelogueCounts = StudyCourseCatalogue::alias('scc')
                                ->join('course_catalogue cc', 'scc.catalogue_id = cc.id')
                                ->where(['scc.user_id'=>$userId,'scc.course_id'=>$courseIds])
                                ->whereNull('cc.delete_time')
                                ->group('scc.course_id')
                                ->column('count(scc.id) as num','scc.course_id');
            //已购买的课程
            $orderList = Order::alias('O')
                        ->where(['user_id'=>$userId,'order_status'=>OrderEnum::ORDER_STSTUS_COMPLETE])
                        ->join('order_course OC','O.id = OC.order_id')
                        ->column('O.id');

        }
        $studyLists = [
            'charge_list'   => [],
            'fee_list'      => [],
        ];
        foreach ($lists as $course) {
            //已经学习目录数量
            $studyCatelogueCount = $studyCatelogueCounts[$course['id']] ?? 0;
            //目录数量
            if(CourseEnum::COURSE_TYPE_COLUMN == $course['type']){
                $catelogueCount = CourseColumn::alias('CC')
                    ->join('course_catalogue CCA', 'CC.relation_id = CCA.course_id')
                    ->count('CCA.id');
            }else{
                $catelogueCount = $catalogueCounts[$course['id']] ?? 0;
            }
            $course['plan'] = '0';
            if($studyCatelogueCount > 0 && $catelogueCount > 0){
                $course['plan'] = round($studyCatelogueCount / $catelogueCount ,2) * 100;
            }
            $course['plan'] = ($course['plan'] > 100 ? '100' : $course['plan']).'%';
            //学习课程名称
            $course['study_catelogue_name'] = $studyCatelogueInfo[$course['id']]['name'] ?? '';

            //课程时长
            $course['catelogue_time'] = changeTimeType($studyCatelogueInfo[$course['id']]['duration'] ?? 0);

            //课程目录数量
            $course['catelogue_count'] = $catelogueCount;
            //已经学习目录数量
            $course['study_catelogue_count'] = $studyCatelogueCount;

            if(in_array($course['source_id'],$orderList)){
                //付费课程
                $studyLists['charge_list'][] = $course->toarray();
            }else{
                //免费课程
                $studyLists['fee_list'][] = $course->toarray();
            }
        }

        return $studyLists;

    }


    /**
     * @notes 热门课程列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/10/19 10:41
     */
    public function indexHost()
    {
        $lists = Course::where(['status'=>1])
            ->field('id,name,type,cover,fee_type,sell_price,(virtual_study_num+study_num) as study_num,create_time')
            ->limit(5)
            ->order('study_num desc,sort desc,id desc')
            ->withCount(['catalogue'])
            ->select();
        return $lists->toArray();
    }


    /**
     * @notes 更新学习进度
     * @param $params
     * @return bool
     * @author ljj
     * @date 2023/9/19 10:22 上午
     */
    public function updateSchedule($params)
    {
        StudyCourseCatalogue::update(['schedule'=>$params['schedule']],['catalogue_id'=>$params['catalogue_id'],'user_id'=>$params['user_id']]);
        return true;
    }

}