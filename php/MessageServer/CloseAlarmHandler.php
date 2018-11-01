<?php
date_default_timezone_set('PRC');
	
require_once 'Handler.php';
require_once 'HallManager.php';
require_once '/../System/EmployeeLib.php';
require_once '/../equpiment/EquipmentLib.php';
	
/**************************************************************************
 *
 * 说明：关闭警情
 *
 *
 *************************************************************************/
class CloseAlarmHandler extends AMHandler 
{
	protected $userName = '';
	protected $cityId = -1;
	protected $user = null;	
	protected $receivers = array();
		
	public function __construct() 
	{
	}
		
	public function handle(array $arr, $room, $socket, $io, $em) 
	{
		if (!$this->verifyProps(['disasterId'], $arr)) {
			return false;
		}		
		
		$this->userName = base64_decode(urldecode($arr['userName']));
		$this->cityId   = base64_decode(urldecode($arr['cityId']));
		$this->user = $em->getRepository('User')->findOneBy(array('userName'=>$this->userName));
						 
		if (!$this->user) {
			return false;
		}
			
		$disasterId = $arr['disasterId'];
			
		$disaster = $em->find('Disaster', $disasterId);
		if (!$disaster) {
			return false;
		}
		
		if ($disaster->getAlarmClosed()) {
			return false;
		}
		
		$disaster->setAlarmClosed(true);
		$disaster->setAlarmClosedTime(new DateTime('now'));
		
		$result = array();
		
		for ($dispatchSchema = $disaster->getDispatchSchema(); $dispatchSchema != null; $dispatchSchema = $dispatchSchema->getUpgradeDispatchSchema()) {
			foreach( $dispatchSchema->getRescueDepartment() as $rescueDepartment ) {
				$departmentId = $rescueDepartment->getDepartmentId();
				$departmentName = $rescueDepartment->getDepartmentName();
				
				$participants = $rescueDepartment->getParticipants();
				$staffList = array();
				foreach ($participants as $userName => $detail){ $staffList[] = $detail['id']; }
				$employeeLib = new EmployeeLib($em);
				$employeeLib->batchSetState($staffList, 0);
				
				$equipLib = new EquipmentLib($em);
				
				$vehicles = $rescueDepartment->getDispatchEquipment()->getActualVehicles();
				$vehicleList = array();
				for ($i = 0, $len = count($vehicles); $i < $len; ++$i) {
					$vehicleList[] = $vehicles[$i]['id'];
				}
				$equipLib->batchSetVehicle($vehicleList, 0);
				
				$devices = $rescueDepartment->getDispatchEquipment()->getActualDevices();
				$deviceList = array();
				for ($i = 0, $len = count($devices); $i < $len; ++$i) {
					$deviceList[] = $devices[$i]['id'];
				}
				$equipLib->batchSetDevice($deviceList,0);
				
				$result[] = array(
					'departmentId' => $departmentId,
					'departmentName' => $departmentName,
					'staffList' => $staffList,
					'vehicleList' => $vehicleList,
					'deviceList' => $deviceList,
					'used' => 0
				);
			}
		}
		
		$em->persist($disaster);
		$em->flush();
		
		foreach ($disaster->getMembers() as $userName => $detail) {
			$this->receivers[] = $userName;
		}

		$this->setSuccessResult(array(
			'msgType' => ClientMessage::CLOSE_ALARM, 
			'data' => array( 
				'id'=> $disaster->getId(), 
				'state' => '完结',
				'alarmClosed' => $disaster->getAlarmClosed(),
				'alarmClosedTime' => $disaster->getAlarmClosedTime()->format('Y-m-d H:i:s'),
				'list' => $result
			))
		);
		
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
			
		//$socket->emit('message', $this->result);
	}
		
	public function error($socket, $io, $room, $em) 
	{
		$socket->emit('sysError', $this->result );
	}
}