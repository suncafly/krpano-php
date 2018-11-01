<?php
	require_once "bootstrap.php";
    
    $str = file_get_contents('C:\wamp64\tmp\targets.txt');//将整个文件内容读入到一个字符串中
    $str_encoding = mb_convert_encoding($str, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');//转换字符集（编码）
	$array = explode("\n", $str_encoding);//转换成数组
	//var_dump($array);
	$row = array();
	$i = 0;
    foreach ($array as $row) {
      	$tx =   explode("     ", $row);
	  	$xx =   explode("\t", $tx[0]);

		$cityId = 21;
		$entityName = 'FireHydrant';
		
	  	$fireHydrant = new FireHydrant();
	  	
	  
  		$fireHydrant = new FireHydrant();
		
		$fireHydrant->setXhsName('消火栓'.$i);
		$fireHydrant->setXhsCode('');
		$fireHydrant->setXhsAddress('宿州');
		$fireHydrant->setLon($xx[1]);
		$fireHydrant->setLati($xx[0]);
		$fireHydrant->setXhsAdminRegion('');
		$fireHydrant->setXhsBelongCompany('');
		$fireHydrant->setXhsBelongFireTeam('');
		$fireHydrant->setXhsBelongPipeWeb('');
		$fireHydrant->setXhsState('');
		$fireHydrant->setXhsPlaceType('');
		$fireHydrant->setXhsPipeDiameter('');
		$fireHydrant->setXhsPipePress('');
		$fireHydrant->setXhsWaterFrom('');
		$fireHydrant->setXhsContentType('');
		$fireHydrant->setXhsContentSize('');
		$fireHydrant->setXhsOtherInfo('');
		$fireHydrant->setImgUrl('img/markerImg/fireHydrant1.png');
		
		//找出当前城市下所有的类型ID
		$city = $entityManager->getRepository('City')->find($cityId);
		
		$Target_Type = null;
		if($city) {
			
            $row = $city->getTargetTypes();

            foreach ($row as $type) {
            	
				//唯一编号
				$name = $type->getEntityClassName();
				if($name == $entityName ) {
				
					$Target_Type =  $type;	
				}
            }
		}
	 	
	
	
	    $fireHydrant->setTargetType($Target_Type);
	
	
	    $entityManager->persist($fireHydrant);
	
	    $Target_Type->addFireHydrantList($fireHydrant);
	
	    $entityManager->flush();
		
		$i++;
		
		echo $fireHydrant->getId() . "\n";
    }
	
// // var_dump(count($arr));
//	for($i = 0; $i<count($arr);$i++) 
//	{
//		
//	}
//	for($i=0; $i〈count($arr); $i++) 
//	{ 
//		//echo $arr[$i].'〈br /〉'; 
//	} 
//  //去除值中的空格
//  foreach ($arr as &$row) {
//      $row = trim($row);
//		
//  }
//
//  unset($row);
    //得到后的数组
   
//	$types = [
//		
//		array("typeDesc" => "重点单位", 'city'=>'8'),
//		array("typeDesc" => "重点单位", 'city'=>'8'),
//		array("typeDesc" => "消防栓", 'city'=>'8'),
//		array("typeDesc" => "消防总队", 'city'=>'8'),
//		array("typeDesc" => "消防支队", 'city'=>'8')
//	];
	
//	foreach( $row as $type ) {
//		$city = $entityManager->find('City', $type['city']);
//		$TargetType = new TargetType();
//
//		$TargetType->setTypeDesc($type['typeDesc']);
//		$dt = new DateTime('NOW');
//
//		$t = $dt->format('Y-m-d H:i:s');
//		$TargetType->setCreatedTime(new DateTime($t));
//		$TargetType->setModifyTime(new DateTime($t));
//
//		$TargetType->setCity($city);
//
//		$entityManager->persist($TargetType);
//	}
//	
//	$entityManager->flush();
//
//	$fireHydrant = new FireHydrant();
//	
//	$fireHydrant->setUsername('root');
//	$fireHydrant->setPassword('123456');
//	$fireHydrant->setActived(true);
//	$fireHydrant->setDepartment("天鹅湖中队");
//	$fireHydrant->setSessionId('');
//	$dt = new DateTime('NOW');
//	
//	$t = $dt->format('Y-m-d H:i:s');
//	$user->setCreatedTime(new DateTime($t));
//	$user->setLoginTime(new DateTime($t));
//	$user->setIsOnline(false);
//	$user->setPrivilege('200'); // 支队接警员权限：100，中队接警员权限：200, 普通用户：300
//	$user->setCity($city);
//	$user->setDepartmentId(7);
//	$entityManager->persist($user);
//	
//	$entityManager->flush();
//	
//	echo $user->getId() . "\n";
