<?php
	require_once "bootstrap.php";
	
	$relationships = array(
		array(
			'type' => 'DateTime',
			'defaultValue' => '1970-01-01 00:00:00',
			'valueRule' => '1970-01-01 00:00:00'
		),
		array(
			'type' => 'Date',
			'defaultValue'=> '1970-01-01',
			'valueRule' => '1970-01-01'
		),
		array('type' => 'Month',
			'defaultValue'=> '1',
			'valueRule' => ''
		),
		array('type' => 'Week',
			'defaultValue'=> '1',
			'valueRule' => ''
		),
		array('type' => 'Telphone',
			'defaultValue'=> '',
			'valueRule' => ''
		),
		array('type' => 'Integer',
			'defaultValue'=> '0',
			'valueRule' => ''
		),
		array('type' => 'Float',
			'defaultValue'=> '0',
			'valueRule' => ''
		),
		array('type' => 'String',
			'defaultValue'=> '',
			'valueRule' => ''
		),
		array('type' => 'Text',
			'defaultValue'=> '',
			'valueRule' => ''
		),
		array('type' => 'Radio',
			'defaultValue'=> '',
			'valueRule' => ''
		),
		array('type' => 'CheckBox',
			'defaultValue'=> '',
			'valueRule' => ''
		),
		array('type' => 'Range',
			'defaultValue'=> '',
			'valueRule' => ''
		),
		array('type' => 'Url',
			'defaultValue'=> '',
			'valueRule' => ''
		),
		array('type' => 'Email',
			'defaultValue'=> '',
			'valueRule' => ''
		),
		array('type' => 'Color',
			'defaultValue'=> '',
			'valueRule' => ''
		),
		array('type' => 'DataList',
			'defaultValue'=> '',
			'valueRule' => ''
		),
		array('type' => 'Custom',
			'defaultValue'=> '',
			'valueRule' => ''
		)
	);
	
	foreach ($relationships as $item) {
		$obj = new FieldType();
		$obj->setType($item['type']);
		$obj->setDefaultValue($item['defaultValue']);
		$obj->setValueRule($item['valueRule']);
		$entityManager->persist($obj);
	}
	
	$entityManager->flush();