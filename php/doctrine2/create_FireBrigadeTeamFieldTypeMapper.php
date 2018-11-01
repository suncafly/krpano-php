<?php
	require_once "bootstrap.php";
	
	// add
	
	$fieldType = $entityManager->getRepository('FieldType')->findOneBy(array('type' => 'Float'));
	
	$props = array(
		array('fieldName'=>'id',             		  	'fieldType' => 'Integer',  		'label'=>'编号',         		 	'editable' => false, 	'defaultValue'=>'',        		'valueRule'=>''),
		array('fieldName'=>'xfddCode',           		'fieldType' => 'String',   		'label'=>'编码',         			'editable' => true, 	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'xfddName',       			'fieldType' => 'String',   		'label'=>'名称',         			'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'xfddOrganization',          'fieldType' => 'String',   		'label'=>'组织结构',     			'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'Lon',              		  	'fieldType' => 'String',   		'label'=>'经度',     				'editable' => false, 	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'Lati',           			'fieldType' => 'String',   		'label'=>'维度',         			'editable' => false, 	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'xfddAdminRegion',         	'fieldType' => 'String',   		'label'=>'行政区域',    		 		'editable' => true,  	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'xfddAddress', 				'fieldType' => 'String',     	'label'=>'地址',     				'editable' => true,  	'defaultValue'=>'',				'valueRule'=>''),
		array('fieldName'=>'xfddHasFireBranchNum', 		'fieldType' => 'String',     	'label'=>'所辖中队数量',     		'editable' => true,  	'defaultValue'=>'',				'valueRule'=>''),
		array('fieldName'=>'xfddActiveLeaderNum',       'fieldType' => 'String', 		'label'=>'现役干部数量',         	'editable' => true,  	'defaultValue'=>'',      		'valueRule'=>''),
		array('fieldName'=>'xfddCivilPersonNum',        'fieldType' => 'String',     	'label'=>'文职雇员数量',     		'editable' => true,  	'defaultValue'=>'',          	'valueRule'=>''),
		array('fieldName'=>'xfddDutyPhone',             'fieldType' => 'String',  		'label'=>'值班电话',         		'editable' => true, 	'defaultValue'=>'',        		'valueRule'=>''),
		array('fieldName'=>'xfddTopLeader',             'fieldType' => 'String',   		'label'=>'大队长',     				'editable' => true, 	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'xfddViceTopLeader',       	'fieldType' => 'String',   		'label'=>'副大队长',         		'editable' => true,   	'defaultValue'=>'',         	'valueRule'=>''),
		array('fieldName'=>'xfddOtherInfo',             'fieldType' => 'Text',  		'label'=>'备注',         			'editable' => true, 	'defaultValue'=>'',        		'valueRule'=>''),
		array('fieldName'=>'imgUrl',       				'fieldType' => 'String',   		'label'=>'图标路径',         		'editable' => false,   	'defaultValue'=>'',         	'valueRule'=>''),
		
	);
	
	foreach( $props as $item ) {
		$fieldType = $entityManager->getRepository('FieldType')->findOneBy(array('type' => $item['fieldType']));

		$ftm = new FieldTypeMapper();
	
		$ftm->setClassName("FireBrigadeTeam");
		$ftm->setFieldName($item['fieldName']);
		$ftm->setFieldLabelName($item['label']);
		$ftm->setFieldDefaultValue($item['defaultValue']);
		$ftm->setFieldRule($item['valueRule']);
		$ftm->setEditable($item['editable']);
		$ftm->setFieldType($fieldType);
		$entityManager->persist($ftm);
	}

	$entityManager->flush();
	