<?php
	require_once "bootstrap.php";
	
	$types = array(
		'Float',
		'Integer'
	);
	
	// add
	
	$fieldType = $entityManager->getRepository('FieldType')->findOneBy(array('type' => 'Float'));
	
	$props = array(
		array('fieldName'=>'id',                 'fieldType' => 'Integer',  'label'=>'编号',         'editable' => false, 'defaultValue'=>'',         'valueRule'=>''),
		array('fieldName'=>'type',               'fieldType' => 'String',   'label'=>'类型',         'editable' => false, 'defaultValue'=>'',          'valueRule'=>''),
		array('fieldName'=>'subType',            'fieldType' => 'String',   'label'=>'子类型',       'editable' => false, 'defaultValue'=>'',          'valueRule'=>''),
		array('fieldName'=>'departmentId',       'fieldType' => 'Integer',  'label'=>'机构编号',     'editable' => false, 'defaultValue'=>'',          'valueRule'=>''),
		array('fieldName'=>'department',         'fieldType' => 'String',   'label'=>'机构名称',     'editable' => false, 'defaultValue'=>'',          'valueRule'=>''),
		array('fieldName'=>'name',               'fieldType' => 'String',   'label'=>'名称',         'editable' => false, 'defaultValue'=>'',          'valueRule'=>''),
		array('fieldName'=>'vendor',             'fieldType' => 'String',   'label'=>'生产厂家',     'editable' => true,  'defaultValue'=>'',          'valueRule'=>''),
		array('fieldName'=>'productionDate',     'fieldType' => 'Date',     'label'=>'生产日期',     'editable' => true,  'defaultValue'=>'1970-01-01','valueRule'=>json_encode(array('le'=>'now'))),
		array('fieldName'=>'purchasingDate',     'fieldType' => 'Date',     'label'=>'购买日期',     'editable' => true,  'defaultValue'=>'1970-01-01','valueRule'=>json_encode(array('le'=>'now'))),
		array('fieldName'=>'licensePlateNumber', 'fieldType' => 'String',   'label'=>'车牌号',       'editable' => true,  'defaultValue'=>'',          'valueRule'=>''),
		array('fieldName'=>'state',              'fieldType' => 'DataList', 'label'=>'状态',         'editable' => true,  'defaultValue'=>'正常',      'valueRule'=>implode(',',array('正常','执勤','损坏'))),
		array('fieldName'=>'passengersAmount',   'fieldType' => 'Integer',  'label'=>'乘客数量',     'editable' => true,  'defaultValue'=>'0',         'valueRule'=>json_encode(array('minValue'=>0,'maxValue'=>100))),
		array('fieldName'=>'note',               'fieldType' => 'Text',     'label'=>'备注',         'editable' => true,  'defaultValue'=>'',          'valueRule'=>''),
		array('fieldName'=>'serviceLife',        'fieldType' => 'Integer',  'label'=>'使用时限(月)', 'editable' => true,  'defaultValue'=>'0',         'valueRule'=>''),
	);
	
	
	foreach( $props as $item ) {
		$fieldType = $entityManager->getRepository('FieldType')->findOneBy(array('type' => $item['fieldType']));

		$ftm = new FieldTypeMapper();
	
		$ftm->setClassName("Vehicle");
		$ftm->setFieldName($item['fieldName']);
		$ftm->setFieldLabelName($item['label']);
		$ftm->setFieldDefaultValue($item['defaultValue']);
		$ftm->setFieldRule($item['valueRule']);
		$ftm->setEditable($item['editable']);
		$ftm->setFieldType($fieldType);
		$entityManager->persist($ftm);
	}

	$entityManager->flush();
	
	/*
	// remove

	$fieldType = $entityManager->getRepository('FieldType')->findOneBy(array('type' => 'Float'));
	
	$fieldTypeMapper = $entityManager->getRepository('FieldTypeMapper')->findOneBy(array('fieldType'=>$fieldType,'className' => 'Vehicle', 'fieldName' => 'name'));
	
	$entityManager->remove($fieldTypeMapper);
	$entityManager->flush();
	*/