<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/6/10
 * Time: 4:50 PM
 */

include "Config.php";
$result = array();
$key = json_decode($_GET["key"]);
//$key = "1527527103665";
$dirName = $_dir . $key;
//循环删除目录和文件函数
function delDirAndFile($dirName)
{
    if ($handle = opendir("$dirName")) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                if (is_dir("$dirName/$item")) {
                    delDirAndFile("$dirName/$item");
                } else {
                    unlink("$dirName/$item");
                }
            }
        }
        closedir($handle);
        rmdir($dirName);
    }
}

if (file_exists($dirName)) {
    delDirAndFile($dirName);
}

$result['status'] = "success";
echo json_encode($result);


