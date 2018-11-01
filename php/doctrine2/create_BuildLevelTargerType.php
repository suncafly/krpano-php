<?php
require_once "bootstrap.php";

$types = [
		array("typeDesc" => "烟感"),
		array("typeDesc" => "摄像头"),
		
	];
	foreach( $types as $type ) {
		
		$TargetType = new UnitBuildLevelTargetType();

		$TargetType->setTypeDesc($type['typeDesc']);
		$dt = new DateTime('NOW');

		$t = $dt->format('Y-m-d H:i:s');
		$TargetType->setCreatedTime(new DateTime($t));
		$TargetType->setModifyTime(new DateTime($t));

		

		$entityManager->persist($TargetType);
	}
	
	$entityManager->flush();
