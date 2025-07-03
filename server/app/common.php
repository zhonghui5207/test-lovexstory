<?php
// 应用公共文件
use app\common\service\FileService;
use think\facade\Cache;
use think\helper\Str;

/**
 * @notes 生成密码加密密钥
 * @param string $plaintext
 * @param string $salt
 * @return string
 * @author 段誉
 * @date 2021/12/28 18:24
 */
function create_password(string $plaintext, string $salt) : string
{
    return md5($salt . md5($plaintext . $salt));
}


/**
 * @notes 随机生成token值
 * @param string $extra
 * @return string
 * @author 段誉
 * @date 2021/12/28 18:24
 */
function create_token(string $extra = '') : string
{
    return md5($extra . time());
}


/**
 * @notes 截取某字符字符串
 * @param $str
 * @param string $symbol
 * @return string
 * @author 段誉
 * @date 2021/12/28 18:24
 */
function substr_symbol_behind($str, $symbol = '.') : string
{
    $result = strripos($str, $symbol);
    if ($result === false) {
        return $str;
    }
    return substr($str, $result + 1);
}


/**
 * @notes 对比php版本
 * @param string $version
 * @return bool
 * @author 段誉
 * @date 2021/12/28 18:27
 */
function comparePHP(string $version) : bool
{
    return version_compare(PHP_VERSION, $version) >= 0 ? true : false;
}


/**
 * @notes 检查文件是否可写
 * @param string $dir
 * @return bool
 * @author 段誉
 * @date 2021/12/28 18:27
 */
function checkDirWrite(string $dir = '') : bool
{
    $route = root_path() . '/' . $dir;
    return is_writable($route);
}


/**
 * 多级线性结构排序
 * 转换前：
 * [{"id":1,"pid":0,"name":"a"},{"id":2,"pid":0,"name":"b"},{"id":3,"pid":1,"name":"c"},
 * {"id":4,"pid":2,"name":"d"},{"id":5,"pid":4,"name":"e"},{"id":6,"pid":5,"name":"f"},
 * {"id":7,"pid":3,"name":"g"}]
 * 转换后：
 * [{"id":1,"pid":0,"name":"a","level":1},{"id":3,"pid":1,"name":"c","level":2},{"id":7,"pid":3,"name":"g","level":3},
 * {"id":2,"pid":0,"name":"b","level":1},{"id":4,"pid":2,"name":"d","level":2},{"id":5,"pid":4,"name":"e","level":3},
 * {"id":6,"pid":5,"name":"f","level":4}]
 * @param array $data 线性结构数组
 * @param string $symbol 名称前面加符号
 * @param string $name 名称
 * @param string $id_name 数组id名
 * @param string $parent_id_name 数组祖先id名
 * @param int $level 此值请勿给参数
 * @param int $parent_id 此值请勿给参数
 * @return array
 */
function linear_to_tree($data, $sub_key_name = 'sub', $id_name = 'id', $parent_id_name = 'pid', $parent_id = 0)
{
    $tree = [];
    foreach ($data as $row) {
        if ($row[$parent_id_name] == $parent_id) {
            $temp = $row;
            $child = linear_to_tree($data, $sub_key_name, $id_name, $parent_id_name, $row[$id_name]);
            if($child){
                $temp[$sub_key_name] = $child;
            }
            $tree[] = $temp;
        }
    }
    return $tree;
}


/**
 * @notes 删除目标目录
 * @param $path
 * @param $delDir
 * @return bool|void
 * @author 段誉
 * @date 2022/4/8 16:30
 */
function del_target_dir($path, $delDir)
{
    //没找到，不处理
    if (!file_exists($path)) {
        return false;
    }

    //打开目录句柄
    $handle = opendir($path);
    if ($handle) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                if (is_dir("$path/$item")) {
                    del_target_dir("$path/$item", $delDir);
                } else {
                    unlink("$path/$item");
                }
            }
        }
        closedir($handle);
        if ($delDir) {
            return rmdir($path);
        }
    } else {
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }
}


/**
 * @notes 通过时间生成订单编号
 * @param $table
 * @param $field
 * @param $date
 * @param string $prefix
 * @param int $rand_suffix_length
 * @param array $pool
 * @return string
 * @author 段誉
 * @date 2021/7/23 14:15
 */
function generate_sn($table, $field, $date = true,$prefix = '', $rand_suffix_length = 4, $pool = []) : string
{
    $suffix = '';
    for ($i = 0; $i < $rand_suffix_length; $i++) {
        if (empty($pool)) {
            $suffix .= rand(0, 9);
        } else {
            $suffix .= $pool[array_rand($pool)];
        }
    }
    $sn = $prefix;
    if($date){
        $sn.= date('YmdHis');
    }
    $sn.= $suffix;
    if ($table->where($field, $sn)->find()) {
        return generate_sn($table, $field, $prefix, $rand_suffix_length, $pool);
    }
    return $sn;
}

/**
 * @notes 隐藏字符串
 * @param string $str 需要隐藏的字符串
 * @param string $replaceStr 隐藏的字符串符号
 * @param int $replaceLength 隐藏的字符串符号长度
 * @return string
 * @author cjhao
 * @date 2021/8/16 19:23
 */
function hide_substr(string $str,string $replaceStr = '*',int $replaceLength = 3)
{
    $strlen   = mb_strlen($str, 'utf-8');
    $firstStr = mb_substr($str, 0, 1, 'utf-8');
    $lastStr  = mb_substr($str, -1, 1, 'utf-8');

    if($strlen > 3){
        return $firstStr . str_repeat($replaceStr, $replaceLength) . $lastStr;
    }
    return $firstStr . str_repeat($replaceStr, $replaceLength);
}


/**
 * User: 意象信息科技 lr
 * Desc: 下载文件
 * @param $url 文件url
 * @param $save_dir 保存目录
 * @param $file_name 文件名
 * @return string
 */
function download_file($url, $save_dir, $file_name)
{
    if (!file_exists($save_dir)) {
        mkdir($save_dir, 0775, true);
    }
    $file_src = $save_dir . $file_name;
    file_exists($file_src) && unlink($file_src);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    $file = curl_exec($ch);
    curl_close($ch);
    $resource = fopen($file_src, 'a');
    fwrite($resource, $file);
    fclose($resource);
    if (filesize($file_src) == 0) {
        unlink($file_src);
        return '';
    }
    return $file_src;
}

function create_user_sn($prefix = '', $length = 8)
{
    $rand_str = '';
    for ($i = 0; $i < $length; $i++) {
        $rand_str .= mt_rand(0, 9);
    }
    $sn = $prefix . $rand_str;
    if (\app\common\model\user\User::where(['sn' => $sn])->find()) {
        return create_user_sn($prefix, $length);
    }
    return $sn;
}
/**
 * @notes 浮点数去除无效的0
 * @param $float
 * @return int|mixed|string
 * @author Tab
 * @date 2021/8/11 10:17
 */
function clear_zero($float)
{
    if($float == intval($float)) {
        return intval($float);
    }else if($float == sprintf('%.1f', $float)) {
        return sprintf('%.1f', $float);
    }
    return $float;
}


/**
 * @notes 去除内容图片域名
 * @param $content
 * @return array|string|string[]
 * @author 段誉
 * @date 2022/9/26 10:43
 */
function clear_file_domain($content)
{
    $fileUrl = FileService::getFileUrl();
    return str_replace($fileUrl, '/', $content);
}



/**
 * @notes 设置内容图片域名
 * @param $content
 * @return array|string|string[]|null
 * @author 段誉
 * @date 2022/9/26 10:43
 */
function get_file_domain($content)
{
    $preg = '/(<img .*?src=")[^https|^http](.*?)(".*?>)/is';
    $fileUrl = FileService::getFileUrl();
    return preg_replace($preg, "\${1}$fileUrl\${2}\${3}", $content);
}


/**
 * @notes uri小写
 * @param $data
 * @return array|string[]
 * @author 段誉
 * @date 2022/7/19 14:50
 */
function lower_uri($data)
{
    if (!is_array($data)) {
        $data = [$data];
    }
    return array_map(function ($item) {
        return strtolower(Str::camel($item));
    }, $data);
}

/**
 * @notes 本地版本
 * @return mixed
 * @author 段誉
 * @date 2021/8/14 15:33
 */
function local_version()
{
    if(!file_exists('./upgrade/')) {
        // 若文件夹不存在，先创建文件夹
        mkdir('./upgrade/', 0777, true);
    }
    if(!file_exists('./upgrade/version.json')) {
        // 获取本地版本号
        $version = config('project.version');
        $data = ['version' => $version];
        $src = './upgrade/version.json';
        // 新建文件
        file_put_contents($src, json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    $json_string = file_get_contents('./upgrade/version.json');
    // 用参数true把JSON字符串强制转成PHP数组
    $data = json_decode($json_string, true);
    return $data;
}

/**
 * @notes 解压压缩包
 * @param $file
 * @param $save_dir
 * @return bool
 * @author 段誉
 * @date 2021/8/14 15:27
 */
function unzip($file, $save_dir)
{
    if (!file_exists($file)) {
        return false;
    }
    $zip = new \ZipArchive();
    if ($zip->open($file) !== TRUE) {//中文文件名要使用ANSI编码的文件格式
        return false;
    }
    $zip->extractTo($save_dir);
    $zip->close();
    return true;
}



/**
 * @notes 遍历指定目录下的文件(目标目录,排除文件)
 * @param $dir //目标文件
 * @param string $exclude_file //要排除的文件
 * @param string $target_suffix //指定后缀
 * @return array|false
 * @author 段誉
 * @date 2021/8/14 14:44
 */
function get_scandir($dir, $exclude_file = '', $target_suffix = '')
{
    if (!file_exists($dir) || empty(trim($dir))) {
        return [];
    }

    $files = scandir($dir);
    $res = [];
    foreach ($files as $item) {
        if ($item == "." || $item == ".." || $item == $exclude_file) {
            continue;
        }
        if (!empty($target_suffix)) {
            if (get_extension($item) == $target_suffix) {
                $res[] = $item;
            }
        } else {
            $res[] = $item;
        }
    }

    if (empty($item)) {
        return false;
    }
    return $res;
}


/**
 * @notes 获取文件扩展名
 * @param $file
 * @return array|string|string[]
 * @author 段誉
 * @date 2021/8/14 15:24
 */
function get_extension($file)
{
    return pathinfo($file, PATHINFO_EXTENSION);
}

/**
 * @notes 生成指定长度编码
 * @param int $len
 * @return string
 * @author 张无忌
 * @date 2021/7/20 15:52
 */
function create_code($len=6)
{
    $letter_all = range('A', 'Z');
    shuffle($letter_all);
    //排除I、O字母
    $letter_array = array_diff($letter_all, ['I', 'O']);
    //随机获取四位字母
    $letter = array_rand(array_flip($letter_array), 4);
    //排除1、0
    $num_array = range('2', '9');
    shuffle($num_array);
    //获取随机六位数字
    $num = array_rand(array_flip($num_array), $len);
    $code = implode('', array_merge($letter, $num));
    return $code;
}


/**
 * @notes 获取音视频文件时长
 * @param $url
 * @return int|mixed
 * @author ljj
 * @date 2023/9/19 6:15 下午
 */
function getFileDuration($url)
{
    $getID3 = new getID3;
    $default = Cache::get('STORAGE_DEFAULT');
    if ($default !== 'local') {
        //远程文件，先保存到本地，读取时长后再删除。
        $absolute_path = FileService::getFileUrl($url);
        $data = file_get_contents($absolute_path);

        //判断目录是否存在
        $directory = dirname($url);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true); // 第三个参数 true 表示递归创建所有必需的中间目录
        }
        $f = fopen($url, "w");
        fwrite($f, $data);
        fclose($f);

        $fileInfo = $getID3->analyze($url);
        //删除本地文件
        unlink($url);
    } else {
        $fileInfo = $getID3->analyze($url);
    }
    return $fileInfo['playtime_seconds'] ?? 0;
}


/**
 * @notes 秒转换成时分秒
 * @param $second
 * @return false|string
 * @author ljj
 * @date 2023/9/20 4:40 下午
 */
function changeTimeType($second)
{
    if ($second >= 3600) {
        $hours = intval($second / 3600);
        $time = $hours . ":" . gmstrftime('%M:%S', $second);
    } else {
        $time = gmstrftime('%M:%S', $second);
    }
    return $time;
}


/**
 * @notes 生成邀请码
 * @param int $letterLen
 * @param int $numLen
 * @return string
 * @author ljj
 * @date 2023/10/26 2:53 下午
 */
function createInvitationCode($letterLen=1,$numLen=3)
{
    $letter_all = range('A', 'Z');
    shuffle($letter_all);
    //排除I、O字母
    $letter_array = array_diff($letter_all, ['I', 'O']);
    //随机获取四位字母
    $letter = array_rand(array_flip($letter_array), $letterLen);
    if (!is_array($letter)) {
        $letter = [$letter];
    }
    //排除1、0
    $num_array = range('2', '9');
    shuffle($num_array);
    //获取随机六位数字
    $num = array_rand(array_flip($num_array), $numLen);
    if (!is_array($num)) {
        $num = [$num];
    }
    $code = implode('', array_merge($letter, $num));
    if (\app\common\model\distribution\Distribution::where(['code' => $code])->find()) {
        return createInvitationCode($letterLen, $numLen);
    }
    return $code;
}