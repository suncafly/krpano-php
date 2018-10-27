<?php

require_once "/../doctrine2/bootstrap.php";
require_once "UserPrivilege.php";

class Rescuer
{
	protected $em = null;
	const STATE_ERROR = 'error';
	const STATE_SUCCESS = 'success';

	protected $result = array("state" => "error", "data" => "调用有误!");	
	public function __construct($em)
	{
		$this->em = $em;
	}

	protected function setResult($state, $data)
	{
		if ($state !== self::STATE_ERROR && $state !== self::STATE_SUCCESS) {
			return;
		}
			
		$this->result = array('state' => $state, 'msg' => $data);
	}
	
	public function getResult()
	{
		return $this->result;
	}
	
	protected function setSuccessResult($data) 
	{
		$this->setResult(self::STATE_SUCCESS, $data);
	}
		
	protected function setErrorResult($data) 
	{
		$this->setResult(self::STATE_ERROR, $data);
	}
	
	public function handleRescuers($departmentId)
	{
		if (!$this->em) {
			return false;
		}
		
		$users = $this->em->getRepository('User')->findBy(array('privilege'=>UserPrivilege::UP_ZHONGDUI_STAFF, 'state' => 0, 'departmentId' => $departmentId));
		if (!$users) {
			return false;
		}
		
		$userList = array();
		foreach($users as $user) {
			$userList[] = array('id' =>$user->getId(), 'userName'=> $user->getUserName(), 'name' => $user->getName(), 'post' => $user->getPost());
		}
		
		$this->setSuccessResult($userList);
		
		return true;
	}
	
	public function response()
	{
		echo json_encode($this->result, JSON_UNESCAPED_UNICODE);
	}	
}

$rescuer = new Rescuer($entityManager);
$rescuer->handleRescuers($_POST['departmentId']);
$rescuer->response();