<?php
    require_once "bootstrap.php";
	
	$departments = array(
		array('id' => 20, 'name' =>'阜南大队阜南中队' ),
		array('id' => 21,  'name' => '界首大队界首中队'),
		array('id' => 22,  'name' => '临泉大队临泉中队'),
		array('id' => 23,  'name' => '太和大队国泰路中队'),
		array('id' => 24,  'name' =>'颍东大队颍东中队' ),
		array('id' => 25,  'name' =>'颍泉大队古泉路中队' ),
		array('id' => 26, 'name' =>'颍上大队城北新区中队' ),
		array('id' => 27, 'name' => '瀛洲大队清河路中队'),
		array('id' => 28, 'name' =>'瀛洲大队瀛洲中队' )
	);
	
	$vendors = array('沈阳捷通消防车有限公司','北奔重卡','沈阳捷通','豪沃','五十铃', '');
	$states = array('正常','执勤','维修');
	$servicesLife = array(0, 24, 36, 48, 60, 120);
	$cityId = 15;
	$equipmentId = 0;
	
	$purchaseDates = array('2013-8-6', '2013-12-8', '2014-12-8', '2017-11-3','2015-4-3','2016-12-8');
	
	
	$types = array(
		array('type' => '举高','subtype' => '高喷', 'data' => []), 
		array('type' => '举高','subtype' => '直臂登高、云梯', 'data' => []), 
		array('type' => '举高','subtype' => '曲臂登高', 'data' => []),

		array('type' => '专勤','subtype' => '排烟', 'data' => []), 
		array('type' => '专勤','subtype' => '照明', 'data' => []),  
		array('type' => '专勤','subtype' => '抢险', 'data' => []), 
		array('type' => '专勤','subtype' => '卫勤', 'data' => []),
		array('type' => '专勤','subtype' => '救护', 'data' => []), 				
		array('type' => '专勤','subtype' => '宣传', 'data' => []), 
		array('type' => '专勤','subtype' => '防化', 'data' => []), 				
		array('type' => '专勤','subtype' => '通信指挥', 'data' => []), 
		array('type' => '专勤','subtype' => '有毒气体处置', 'data' => []),
		array('type' => '后援','subtype' => '保障', 'data' => []), 
		array('type' => '后援','subtype' => '抢修', 'data' => []), 				
		array('type' => '后援','subtype' => '排障', 'data' => []), 
		array('type' => '后援','subtype' => '拖车', 'data' => []), 
		array('type' => '后援','subtype' => '运兵', 'data' => []), 
		array('type' => '后援','subtype' => '运输', 'data' => []),  
		array('type' => '后援','subtype' => '运渣', 'data' => []), 
		array('type' => '后援','subtype' => '吊车', 'data' => []), 
		array('type' => '后援','subtype' => '移动供气', 'data' => []),
		array('type' => '灭火','subtype' => '干粉', 'data' => []), 
		array('type' => '灭火','subtype' => '水罐', 'data' => []), 
		array('type' => '灭火','subtype' => '泡沫', 'data' => []),
		array('type' => '灭火','subtype' => '涡喷', 'data' => []),  				
		array('type' => '灭火','subtype' => '联用', 'data' => []),  
		array('type' => '灭火','subtype' => '液氮', 'data' => []), 
		array('type' => '灭火','subtype' => '二氧化碳', 'data' => []), 
		array('type' => '灭火','subtype' => '压缩空气泡沫', 'data' => [])
	);
	
	$city = $entityManager->find('City', $cityId);
		
	for( $i = 0, $departmentsLen = count($departments); $i < $departmentsLen; $i++ ) {
		$departmentId = $departments[$i]['id'];
		$departmentName = $departments[$i]['name'];
		
		$index = 1;
		for ( $j = 0, $typesLen = count($types); $j < $typesLen; $j++ ) {
			$type = $types[$j]['type'];
			$subtype = $types[$j]['subtype'];
			
			$name = "{$type} - {$subtype} - {$index}";
			
			$vendor = $vendors[rand(0,count($vendors)-1)];
			

			$productionDate = '';		
			
			$purchaseDate = $purchaseDates[rand(0,count($purchaseDates)-1)];
			
			$licensePlateNumber = '';
			
			$passengersAmount = rand(0,5);
			
			$serviceLife = $servicesLife[rand(0,count($servicesLife)-1)];
			
			$state = $states[rand(0,count($states)-1)];
			
			$equipment = $entityManager->getRepository('Equipment')->findOneBy(array('type' => $type, 'subType' => $subtype));
			
			$vehicle = new Vehicle();
			
			$vehicle->setType($type);
			$vehicle->setSubType($subtype);
			$vehicle->setDepartment($departmentName);
			$vehicle->setDepartmentId($departmentId);
			$vehicle->setName($name);
			$vehicle->setVendor($vendor);
			$vehicle->setProductionDate(new DateTime($productionDate));
			$vehicle->setPurchasingDate(new DateTime($purchaseDate));
			$vehicle->setState($state);
			$vehicle->setPassengersAmount($passengersAmount);
			$vehicle->setServiceLife($serviceLife);
			$vehicle->setCity($city);
			$vehicle->setEquipment($equipment);
			$vehicle->setNote('');
			$vehicle->setLicensePlateNumber('');
			$vehicle->setUsed(false);
			$entityManager->persist($vehicle);
			
			$index++;
		}
	}

	$entityManager->flush();