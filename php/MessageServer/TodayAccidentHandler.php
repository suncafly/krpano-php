<?php
	date_default_timezone_set('PRC');
	
	require_once 'Handler.php';
	require_once 'HallManager.php';
	
	/**************************************************************************
	 *
	 * 说明：今日灾情
	 *
	 *
	 *************************************************************************/
	class TodayAccidentHandler extends AMHandler 
	{
		protected $userName = '';
		protected $cityId = -1;
		protected $user = null;
		
		public function __construct() 
		{
		}
		
		public function handle(array $arr, $room, $socket, $io, $em) 
		{
			$this->userName = base64_decode(urldecode($arr['userName']));
			$this->cityId   = base64_decode(urldecode($arr['cityId']));
			$this->user = $em->getRepository('User')->findOneBy(array('userName'=>$this->userName));
						 
			if (!$this->user) {
				return false;
			}

			// 按条件过滤
			if ($this->user->getPrivilege() == UserPrivilege::UP_ZHIDUI) {
				if (!$this->handleZhiDui($arr, $room, $socket, $io, $em)) {
					return false;
				}
			} elseif ($this->user->getPrivilege() == UserPrivilege::UP_ZHONGDUI) {
				if (!$this->handleZhongDui($arr, $room, $socket, $io, $em)) {
					return false;
				}
			} elseif ($this->user->getPrivilege() == UserPrivilege::UP_ZHONGDUI_STAFF) {
				if (!$this->handleZhongDuiStaff($arr, $room, $socket, $io, $em)) {
					return false;
				}
			}
			
			return true;
		}
		
		public function dispatch($socket, $io, $room, $em) 
		{
			$socket->emit('message', $this->result);
		}
		
		public function error($socket, $io, $room, $em) 
		{
			$socket->emit('sysError', $this->result );
		}
		
		protected function handleZhiDui(array $arr , $room, $socket, $io, $em)
		{
			if (!$this->user) {
				return false;
			}
			
			$now = new DateTime('now');
			$disasters = $em->getRepository('Disaster')->findBy(array('date' => $now, 'operator'=> $this->user->getUserName()), array('time'=>'ASC'));
			
			$result = array();
			
			foreach($disasters as $disaster) {
				$currentDS = $disaster->getDispatchSchema();
				
				for ($currentDS = $disaster->getDispatchSchema(); $currentDS->getUpgradeDispatchSchema() != null; $currentDS = $currentDS->getUpgradeDispatchSchema()) 
					; 
				if ($currentDS) {
					$result[] = array(
						'id' => $disaster->getId(),
						'address' => $disaster->getAddress(),
						'state' => $disaster->getAlarmClosed() ? '完结' : '正在处理',
						'stateCode' =>  $disaster->getAlarmClosed(),
						'operator' => $disaster->getOperator(),
						'date' => $disaster->getDate()->format('Y-m-d'),
						'time' =>  date("Y-m-d H:i:s",strtotime($disaster->getTime())),
						'category' => $currentDS->getDisasterHandlerSchema()->getDisasterCategory()->getStrategyName(),
						'level' => $currentDS->getDisasterHandlerSchema()->getDisasterDesc()->getLevel(),
						'msg' => ''
					);
				}
			}
			
			$this->setSuccessResult(array('msgType' => ClientMessage::TODAY_ACCIDENT, 'list' => $result));
			return true;
		}
		
		protected function handleZhongDui(array $arr , $room, $socket, $io, $em)
		{
			if (!$this->user) {
				return false;
			}
			
			$now = new DateTime('now');
			$disasters = $em->getRepository('Disaster')->findBy(array('date' => $now), array('time'=>'ASC'));
			
			$result = array();
			
			$currentUserName = $this->user->getUserName();
			$currentDepartmentId = $this->user->getDepartmentId();
			foreach($disasters as $disaster) {
				$members = $disaster->getMembers();
				
				if (array_key_exists($currentUserName, $members)) {
					
					for ($currentDS = $disaster->getDispatchSchema(); $currentDS->getUpgradeDispatchSchema() != null; $currentDS = $currentDS->getUpgradeDispatchSchema()) 
						;
					if ($currentDS) {
						// 中队接警员
						$result[] = array(
							'id' => $disaster->getId(),
							'address' => $disaster->getAddress(),
							'state' => $disaster->getAlarmClosed() ? '完结' : '正在处理',
							'stateCode' =>  $disaster->getAlarmClosed(),
							'operator' => $disaster->getOperator(),
							'date' => $disaster->getDate()->format('Y-m-d'),
							'time' =>  date("Y-m-d H:i:s",strtotime($disaster->getTime())),
							'category' => $currentDS->getDisasterHandlerSchema()->getDisasterCategory()->getStrategyName(),
							'level' => $currentDS->getDisasterHandlerSchema()->getDisasterDesc()->getLevel(),
							'msg' => ''
						);	
					}
				} else {
					
					for ($dispatchSchema = $disaster->getDispatchSchema(); $dispatchSchema != null; $dispatchSchema = $dispatchSchema->getUpgradeDispatchSchema()) {
						$rescueDepartmentList = $dispatchSchema->getRescueDepartment()->toArray();
						for ($idx = 0, $listLen = count($rescueDepartmentList); $idx < $listLen; ++$idx) {
							$rescueDepartment = $rescueDepartmentList[$idx];
							//echo $rescueDepartment->getDepartmentId() == $currentDepartmentId, "\n";
							//echo $rescueDepartment->getOperator(), "\n";
							if ($rescueDepartment->getDepartmentId() == $currentDepartmentId && $rescueDepartment->getOperator() == "") {
								// 中队接警员
								$result[] = array(
									'id' => $disaster->getId(),
									'address' => $disaster->getAddress(),
									'state' => $disaster->getAlarmClosed() ? '完结' : '正在处理',
									'stateCode' =>  $disaster->getAlarmClosed(),
									'operator' => $disaster->getOperator(),
									'date' => $disaster->getDate()->format('Y-m-d'),
									'time' =>  date("Y-m-d H:i:s",strtotime($disaster->getTime())),
									'category' => $dispatchSchema->getDisasterHandlerSchema()->getDisasterCategory()->getStrategyName(),
									'level' => $dispatchSchema->getDisasterHandlerSchema()->getDisasterDesc()->getLevel(),
									'msg' => ''
								);	
							}
						}
					}
				}
			}
			
			
			$this->setSuccessResult(array('msgType' => ClientMessage::TODAY_ACCIDENT, 'list' => $result));
			return true;
		}
		
		protected function handleZhongDuiStaff(array $arr , $room, $socket, $io, $em)
		{
			if (!$this->user) {
				return false;
			}
			
			$now = new DateTime('now');
			$disasters = $em->getRepository('Disaster')->findBy(array('date' => $now), array('time'=>'ASC'));
			
			$result = array();
			
			foreach($disasters as $disaster) {
				$members = $disaster->getMembers();
				
				if (array_key_exists($this->user->getUserName(), $members)) {
					for ($currentDS = $disaster->getDispatchSchema(); $currentDS->getUpgradeDispatchSchema() != null; $currentDS = $currentDS->getUpgradeDispatchSchema()) 
						;
					if ($currentDS) {
						// 中队接警员
						$result[] = array(
							'id' => $disaster->getId(),
							'address' => $disaster->getAddress(),
							'state' => $disaster->getAlarmClosed() ? '完结' : '正在处理',
							'stateCode' =>  $disaster->getAlarmClosed(),
							'operator' => $disaster->getOperator(),
							'date' => $disaster->getDate()->format('Y-m-d'),
							'time' =>  date("Y-m-d H:i:s",strtotime($disaster->getTime())),
							'category' => $currentDS->getDisasterHandlerSchema()->getDisasterCategory()->getStrategyName(),
							'level' => $currentDS->getDisasterHandlerSchema()->getDisasterDesc()->getLevel(),
							'msg' => ''
						);	
					}
				}
			}
			
			$this->setSuccessResult(array('msgType' => ClientMessage::TODAY_ACCIDENT, 'list' => $result));
			return true;
		}
	}