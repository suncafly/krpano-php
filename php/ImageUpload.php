<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/4/29
 * Time: 8:21 PM
 */
$result = array();
$result['status'] = "success";
$title = $_POST["title"];
$_dir = "../data/";

if (!is_dir($_dir)) {
    $result['status'] = "error";
    $result["msg"] = "上传目录不存在";
}

$file = $_FILES["imgUpload"];
$_name = $file["name"];
$_uuid = $_POST["timestamp"];
$index =$_POST["index"];
if(!file_exists($_dir . $_uuid)){
    mkdir($_dir . $_uuid, 0777, true);
}
$type = strtolower(substr($_name, strrpos($_name, '.') + 1));
$fileName = $_dir . $_uuid . "/" . $index . "." . $type;
if (!move_uploaded_file($file["tmp_name"], $fileName)) {
    $result['status'] = "error";
    echo json_encode($result);
} else {
    echo json_encode($result);
}