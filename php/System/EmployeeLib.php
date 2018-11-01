<?php
require_once "/../doctrine2/bootstrap.php";

class EmployeeLib
{
	protected $em = null;
	
	public function __construct($em)
	{
		$this->em = $em;
	}
	
	public function getList($idList)
	{
		$list = array();
		if (!$this->em) {
			return $list;
		}
		
		$idListLen = count($idList);
		
		if ($idListLen === 0) {
			return $list;
		}
		
		for ($i = 0; $i < $idListLen; ++$i) {
			$user = $this->em->find('User', $idList[$i]);
			if (!$user) {
				continue;
			}
			
			$userName = $user->getUserName();
			
			$list[$userName] = $this->buildUserItem($user);
		}
		
		return $list;
	}
	
	public function batchSetState($idList, $state)
	{
		if (!$this->em) {
			return;
		}
		
		$idListLen = count($idList);
		
		if ($idListLen === 0) {
			return;
		}
		
		for ($i = 0; $i < $idListLen; ++$i) {
			$user = $this->em->find('User', $idList[$i]);
			if (!$user) {
				continue;
			}
			
			$user->setState($state);
			
			$this->em->persist($user);
		}
		
		$this->em->flush();
	}
	
	protected function buildUserItem($user)
	{
		return array(
			'id'=>$user->getId(),
			'userName' => $user->getUserName(),
			'name' => $user->getName(),
			'departmentId' => $user->getDepartmentId(),
			'departmentName' => $user->getDepartment()
 		);
	}
}