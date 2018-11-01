<?php
	require_once "bootstrap.php";
	
	// add
	
	$fieldType = $entityManager->getRepository('FieldType')->findOneBy(array('type' => 'Float'));
	
	$props = array(
		array('fieldName'=>'id',             		  	'fieldType' => 'Integer',  		'label'=>'编号',         		 	'editable' => false, 	'defaultValue'=>'',        		'valueRule'=>''),
		array('fieldName'=>'xhsName',           		'fieldType' => 'String',   		'label'=>'名称',         			'editable' => true, 	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'xhsCode',       			'fieldType' => 'String',   		'label'=>'编码',         			'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'xhsAddress',            	'fieldType' => 'String',   		'label'=>'地址',     			 	'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'Lon',              		  	'fieldType' => 'String',   		'label'=>'经度',     				'editable' => false, 	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'Lati',           			'fieldType' => 'String',   		'label'=>'维度',         			'editable' => false, 	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'xhsAdminRegion',         	'fieldType' => 'String',   		'label'=>'行政区',    		 		'editable' => true,  	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'xhsBelongCompany', 			'fieldType' => 'String',     	'label'=>'所属单位',     			'editable' => true,  	'defaultValue'=>'',				'valueRule'=>''),
		array('fieldName'=>'xhsBelongFireTeam', 		'fieldType' => 'String',     	'label'=>'所属中队',     			'editable' => true,  	'defaultValue'=>'',				'valueRule'=>''),
		array('fieldName'=>'xhsBelongPipeWeb',          'fieldType' => 'String', 		'label'=>'所属管网',         		'editable' => true,  	'defaultValue'=>'',      		'valueRule'=>''),
		array('fieldName'=>'xhsState',           		'fieldType' => 'DataList',     	'label'=>'可用状态',     			'editable' => true,  	'defaultValue'=>'',          	'valueRule'=>implode(',',array('正常','执勤','损坏'))),
		array('fieldName'=>'xhsPlaceType',    			'fieldType' => 'String',  		'label'=>'放置形式', 				'editable' => true,  	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'xhsPipeDiameter',            'fieldType' => 'String',  		'label'=>'管网直径',         		'editable' => true, 	'defaultValue'=>'',        		'valueRule'=>''),
		array('fieldName'=>'xhsPipePress',         		'fieldType' => 'String',   		'label'=>'管网压力',     			'editable' => true, 	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'xhsWaterFrom',       		'fieldType' => 'String',   		'label'=>'取水形式',         		'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'xhsContentType',            'fieldType' => 'String',  		'label'=>'接口方式',         		'editable' => true, 	'defaultValue'=>'',        		'valueRule'=>''),
		array('fieldName'=>'xhsContentSize',           	'fieldType' => 'String',   		'label'=>'接口口径',         		'editable' => true, 	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'xhsOtherInfo',       		'fieldType' => 'Text',   		'label'=>'备注',         			'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'imgUrl',       				'fieldType' => 'String',   		'label'=>'图标路径',         		'editable' => false,   	'defaultValue'=>'',         	'valueRule'=>''),
		
	);
	
	foreach( $props as $item ) {
		$fieldType = $entityManager->getRepository('FieldType')->findOneBy(array('type' => $item['fieldType']));

		$ftm = new FieldTypeMapper();
	
		$ftm->setClassName("FireHydrant");
		$ftm->setFieldName($item['fieldName']);
		$ftm->setFieldLabelName($item['label']);
		$ftm->setFieldDefaultValue($item['defaultValue']);
		$ftm->setFieldRule($item['valueRule']);
		$ftm->setEditable($item['editable']);
		$ftm->setFieldType($fieldType);
		$entityManager->persist($ftm);
	}

	$entityManager->flush();
	