<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/1
 * Time: 7:48 AM
 */

$src = "../data";
$dir = opendir($src);

$result = array();
while (false !== ($file = readdir($dir))) {
    if (($file != '.') && ($file != '..')) {
        if (is_dir($src . '/' . $file)) {
            $result[$file] = "/demo/data/" . $file . "/vtour/";
        }
    }
}
closedir($dir);
echo  json_encode($result);