<?php
require_once "/../doctrine2/bootstrap.php";

class DisasterDetailDesc
{
	public function __construct() 
	{
		
	}
	
	// 构建警情信息
	protected function buildAlamDesc($disaster, $dispatchSchema, $rescueDepartment, $disasterProcess, $user)
	{
		$result = array();
	
		$result[] = array('name'=>'地址', 'value'=> $disaster->getAddress(), 'type' => 'address', 'data' => array('lat'=>$disaster->getAddressLat(), 'lng'=>$disaster->getAddressLng(), 'keyUnitId' => $dispatchSchema->getKeyUnitId()));
		$result[] = array('name'=>'联系人', 'value' => $disaster->getContact(), 'type' => 'text');
		$result[] = array('name'=>'电话', 'value' => $disaster->getTelephone(), 'type' => 'telephone');
		
		$disasterCategoryDesc = $dispatchSchema->getDisasterHandlerSchema()->getDisasterCategory()->getDesc();
		$result[] = array('name' => '灾情类别', 'value' => $disasterCategoryDesc['strategyName']. '-'. $disasterCategoryDesc['categoryName'], 'type' => 'text');
		
		$disasterDesc = $dispatchSchema->getDisasterHandlerSchema()->getDisasterDesc()->getDesc();	
		$desc = array();
		foreach ($disasterDesc as $key => $val){
			if (is_array($val) && count($val) > 0) {
				$name = $val['name'];
				
				$items = array();
				for ($i = 0, $len = count($val['value']); $i < $len; ++$i) {
					$items[] = $val['value'][$i]['content'];
				}
				
				$desc[] = $name ." : ". implode(',', $items);
			}
		}
		
		$result[] = array('name' => '灾情描述', 'value' => implode(';', $desc), 'type' => 'text');
		
		if ($dispatchSchema->getNote() != "") {
			$result[] = array('name' => '备注', 'value' => $dispatchSchema->getNote(), 'type' => 'text'); 
		}
		
		if ($dispatchSchema->getPlan2dUrl() != "") {
			$result[] = array('name' => '二维预案', 'value' => $dispatchSchema->getPlan2dName(), 'type' => 'link', 'data' =>array('url'=>$dispatchSchema->getPlan2dUrl()));
		}
		
		if ($dispatchSchema->getPlan3dUrl() != "") {
			$result[] = array('name' => '三维预案', 'value' => $dispatchSchema->getPlan3dName(), 'type' => 'link', 'data' =>array('url'=>$dispatchSchema->getPlan3dUrl()));
		}
		
		$suggestedVehicles = $rescueDepartment->getDispatchEquipment()->getSuggestedVehicles();
		if (count($suggestedVehicles)>0) {
			$result[] = array('name' => '推荐车辆', 'value' => count($suggestedVehicles), 'type' => 'equipment', 'data' => $suggestedVehicles);
		}
		
		$suggestedDevices = $rescueDepartment->getDispatchEquipment()->getSuggestedDevices();
		if (count($suggestedDevices)>0) {
			$result[] = array('name' => '推荐器材', 'value' => count($suggestedDevices), 'type' => 'equipment', 'data' => $suggestedDevices);
		}
		
		return $result;
	}
	
	protected function buildReceiveAlarmDesc($disaster, $dispatchSchema, $rescueDepartment, $disasterProcess, $user)
	{
		$result = array();
		
		return $result;
	}
	
	protected function buildUploadPowerDesc($disaster, $dispatchSchema, $rescueDepartment, $disasterProcess, $user)
	{
		$result = array();
		
		$commanders = $rescueDepartment->getCommanders();
		$commanderNameList = array();
		foreach( $commanders as $userName => $detail ) {
			$commanderNameList[] = $detail['name'];
		}
		
		$result[] = array('name'=>'指挥人员', 'value' => implode(',', $commanderNameList), 'type' => 'text');
		
		$participants = $rescueDepartment->getParticipants();
		$staffNameList = array();
		$staffList = array();
		foreach( $participants as $userName => $detail ) {
			$staffNameList[] = $userName;
			$staffList[] = $detail;
		}
		
		$result[] = array('name'=>'救援人数', 'value' =>count($staffNameList), 'type' => 'staff', 'data' => $staffList);
		
		$vehicles = $rescueDepartment->getDispatchEquipment()->getActualVehicles();
		if (count($vehicles) > 0) {
			$result[] = array('name'=>'实派车辆', 'value' =>count($vehicles), 'type' => 'equipment', 'data' => $vehicles );
		}
		
		$devices = $rescueDepartment->getDispatchEquipment()->getActualDevices();
		if (count($devices) > 0) {
			$result[] = array('name'=>'实派器材', 'value' =>count($devices), 'type' => 'equipment', 'data' => $devices);
		}
		
		return $result;
	}
	
	protected function buildArriveSceneDesc($disaster, $dispatchSchema, $rescueDepartment, $disasterProcess, $user)
	{
		$result = array();
		
		return $result;
	}
	
	protected function buildDisasterFinishedDesc($disaster, $dispatchSchema, $rescueDepartment, $disasterProcess, $user)
	{
		$result = array();
		
		return $result;
	}
	
	protected function buildGoBackDesc($disaster, $dispatchSchema, $rescueDepartment, $disasterProcess, $user)
	{
		$result = array();
		
		return $result;
	}
	
	protected function buildInDepartmentDesc($disaster, $dispatchSchema, $rescueDepartment, $disasterProcess, $user)
	{
		$result = array();
		
		return $result;
	}
	
	public function buildDesc($disaster, $dispatchSchema, $rescueDepartment, $disasterProcess, $user)
	{
		if (!$disaster || !$disasterProcess) {
			return null;
		}
		
		if ($disasterProcess->getProcessState()->getType() == 0) {
			return $this->buildAlamDesc($disaster, $dispatchSchema, $rescueDepartment, $disasterProcess, $user);
		} elseif ($disasterProcess->getProcessState()->getType() == 1) {
			return $this->buildReceiveAlarmDesc($disaster, $dispatchSchema, $rescueDepartment, $disasterProcess, $user);
		} elseif ($disasterProcess->getProcessState()->getType() == 2) {
			return $this->buildUploadPowerDesc($disaster, $dispatchSchema, $rescueDepartment,  $disasterProcess, $user);
		} elseif ($disasterProcess->getProcessState()->getType() == 3) {
			return $this->buildArriveSceneDesc($disaster, $dispatchSchema, $rescueDepartment, $disasterProcess, $user);
		} elseif ($disasterProcess->getProcessState()->getType() == 4) {
			return $this->buildDisasterFinishedDesc($disaster,$dispatchSchema,  $rescueDepartment, $disasterProcess, $user);
		} elseif ($disasterProcess->getProcessState()->getType() == 5) {
			return $this->buildGoBackDesc($disaster, $dispatchSchema, $rescueDepartment, $disasterProcess, $user);
		} elseif ($disasterProcess->getProcessState()->getType() == 6) {
			return $this->buildInDepartmentDesc($disaster,$dispatchSchema,  $rescueDepartment, $disasterProcess, $user);
		}
		
		return null;
	}
}

/*
$params = array('id' => 4, 'userName'=>'tehroot');

$disaster = $entityManager->getRepository('Disaster')->findOneBy(array('id'=>$params['id']));
$user = $entityManager->getRepository('User')->findOneBy(array('userName'=>$params['userName']));

$dispatchSchema = $disaster->getDispatchSchema();
$rescueDepartments = $dispatchSchema->getRescueDepartment()->toArray();

$rescueDepartment = $rescueDepartments[0];

$disasterProcess = $rescueDepartment->getDisasterProcess();


$ddd = new DisasterDetailDesc();
var_dump( $ddd->buildDesc($disaster, $dispatchSchema, $rescueDepartment, $disasterProcess, $user));
*/