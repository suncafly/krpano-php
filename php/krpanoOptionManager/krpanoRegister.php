<?php
/**
 * 注册krpano
 * @author 李长明  2018年9月26号
*/
if(!defined('IN_T'))
{
   die('hacking attempt');
}

//注册文件地址
$krpano_reg = ROOT_PATH."php/krpanoOptionManager/krpano_reg.bat";
$krpano_reg = "krpano_reg.bat";

//调用注册文件，并输出结果
exec($krpano_reg." ".ROOT_PATH."");
?>