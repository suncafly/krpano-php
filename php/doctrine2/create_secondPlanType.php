

<?php
require_once "bootstrap.php";

$types = [
		array("typeDesc" => "重点单位", 'city'=>'8'),
		array("typeDesc" => "消防栓", 'city'=>'8'),
		array("typeDesc" => "消防总队", 'city'=>'8'),
		array("typeDesc" => "消防支队", 'city'=>'8')
	];
	foreach( $types as $type ) {
		$city = $entityManager->find('City', $type['city']);
		$TargetType = new TargetType();

		$TargetType->setTypeDesc($type['typeDesc']);
		$dt = new DateTime('NOW');

		$t = $dt->format('Y-m-d H:i:s');
		$TargetType->setCreatedTime(new DateTime($t));
		$TargetType->setModifyTime(new DateTime($t));

		$TargetType->setCity($city);

		$entityManager->persist($TargetType);
	}
	
	$entityManager->flush();
