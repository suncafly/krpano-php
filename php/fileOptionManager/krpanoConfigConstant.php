<?php
/**
 * 定义常量
 * @author lichangming 
**/

if(!defined('IN_T'))
{
   die('hacking attempt');
}

//程序所在根目录
if(!defined('ROOT_PATH')){
   define('ROOT_PATH',str_replace('php/fileOptionManager/krpanoConfigConstant.php','',str_replace('\\', '/', __FILE__)));
}


//设置该次请求超时时长，1800s
@ini_set("max_execution_time", "1800"); 
//兼容php-fpm设置超时
@ini_set("request_terminate_timeout", "1800");

//定义krpano切图临时文件存储路径

define('KRTEMP', ROOT_PATH. 'temp/krpano');

define('KRODRION', ROOT_PATH. 'data/krpano');

//定义krpano位置
define('KRPANO_MULTI', ROOT_PATH.'plugins/krpano-1.19-pr14/make_multi.bat');
?>