<?php
date_default_timezone_set('PRC');
	
require_once 'Handler.php';
require_once 'HallManager.php';
	
/**************************************************************************
 *
 * 说明：有效警情列表处理类
 *
 *
 *************************************************************************/
class HistoryValidAccidentListHandler extends AMHandler 
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
		$socket->emit('sysError', $this->result);
	}
	
	protected function handleZhiDui(array $arr , $room, $socket, $io, $em)
	{
		if (!$this->user) {
			return false;
		}
			
		$maxDate = new DateTime('-1 days');
			
		$validDateList = array();
			
		$date = $maxDate;
			
		$qb = $em->getRepository('Disaster')->createQueryBuilder('d');
		$qb->where("d.date <= ?1")->orderBy('d.time', 'ASC')->setParameter(1, $date);
		$q = $qb->getQuery();
		
		$disasters = $q->getResult();
			
		foreach($disasters as $disaster) {
			$cDate = $disaster->getDate()->format('Y-m-d');
			if (! in_array($cDate, $validDateList)) {
				$validDateList[] = $cDate;
			}
		}
			
		$result = array(
			'maxDate' => $maxDate->format('Y-m-d'),
			'validDates' => $validDateList
		);
			
		$this->setSuccessResult(array('msgType' => ClientMessage::HISTORY_VALID_ACCIDENT_LIST, 'data' => $result));
		return true;
	}
		
	protected function handleZhongDui(array $arr , $room, $socket, $io, $em)
	{
		if (!$this->user) {
			return false;
		}
		
		$maxDate = new DateTime('-1 days');
			
		$validDateList = array();
		
		$date = $maxDate;
			
		$qb = $em->getRepository('Disaster')->createQueryBuilder('d');
		$qb->where("d.date <= ?1")->orderBy('d.time', 'ASC')->setParameter(1, $date);
		$q = $qb->getQuery();
		
		$disasters = $q->getResult();
			
		$currentUserName = $this->user->getUserName();
		$currentDepartmentId = $this->user->getDepartmentId();
		foreach($disasters as $disaster) {
			$members = $disaster->getMembers();
			
			if (array_key_exists($currentUserName, $members)) {
				for ($currentDS = $disaster->getDispatchSchema(); $currentDS->getUpgradeDispatchSchema() != null; $currentDS = $currentDS->getUpgradeDispatchSchema()) 
					;
					
				if ($currentDS) {
					// 中队接警员
					$cDate = $disaster->getDate()->format('Y-m-d');
					if (! in_array($cDate, $validDateList)) {
						$validDateList[] = $cDate;
					}
				}
			} else {
				for ($dispatchSchema = $disaster->getDispatchSchema(); $dispatchSchema != null; $dispatchSchema = $dispatchSchema->getUpgradeDispatchSchema()) {
					$rescueDepartmentList = $dispatchSchema->getRescueDepartment()->toArray();
					for ($idx = 0, $listLen = count($rescueDepartmentList); $idx < $listLen; ++$idx) {
						$rescueDepartment = $rescueDepartmentList[$idx];
						if ($rescueDepartment->getDepartmentId() == $currentDepartmentId && $rescueDepartment->getOperator() == "") {
							// 中队接警员
							$cDate = $disaster->getDate()->format('Y-m-d');
							if (! in_array($cDate, $validDateList)) {
								$validDateList[] = $cDate;
							}
						}
					}
				}
			}
		}
			
		$result = array(
			'maxDate' => $maxDate->format('Y-m-d'),
			'validDates' => $validDateList
		);
			
		$this->setSuccessResult(array('msgType' => ClientMessage::HISTORY_VALID_ACCIDENT_LIST, 'data' => $result));
			
		return true;
	}
		
	protected function handleZhongDuiStaff(array $arr , $room, $socket, $io, $em)
	{
		if (!$this->user) {
			return false;
		}
			
		$maxDate = new DateTime('-1 days');
			
		$validDateList = array();
			
		$date = $maxDate;
		
		$qb = $em->getRepository('Disaster')->createQueryBuilder('d');
		$qb->where("d.date <= ?1")->orderBy('d.time', 'ASC')->setParameter(1, $date);
		$q = $qb->getQuery();
		
		$disasters = $q->getResult();
		
		//\Doctrine\Common\Util\Debug::dump($disasters);
		$result = array();
			
		foreach($disasters as $disaster) {
			$members = $disaster->getMembers();
				
			if (array_key_exists($this->user->getUserName(), $members)) {
				for ($currentDS = $disaster->getDispatchSchema(); $currentDS->getUpgradeDispatchSchema() != null; $currentDS = $currentDS->getUpgradeDispatchSchema()) 
					;
				if ($currentDS) {
					// 中队出警人员
					$cDate = $disaster->getDate()->format('Y-m-d');
					if (! in_array($cDate, $validDateList)) {
						$validDateList[] = $cDate;
					}
				}
			}
		}
			
		$result = array(
			'maxDate' => $maxDate->format('Y-m-d'),
			'validDates' => $validDateList
		);
			
		$this->setSuccessResult(array('msgType' => ClientMessage::HISTORY_VALID_ACCIDENT_LIST, 'data' => $result));
			
		return true;
	}		
}