<?php
date_default_timezone_set('PRC');
	
require_once 'Handler.php';
require_once 'HallManager.php';
require_once '/../Utils/XDate.php';
require_once '/../equpiment/EquipmentLib.php';
require_once "/../PushService/PushService.php";

/**************************************************************************
 *
 * 说明：新灾情消息处理类
 *
 *
 *************************************************************************/
class NewDisasterHandler extends AMHandler 
{
	private $userName;
	private $cityId;
	private $user;
	private $contact;
	private $telephone;
	private $address;
	
	private $receiveDepartments = array();
		
	public function __construct() 
	{
		
	}
	
	public function setFireContent($contact,$telephone,$address) {
			
		$this->contact = $contact;
		$this->telephone = $telephone;
		$this->address = $address;
	}

	
	public function handle(array $arr , $room, $socket, $io, $em) 
	{
		$this->userName = base64_decode(urldecode($arr['userName']));
		$this->cityId   = base64_decode(urldecode($arr['cityId']));
			
		$this->user = $em->getRepository('User')->findOneBy(array('userName'=>$this->userName));
						 
		if (!$this->user) {
			return false;
		}
		
		if ($this->user->getPrivilege() != UserPrivilege::UP_ZHIDUI) {
			return false;
		}
		
		$contact    = $arr['contact'];
		$telephone  = $arr['telephone'];
		$address    = $arr['address'];
		$addressLng = $arr['addressLng'];
		$addressLat = $arr['addressLat'];
		$note       = $arr['note'];
		
		$settings = $arr['settings'];
		$strategy = $arr['strategy'];
		
		
		$this->setFireContent($contact,$telephone,$address);
		
		$disaster = new Disaster();
		
		$disaster->setContact($contact);
		$disaster->setTelephone($telephone);
		$disaster->setAddress($address);
		$disaster->setAddressLng($addressLng);
		$disaster->setAddressLat($addressLat);
		$disaster->setAlarmClosed(false);
		$disaster->setAlarmClosedTime(new DateTime('now'));
		$disaster->setOperator($this->userName);
		$disaster->setDate(new DateTime('now'));
		$disaster->setTime(XDate::getTime());
		$members = array();
		$members[$this->userName] = array(
			'id'=>$this->user->getId(),
			'userName' => $this->user->getUserName(),
			'name' => $this->user->getName(),
			'departmentId' => $this->user->getDepartmentId(),
			'departmentName' => $this->user->getDepartment(),
			'confirmed' => true
 		);
		
		$disaster->setMembers($members);
		
		$keyUnitId   = $arr['keyUnitId'];
		$plan2dName = $arr['plan2dName'];
		$plan3dName = $arr['plan3dName'];
		$plan2dUrl  = $arr['plan2dUrl'];
		$plan3dUrl  = $arr['plan3dUrl'];
		
		$dispatchSchema = new DispatchSchema();
		$disaster->setDispatchSchema($dispatchSchema);
		
		$dispatchSchema->setDisaster($disaster);
		
		$dispatchSchema->setPlan2dName($plan2dName);
		$dispatchSchema->setPlan2dUrl($plan2dUrl);
		$dispatchSchema->setPlan3dName($plan3dName);
		$dispatchSchema->setPlan3dUrl($plan3dUrl);
		
		$dispatchSchema->setKeyUnitId($keyUnitId);
		
		$dispatchSchema->setDate(new DateTime('now'));
		$dispatchSchema->setTime(XDate::getTime());
		$dispatchSchema->setNote($note);
		$dispatchSchema->setUpgradeDispatchSchema(null);
		
		//	添加救援机构
		foreach ($strategy as $key => $item) {
			$rescueDepartment = new RescueDepartment();
			$rescueDepartment->setDispatchSchema($dispatchSchema);
			$rescueDepartment->setDepartmentId($item['departmentId']);
			$rescueDepartment->setDepartmentName($item['departmentName']);
			$rescueDepartment->setParticipants(array());
			$rescueDepartment->setCommanders(array());
			$rescueDepartment->setOperator('');
			
			$processState = $em->getRepository('ProcessState')->findOneBy(array('type'=>0));
			$disasterProcess = new DisasterProcess();
			$disasterProcess->setIsOk(true);
			$disasterProcess->setProcessState($processState);
			$disasterProcess->setTime(new DateTime('now'));
			$rescueDepartment->setDisasterProcess($disasterProcess);
			
			$dispatchEquipment = new DispatchEquipment();
			$rescueDepartment->setDispatchEquipment($dispatchEquipment);
			
			$dispatchEquipment->setRescueDepartment($rescueDepartment);
			
			$equipLib = new EquipmentLib($em);
			$vehicles = array();
			for ( $i = 0, $len = count($item['vehicles']); $i < $len; ++$i) {
				$vehicles[] = $item['vehicles'][$i]['id'];
			}
			
			$dispatchEquipment->setSuggestedVehicles($equipLib->getDepartmentVehicleList($item['departmentId'], $this->cityId, $vehicles));
			
			$devices = array();
			for ( $i = 0, $len = count($item['devices']); $i < $len; ++$i) {
				$devices[] = $item['devices'][$i]['id'];
			}
			
			$dispatchEquipment->setSuggestedDevices($equipLib->getDepartmentDeviceList($item['departmentId'], $this->cityId, $devices));
			
			$dispatchEquipment->setActualVehicles(array());
			$dispatchEquipment->setActualDevices(array());

			$dispatchSchema->addRescueDepartment($rescueDepartment);
		}
		
		$disasterType = $settings['type'];
		$disasterCategory = $em->getRepository('DisasterCategory')->findOneBy(array('disasterDescClassName'=> $disasterType));
		$disasterHandleSchema = new DisasterHandleSchema();
		$dispatchSchema->setDisasterHandlerSchema($disasterHandleSchema);
		$disasterHandleSchema->setDispatchSchema($dispatchSchema);
		
		$disasterHandleSchema->setDisasterCategory($disasterCategory);
		
		$className = $disasterCategory->getDisasterDescClassName();
		$disasterDesc = new $className();
		$disasterDesc->setParams($settings);
		$disasterDesc->setDisasterHandleSchema($disasterHandleSchema);
		
		$disasterHandleSchema->setDisasterDesc($disasterDesc);
		
		$em->persist($disaster);
		
		try {
			$em->flush();
		} catch(Exception $e){
			echo $e->getMessage();
			return false;
			//\Doctrine\Common\Util\Debug::dump($disaster);
		}
		
		$rds = array();
		foreach ($dispatchSchema->getRescueDepartment() as $rd) {
			$this->receiveDepartments[] = array('departmentId' => $rd->getDepartmentId(), 'departmentName' => $rd->getDepartmentName());
			
			$dpList = array();
			for ( $dp = $rd->getDisasterProcess(); $dp != null; $dp = $dp->getNextDisasterProcess() ) {
				$dpList[] = array(
					'id' =>$dp->getId(),
					'isOk' => $dp->getIsOk(),
					'type' => $dp->getProcessState()->getType(),
					'content' => $dp->getProcessState()->getContent(),
					'time' => $dp->getTime()->format('Y-m-d H:i:s')
				);
			}
			
			$rds[] = array(
				'departmentId' => $rd->getDepartmentId(),
				'departmentName' => $rd->getDepartmentName(),
				'participants' => $rd->getParticipants(),
				'commanders'   => $rd->getCommanders(),
				'operator'     => $rd->getOperator(),
				'disasterProcessList' => $dpList,
				'dispatchEquipment' => array(
					'suggestedVehicles' => $rd->getDispatchEquipment()->getSuggestedVehicles(),
					'suggestedDevices' => $rd->getDispatchEquipment()->getSuggestedDevices()
				)
			);
		}
		
		// 构造发送数据
		$data = array(
			'msgType' => ClientMessage::NEW_DISASTER,
			'disaster' => array(
				'id' => $disaster->getId()/*,
				
				'contact' => $contact,
				'telephone' => $telephone,
				'address' => $address,
				'addressLng' => $addressLng,
				'addressLat' => $addressLat,
				'alarmClosed' => false,
				'operator'    => $this->userName,
				'date' => $disaster->getDate()->format('Y-m-d'),
				'time' => $disaster->getTime(),
				'dispatchSchema' => array(
					'id' => $dispatchSchema->getId(),
					'plan2dName' => $dispatchSchema->getPlan2dName(),
					'plan2dUrl' => $dispatchSchema->getPlan2dUrl(),
					'plan3dName' => $dispatchSchema->getPlan3dName(),
					'plan3dUrl' => $dispatchSchema->getPlan3dUrl(),
					'date' => $dispatchSchema->getDate()->format('Y-m-d'),
					'time' => $dispatchSchema->getTime(),
					'note' => $dispatchSchema->getNote(),
					'disasterHandleSchema' => array(
						'disasterCategory' => $disasterHandleSchema->getDisasterCategory()->getDesc(),
						'disasterDesc' => $disasterHandleSchema->getDisasterDesc()->getDesc()
					),
					'rescueDepartment' => $rds
				)*/
			)
		);
		
		$this->setSuccessResult($data);
		
		return true;
	}
		
	public function dispatch($socket, $io, $room, $em) 
	{	
		for ($i = 0, $len = count($this->receiveDepartments); $i < $len; $i++) {
			$current = $this->receiveDepartments[$i];
			$departmentId = $current['departmentId'];
			$users = $room->getUsersByDepartmentId($departmentId);
			
			for ( $idx = 0, $usersLen = count($users); $idx < $usersLen; $idx++) {
				if ($users[$idx]['user']->getPrivilege() != UserPrivilege::UP_ZHONGDUI) {
					continue;
				}
				
				// 只有中队接警员可接受支队接警员发送的警情
				$socketId = $users[$idx]['socketId'];
				
				if (!isset($io->sockets->sockets[$socketId])) {
					continue;
				}
				
				$io->sockets->sockets[$socketId]->emit('message', $this->result);
			}
		}
		
		$socket->emit('message', $this->result);
		
		
		/**
		 * 
		 * 推送警情消息到各个手机用户  默认接收用户为当前登录状态下的所有中队接警人员
		 */
		/*$dt = new DateTime('now');
		$title = '指挥中心  '.$dt->format('Y-m-d H:i:s');
		$content = $this->address . '发生灾情，请立即出动，联系人:' .$this->contact. "，联系方式：".$this->telephone."。";
		
		
		$userToken = array();
		$receiversUserList = array();
		
		for ($i = 0, $len = count($this->receiveDepartments); $i < $len; $i++) {
				
			$current = $this->receiveDepartments[$i];
			$departmentId = $current['departmentId'];
			$users = $room->getUsersByDepartmentId($departmentId);
			
			for ( $idx = 0, $usersLen = count($users); $idx < $usersLen; $idx++) {
					
				//只有救援人员才可以接收到消息	
				if ($users[$idx]['user']->getPrivilege() == UserPrivilege::UP_ZHONGDUI_STAFF) {
						
					$token = $users[$idx]['user']->getToken();
					$userName = $users[$idx]['user']->getUserName();
					if($token != "") {
						$userToken[] = $token;
						$receiversUserList[] = $userName;
					}
				}
			}
		}
		
		$type = 0;
		$params = array('type' => 0);
		
		$ret = PushService::toTokenListDevices($title, $content, $userToken, $type, $params);
		
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
		
		*/
	}
		
	public function error($socket, $io, $room, $em) 
	{
		$socket->emit('sysError', $this->result);
	}
}