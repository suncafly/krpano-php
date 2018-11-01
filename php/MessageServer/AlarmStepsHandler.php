<?php
	date_default_timezone_set('PRC');
	
	require_once 'Handler.php';
	require_once 'HallManager.php';
	require_once '/../System/UserPrivilege.php';
	require_once '/../System/EmployeeLib.php';
	require_once '/../System/DisasterDetailDesc.php';
	require_once "/../PushService/PushService.php";
	
	/**************************************************************************
	 *
	 * 说明：警情各阶段处理
	 *
	 *
	 *************************************************************************/
	class AlarmStepsHandler extends AMHandler 
	{
		protected $userName = '';
		protected $cityId = -1;
		protected $user = null;
		protected $receivers = array();
		
		protected $extraReceivers = array();
		protected $extraResult = array('state' => 'error', 'msg' => '传输有误！');
		
		public function __construct() 
		{
		}
		
		public function handle(array $params , $room, $socket, $io, $em) 
		{
			if (!$this->verifyProps(['userName', 'cityId', 'opCode'], $params)){
				return false;
			}
			
			$this->userName = base64_decode(urldecode($params['userName']));
			$this->cityId   = base64_decode(urldecode($params['cityId']));
			$this->user = $em->getRepository('User')->findOneBy(array('userName'=>$this->userName));
						 
			if (!$this->user) {
				return false;
			}
			
			$opCode = $params['opCode'];
			
			if (!method_exists($this, $opCode)) {
				return false;
			}
			
			if (!call_user_func_array(array($this, $opCode), array($params, $room, $socket, $io, $em))) {
				return false;
			}
			
			return true;
		}
		
		// 确认收到通知，准备出警
		protected function confirmNotification($params, $room, $socket, $io, $em)
		{
			if (!$this->verifyProps(['disasterId', 'dispatchSchemaId'], $params)) {
				return false;
			}
			
			if ($this->user->getPrivilege() != UserPrivilege::UP_ZHONGDUI) {
				return false;
			}
			
			$processStateType = 0;
			
			$dispatchSchemaId = $params['dispatchSchemaId'];
			$disasterId = $params['disasterId'];
			
			// 获取指定灾情
			$disaster = $em->find('Disaster', $disasterId);
			if (!$disaster) {
				return false;
			}
			
			// 获取派遣方案
			for ($ds = $disaster->getDispatchSchema(); $ds != null; $ds = $ds->getUpgradeDispatchSchema()) {
				if ($ds->getId() == $dispatchSchemaId ) {
					break;
				}
			}
			
			if (!$ds) {
				return false;
			}
			
			$rd = null;
			$rds = $ds->getRescueDepartment()->toArray();
			for ($i = 0, $len = count($rds); $i < $len; ++$i) {
				if ($rds[$i]->getDepartmentId() == $this->user->getDepartmentId() && $rds[$i]->getOperator() == "") {
					$rd = $rds[$i];
					break;
				}
			}
			
			if (!$rd) {
				return false;
			}
			
			$currentUserName = $this->user->getUserName();
			
			$rd->setOperator($currentUserName);
			
			$members = $disaster->getMembers();
			
			if (!array_key_exists($currentUserName, $members)) {
				$members[$currentUserName] = array(
					'id'=>$this->user->getId(),
					'userName' => $this->user->getUserName(),
					'name' => $this->user->getName(),
					'departmentId' => $this->user->getDepartmentId(),
					'departmentName' => $this->user->getDepartment(),
					'confirmed' => true
				);
				
				$disaster->setMembers($members);
			}
			
			/*
			$participants = $rds[$i]->getParticipants();
			
			if (!array_key_exists($currentUserName, $participants) ) {
				$participants[$currentUserName] = array( 
					'departmentName' => $this->user->getDepartment(), 
					'departmentId'   => $this->user->getDepartmentId(),
					'dispatchSchemaId' => $dispatchSchemaId
				);
				
				$rds[$i]->setParticipants($participants);
			}*/
			
			$step = 0;
			for ($dp = $rds[$i]->getDisasterProcess(); $dp != null; $dp = $dp->getNextDisasterProcess()) {
				if ($dp->getProcessState()->getType() == $processStateType) {
					break;
				}
				++$step;
			}
			
			if (!$dp) {
				return false;
			}
			
			// 警情推送结束
			$dp->setIsOk(true);
			
			$nextDP = $dp->getNextDisasterProcess();
			if (!$nextDP) {
				$nextDP = new DisasterProcess();
				$dp->setNextDisasterProcess($nextDP);
				$ps = $em->getRepository('Processstate')->findOneBy(array('type' => 1));
				if (!$ps) {
					return false;
				}
							
				$nextDP->setIsOk(true);
				$nextDP->setProcessState($ps);
				$nextDP->setTime(new DateTime('now'));
			}
			
			++$step;
			
			$em->persist($disaster);
			$em->flush();
			
			// 当前处理阶段
			$currentDisasterProcess	= array(
				'id' => $nextDP->getId(),
				'isOk' => $nextDP->getIsOk(),
				'type' => $nextDP->getProcessState()->getType(),
				'content' => $nextDP->getProcessState()->getContent(),
				'time' => $nextDP->getTime()->format('Y-m-d H:i:s')
			);			
			
			$this->receivers[] = $disaster->getOperator();
			
			$result = array(
				'msgType' => ClientMessage::ALARM_STEP_TYPE_1_RECEIVE_ALARM,
				'data' => array(
					'id'         => $disaster->getId(),
					'step'       => $step,
					'time'       => $currentDisasterProcess['time'],
					'action'     => $currentDisasterProcess['content'],
					'departmentName' => $rd->getDepartmentName(),
					'departmentId'   => $rd->getDepartmentId(),
					'dispatchSchemaId' => $dispatchSchemaId,
					'currentDisasterProcess' => $currentDisasterProcess
				)
			);
			
			$this->setSuccessResult($result);
			
			return true;
		}
		
		// 警情呈报确认
		protected function confirmUploadPower($params, $room, $socket, $io, $em)
		{
			if (!$this->verifyProps(['disasterId', 'dispatchSchemaId', 'commanderList', 'staffList', 'vehicleList', 'deviceList'], $params)) {
				return false;
			}
			
			if ($this->user->getPrivilege() != UserPrivilege::UP_ZHONGDUI) {
				return false;
			}			
			
			$processStateType = 1;
			
			$dispatchSchemaId = $params['dispatchSchemaId'];
			$disasterId = $params['disasterId'];
			
			// 获取指定灾情
			$disaster = $em->find('Disaster', $disasterId);
			if (!$disaster) {
				return false;
			}
			
			// 获取派遣方案
			for ($ds = $disaster->getDispatchSchema(); $ds != null; $ds = $ds->getUpgradeDispatchSchema()) {
				if ($ds->getId() == $dispatchSchemaId ) {
					break;
				}
			}
			
			if (!$ds) {
				return false;
			}
			
			$commanderList = $params['commanderList'];
			$staffList = $params['staffList'];
			$vehicleList = $params['vehicleList'];
			$deviceList = $params['deviceList'];
			
			$rd = null;
			$rds = $ds->getRescueDepartment()->toArray();
			for ($i = 0, $len = count($rds); $i < $len; ++$i) {
				if ($rds[$i]->getDepartmentId() == $this->user->getDepartmentId() && $rds[$i]->getOperator() == $this->user->getUserName() ) {
					$rd = $rds[$i];
					break;
				}
			}
			
			if (! $rd) {
				return false;
			}
			
			$employeeLib = new EmployeeLib($em);
			$commanders = $employeeLib->getList($commanderList);
			$rd->setCommanders($commanders);
			$participants = $employeeLib->getList($staffList);
			$rd->setParticipants($participants);
			
			$members = $disaster->getMembers();
			
			$idList = array();
			foreach($participants as $userName => $detail) {
				$this->extraReceivers[] = $userName;
				if (! array_key_exists($userName, $members)) {
					//$members[$userName] = $detail;
					
					$members[$userName] = array(
						'id'=>$detail['id'],
						'userName' => $detail['userName'],
						'name' => $detail['name'],
						'departmentId' => $detail['departmentId'],
						'departmentName' => $detail['departmentName'],
						'confirmed' => false
					);
				}
				
				$idList[] = $detail['id'];
			}
			
			$disaster->setMembers($members);
			
			$equipLib = new EquipmentLib($em);
			$rd->getDispatchEquipment()->setActualVehicles($equipLib->getDepartmentVehicleList($rd->getDepartmentId(), $this->cityId, $vehicleList));
			$rd->getDispatchEquipment()->setActualDevices($equipLib->getDepartmentDeviceList($rd->getDepartmentId(), $this->cityId, $deviceList));
			
			$step = 0;
			for ($dp = $rds[$i]->getDisasterProcess(); $dp != null; $dp = $dp->getNextDisasterProcess()) {
				if ($dp->getProcessState()->getType() == $processStateType) {
					break;
				}
				++$step;
			}
			
			if (!$dp) {
				return false;
			}
			
			// 警情呈报结束
			$dp->setIsOk(true);
			
			$nextDP = $dp->getNextDisasterProcess();
			if (!$nextDP) {
				$nextDP = new DisasterProcess();
				$dp->setNextDisasterProcess($nextDP);
				$ps = $em->getRepository('Processstate')->findOneBy(array('type' => 2));
				if (!$ps) {
					return false;
				}
							
				$nextDP->setIsOk(true);
				$nextDP->setProcessState($ps);
				$nextDP->setTime(new DateTime('now'));
			}
			
			++$step;
			
			$em->persist($disaster);
			$em->flush();
			
			$employeeLib->batchSetState($idList, 1);
			
			$equipLib->batchSetVehicle($vehicleList, 1);
			$equipLib->batchSetDevice($deviceList,1);
			
			/*
			$commanderNameList = array();
			$numberOfPeople = 0;
			
			foreach( $participants as $userName => $detail) { $numberOfPeople++;}
			foreach( $commanderList as $userName => $detail) { $commanderNameList[] = $userName; } 
			*/
			
			$ddd = new DisasterDetailDesc();
			$desc = $ddd->buildDesc($disaster, $ds, $rd, $nextDP, $this->user);
			
			// 当前处理阶段
			$currentDisasterProcess	= array(
				'id' => $nextDP->getId(),
				'isOk' => $nextDP->getIsOk(),
				'type' => $nextDP->getProcessState()->getType(),
				'content' => $nextDP->getProcessState()->getContent(),
				'time' => $nextDP->getTime()->format('Y-m-d H:i:s'),
				'desc' => $desc
			);			
			
			$this->receivers[] = $disaster->getOperator();
		
			$result = array(
				'msgType' => ClientMessage::ALARM_STEP_TYPE_2_UPLOAD_POWER,
				'data' => array(
					'id'         => $disaster->getId(),
					'step'       => $step,
					'time'       => $currentDisasterProcess['time'],
					'action'     => $currentDisasterProcess['content'],
					'desc' => $desc,
					/*'desc'       => array('指挥人员'=>implode(',',$commanderNameList), '人数'=>$numberOfPeople),*/
					'commanders' =>$commanders,
					'staffs' =>$participants,
					'vehicles' => $rd->getDispatchEquipment()->getActualVehicles(),
					'devices'  =>$rd->getDispatchEquipment()->getActualDevices(),
					'departmentName' => $rd->getDepartmentName(),
					'departmentId'   => $rd->getDepartmentId(),					
					'dispatchSchemaId' => $dispatchSchemaId,
					'currentDisasterProcess' => $currentDisasterProcess,
					'members' => $disaster->getMembers(),
					'vehicleList' => $vehicleList,
					'deviceList' => $deviceList,
					'used' => 1
				)
			);
			
			$this->setSuccessResult($result);
			
			$this->extraResult = array('state' => 'success', 'msg' => array(
				'msgType' => ClientMessage::NEW_DISASTER,
				'disaster' => array(
					'id' => $disaster->getId()
				)
			));
			
			
			/**
			 * 
			 * 推送警情消息到各个手机用户  默认接收用户为当前登录状态下的所有中队接警人员
			 */
			$dt = new DateTime('now');
			$title = '指挥中心  '.$dt->format('Y-m-d H:i:s');
			$content = $disaster->getAddress() . '发生灾情，请立即出动，联系人:' .$disaster->getContact(). "，联系方式：".$disaster->getTelephone()."。";
			
			
			$userToken = array();
			$receiversUserList = array();
			$accountList = array();
			
			
			$users = $room->getUsersByDepartmentId($rd->getDepartmentId());
			
			
			//之前是按照token值推送 现在改为MD5自定义
//			for ( $idx = 0, $usersLen = count($users); $idx < $usersLen; $idx++) {
//						
//				//只有救援人员才可以接收到消息	
//				if ($users[$idx]['user']->getPrivilege() == UserPrivilege::UP_ZHONGDUI_STAFF) {
//						
//					$token = $users[$idx]['user']->getToken();
//					$userName = $users[$idx]['user']->getUserName();
//					if($token != "") {
//						$userToken[] = $token;
//						$receiversUserList[] = $userName;
//					}
//				}
//			}
			
			for ( $idx = 0, $usersLen = count($users); $idx < $usersLen; $idx++) {
						
				//只有救援人员才可以接收到消息	
				if ($users[$idx]['user']->getPrivilege() == UserPrivilege::UP_ZHONGDUI_STAFF) {
						
					$userName = $users[$idx]['user']->getUserName();
					$password = $users[$idx]['user']->getPassWord();
					$userSignature = MD5($userName. ''.$password);
					if($userSignature != "") {
						$accountList[] = $userSignature;
						$receiversUserList[] = $userName;
					}
				}
			}
			
			
			$type = 0;
			$params = json_encode(array('type' => 0));
			
			
			//$ret = PushService::toTokenListDevices($title, $content, $userToken, $type, $params);
			
			$ret = PushService::PushAlramAccountList($title, $content, $accountList, $type, $params);
			
			
			
			//将推送出去的消息落地
				
			$pushMessageLog = new PushMessageLog();
			$pushMessageLog->setContent($content);
			$pushMessageLog->setSender($this->userName);
			$pushMessageLog->setReceivers($receiversUserList);
			$pushMessageLog->setTime(XDate::getTime());
			
			$dataType = "警情信息";
			
			$pushMessageLog->setMsgType($dataType);
			
			if($ret['ret_code'] == 0) {
				$pushMessageLog->setSendFlag("成功");
			}
			else {
				$pushMessageLog->setSendFlag("失败");
			}
			
			$em->persist($pushMessageLog);
			$em->flush();
			
			return true;
		}
		
		// 抵达现场确认
		protected function confirmArriveScene($params, $room, $socket, $io, $em) 
		{
			if (!$this->verifyProps(['disasterId', 'dispatchSchemaId'], $params)) {
				return false;
			}
			
			if ($this->user->getPrivilege() != UserPrivilege::UP_ZHONGDUI) {
				return false;
			}
			
			$processStateType = 2;
			$nextProcessStateType = 3;
			
			$msgType = ClientMessage::ALARM_STEP_TYPE_3_ARRIVE_SCENE;
			
			$dispatchSchemaId = $params['dispatchSchemaId'];
			$disasterId = $params['disasterId'];
			
			// 获取指定灾情
			$disaster = $em->find('Disaster', $disasterId);
			if (!$disaster) {
				return false;
			}
			
			// 获取派遣方案
			for ($ds = $disaster->getDispatchSchema(); $ds != null; $ds = $ds->getUpgradeDispatchSchema()) {
				if ($ds->getId() == $dispatchSchemaId ) {
					break;
				}
			}
			
			if (!$ds) {
				return false;
			}
			
			$rd = null;
			$rds = $ds->getRescueDepartment()->toArray();
			for ($i = 0, $len = count($rds); $i < $len; ++$i) {
				if ($rds[$i]->getDepartmentId() == $this->user->getDepartmentId() && $rds[$i]->getOperator() == $this->user->getUserName()) {
					$rd = $rds[$i];
					break;
				}
			}
			
			if (!$rd) {
				return false;
			}
			
			$step = 0;
			for ($dp = $rds[$i]->getDisasterProcess(); $dp != null; $dp = $dp->getNextDisasterProcess()) {
				if ($dp->getProcessState()->getType() == $processStateType) {
					break;
				}
				++$step;
			}
			
			if (!$dp) {
				return false;
			}
			
			// 警情推送结束
			$dp->setIsOk(true);
			
			$nextDP = $dp->getNextDisasterProcess();
			if (!$nextDP) {
				$nextDP = new DisasterProcess();
				$dp->setNextDisasterProcess($nextDP);
				$ps = $em->getRepository('Processstate')->findOneBy(array('type' => $nextProcessStateType));
				if (!$ps) {
					return false;
				}
							
				$nextDP->setIsOk(true);
				$nextDP->setProcessState($ps);
				$nextDP->setTime(new DateTime('now'));
			}
			
			++$step;
			
			$em->persist($disaster);
			$em->flush();
			
			// 当前处理阶段
			$currentDisasterProcess	= array(
				'id' => $nextDP->getId(),
				'isOk' => $nextDP->getIsOk(),
				'type' => $nextDP->getProcessState()->getType(),
				'content' => $nextDP->getProcessState()->getContent(),
				'time' => $nextDP->getTime()->format('Y-m-d H:i:s')
			);			
			
			$this->receivers[] = $disaster->getOperator();
			
			foreach( $rd->getParticipants() as $userName => $participant) {
				$this->receivers[] = $userName;
			}
			
			$result = array(
				'msgType' => $msgType,
				'data' => array(
					'id'         => $disaster->getId(),
					'step'       => $step,
					'time'       => $currentDisasterProcess['time'],
					'action'     => $currentDisasterProcess['content'],
					'departmentName' => $rd->getDepartmentName(),
					'departmentId'   => $rd->getDepartmentId(),					
					'dispatchSchemaId' => $dispatchSchemaId,
					'currentDisasterProcess' => $currentDisasterProcess
				)
			);
			
			$this->setSuccessResult($result);
			
			return true;
		}
		
		// 灾情处置完毕确认
		protected function confirmDisasterFinished($params, $room, $socket, $io, $em) 
		{
			if (!$this->verifyProps(['disasterId', 'dispatchSchemaId'], $params)) {
				return false;
			}
			
			if ($this->user->getPrivilege() != UserPrivilege::UP_ZHONGDUI) {
				return false;
			}
			
			$processStateType = 3;
			$nextProcessStateType = 4;
			
			$msgType = ClientMessage::ALARM_STEP_TYPE_4_DISASTER_FINISHED;
			
			$dispatchSchemaId = $params['dispatchSchemaId'];
			$disasterId = $params['disasterId'];
			
			// 获取指定灾情
			$disaster = $em->find('Disaster', $disasterId);
			if (!$disaster) {
				return false;
			}
			
			// 获取派遣方案
			for ($ds = $disaster->getDispatchSchema(); $ds != null; $ds = $ds->getUpgradeDispatchSchema()) {
				if ($ds->getId() == $dispatchSchemaId ) {
					break;
				}
			}
			
			if (!$ds) {
				return false;
			}
			
			$rd = null;
			$rds = $ds->getRescueDepartment()->toArray();
			for ($i = 0, $len = count($rds); $i < $len; ++$i) {
				if ($rds[$i]->getDepartmentId() == $this->user->getDepartmentId() && $rds[$i]->getOperator() == $this->user->getUserName()) {
					$rd = $rds[$i];
					break;
				}
			}
			
			if (!$rd) {
				return false;
			}
			
			$step = 0;
			for ($dp = $rds[$i]->getDisasterProcess(); $dp != null; $dp = $dp->getNextDisasterProcess()) {
				if ($dp->getProcessState()->getType() == $processStateType) {
					break;
				}
				++$step;
			}
			
			if (!$dp) {
				return false;
			}
			
			// 警情推送结束
			$dp->setIsOk(true);
			
			$nextDP = $dp->getNextDisasterProcess();
			if (!$nextDP) {
				$nextDP = new DisasterProcess();
				$dp->setNextDisasterProcess($nextDP);
				$ps = $em->getRepository('Processstate')->findOneBy(array('type' => $nextProcessStateType));
				if (!$ps) {
					return false;
				}
							
				$nextDP->setIsOk(true);
				$nextDP->setProcessState($ps);
				$nextDP->setTime(new DateTime('now'));
			}
			
			++$step;
			

			
			$em->persist($disaster);
			$em->flush();
			
			// 当前处理阶段
			$currentDisasterProcess	= array(
				'id' => $nextDP->getId(),
				'isOk' => $nextDP->getIsOk(),
				'type' => $nextDP->getProcessState()->getType(),
				'content' => $nextDP->getProcessState()->getContent(),
				'time' => $nextDP->getTime()->format('Y-m-d H:i:s')
			);			
			
			$this->receivers[] = $disaster->getOperator();
			
			foreach( $rd->getParticipants() as $userName => $participant) {
				$this->receivers[] = $userName;
			}
			
			$result = array(
				'msgType' => $msgType,
				'data' => array(
					'id'         => $disaster->getId(),
					'step'       => $step,
					'time'       => $currentDisasterProcess['time'],
					'action'     => $currentDisasterProcess['content'],
					'departmentName' => $rd->getDepartmentName(),
					'departmentId'   => $rd->getDepartmentId(),					
					'dispatchSchemaId' => $dispatchSchemaId,
					'currentDisasterProcess' => $currentDisasterProcess
				)
			);
			
			$this->setSuccessResult($result);			
			return true;
		}
		
		// 返回确认
		protected function confirmGoBack($params, $room, $socket, $io, $em) 
		{
			if (!$this->verifyProps(['disasterId', 'dispatchSchemaId'], $params)) {
				return false;
			}
	
			if ($this->user->getPrivilege() != UserPrivilege::UP_ZHONGDUI) {
				return false;
			}	
			
			$processStateType = 4;
			$nextProcessStateType = 5;
			
			$msgType = ClientMessage::ALARM_STEP_TYPE_5_GO_BACK;
			
			$dispatchSchemaId = $params['dispatchSchemaId'];
			$disasterId = $params['disasterId'];
			
			// 获取指定灾情
			$disaster = $em->find('Disaster', $disasterId);
			if (!$disaster) {
				return false;
			}
			
			// 获取派遣方案
			for ($ds = $disaster->getDispatchSchema(); $ds != null; $ds = $ds->getUpgradeDispatchSchema()) {
				if ($ds->getId() == $dispatchSchemaId ) {
					break;
				}
			}
			
			if (!$ds) {
				return false;
			}
			
			$rd = null;
			$rds = $ds->getRescueDepartment()->toArray();
			for ($i = 0, $len = count($rds); $i < $len; ++$i) {
				if ($rds[$i]->getDepartmentId() == $this->user->getDepartmentId() && $rds[$i]->getOperator() == $this->user->getUserName()) {
					$rd = $rds[$i];
					break;
				}
			}
			
			if (!$rd) {
				return false;
			}
			
			$step = 0;
			for ($dp = $rds[$i]->getDisasterProcess(); $dp != null; $dp = $dp->getNextDisasterProcess()) {
				if ($dp->getProcessState()->getType() == $processStateType) {
					break;
				}
				++$step;
			}
			
			if (!$dp) {
				return false;
			}
			
			// 警情推送结束
			$dp->setIsOk(true);
			
			$nextDP = $dp->getNextDisasterProcess();
			if (!$nextDP) {
				$nextDP = new DisasterProcess();
				$dp->setNextDisasterProcess($nextDP);
				$ps = $em->getRepository('Processstate')->findOneBy(array('type' => $nextProcessStateType));
				if (!$ps) {
					return false;
				}
							
				$nextDP->setIsOk(true);
				$nextDP->setProcessState($ps);
				$nextDP->setTime(new DateTime('now'));
			}
			
			++$step;
			
			$em->persist($disaster);
			$em->flush();
			
			// 当前处理阶段
			$currentDisasterProcess	= array(
				'id' => $nextDP->getId(),
				'isOk' => $nextDP->getIsOk(),
				'type' => $nextDP->getProcessState()->getType(),
				'content' => $nextDP->getProcessState()->getContent(),
				'time' => $nextDP->getTime()->format('Y-m-d H:i:s')
			);			
			
			$this->receivers[] = $disaster->getOperator();
			foreach( $rd->getParticipants() as $userName => $participant) {
				$this->receivers[] = $userName;
			}
			
			$result = array(
				'msgType' => $msgType,
				'data' => array(
					'id'         => $disaster->getId(),
					'step'       => $step,
					'time'       => $currentDisasterProcess['time'],
					'action'     => $currentDisasterProcess['content'],
					'departmentName' => $rd->getDepartmentName(),
					'departmentId'   => $rd->getDepartmentId(),					
					'dispatchSchemaId' => $dispatchSchemaId,
					'currentDisasterProcess' => $currentDisasterProcess
				)
			);
			
			$this->setSuccessResult($result);			
			return true;
		}
		
		// 归队确认
		protected function confirmInDepartment($params, $room, $socket, $io, $em) 
		{
			if (!$this->verifyProps(['disasterId', 'dispatchSchemaId'], $params)) {
				return false;
			}

			if ($this->user->getPrivilege() != UserPrivilege::UP_ZHONGDUI) {
				return false;
			}			
			
			$processStateType = 5;
			$nextProcessStateType = 6;
			
			$msgType = ClientMessage::ALARM_STEP_TYPE_6_IN_DEPARTMENT;
			
			$dispatchSchemaId = $params['dispatchSchemaId'];
			$disasterId = $params['disasterId'];
			
			// 获取指定灾情
			$disaster = $em->find('Disaster', $disasterId);
			if (!$disaster) {
				return false;
			}
			
			// 获取派遣方案
			for ($ds = $disaster->getDispatchSchema(); $ds != null; $ds = $ds->getUpgradeDispatchSchema()) {
				if ($ds->getId() == $dispatchSchemaId ) {
					break;
				}
			}
			
			if (!$ds) {
				return false;
			}
			
			$rd = null;
			$rds = $ds->getRescueDepartment()->toArray();
			for ($i = 0, $len = count($rds); $i < $len; ++$i) {
				if ($rds[$i]->getDepartmentId() == $this->user->getDepartmentId() && $rds[$i]->getOperator() == $this->user->getUserName()) {
					$rd = $rds[$i];
					break;
				}
			}
			
			if (!$rd) {
				return false;
			}
			
			$step = 0;
			for ($dp = $rds[$i]->getDisasterProcess(); $dp != null; $dp = $dp->getNextDisasterProcess()) {
				if ($dp->getProcessState()->getType() == $processStateType) {
					break;
				}
				++$step;
			}
			
			if (!$dp) {
				return false;
			}
			
			// 警情推送结束
			$dp->setIsOk(true);
			
			$nextDP = $dp->getNextDisasterProcess();
			if (!$nextDP) {
				$nextDP = new DisasterProcess();
				$dp->setNextDisasterProcess($nextDP);
				$ps = $em->getRepository('Processstate')->findOneBy(array('type' => $nextProcessStateType));
				if (!$ps) {
					return false;
				}
							
				$nextDP->setIsOk(true);
				$nextDP->setProcessState($ps);
				$nextDP->setTime(new DateTime('now'));
			}
			
			++$step;

			$em->persist($disaster);
			$em->flush();	
			
			// 当前处理阶段
			$currentDisasterProcess	= array(
				'id' => $nextDP->getId(),
				'isOk' => $nextDP->getIsOk(),
				'type' => $nextDP->getProcessState()->getType(),
				'content' => $nextDP->getProcessState()->getContent(),
				'time' => $nextDP->getTime()->format('Y-m-d H:i:s')
			);			
			
			$this->receivers[] = $disaster->getOperator();
			$idList = array();
			foreach( $rd->getParticipants() as $userName => $participant) {
				$this->receivers[] = $userName;
				$idList[] = $participant['id'];
			}
			
			$vehicleList = array();
			
			$vehicles = $rd->getDispatchEquipment()->getActualVehicles();
			for ( $i = 0, $len = count($vehicles); $i < $len; $i++) {
				$vehicleList[] = $vehicles[$i]['id'];
			}
			
			$deviceList = array();
			$devices = $rd->getDispatchEquipment()->getActualDevices();
			for ( $i = 0, $len = count($devices); $i < $len; $i++) {
				$deviceList[] = $devices[$i]['id'];
			}			
			
			$employeeLib = new EmployeeLib($em);
			$employeeLib->batchSetState($idList, 0);
			
			$equipLib = new EquipmentLib($em);
			$equipLib->batchSetVehicle( $vehicleList, 0 );
			$equipLib->batchSetDevice( $deviceList, 0 );
			
			$result = array(
				'msgType' => $msgType,
				'data' => array(
					'id'         => $disaster->getId(),
					'step'       => $step,
					'time'       => $currentDisasterProcess['time'],
					'action'     => $currentDisasterProcess['content'],
					'departmentName' => $rd->getDepartmentName(),
					'departmentId'   => $rd->getDepartmentId(),					
					'dispatchSchemaId' => $dispatchSchemaId,
					'currentDisasterProcess' => $currentDisasterProcess,
					'vehicleList' => $vehicleList,
					'deviceList' => $deviceList,
					'used' => 0
				)
			);
			
			$this->setSuccessResult($result);			
			return true;
		}
		
		public function dispatch($socket, $io, $room, $em) 
		{
			for ($i = 0, $len = count($this->receivers); $i < $len; ++$i) {
				$userItem = $room->getUser($this->receivers[$i]);
				if (!$userItem) {
					continue;
				}
			
				$socketId = $userItem['socketId'];
				
				if (!isset($io->sockets->sockets[$socketId])) {
					continue;
				}
				
				$io->sockets->sockets[$socketId]->emit('message', $this->result);
			}
			
			$socket->emit('message', $this->result);
			
			if (count($this->extraReceivers) > 0) {
				$this->extraDispatch($socket, $io, $room, $em);
			}
		}
		
		protected function extraDispatch($socket, $io, $room, $em) 
		{
			for ($i = 0, $len = count($this->extraReceivers); $i < $len; ++$i) {
				$userItem = $room->getUser($this->extraReceivers[$i]);
				if (!$userItem) {
					continue;
				}

				$socketId = $userItem['socketId'];
				
				if (!isset($io->sockets->sockets[$socketId])) {
					continue;
				}
				
				$io->sockets->sockets[$socketId]->emit('message', $this->extraResult);
			}
		}
		
		public function error($socket, $io, $room, $em) 
		{
			$socket->emit('sysError', $this->result );
		}
	}