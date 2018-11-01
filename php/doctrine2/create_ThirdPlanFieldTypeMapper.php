<?php
	require_once "bootstrap.php";
	
	// add
	
	$fieldType = $entityManager->getRepository('FieldType')->findOneBy(array('type' => 'Float'));
	
	$props = array(
		array('fieldName'=>'id',             		  	'fieldType' => 'Integer',  		'label'=>'编号',         		 	'editable' => false, 	'defaultValue'=>'',        		'valueRule'=>''),
		array('fieldName'=>'code',           			'fieldType' => 'String',   		'label'=>'编码',         			'editable' => true, 	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'uploadPerson',       		'fieldType' => 'String',   		'label'=>'上传人',         			'editable' => true,   	'defaultValue'=>'众智软件',      'valueRule'=>''),
		array('fieldName'=>'uploadTime',            	'fieldType' => 'Date',   		'label'=>'上传时间',     			'editable' => true,   	'defaultValue'=>'1970-01-01',	'valueRule'=>json_encode(array('le'=>'now'))),
		array('fieldName'=>'produceTime',              	'fieldType' => 'Date',   		'label'=>'制作时间',     			'editable' => true, 	'defaultValue'=>'1970-01-01',	'valueRule'=>json_encode(array('le'=>'now'))),
		array('fieldName'=>'produceCompany',           	'fieldType' => 'String',   		'label'=>'制作单位',         		'editable' => true, 	'defaultValue'=>'众智软件',      'valueRule'=>''),
		array('fieldName'=>'name',         				'fieldType' => 'String',   		'label'=>'预案名称',    		 		'editable' => true,  	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'planDetailInfo', 			'fieldType' => 'Text',     	    'label'=>'预案简介',     			'editable' => true,  	'defaultValue'=>'',				'valueRule'=>''),
		array('fieldName'=>'otherInfo',       			'fieldType' => 'Text',   		'label'=>'备注',         			'editable' => true,   	'defaultValue'=>'',  			'valueRule'=>''),
		array('fieldName'=>'downloadNum', 				'fieldType' => 'String',     	'label'=>'下载次数',     			'editable' => false,  	'defaultValue'=>'',				'valueRule'=>''),
		array('fieldName'=>'filePathInServer',          'fieldType' => 'String', 		'label'=>'文件路径',         		'editable' => false,  	'defaultValue'=>'',      		'valueRule'=>''),
		array('fieldName'=>'fileServerName',           	'fieldType' => 'String',     	'label'=>'上传文件名',     			'editable' => false,  	'defaultValue'=>'',          	'valueRule'=>''),
		
	);
	
	foreach( $props as $item ) {
		$fieldType = $entityManager->getRepository('FieldType')->findOneBy(array('type' => $item['fieldType']));

		$ftm = new FieldTypeMapper();
	
		$ftm->setClassName("ThirdPlanInfo");
		$ftm->setFieldName($item['fieldName']);
		$ftm->setFieldLabelName($item['label']);
		$ftm->setFieldDefaultValue($item['defaultValue']);
		$ftm->setFieldRule($item['valueRule']);
		$ftm->setEditable($item['editable']);
		$ftm->setFieldType($fieldType);
		$entityManager->persist($ftm);
	}

	$entityManager->flush();
	