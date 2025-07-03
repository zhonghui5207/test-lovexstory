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

namespace app\common\enum;


class QuestionbankEnum
{
    //题库状态
    const STATUS_NOT = 1; //未开始
    const STATUS_ING = 2; //进行中
    const STATUS_END = 3; //已下架

    //题目类型
    const TOPIC_TYPE_RADIO = 1; //单选题
    const TOPIC_TYPE_MULTIPLE = 2; //多选题
    const TOPIC_TYPE_INDEFINITE = 3; //不定项

    //题目难度
    const TOPIC_DIFFICULTY_SIMPLE = 1; //简单
    const TOPIC_DIFFICULTY_MEDIUM = 2; //中等
    const TOPIC_DIFFICULTY_DIFFICULT = 3; //难

    //答题状态
    const RECORD_STATUS_NOT = 1; //未交卷
    const RECORD_STATUS_FINISH = 2; //已交卷

    //题库付费方式
    const PAY_TYPE_CHARGE = 1; //收费
    const PAY_TYPE_FREE = 2; //免费


    /**
     * @notes 题库状态
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2023/12/26 6:48 下午
     */
    public static function getStatusDesc($value = true)
    {
        $data = [
            self::STATUS_NOT => '未开始',
            self::STATUS_ING => '进行中',
            self::STATUS_END => '已下架',
        ];
        if (true === $value) {
            return $data;
        }
        return $data[$value] ?? '';
    }

    /**
     * @notes 题目类型
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2023/12/27 12:06 下午
     */
    public static function getTopicTypeDesc($value = true)
    {
        $data = [
            self::TOPIC_TYPE_RADIO => '单选',
            self::TOPIC_TYPE_MULTIPLE => '多选',
            self::TOPIC_TYPE_INDEFINITE => '不定项',
        ];
        if (true === $value) {
            return $data;
        }
        return $data[$value] ?? '';
    }

    /**
     * @notes 题目难度
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2023/12/27 12:09 下午
     */
    public static function getTopicDifficultyDesc($value = true)
    {
        $data = [
            self::TOPIC_DIFFICULTY_SIMPLE => '简单',
            self::TOPIC_DIFFICULTY_MEDIUM => '中等',
            self::TOPIC_DIFFICULTY_DIFFICULT => '难',
        ];
        if (true === $value) {
            return $data;
        }
        return $data[$value] ?? '';
    }

    /**
     * @notes 答题状态
     * @param bool $value
     * @return string|string[]
     * @author ljj
     * @date 2023/12/27 6:42 下午
     */
    public static function getRecordStatusDesc($value = true)
    {
        $data = [
            self::RECORD_STATUS_NOT => '未交卷',
            self::RECORD_STATUS_FINISH => '已交卷',
        ];
        if (true === $value) {
            return $data;
        }
        return $data[$value] ?? '';
    }
}