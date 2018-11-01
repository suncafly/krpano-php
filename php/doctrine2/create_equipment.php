<?php
    require_once "bootstrap.php";
	
	$vehicles = array(
		array(
			'type' => '举高',
			'list' => array( 
				array('subtype' => '高喷', 'data' => []), 
				array('subtype' => '直臂登高、云梯', 'data' => []), 
				array('subtype' => '曲臂登高', 'data' => [])
			)
		),
		array(
			'type' => '专勤',
	        'list' => array(
				array('subtype' => '排烟', 'data' => []), 
				array('subtype' => '照明', 'data' => []),  
				array('subtype' => '抢险', 'data' => []), 
				array('subtype' => '卫勤', 'data' => []),
				array('subtype' => '救护', 'data' => []), 				
				array('subtype' => '宣传', 'data' => []), 
				array('subtype' => '防化', 'data' => []), 				
				array('subtype' => '通信指挥', 'data' => []), 
				array('subtype' => '有毒气体处置', 'data' => [])				
			) 
		),
		array(
			'type' => '后援',
	        'list' => array(
				array('subtype' => '保障', 'data' => []), 
				array('subtype' => '抢修', 'data' => []), 				
				array('subtype' => '排障', 'data' => []), 
				array('subtype' => '拖车', 'data' => []), 
				array('subtype' => '运兵', 'data' => []), 
				array('subtype' => '运输', 'data' => []),  
				array('subtype' => '运渣', 'data' => []), 
				array('subtype' => '吊车', 'data' => []), 
				array('subtype' => '移动供气', 'data' => [])	
			)
		),
		array(
			'type' => '灭火',
	        'list' => array(
				array('subtype' => '干粉', 'data' => []), 
				array('subtype' => '水罐', 'data' => []), 
				array('subtype' => '泡沫', 'data' => []),
				array('subtype' => '涡喷', 'data' => []),  				
				array('subtype' => '联用', 'data' => []),  
				array('subtype' => '液氮', 'data' => []), 
				array('subtype' => '二氧化碳', 'data' => []), 
				array('subtype' => '压缩空气泡沫', 'data' => [])
			)
		)	
	);
	
	$devices = array(
		array(
			'type' =>'基本防护', 
			'list' => array(
				array('subtype' => '消防头盔', 'data' => []),
				array('subtype' => '消防手套', 'data' => []),	
				array('subtype' => '消防腰斧', 'data' => []),	
				array('subtype' => '消防护目镜', 'data' => []),					
				array('subtype' => '消防安全腰带', 'data' => []),	
				array('subtype' => '消防轻型安全绳', 'data' => []),	
				array('subtype' => '消防员呼救器', 'data' => []),		
				array('subtype' => '消防员灭火防护服', 'data' => []),				
				array('subtype' => '消防员灭火防护靴', 'data' => []),	
				array('subtype' => '消防员灭火防护头套', 'data' => []),
				array('subtype' => '抢险救援头盔', 'data' => []),
				array('subtype' => '抢险救援靴', 'data' => []),
				array('subtype' => '抢险救援手套', 'data' => []),				
				array('subtype' => '抢险救援服', 'data' => []),				
				array('subtype' => '正压式消防空气呼吸器', 'data' => []),
				array('subtype' => '佩戴式防爆照明灯', 'data' => []),
				array('subtype' => '方位灯', 'data' => []),
				array('subtype' => '防静电内衣', 'data' => [])
			)
		),
		array(
			'type' =>'特种防护', 
			'list' => array(
				array('subtype' => '防蜂服', 'data' => []),
				array('subtype' => '防爆服', 'data' => []),	
				array('subtype' => '防静电服', 'data' => []),
				array('subtype' => '电绝缘装具', 'data' => []),
				array('subtype' => '内置纯棉手套', 'data' => []),				
				array('subtype' => '消防通用安全绳', 'data' => []),				
				array('subtype' => '消防阻燃毛衣', 'data' => []),					
				array('subtype' => '消防用荧光棒', 'data' => []),			
				array('subtype' => '消防专用救生衣', 'data' => []),
				array('subtype' => '消防防坠落辅助部件', 'data' => []),
				array('subtype' => '消防过滤式综合防毒面具', 'data' => []),				
				array('subtype' => '消防员单兵定位装置', 'data' => []),
				array('subtype' => '消防员降温背心', 'data' => []),	
				array('subtype' => '消防员隔热防护服', 'data' => []),
				array('subtype' => '消防员避火防护服', 'data' => []),					
				array('subtype' => '消防员呼救器后场接收装置', 'data' => []),				
				array('subtype' => '消防Ⅰ类安全吊带', 'data' => []),					
				array('subtype' => '消防Ⅱ类安全吊带', 'data' => []),		
				array('subtype' => '消防Ⅲ类安全吊带', 'data' => []),	
				array('subtype' => '特级化学防护服', 'data' => []),				
				array('subtype' => '一级化学防护服', 'data' => []),
				array('subtype' => '二级化学防护服', 'data' => []),
				array('subtype' => '核沾染防护服', 'data' => []),			
				array('subtype' => '长管空气呼吸器（移动供气源）', 'data' => []),
				array('subtype' => '正压式消防氧气呼吸器', 'data' => []),		
				array('subtype' => '强制送风呼吸器', 'data' => []),
				array('subtype' => '手提式强光照明灯', 'data' => []),		
				array('subtype' => '头骨振动式通信装置', 'data' => []),
				array('subtype' => '防爆手持电台', 'data' => []),			
				array('subtype' => '防高温手套', 'data' => []),
				array('subtype' => '防化手套', 'data' => []),		
				array('subtype' => '潜水装具', 'data' => [])
			)
		),
		array(
			'type' =>'侦检', 
			'list' => array(
				array('subtype' => '测温仪', 'data' => []),
				array('subtype' => '激光测距仪', 'data' => []),				
				array('subtype' => '消防用红外热像仪', 'data' => []),
				array('subtype' => '漏电探测仪', 'data' => []),
				array('subtype' => '水质分析仪', 'data' => []),
				array('subtype' => '电子气象仪', 'data' => []),	
				array('subtype' => '有毒气体探测仪', 'data' => []),
				array('subtype' => '军事毒剂侦检仪', 'data' => []),
				array('subtype' => '可燃气体检测仪', 'data' => []),
				array('subtype' => '音频生命探测仪', 'data' => []),			
				array('subtype' => '雷达生命探测仪', 'data' => []),
				array('subtype' => '核放射探测仪', 'data' => []),
				array('subtype' => '便携危险化学品检测片', 'data' => []),
				array('subtype' => '视频生命探测仪', 'data' => []),		
				array('subtype' => '电子酸碱测试仪', 'data' => []),
				array('subtype' => '移动式生物快速侦检仪', 'data' => []),
				array('subtype' => '无线复合气体探测仪', 'data' => [])				
			)
		),
		array(
			'type' =>'警戒', 
			'list' => array(
				array('subtype' => '出入口标志牌', 'data' => []),
				array('subtype' => '危险警示牌', 'data' => []),			
				array('subtype' => '警戒标志杆', 'data' => []),
				array('subtype' => '闪光警示灯', 'data' => []),			
				array('subtype' => '手持扩音器', 'data' => []),				
				array('subtype' => '隔离警示带', 'data' => []),
				array('subtype' => '锥型事故标志柱', 'data' => [])			
			)
		),
		array(
			'type' =>'救生', 
			'list' => array(
				array('subtype' => '灭火毯', 'data' => []),			
				array('subtype' => '救援支架', 'data' => []),
				array('subtype' => '救生抛投器', 'data' => []),
				array('subtype' => '救生照明线', 'data' => []),	
				array('subtype' => '救生软梯', 'data' => []),			
				array('subtype' => '救生缓降器', 'data' => []),	
				array('subtype' => '救生衣', 'data' => []),
				array('subtype' => '救生圈', 'data' => []),	
				array('subtype' => '机动橡皮舟', 'data' => []),	
				array('subtype' => '水面漂浮救生绳', 'data' => []),	
				array('subtype' => '躯体固定气囊', 'data' => []),
				array('subtype' => '肢体固定气囊', 'data' => []),
				array('subtype' => '婴儿呼吸袋', 'data' => []),
				array('subtype' => '消防过滤式自救呼吸器', 'data' => []),
				array('subtype' => '消防救生气垫', 'data' => []),				
				array('subtype' => '折叠式担架', 'data' => []),			
				array('subtype' => '伤员固定抬板', 'data' => []),
				array('subtype' => '多功能担架', 'data' => []),
				array('subtype' => '敛尸袋', 'data' => []),
				array('subtype' => '医药急救箱', 'data' => []),
				array('subtype' => '医用简易呼吸器', 'data' => []),
				array('subtype' => '长管空气呼吸器（移动供气源）', 'data' => []),				
				array('subtype' => '气动起重气垫', 'data' => []),
				array('subtype' => '单杠梯', 'data' => []),	
				array('subtype' => '自喷荧光漆', 'data' => []),
				array('subtype' => '电源逆变器', 'data' => []),
				array('subtype' => '六米拉梯', 'data' => []),
				array('subtype' => '九米拉梯', 'data' => []),
				array('subtype' => '十五米金属拉梯', 'data' => []),
				array('subtype' => '挂钩梯', 'data' => []),
				array('subtype' => '矛勾', 'data' => []),
				array('subtype' => '正压式空气呼吸器充气泵', 'data' => [])
			)
		),
		array(
			'type' =>'破拆', 
			'list' => array(
				array('subtype' => '铁锹', 'data' => []),
				array('subtype' => '铁铤', 'data' => []),				
				array('subtype' => '撬棍', 'data' => []),
				array('subtype' => '冲击钻', 'data' => []),
				array('subtype' => '凿岩机', 'data' => []),					
				array('subtype' => '丁字镐', 'data' => []),
				array('subtype' => '消防斧', 'data' => []),
				array('subtype' => '消防大锤', 'data' => []),				
				array('subtype' => '毁锁器', 'data' => []),			
				array('subtype' => '无齿锯', 'data' => []),
				array('subtype' => '机动链锯', 'data' => []),
				array('subtype' => '双轮异向切割锯', 'data' => []),
				array('subtype' => '液压千斤顶', 'data' => []),
				array('subtype' => '液压万向剪切钳', 'data' => []),				
				array('subtype' => '玻璃破碎器', 'data' => []),				
				array('subtype' => '气动切割刀', 'data' => []),
				array('subtype' => '多功能刀具', 'data' => []),
				array('subtype' => '多功能挠钩', 'data' => []),
				array('subtype' => '电动剪扩钳', 'data' => []),				
				array('subtype' => '绝缘剪断钳', 'data' => []),				
				array('subtype' => '重型支撑套具', 'data' => []),
				array('subtype' => '手持式钢筋速断器', 'data' => []),
				array('subtype' => '便携式汽油金属切割器', 'data' => []),	
				array('subtype' => '手动破拆工具组', 'data' => []),
				array('subtype' => '液压破拆工具组', 'data' => []),
				array('subtype' => '混凝土液压破拆工具组', 'data' => []),				
				array('subtype' => '便携式防盗门破拆工具组', 'data' => [])
			)
		),
		array(
			'type' =>'堵漏', 
			'list' => array(
				array('subtype' => '无火花工具', 'data' => []),			
				array('subtype' => '强磁堵漏工具', 'data' => []),				
				array('subtype' => '注入式堵漏工具', 'data' => []),
				array('subtype' => '粘贴式堵漏工具', 'data' => []),
				array('subtype' => '电磁式堵漏工具', 'data' => []),
				array('subtype' => '堵漏枪', 'data' => []),
				array('subtype' => '木制堵漏楔', 'data' => []),	
				array('subtype' => '高温高压堵漏胶棒', 'data' => []),					
				array('subtype' => '金属堵漏套管', 'data' => []),
				array('subtype' => '阀门堵漏套具', 'data' => []),	
				array('subtype' => '捆绑式堵漏袋', 'data' => []),
				array('subtype' => '下水道阻流袋', 'data' => []),				
				array('subtype' => '内封式堵漏袋', 'data' => []),
				array('subtype' => '外封式堵漏袋', 'data' => []),
				array('subtype' => '气动吸盘式堵漏器', 'data' => [])			

			)
		),	
		array(
			'type' => '输转', 
			'list' => array(
				array('subtype' => '吸附垫', 'data' => []),	
				array('subtype' => '集污袋', 'data' => []),					
				array('subtype' => '围油栏', 'data' => []),
				array('subtype' => '排污泵', 'data' => []),
				array('subtype' => '泡沫吸液泵', 'data' => []),					
				array('subtype' => '防爆输转泵', 'data' => []),
				array('subtype' => '手动隔膜抽吸泵', 'data' => []),
				array('subtype' => '粘稠液体抽吸泵', 'data' => []),
				array('subtype' => '有毒物质密封桶', 'data' => []),
				array('subtype' => '浮艇泵', 'data' => [])					
			)
		),	
		array(
			'type' =>'洗消', 
			'list' => array(
				array('subtype' => '消毒粉', 'data' => []),	
				array('subtype' => '有机磷降解酶', 'data' => []),				
				array('subtype' => '三合二洗消剂', 'data' => []),			
				array('subtype' => '强酸、碱清洗剂', 'data' => []),
				array('subtype' => '三合一强氧化洗消粉', 'data' => []),		
				array('subtype' => '强酸、碱洗消器', 'data' => []),
				array('subtype' => '生化洗消装置', 'data' => []),
				array('subtype' => '公众洗消站', 'data' => []),		
				array('subtype' => '简易洗消喷淋器', 'data' => []),
				array('subtype' => '单人洗消帐篷', 'data' => [])			
			)
		),
		array(
			'type' =>'排烟照明', 
			'list' => array(
				array('subtype' => '移动发电机', 'data' => []),
				array('subtype' => '移动照明灯组', 'data' => []),				
				array('subtype' => '移动式排烟机', 'data' => []),
				array('subtype' => '消防排烟机器人', 'data' => [])	,			
				array('subtype' => '坑道小型空气输送机', 'data' => [])
			)
		),
		array(
			'type' =>'射水', 
			'list' => array(
				array('subtype' => '16mm—直流水枪', 'data' => []),				
				array('subtype' => '19mm—直流水枪', 'data' => []),
				array('subtype' => '自摆炮', 'data' => []),	
				array('subtype' => '移动消防炮', 'data' => []),	
				array('subtype' => '泡沫枪', 'data' => []),				
				array('subtype' => '泡沫钩管', 'data' => []),
				array('subtype' => '中倍数泡沫发生器', 'data' => []),
				array('subtype' => '高倍数泡沫发生器', 'data' => []),				
				array('subtype' => '无后座力多功能水枪', 'data' => [])				
			)
		),	
		array(
			'type' =>'输水', 
			'list' => array(
				array('subtype' => '水囊、水槽', 'data' => []),
				array('subtype' => '水带护桥', 'data' => []),
				array('subtype' => '水带挂钩', 'data' => []),	
				array('subtype' => '水带包布', 'data' => []),	
				array('subtype' => '水幕水带', 'data' => []),		
				array('subtype' => '65卡扣—水带', 'data' => []),
				array('subtype' => '80卡扣—水带', 'data' => []),
				array('subtype' => '65拧口—水带', 'data' => []),
				array('subtype' => '80拧口—水带', 'data' => []),	
				array('subtype' => '50转65—异径接口', 'data' => []),	
				array('subtype' => '65转50—异径接口', 'data' => []),
				array('subtype' => '65转65—异径接口', 'data' => []),				
				array('subtype' => '65转80—异径接口', 'data' => []),
				array('subtype' => '80转65—异径接口', 'data' => []),				
				array('subtype' => '吸水管', 'data' => []),
				array('subtype' => '集水器', 'data' => []),
				array('subtype' => '分水器', 'data' => []),
				array('subtype' => '止水器', 'data' => []),	
		        array('subtype' => '地上消火栓扳手', 'data' => []),			
				array('subtype' => '地下消火栓钥匙', 'data' => [])
			)
		)			
	);
	
	$arr = array(
		'Vehicle' => $vehicles,
		'Device' => $devices
	);
	
	$index = 0;
	foreach ( $arr as $key => $data ) {
		for ( $i = 0, $count = count($data); $i < $count; $i++ ) {
			$type = $data[$i]['type'];
		
			$list = $data[$i]['list'];

			for ( $j = 0, $listLen = count($list); $j < $listLen; $j++ ) {
				$subType = $list[$j]['subtype'];
			
				$equip = new Equipment();
				$equip->setCategoryId($index);
				$equip->setCategoryName($key);
				$equip->setType($type);
				$equip->setSubType($subType);
				$entityManager->persist($equip);
			}
		}
		
		$index++;
	}
	
	$entityManager->flush();