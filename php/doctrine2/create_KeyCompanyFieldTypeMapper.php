<?php
	require_once "bootstrap.php";
	
	// add
	
	$fieldType = $entityManager->getRepository('FieldType')->findOneBy(array('type' => 'Float'));
	
	$props = array(
		array('fieldName'=>'id',             		  	'fieldType' => 'Integer',  		'label'=>'编号',         		 	'editable' => false, 	'defaultValue'=>'',        		'valueRule'=>''),
		array('fieldName'=>'zzdwAdminTeam',           	'fieldType' => 'String',   		'label'=>'防火管辖大队',         	'editable' => true, 	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'zzdwDefendRespons',       	'fieldType' => 'String',   		'label'=>'灭火责任对站',         	'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'zzdwCode',            	  	'fieldType' => 'String',   		'label'=>'编码',     			 	'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'Lon',              		  	'fieldType' => 'String',   		'label'=>'经度',     				'editable' => false, 	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'Lati',           			'fieldType' => 'String',   		'label'=>'维度',         			'editable' => false, 	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'zzdwName',         			'fieldType' => 'String',   		'label'=>'名称',    		 			'editable' => true,  	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'zzdwRoad', 					'fieldType' => 'String',     	'label'=>'路/街',     				'editable' => true,  	'defaultValue'=>'',				'valueRule'=>''),
		array('fieldName'=>'zzdwAddress', 				'fieldType' => 'String',     	'label'=>'地址',     				'editable' => true,  	'defaultValue'=>'',				'valueRule'=>''),
		array('fieldName'=>'zzdwRegion',          		'fieldType' => 'String', 		'label'=>'行政区域',         		'editable' => true,  	'defaultValue'=>'',      		'valueRule'=>''),
		array('fieldName'=>'zzdwLegalPhone',           	'fieldType' => 'String',     	'label'=>'消防法人以及联系方式',     'editable' => true,  	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'zzdwAdminPersonPhone',    	'fieldType' => 'String',  		'label'=>'消防管理人及联系方式', 		'editable' => true,  	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'zzdwDutyPhone',             'fieldType' => 'String',  		'label'=>'值班电话',         		'editable' => true, 	'defaultValue'=>'',        		'valueRule'=>''),
		array('fieldName'=>'zzdwOverseerPhone',         'fieldType' => 'String',   		'label'=>'防火监督员及联系方式',     'editable' => true, 		'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'zzdwEastInfo',       		'fieldType' => 'Text',   		'label'=>'东临建筑情况描述',         'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'zzdwSouthInfo',             'fieldType' => 'Text',  		'label'=>'南临建筑情况描述',         'editable' => true, 	'defaultValue'=>'',        		'valueRule'=>''),
		array('fieldName'=>'zzdwWestInfo',           	'fieldType' => 'Text',   		'label'=>'西临建筑情况描述',         'editable' => true, 	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'zzdwNorthInfo',       		'fieldType' => 'Text',   		'label'=>'北临建筑情况描述',         'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'zzdwType',             		'fieldType' => 'String',  		'label'=>'单位性质',         		'editable' => false, 	'defaultValue'=>'',        		'valueRule'=>''),
		array('fieldName'=>'zzdwFireLevel',           	'fieldType' => 'String',   		'label'=>'防火级别',         		'editable' => true, 	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'zzdwPlanLevel',       		'fieldType' => 'DataList',   	'label'=>'预案级别',         		'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>implode(',',array('一级','二级','三级'))),
		array('fieldName'=>'zzdwAllInfo',       		'fieldType' => 'Text',   		'label'=>'总体概况',         		'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'imgUrl',       				'fieldType' => 'String',   		'label'=>'图标路径',         		'editable' => false,   	'defaultValue'=>'',         	'valueRule'=>''),
		
	);
	
	foreach( $props as $item ) {
		$fieldType = $entityManager->getRepository('FieldType')->findOneBy(array('type' => $item['fieldType']));

		$ftm = new FieldTypeMapper();
	
		$ftm->setClassName("KeyCompany");
		$ftm->setFieldName($item['fieldName']);
		$ftm->setFieldLabelName($item['label']);
		$ftm->setFieldDefaultValue($item['defaultValue']);
		$ftm->setFieldRule($item['valueRule']);
		$ftm->setEditable($item['editable']);
		$ftm->setFieldType($fieldType);
		$entityManager->persist($ftm);
	}

	$entityManager->flush();
	