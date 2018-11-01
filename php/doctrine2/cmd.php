<?php
 
// 注意在 4.0.0-RC2 之前不存在 !== 运算符
header('charset: utf-8;');
//获取当前需要操作的类型
$curDir = getcwd();

$handle = opendir($curDir);

$type = $argv[1];

switch ($type) {
	case "update": 
		echo 'C:\wamp64\www\SmartFireManagerSystem\php\doctrine2\vendor\bin\doctrine orm:schema-tool:update --force';
		break;
	case "create":
		exec('C:\wamp64\www\SmartFireManagerSystem\php\doctrine2\vendor\bin\doctrine orm:schema-tool:create');
		break;
	case "getter":
		exec('C:\wamp64\www\SmartFireManagerSystem\php\doctrine2\vendor\bin\doctrine  orm:generate:entities');
		break;
	default:
		exec('C:\wamp64\www\krpanoWorkPlace\workPlace\php\doctrine2\vendor\bin\doctrine orm:schema-tool:update --force');
		break;
}
