<?php

	require_once "bootstrap.php";
	
	$params = array(
		array('火灾', '普通火灾',      GeneralFireDesc::class),
		array('火灾', '交通火灾',      TrafficFireDesc::class),
		array('火灾', '建筑火灾',      BuildingFireDesc::class),
		array('火灾', '石化火灾',      PetrifactionFireDesc::class),
		array('火灾', '特殊火灾',      SpecialFireDesc::class),
		array('救援', '社会救援',      SocialAssistanceDesc::class),
		array('救援', '自然灾害',      NaturalDisasterDesc::class),
		array('救援', '建\构筑物倒塌', BuildingCollapseDesc::class),
		array('救援', '交通事故',      TrafficAccidentDesc::class),
		array('救援', '危化品事故',    HazardousMaterialsDesc::class)
	);
	
	for ($i = 0, $len = count($params); $i < $len; $i++) {
		$ps = new DisasterCategory();
		$ps->setStrategyName($params[$i][0]);
		$ps->setCategoryName($params[$i][1]);
		$ps->setDisasterDescClassName($params[$i][2]);
		$entityManager->persist($ps);
	}
	
	$entityManager->flush();