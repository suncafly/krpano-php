<?php
/**
 * Created by PhpStorm.
 * User: Lmt
 * Date: 2018/5/1
 * Time: 5:50 AM
 */


include "Config.php";
$timestamp = $_POST["timestamp"];//图片目录名称，使用时间戳命名
$result = array();
$result['status'] = "success";
//判断文件目录是否存在
if (file_exists($_dir . $timestamp)) {
    $file = scandir($_dir . $timestamp);
    $cmd = $path_kr_url;
    foreach ($file as $key => $value) {
        if ($value != "." && $value != "..") {
            $cmd = $cmd . " " . $img_url . $timestamp . "/" . $value;
        }
    }
    exec($cmd, $log, $status);
    $src = "../common";
    $dst = "../data/" . $timestamp . "/vtour";
    copyFile($src, $dst);
    updateTourXml($timestamp);
    echo json_encode($result);
}

//拷贝公共文件
function copyFile($src, $dst)
{
    $dir = opendir($src);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                @mkdir($dst . '/' . $file);
                copyFile($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

//更新tour.xml文件，添加onready方法调用
function updateTourXml($_uuid)
{
    $xmlfile = '../data/' . $_uuid . '/vtour/tour.xml';
    $dom = new DOMDocument(null);
    $dom->load($xmlfile);
    $actionList = $dom->getElementsByTagName('action');
    $defaultActionItem = $actionList->item(0);
    $defaultActionItem->nodeValue =
        "if(startscene === null OR !scene[get(startscene)],
		copy(startscene,scene[0].name); );
		loadscene(get(startscene), null, MERGE);
	    if(startactions !== null, startactions() );js('onready');";

    $sceneList = $dom->getElementsByTagName("scene");
    foreach ($sceneList as $sceneItem){
        $node = $dom->createElement("autorotate");
        $node->setAttribute("enabled", "false");
        $node->setAttribute("waittime", "1.5");
        $node->setAttribute("accel", "1.0");
        $node->setAttribute("speed", "5.0");
        $node->setAttribute("horizon", "0.0");
        $sceneItem->appendChild($node);
    }
    $dom->save($xmlfile);
}