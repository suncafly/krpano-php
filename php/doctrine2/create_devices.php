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
	$servicesLife = array(0, 24, 36, 48, 60, 12, 96, 120);
	$cityId = 15;
	$equipmentId = 0;
	
	$purchaseDates = array('2013-8-6', '2013-12-8', '2014-12-8', '2017-11-3','2015-4-3','2016-12-8');
	
	
	$types = array(
		array('type' =>'基本防护', 'subtype' => '消防头盔', 'data' => []),
		array('type' =>'基本防护', 'subtype' => '消防手套', 'data' => []),	
		array('type' =>'基本防护', 'subtype' => '消防腰斧', 'data' => []),	
		array('type' =>'基本防护', 'subtype' => '消防护目镜', 'data' => []),			
		array('type' =>'基本防护', 'subtype' => '消防安全腰带', 'data' => []),	
		array('type' =>'基本防护', 'subtype' => '消防轻型安全绳', 'data' => []),	
		array('type' =>'基本防护', 'subtype' => '消防员呼救器', 'data' => []),		
		array('type' =>'基本防护', 'subtype' => '消防员灭火防护服', 'data' => []),		
		array('type' =>'基本防护', 'subtype' => '消防员灭火防护靴', 'data' => []),	
		array('type' =>'基本防护', 'subtype' => '消防员灭火防护头套', 'data' => []),
		array('type' =>'基本防护', 'subtype' => '抢险救援头盔', 'data' => []),
		array('type' =>'基本防护', 'subtype' => '抢险救援靴', 'data' => []),
		array('type' =>'基本防护', 'subtype' => '抢险救援手套', 'data' => []),		
		array('type' =>'基本防护', 'subtype' => '抢险救援服', 'data' => []),		
		array('type' =>'基本防护', 'subtype' => '正压式消防空气呼吸器', 'data' => []),
		array('type' =>'基本防护', 'subtype' => '佩戴式防爆照明灯', 'data' => []),
		array('type' =>'基本防护', 'subtype' => '方位灯', 'data' => []),
		array('type' =>'基本防护', 'subtype' => '防静电内衣', 'data' => []),

		array('type' =>'特种防护', 'subtype' => '防蜂服', 'data' => []),
		array('type' =>'特种防护', 'subtype' => '防爆服', 'data' => []),	
		array('type' =>'特种防护', 'subtype' => '防静电服', 'data' => []),
		array('type' =>'特种防护', 'subtype' => '电绝缘装具', 'data' => []),
		array('type' =>'特种防护', 'subtype' => '内置纯棉手套', 'data' => []),		
		array('type' =>'特种防护', 'subtype' => '消防通用安全绳', 'data' => []),		
		array('type' =>'特种防护', 'subtype' => '消防阻燃毛衣', 'data' => []),			
		array('type' =>'特种防护', 'subtype' => '消防用荧光棒', 'data' => []),			
		array('type' =>'特种防护', 'type' =>'特种防护', 'subtype' => '消防专用救生衣', 'data' => []),
		array('type' =>'特种防护', 'subtype' => '消防防坠落辅助部件', 'data' => []),
		array('type' =>'特种防护', 'subtype' => '消防过滤式综合防毒面具', 'data' => []),		
		array('type' =>'特种防护', 'subtype' => '消防员单兵定位装置', 'data' => []),
		array('type' =>'特种防护', 'subtype' => '消防员降温背心', 'data' => []),	
		array('type' =>'特种防护', 'subtype' => '消防员隔热防护服', 'data' => []),
		array('type' =>'特种防护', 'subtype' => '消防员避火防护服', 'data' => []),			
		array('type' =>'特种防护', 'subtype' => '消防员呼救器后场接收装置', 'data' => []),		
		array('type' =>'特种防护', 'subtype' => '消防Ⅰ类安全吊带', 'data' => []),			
		array('type' =>'特种防护', 'subtype' => '消防Ⅱ类安全吊带', 'data' => []),		
		array('type' =>'特种防护', 'subtype' => '消防Ⅲ类安全吊带', 'data' => []),	
		array('type' =>'特种防护', 'subtype' => '特级化学防护服', 'data' => []),		
		array('type' =>'特种防护', 'subtype' => '一级化学防护服', 'data' => []),
		array('type' =>'特种防护', 'subtype' => '二级化学防护服', 'data' => []),
		array('type' =>'特种防护', 'subtype' => '核沾染防护服', 'data' => []),			
		array('type' =>'特种防护', 'subtype' => '长管空气呼吸器（移动供气源）', 'data' => []),
		array('type' =>'特种防护', 'subtype' => '正压式消防氧气呼吸器', 'data' => []),		
		array('type' =>'特种防护', 'subtype' => '强制送风呼吸器', 'data' => []),
		array('type' =>'特种防护', 'subtype' => '手提式强光照明灯', 'data' => []),		
		array('type' =>'特种防护', 'subtype' => '头骨振动式通信装置', 'data' => []),
		array('type' =>'特种防护', 'subtype' => '防爆手持电台', 'data' => []),			
		array('type' =>'特种防护', 'subtype' => '防高温手套', 'data' => []),
		array('type' =>'特种防护', 'subtype' => '防化手套', 'data' => []),		
		array('type' =>'特种防护', 'subtype' => '潜水装具', 'data' => []),

		array('type' =>'侦检', 'subtype' => '测温仪', 'data' => []),
		array('type' =>'侦检', 'subtype' => '激光测距仪', 'data' => []),		
		array('type' =>'侦检', 'subtype' => '消防用红外热像仪', 'data' => []),
		array('type' =>'侦检', 'subtype' => '漏电探测仪', 'data' => []),
		array('type' =>'侦检', 'subtype' => '水质分析仪', 'data' => []),
		array('type' =>'侦检', 'subtype' => '电子气象仪', 'data' => []),	
		array('type' =>'侦检', 'subtype' => '有毒气体探测仪', 'data' => []),
		array('type' =>'侦检', 'subtype' => '军事毒剂侦检仪', 'data' => []),
		array('type' =>'侦检', 'subtype' => '可燃气体检测仪', 'data' => []),
		array('type' =>'侦检', 'subtype' => '音频生命探测仪', 'data' => []),			
		array('type' =>'侦检', 'subtype' => '雷达生命探测仪', 'data' => []),
		array('type' =>'侦检', 'subtype' => '核放射探测仪', 'data' => []),
		array('type' =>'侦检', 'subtype' => '便携危险化学品检测片', 'data' => []),
		array('type' =>'侦检', 'subtype' => '视频生命探测仪', 'data' => []),		
		array('type' =>'侦检', 'subtype' => '电子酸碱测试仪', 'data' => []),
		array('type' =>'侦检', 'subtype' => '移动式生物快速侦检仪', 'data' => []),
		array('type' =>'侦检', 'subtype' => '无线复合气体探测仪', 'data' => [])	,			

		array('type' =>'警戒', 'subtype' => '出入口标志牌', 'data' => []),
		array('type' =>'警戒', 'subtype' => '危险警示牌', 'data' => []),			
		array('type' =>'警戒', 'subtype' => '警戒标志杆', 'data' => []),
		array('type' =>'警戒', 'subtype' => '闪光警示灯', 'data' => []),			
		array('type' =>'警戒', 'subtype' => '手持扩音器', 'data' => []),		
		array('type' =>'警戒', 'subtype' => '隔离警示带', 'data' => []),
		array('type' =>'警戒', 'subtype' => '锥型事故标志柱', 'data' => []),			

		array('type' =>'救生','subtype' => '灭火毯', 'data' => []),			
		array('type' =>'救生','subtype' => '救援支架', 'data' => []),
		array('type' =>'救生','subtype' => '救生抛投器', 'data' => []),
		array('type' =>'救生','subtype' => '救生照明线', 'data' => []),	
		array('type' =>'救生','subtype' => '救生软梯', 'data' => []),			
		array('type' =>'救生','subtype' => '救生缓降器', 'data' => []),	
		array('type' =>'救生','subtype' => '救生衣', 'data' => []),
		array('type' =>'救生','subtype' => '救生圈', 'data' => []),	
		array('type' =>'救生','subtype' => '机动橡皮舟', 'data' => []),	
		array('type' =>'救生','subtype' => '水面漂浮救生绳', 'data' => []),	
		array('type' =>'救生','subtype' => '躯体固定气囊', 'data' => []),
		array('type' =>'救生','subtype' => '肢体固定气囊', 'data' => []),
		array('type' =>'救生','subtype' => '婴儿呼吸袋', 'data' => []),
		array('type' =>'救生','subtype' => '消防过滤式自救呼吸器', 'data' => []),
		array('type' =>'救生','subtype' => '消防救生气垫', 'data' => []),		
		array('type' =>'救生','subtype' => '折叠式担架', 'data' => []),			
		array('type' =>'救生','subtype' => '伤员固定抬板', 'data' => []),
		array('type' =>'救生','subtype' => '多功能担架', 'data' => []),
		array('type' =>'救生','subtype' => '敛尸袋', 'data' => []),
		array('type' =>'救生','subtype' => '医药急救箱', 'data' => []),
		array('type' =>'救生','subtype' => '医用简易呼吸器', 'data' => []),
		array('type' =>'救生','subtype' => '长管空气呼吸器（移动供气源）', 'data' => []),		
		array('type' =>'救生','subtype' => '气动起重气垫', 'data' => []),
		array('type' =>'救生','subtype' => '单杠梯', 'data' => []),	
		array('type' =>'救生','subtype' => '自喷荧光漆', 'data' => []),
		array('type' =>'救生','subtype' => '电源逆变器', 'data' => []),
		array('type' =>'救生','subtype' => '六米拉梯', 'data' => []),
		array('type' =>'救生','subtype' => '九米拉梯', 'data' => []),
		array('type' =>'救生','subtype' => '十五米金属拉梯', 'data' => []),
		array('type' =>'救生','subtype' => '挂钩梯', 'data' => []),
		array('type' =>'救生','subtype' => '矛勾', 'data' => []),
		array('type' =>'救生','subtype' => '正压式空气呼吸器充气泵', 'data' => []),

		array('type' =>'破拆', 'subtype' => '铁锹', 'data' => []),
		array('type' =>'破拆', 'subtype' => '铁铤', 'data' => []),		
		array('type' =>'破拆', 'subtype' => '撬棍', 'data' => []),
		array('type' =>'破拆', 'subtype' => '冲击钻', 'data' => []),
		array('type' =>'破拆', 'subtype' => '凿岩机', 'data' => []),			
		array('type' =>'破拆', 'subtype' => '丁字镐', 'data' => []),
		array('type' =>'破拆', 'subtype' => '消防斧', 'data' => []),
		array('type' =>'破拆', 'subtype' => '消防大锤', 'data' => []),		
		array('type' =>'破拆', 'subtype' => '毁锁器', 'data' => []),			
		array('type' =>'破拆', 'subtype' => '无齿锯', 'data' => []),
		array('type' =>'破拆', 'subtype' => '机动链锯', 'data' => []),
		array('type' =>'破拆', 'subtype' => '双轮异向切割锯', 'data' => []),
		array('type' =>'破拆', 'subtype' => '液压千斤顶', 'data' => []),
		array('type' =>'破拆', 'subtype' => '液压万向剪切钳', 'data' => []),		
		array('type' =>'破拆', 'subtype' => '玻璃破碎器', 'data' => []),		
		array('type' =>'破拆', 'subtype' => '气动切割刀', 'data' => []),
		array('type' =>'破拆', 'subtype' => '多功能刀具', 'data' => []),
		array('type' =>'破拆', 'subtype' => '多功能挠钩', 'data' => []),
		array('type' =>'破拆', 'subtype' => '电动剪扩钳', 'data' => []),		
		array('type' =>'破拆', 'subtype' => '绝缘剪断钳', 'data' => []),		
		array('type' =>'破拆', 'subtype' => '重型支撑套具', 'data' => []),
		array('type' =>'破拆', 'subtype' => '手持式钢筋速断器', 'data' => []),
		array('type' =>'破拆', 'subtype' => '便携式汽油金属切割器', 'data' => []),	
		array('type' =>'破拆', 'subtype' => '手动破拆工具组', 'data' => []),
		array('type' =>'破拆', 'subtype' => '液压破拆工具组', 'data' => []),
		array('type' =>'破拆', 'subtype' => '混凝土液压破拆工具组', 'data' => []),		
		array('type' =>'破拆', 'subtype' => '便携式防盗门破拆工具组', 'data' => []),

		array('type' =>'堵漏', 'subtype' => '无火花工具', 'data' => []),			
		array('type' =>'堵漏', 'subtype' => '强磁堵漏工具', 'data' => []),		
		array('type' =>'堵漏', 'subtype' => '注入式堵漏工具', 'data' => []),
		array('type' =>'堵漏', 'subtype' => '粘贴式堵漏工具', 'data' => []),
		array('type' =>'堵漏', 'subtype' => '电磁式堵漏工具', 'data' => []),
		array('type' =>'堵漏', 'subtype' => '堵漏枪', 'data' => []),
		array('type' =>'堵漏', 'subtype' => '木制堵漏楔', 'data' => []),	
		array('type' =>'堵漏', 'subtype' => '高温高压堵漏胶棒', 'data' => []),			
		array('type' =>'堵漏', 'subtype' => '金属堵漏套管', 'data' => []),
		array('type' =>'堵漏', 'subtype' => '阀门堵漏套具', 'data' => []),	
		array('type' =>'堵漏', 'subtype' => '捆绑式堵漏袋', 'data' => []),
		array('type' =>'堵漏', 'subtype' => '下水道阻流袋', 'data' => []),		
		array('type' =>'堵漏', 'subtype' => '内封式堵漏袋', 'data' => []),
		array('type' =>'堵漏', 'subtype' => '外封式堵漏袋', 'data' => []),
		array('type' =>'堵漏', 'subtype' => '气动吸盘式堵漏器', 'data' => []),			

		array('type' => '输转','subtype' => '吸附垫', 'data' => []),	
		array('type' => '输转','subtype' => '集污袋', 'data' => []),			
		array('type' => '输转','subtype' => '围油栏', 'data' => []),
		array('type' => '输转','subtype' => '排污泵', 'data' => []),
		array('type' => '输转','subtype' => '泡沫吸液泵', 'data' => []),			
		array('type' => '输转','subtype' => '防爆输转泵', 'data' => []),
		array('type' => '输转','subtype' => '手动隔膜抽吸泵', 'data' => []),
		array('type' => '输转','subtype' => '粘稠液体抽吸泵', 'data' => []),
		array('type' => '输转','subtype' => '有毒物质密封桶', 'data' => []),
		array('type' => '输转','subtype' => '浮艇泵', 'data' => [])	,		

		array('type' =>'洗消', 'subtype' => '消毒粉', 'data' => []),	
		array('type' =>'洗消', 'subtype' => '有机磷降解酶', 'data' => []),		
		array('type' =>'洗消', 'subtype' => '三合二洗消剂', 'data' => []),			
		array('type' =>'洗消', 'subtype' => '强酸、碱清洗剂', 'data' => []),
		array('type' =>'洗消', 'subtype' => '三合一强氧化洗消粉', 'data' => []),		
		array('type' =>'洗消', 'subtype' => '强酸、碱洗消器', 'data' => []),
		array('type' =>'洗消', 'subtype' => '生化洗消装置', 'data' => []),
		array('type' =>'洗消', 'subtype' => '公众洗消站', 'data' => []),		
		array('type' =>'洗消', 'subtype' => '简易洗消喷淋器', 'data' => []),
		array('type' =>'洗消', 'subtype' => '单人洗消帐篷', 'data' => []),			

		array('type' =>'排烟照明', 'subtype' => '移动发电机', 'data' => []),
		array('type' =>'排烟照明', 'subtype' => '移动照明灯组', 'data' => []),		
		array('type' =>'排烟照明', 'subtype' => '移动式排烟机', 'data' => []),
		array('type' =>'排烟照明', 'subtype' => '消防排烟机器人', 'data' => [])	,			
		array('type' =>'排烟照明', 'subtype' => '坑道小型空气输送机', 'data' => []),

		array('type' =>'射水','subtype' => '16mm—直流水枪', 'data' => []),		
		array('type' =>'射水','subtype' => '19mm—直流水枪', 'data' => []),
		array('type' =>'射水','subtype' => '自摆炮', 'data' => []),	
		array('type' =>'射水','subtype' => '移动消防炮', 'data' => []),	
		array('type' =>'射水','subtype' => '泡沫枪', 'data' => []),		
		array('type' =>'射水','subtype' => '泡沫钩管', 'data' => []),
		array('type' =>'射水','subtype' => '中倍数泡沫发生器', 'data' => []),
		array('type' =>'射水','subtype' => '高倍数泡沫发生器', 'data' => []),		
		array('type' =>'射水','subtype' => '无后座力多功能水枪', 'data' => []),		

		array('type' =>'输水','subtype' => '水囊、水槽', 'data' => []),
		array('type' =>'输水','subtype' => '水带护桥', 'data' => []),
		array('type' =>'输水','subtype' => '水带挂钩', 'data' => []),	
		array('type' =>'输水','subtype' => '水带包布', 'data' => []),	
		array('type' =>'输水','subtype' => '水幕水带', 'data' => []),		
		array('type' =>'输水','subtype' => '65卡扣—水带', 'data' => []),
		array('type' =>'输水','subtype' => '80卡扣—水带', 'data' => []),
		array('type' =>'输水','subtype' => '65拧口—水带', 'data' => []),
		array('type' =>'输水','subtype' => '80拧口—水带', 'data' => []),	
		array('type' =>'输水','subtype' => '50转65—异径接口', 'data' => []),	
		array('type' =>'输水','subtype' => '65转50—异径接口', 'data' => []),
		array('type' =>'输水','subtype' => '65转65—异径接口', 'data' => []),		
		array('type' =>'输水','subtype' => '65转80—异径接口', 'data' => []),
		array('type' =>'输水','subtype' => '80转65—异径接口', 'data' => []),		
		array('type' =>'输水','subtype' => '吸水管', 'data' => []),
		array('type' =>'输水','subtype' => '集水器', 'data' => []),
		array('type' =>'输水','subtype' => '分水器', 'data' => []),
		array('type' =>'输水','subtype' => '止水器', 'data' => []),	
	    array('type' =>'输水','subtype' => '地上消火栓扳手', 'data' => []),			
		array('type' =>'输水','subtype' => '地下消火栓钥匙', 'data' => [])
	);
	
	$city = $entityManager->find('City', $cityId);
	$index = 1;
	for( $i = 0, $departmentsLen = count($departments); $i < $departmentsLen; $i++ ) {
		$departmentId = $departments[$i]['id'];
		$departmentName = $departments[$i]['name'];
		
		for ( $j = 0, $typesLen = count($types); $j < $typesLen; $j++ ) {
			//if ($index % 5 == 0 ) {
			//	continue;
			//}	
			
			$type = $types[$j]['type'];
			$subtype = $types[$j]['subtype'];
			
			$name = "{$subtype} {$index}";
			
			$vendor = $vendors[rand(0,count($vendors)-1)];
			

			$productionDate = '';		
			
			$purchaseDate = $purchaseDates[rand(0,count($purchaseDates)-1)];
			
			$licensePlateNumber = '';
			
			$passengersAmount = rand(0,5);
			
			$serviceLife = $servicesLife[rand(0,count($servicesLife)-1)];
			
			$state = $states[rand(0,count($states)-1)];			
			
			$equipment = $entityManager->getRepository('Equipment')->findOneBy(array('type' => $type, 'subType' => $subtype));
			
			$device = new Device();
			

			$device->setType($type);
			$device->setSubType($subtype);
			$device->setDepartment($departmentName);
			$device->setDepartmentId($departmentId);
			$device->setName($name);
			$device->setVendor($vendor);
			$device->setProductionDate(new DateTime($productionDate));
			$device->setPurchasingDate(new DateTime($purchaseDate));
			$device->setState($state);
			$device->setServiceLife($serviceLife);
			$device->setCity($city);
			$device->setEquipment($equipment);
			$device->setNote('');
			$device->setUsed(false);
			$entityManager->persist($device);
			
			$index++;
		}
	}

	$entityManager->flush();