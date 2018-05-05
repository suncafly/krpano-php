<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/2
 * Time: 10:16 AM
 */
$PATH = __FILE__;
$PATH = substr($PATH, 0, strrpos($PATH, "/php"));
$KRPANO_TOOL = $PATH . "/krpano-1.19-pr16/krpanotools";
$MAKEPANO = "makepano";
$CONFIG_KEY = "-config=";
$CONFIG = $PATH . "/krpano-1.19-pr16/templates/vtour-multires.config";
$_URL = $PATH . "/data/";