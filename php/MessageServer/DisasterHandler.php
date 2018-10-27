<?php
	date_default_timezone_set('PRC');
	
	require_once 'Handler.php';
	require_once 'HallManager.php';
	require_once '/../System/UserPrivilege.php';
	require_once '/../System/DisasterDetailDesc.php';
	/**************************************************************************
	 *
	 * 说明：灾情
	 *
	 *
	 *************************************************************************/
	class DisasterHandler extends AMHandler 
	{
		protected $userName = '';
		protected $cityId   = -1;
		protected $user = null;
			
		public function __construct() 
		{
		}
		
		// 构建支队灾情数据
		protected function handleZhiDui(array $params , $room, $socket, $io, $em)
		{
			$disaster = $em->getRepository('Disaster')->findOneBy(array('id'=>$params['id']));
			//\Doctrine\Common\Util\Debug::dump($disaster);
			if (!$disaster) {
				return false;
			}
			
			// 构建聊天信息
			$chatMessageList = [];
			$chatMessages = $em->getRepository('ChatMessage')->findBy(array('disaster' =>$disaster), array('time' => 'ASC'));
			foreach($chatMessages as $chatMessage) {
				$messageDesc = $chatMessage->getDesc();
				if (array_key_exists($this->userName, $chatMessage->getReceivers())) {
					$chatMessageList[] = $messageDesc;
				}
			}
			
			$dstPostitionList = array();
			
			// 派遣方案	
			$dispatchSchemaList = array();

			for ($dispatchSchema = $disaster->getDispatchSchema(); 
			     $dispatchSchema != null; 
				$dispatchSchema = $dispatchSchema->getUpgradeDispatchSchema()) {
				
				// 救援机构
				$rescueDepartments = $dispatchSchema->getRescueDepartment();
				$rdList = array();
				foreach($rescueDepartments as $rescueDepartment) {
					// 处理阶段
					$dpList = array();
					for ($disasterProcess = $rescueDepartment->getDisasterProcess();
						$disasterProcess != null;
						$disasterProcess = $disasterProcess->getNextDisasterProcess()) {
						$ddd = new DisasterDetailDesc();
						$dpList[] = array(
							'id' => $disasterProcess->getId(),
							'isOk' => $disasterProcess->getIsOk(),
							'type' => $disasterProcess->getProcessState()->getType(),
							'content' => $disasterProcess->getProcessState()->getContent(),
							'time' => $disasterProcess->getTime()->format('Y-m-d H:i:s'),
							'desc' => $ddd->buildDesc($disaster, $dispatchSchema, $rescueDepartment, $disasterProcess, $this->user)
						);
					}
					
					$departmentId = $rescueDepartment->getDepartmentId();
					
					$fireSquadronTeam = $em->find('fireSquadronTeam', $departmentId);
					if ($fireSquadronTeam) {
						$dstPostitionList[] = array('lng' => $fireSquadronTeam->getLon(), 'lat'=> $fireSquadronTeam->getLati());
					}
					
					$rdList[] = array(
						'departmentId' => $rescueDepartment->getDepartmentId(),
						'departmentName' => $rescueDepartment->getDepartmentName(),
						'participants' => $rescueDepartment->getParticipants(),
						'operator' => $rescueDepartment->getOperator(),
						'commanders'   => $rescueDepartment->getCommanders(),
						'disasterProcessList' => $dpList,
						'suggestedVehicles' => $rescueDepartment->getDispatchEquipment()->getSuggestedVehicles(),
						'suggestedDevices' =>$rescueDepartment->getDispatchEquipment()->getSuggestedDevices(),
						'actualVehicles' =>$rescueDepartment->getDispatchEquipment()->getActualVehicles(),
						'actualDevices' =>$rescueDepartment->getDispatchEquipment()->getActualDevices()
					);
				}
				
				$dispatchSchemaList[] = array(
					'id' => $dispatchSchema->getId(),
					'plan2dName' => $dispatchSchema->getPlan2dName(),
					'plan2dUrl'  => $dispatchSchema->getPlan2dUrl(),
					'plan3dName' => $dispatchSchema->getPlan3dName(),
					'plan3dUrl' => $dispatchSchema->getPlan3dUrl(),
					'date' => $dispatchSchema->getDate()->format('Y-m-d'),
					'time' => $dispatchSchema->getTime(),
					'note' => $dispatchSchema->getNote(),
					'rescueDepartmentList' => $rdList,
					'disasterCategory' => $dispatchSchema->getDisasterHandlerSchema()->getDisasterCategory()->getDesc(),
					'disasterDesc' => $dispatchSchema->getDisasterHandlerSchema()->getDisasterDesc()->getDesc()
				);
			}

			$result = array(
				'id'         => $disaster->getId(),
				'contact'    => $disaster->getContact(),
				'telephone'  => $disaster->getTelephone(),
				'address'    => $disaster->getAddress(),
				'addressLng' => $disaster->getAddressLng(),
				'addressLat' => $disaster->getAddressLat(),
				'alarmClosed' => $disaster->getAlarmClosed(),
				'alarmClosedTime' => $disaster->getAlarmClosedTime()->format('Y-m-d H:i:s'),
				'operator'    => $disaster->getOperator(),
				'date' => $disaster->getDate()->format('Y-m-d'),
				'time' => $disaster->getTime(),
				'dispatchSchemaList' => $dispatchSchemaList,
				'chatMessages' => $chatMessageList,
				'members' => $disaster->getMembers(),
				'paths' => array( 
					'src' => array('lng' => $disaster->getAddressLng(), 'lat' => $disaster->getAddressLat()), 
					'dstList' => $dstPostitionList
				)
			);

			$this->setSuccessResult(array('msgType' => ClientMessage::DISASTER, 'data' => $result));
			return true;
		}
		
		// 处理对应中队对应灾情数据
		protected function handleZhongDui(array $params, $room, $socket, $io, $em)
		{
			$disaster = $em->getRepository('Disaster')->findOneBy(array('id'=>$params['id']));
			//\Doctrine\Common\Util\Debug::dump($disaster);
			if (!$disaster) {
				return false;
			}
			
			$departmentId = $this->user->getDepartmentId();
			
			// 构建聊天信息
			$chatMessageList = [];
			$chatMessages = $em->getRepository('ChatMessage')->findBy(array('disaster' =>$disaster), array('time' => 'ASC'));
			foreach($chatMessages as $chatMessage) {
				$messageDesc = $chatMessage->getDesc();
				if (array_key_exists($this->userName, $chatMessage->getReceivers())) {
					$chatMessageList[] = $messageDesc;
				}
			}

			// 自属中队列表
			$selfDepartmentList = array();
			
			// 派遣方案	
			$dispatchSchemaList = array();
			
			$schemaIdx = 1;
			for ($dispatchSchema = $disaster->getDispatchSchema(); 
			     $dispatchSchema != null;
				$dispatchSchema = $dispatchSchema->getUpgradeDispatchSchema(), ++$schemaIdx) {
				$dispatchSchemaId = $dispatchSchema->getId();
				
				// 救援机构
				$rescueDepartments = $dispatchSchema->getRescueDepartment();
				$rdList = array();
				foreach ($rescueDepartments as $rescueDepartment) {
					$rdList[] = array(
						'departmentId' => $rescueDepartment->getDepartmentId(),
						'departmentName' => $rescueDepartment->getDepartmentName(),
						'participants' => $rescueDepartment->getParticipants()
					);
					
					// 存储自己机构
					if ($rescueDepartment->getDepartmentId() == $departmentId) {
						// 处理阶段
						$dpList = array();
						
						$publishTime = null;
						for ($disasterProcess = $rescueDepartment->getDisasterProcess();
							$disasterProcess != null;
							$disasterProcess = $disasterProcess->getNextDisasterProcess()) {
							$ddd = new DisasterDetailDesc();
							
							$dpList[] = array(
								'schemaNumber' => $schemaIdx,
								'id' => $disasterProcess->getId(),
								'isOk' => $disasterProcess->getIsOk(),
								'type' => $disasterProcess->getProcessState()->getType(),
								'content' => $disasterProcess->getProcessState()->getContent(),
								'time' => $disasterProcess->getTime()->format('Y-m-d H:i:s'),
								'desc' => $ddd->buildDesc($disaster, $dispatchSchema, $rescueDepartment, $disasterProcess, $this->user)
							);
							
							if (!$publishTime) {
								$publishTime = $disasterProcess->getTime()->format('Y-m-d H:i:s');
							}
						}
					
						$selfDepartmentList[] = array(
							'dispatchSchemaId' => $dispatchSchemaId,
							'schemaNumber' => $schemaIdx,
							'publishTime'  => $publishTime,
							'departmentId' => $rescueDepartment->getDepartmentId(),
							'departmentName' => $rescueDepartment->getDepartmentName(),
							'participants' => $rescueDepartment->getParticipants(),
							'operator' => $rescueDepartment->getOperator(),
							'commanders'   => $rescueDepartment->getCommanders(),
							'disasterProcessList' => $dpList,
							'suggestedVehicles' => $rescueDepartment->getDispatchEquipment()->getSuggestedVehicles(),
							'suggestedDevices' =>$rescueDepartment->getDispatchEquipment()->getSuggestedDevices(),
							'actualVehicles' =>$rescueDepartment->getDispatchEquipment()->getActualVehicles(),
							'actualDevices' =>$rescueDepartment->getDispatchEquipment()->getActualDevices()
						);
					}
				}
				
				$dispatchSchemaList[] = array(
					'id' => $dispatchSchema->getId(),
					'plan2dName' => $dispatchSchema->getPlan2dName(),
					'plan2dUrl'  => $dispatchSchema->getPlan2dUrl(),
					'plan3dName' => $dispatchSchema->getPlan3dName(),
					'plan3dUrl' => $dispatchSchema->getPlan3dUrl(),
					'date' => $dispatchSchema->getDate()->format('Y-m-d'),
					'time' => $dispatchSchema->getTime(),
					'note' => $dispatchSchema->getNote(),
					'rescueDepartmentList' => $rdList,
					'disasterCategory' => $dispatchSchema->getDisasterHandlerSchema()->getDisasterCategory()->getDesc(),
					'disasterDesc' => $dispatchSchema->getDisasterHandlerSchema()->getDisasterDesc()->getDesc()
				);
			}
			
			$selfDepartmentListLen = count($selfDepartmentList);
			if ($selfDepartmentListLen == 0) {
				return false;
			}
			
			$selfDepartment = null;
			
			if ($this->user->getPrivilege() == UserPrivilege::UP_ZHONGDUI) {
				// 中队接警员权限
				for ($idx = 0; $idx < $selfDepartmentListLen; ++$idx) {
					if ($selfDepartmentList[$idx]['operator'] == "") {
						if (!$selfDepartment) {
							$selfDepartment = $selfDepartmentList[$idx];
						}
					}
					elseif ($this->userName == $selfDepartmentList[$idx]['operator']) {
						$selfDepartment = $selfDepartmentList[$idx];
						break;
					}
				}
			} elseif ($this->user->getPrivilege() == UserPrivilege::UP_ZHONGDUI_STAFF) {
				// 中队救援人员
				for ($idx = 0; $idx < $selfDepartmentListLen; ++$idx) {
					if (array_key_exists($this->userName, $selfDepartmentList[$idx]['participants']) ) {
						$selfDepartment = $selfDepartmentList[$idx];
						break;
					}
				}
			}
			
			if (!$selfDepartment) {
				return false;
			}
			
			if (count($selfDepartment['disasterProcessList'])== 0) {
				return false;
			}
			
			// 当前处理阶段
			$currentDisasterProcess = $selfDepartment['disasterProcessList'][count($selfDepartment['disasterProcessList'])-1];
			
			if (($this->user->getPrivilege() == UserPrivilege::UP_ZHONGDUI_STAFF) && ($currentDisasterProcess['type'] == 0 || $currentDisasterProcess['type'] == 1) ) {
				return false;
			}
			
			$disasterDetailDesc = $currentDisasterProcess['desc'];
			
			$fireSquadronTeam = $em->find('fireSquadronTeam', $selfDepartment['departmentId']);
			if (!$fireSquadronTeam) { 
				return false;
			}
			
			$dstPostition = array('lng' => $fireSquadronTeam->getLon(), 'lat'=> $fireSquadronTeam->getLati());
			
			//array_unshift($disasterDetailDesc, array('name'=>$currentDisasterProcess['time'], 'value' =>'', 'type' => 'text'); 
			
			$result = array(
				'id'         => $disaster->getId(),
				'contact'    => $disaster->getContact(),
				'telephone'  => $disaster->getTelephone(),
				'address'    => $disaster->getAddress(),
				'addressLng' => $disaster->getAddressLng(),
				'addressLat' => $disaster->getAddressLat(),
				'alarmClosed' => $disaster->getAlarmClosed(),
				'alarmClosedTime' => $disaster->getAlarmClosedTime()->format('Y-m-d H:i:s'),				
				'operator'    => $disaster->getOperator(),
				'date' => $disaster->getDate()->format('Y-m-d'),
				'time' => $disaster->getTime(),
				'dispatchSchemaList' => $dispatchSchemaList,
				'chatMessages' => $chatMessageList,
				'selfDepartment'=> $selfDepartment,
				'currentDisasterProcess' => $currentDisasterProcess,
				'members' => $disaster->getMembers(),
				'desc' => $disasterDetailDesc,
				'path' => array( 
					'src' => array('lng' => $disaster->getAddressLng(), 'lat' => $disaster->getAddressLat()), 
					'dst' => $dstPostition
				)
			);

			$this->setSuccessResult(array('msgType' => ClientMessage::DISASTER, 'data' => $result));			
			return true;
		}
		
		public function handle(array $params , $room, $socket, $io, $em) 
		{
			if (!$this->verifyProps(['id'], $params)) {
				return false;
			}
			
			$this->userName = base64_decode(urldecode($params['userName']));
			$this->cityId   = base64_decode(urldecode($params['cityId']));
			
			$this->user = $em->getRepository('User')->findOneBy(array('userName'=>$this->userName));
						 
			if (!$this->user) {
				return false;
			}
			
			if ($this->user->getPrivilege() == UserPrivilege::UP_ZHIDUI) {
				// 支队级灾情信息
				return $this->handleZhiDui($params, $room, $socket, $io, $em);
			} else if ($this->user->getPrivilege() == UserPrivilege::UP_ZHONGDUI || $this->user->getPrivilege() == UserPrivilege::UP_ZHONGDUI_STAFF) {
				// 中队级灾情信息
				return $this->handleZhongDui($params, $room, $socket, $io, $em);
			}
			
			return true;
		}
		
		public function dispatch($socket, $io, $room, $em) 
		{
			$socket->emit('message', $this->result);
		}
		
		public function error($socket, $io, $room, $em) 
		{
			$socket->emit('sysError', $this->result);
		}
	}