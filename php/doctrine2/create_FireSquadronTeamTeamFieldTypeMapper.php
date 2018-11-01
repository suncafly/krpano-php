<?php
	require_once "bootstrap.php";
	
	// add
	
	$fieldType = $entityManager->getRepository('FieldType')->findOneBy(array('type' => 'Float'));
	
	$props = array(
		array('fieldName'=>'id',             		  	'fieldType' => 'Integer',  		'label'=>'编号',         		 	'editable' => false, 	'defaultValue'=>'',        		'valueRule'=>''),
		array('fieldName'=>'xfzdCode',           		'fieldType' => 'String',   		'label'=>'编码',         			'editable' => true, 	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'xfzdName',       			'fieldType' => 'String',   		'label'=>'名称',         			'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'xfzdOrganization',          'fieldType' => 'String',   		'label'=>'组织结构',     			'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'Lon',              		  	'fieldType' => 'String',   		'label'=>'经度',     				'editable' => false, 	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'Lati',           			'fieldType' => 'String',   		'label'=>'维度',         			'editable' => false, 	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'xfzdBelongToFireBrigade',   'fieldType' => 'String',   		'label'=>'所属大队',    		 		'editable' => true,  	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'xfzdAdminRegion', 			'fieldType' => 'String',     	'label'=>'行政区域',     			'editable' => true,  	'defaultValue'=>'',				'valueRule'=>''),
		array('fieldName'=>'xfzdAddress', 				'fieldType' => 'String',     	'label'=>'地址',     				'editable' => true,  	'defaultValue'=>'',				'valueRule'=>''),
		array('fieldName'=>'xfzdActiveNumber',       	'fieldType' => 'String', 		'label'=>'现役人数',         		'editable' => true,  	'defaultValue'=>'',      		'valueRule'=>''),
		array('fieldName'=>'xfzdContractNumber',        'fieldType' => 'String',     	'label'=>'合同制员工',     			'editable' => true,  	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'xfzdDutyNumber',            'fieldType' => 'String',  		'label'=>'执勤人数',         		'editable' => true, 	'defaultValue'=>'',        		'valueRule'=>''),
		array('fieldName'=>'xfzdDutyCar',             	'fieldType' => 'String',   		'label'=>'执行车辆',     			'editable' => true, 	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'xfzdZddwNum',       		'fieldType' => 'String',   		'label'=>'重点单位数量',         	'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'xfzdDutyPhone',             'fieldType' => 'Text',  		'label'=>'值班电话',         		'editable' => true, 	'defaultValue'=>'',        		'valueRule'=>''),
		array('fieldName'=>'xfzdOtherInfo',             'fieldType' => 'Text',  		'label'=>'备注',         			'editable' => true, 	'defaultValue'=>'',        		'valueRule'=>''),
		array('fieldName'=>'imgUrl',       				'fieldType' => 'String',   		'label'=>'图标路径',         		'editable' => false,   	'defaultValue'=>'',         	'valueRule'=>''),
		
	);
	
	foreach( $props as $item ) {
		$fieldType = $entityManager->getRepository('FieldType')->findOneBy(array('type' => $item['fieldType']));

		$ftm = new FieldTypeMapper();
	
		$ftm->setClassName("FireSquadronTeam");
		$ftm->setFieldName($item['fieldName']);
		$ftm->setFieldLabelName($item['label']);
		$ftm->setFieldDefaultValue($item['defaultValue']);
		$ftm->setFieldRule($item['valueRule']);
		$ftm->setEditable($item['editable']);
		$ftm->setFieldType($fieldType);
		$entityManager->persist($ftm);
	}

	$entityManager->flush();
	