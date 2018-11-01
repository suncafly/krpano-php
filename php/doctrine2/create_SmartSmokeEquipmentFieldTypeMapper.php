<?php
	require_once "bootstrap.php";
	
	// add
	
	$fieldType = $entityManager->getRepository('FieldType')->findOneBy(array('type' => 'Float'));
	
	$props = array(
		array('fieldName'=>'id',             		  	'fieldType' => 'Integer',  		'label'=>'编号',         		 	'editable' => false, 	'defaultValue'=>'',        		'valueRule'=>''),
		array('fieldName'=>'name',           			'fieldType' => 'String',   		'label'=>'名称',         			'editable' => true, 	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'code',           			'fieldType' => 'String',   		'label'=>'编码',         			'editable' => true, 	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'address',       			'fieldType' => 'String',   		'label'=>'地址',         			'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'Lon',              		  	'fieldType' => 'String',   		'label'=>'经度',     				'editable' => false, 	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'Lati',           			'fieldType' => 'String',   		'label'=>'维度',         			'editable' => false, 	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'belongCompany',         	'fieldType' => 'String',   		'label'=>'所属重点单位',    		'editable' => true,  	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'installTime', 				'fieldType' => 'Date',     		'label'=>'安装时间',     			'editable' => true,  	'defaultValue'=>'',				'valueRule'=>''),
		array('fieldName'=>'lastInspectionTime', 		'fieldType' => 'Date',     		'label'=>'上次检修时间',     		'editable' => true,  	'defaultValue'=>'',				'valueRule'=>''),
		array('fieldName'=>'temperature',          		'fieldType' => 'String', 		'label'=>'温度',         			'editable' => true,  	'defaultValue'=>'',      		'valueRule'=>''),
		array('fieldName'=>'humidity',          		'fieldType' => 'String', 		'label'=>'湿度',         			'editable' => true,  	'defaultValue'=>'',      		'valueRule'=>''),
		array('fieldName'=>'state',           			'fieldType' => 'DataList',     	'label'=>'可用状态',     			'editable' => true,  	'defaultValue'=>'正常',      		'valueRule'=>implode(',',array('正常','执勤','损坏'))),
		array('fieldName'=>'otherInfo',    				'fieldType' => 'Text',  		'label'=>'备注', 					'editable' => true,  	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'imgUrl',       				'fieldType' => 'String',   		'label'=>'图标路径',         		'editable' => false,   	'defaultValue'=>'',         	'valueRule'=>''),
	);
	
	foreach( $props as $item ) {
		$fieldType = $entityManager->getRepository('FieldType')->findOneBy(array('type' => $item['fieldType']));

		$ftm = new FieldTypeMapper();
	
		$ftm->setClassName("SmartSmokeEquipment");
		$ftm->setFieldName($item['fieldName']);
		$ftm->setFieldLabelName($item['label']);
		$ftm->setFieldDefaultValue($item['defaultValue']);
		$ftm->setFieldRule($item['valueRule']);
		$ftm->setEditable($item['editable']);
		$ftm->setFieldType($fieldType);
		$entityManager->persist($ftm);
	}

	$entityManager->flush();
	