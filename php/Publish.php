<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/1
 * Time: 5:50 AM
 */


//$KRPANO_TOOL = "/usr/local/krpano-1.19-pr16/krpanotools";
//$MAKEPANO = "makepano";
//$CONFIG_KEY = "-config=";
//$CONFIG = "/usr/local/krpano-1.19-pr16/templates/vtour-multires.config";
//$_URL = "/Applications/MAMP/htdocs/demo/data/";
include "Config.php";
$_uuid = $_POST["timestamp"];
$_dir = "../data/";
$result = array();
$result['status'] = "success";

if (file_exists($_dir . $_uuid)) {
    $file = scandir($_dir . $_uuid);
    $cmd = $KRPANO_TOOL . " " . $MAKEPANO . " " . $CONFIG_KEY . $CONFIG;

    foreach ($file as $key => $value) {
        if ($value != "." && $value != "..") {
            $cmd = $cmd . " " . $_URL . $_uuid . "/" . $value;
        }
    }
    exec($cmd, $log, $status);
    $src = "../common";
    $dst = "../data/" . $_uuid . "/vtour";
    copyFile($src, $dst);
    updateTourXml($_uuid);
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

function updateTourXml($_uuid)
{
    $xmlfile = '../data/' . $_uuid . '/vtour/tour.xml';
    $dom = new DOMDocument(null);
    $dom->load($xmlfile);
    $em = $dom->getElementsByTagName('action');
    $em = $em->item(0);
    $em->nodeValue =
        "if(startscene === null OR !scene[get(startscene)],
		copy(startscene,scene[0].name); );
		loadscene(get(startscene), null, MERGE);
	    if(startactions !== null, startactions() );js('onready');";
    $dom->save($xmlfile);
}