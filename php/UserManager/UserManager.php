<?php

require_once "/../doctrine2/bootstrap.php";

class UserManager
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

	/**
	 * 说明：检测属性是否在对应数组或对象中
	 */
	protected function verifyProps($props, $o) 
	{
		if ( is_object( $o ) ) {
			for ($i = 0, $len = count($props); $i < $len; $i++) {
				if (!array_key_exists($props[$i], $o)) {
					return false;
				}
			}
		} else if (is_array($o)) {
			if (count($o) == 0) {
				return false;
			}
				
			$subElementsArrayCount = 0;
			foreach ($o as $key => $val) {
				if (is_array( $val )) {
					$subElementsArrayCount++;
				}
			}
				
			if (count($o) == $subElementsArrayCount) {
				for ($j = 0,$oLen = count($o); $j < $oLen; $j++) {
					for ($i = 0, $len = count( $props ); $i < $len; $i++) {
						if (!array_key_exists($props[$i], $o[$j])) {
							return false;
						}
					}
				}
			} else {
				for ($i = 0, $len = count( $props ); $i < $len; $i++) {
					if (!array_key_exists( $props[$i], $o)) {
						return false;
					}
				}
			}
		} else {
			return false;
		}
			
		return true;
	}
	
	public function handle(array $params)
	{
		if (!$this->em) {
			return false;
		}
		
		if (!$this->verifyProps(['userName'], $params)) {
			return false;
		}
		
		$userName = base64_decode(urldecode($params['userName']));
		
		$user = $this->em->getRepository('User')->findOneBy(array('userName' => $userName));
		if (!$user) {
			return false;
		}
		
		$this->setSuccessResult(array(
			'id' => $user->getId(),
			'userName' => $user->getUserName(),
			'name' => $user->getName(),
			'department' => $user->getDepartment(),
			'post' => $user->getPost()
		));
		
		return true;
	}
	
	public function response()
	{
		echo json_encode($this->result, JSON_UNESCAPED_UNICODE);
	}	
}

/*
$_POST['userName'] = "cm9vdDA%3D";
*/

$userManager = new UserManager($entityManager);
$userManager->handle($_POST);
$userManager->response();