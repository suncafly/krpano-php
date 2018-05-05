<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/2
 * Time: 10:16 AM
 */
$path = __FILE__;
$path = substr($path, 0, strrpos($path, "/php"));
$path_kr_url = $path . "/krpano-1.19-pr16/krpanotools makepano -config="
    . $path . "/krpano-1.19-pr16/templates/vtour-multires.config";
$img_url = $path . "/data/";
$_dir = "../data/";
