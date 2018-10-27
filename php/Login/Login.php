<?php
// Login.php
require_once "../doctrine2/bootstrap.php";
require_once "../System/UserPrivilege.php";
	
date_default_timezone_set("Asia/Shanghai");
	
// 开启session
session_start();

/*
$_POST['userName'] = 'tehuser0001';
$_POST['password'] = '123456';
$_POST['environment'] = 'mobile';
*/
/*
if (!array_key_exists('userName', $_POST) || !array_key_exists('password', $_POST)) {
	echo json_encode(array(
		'state' => 'error',
		'msg' =>'非法输入'
	));
		
	exit();
}*/
	
class Auth
{
	const STATE_ERROR = 'error';
	const STATE_SUCCESS = 'success';
	protected $result = array("state" => "error", "msg" => "调用有误!");
	
	protected $em = null;

	protected $userName;
	protected $password;
	protected $environment;
	
	public function __construct($em)
	{
		$this->em = $em;
	}

	/**
	 * 说明：检测属性是否在对应数组或对象中
	 */
	protected function verifyProps($props, $o) 
	{
		if (is_object($o)) {
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
				if (is_array($val)) {
					$subElementsArrayCount++;
				}
			}
				
			if (count($o) == $subElementsArrayCount) {
				for ($j = 0,$oLen = count($o); $j < $oLen; $j++) {
					for ($i = 0, $len = count( $props ); $i < $len; $i++) {
						if (! array_key_exists($props[$i], $o[$j])) {
							return false;
						}
					}
				}
			} else {
				for ($i = 0, $len = count( $props ); $i < $len; $i++) {
					if (! array_key_exists( $props[$i], $o)) {
						return false;
					}
				}
			}
		} else {
			return false;
		}
			
		return true;
	}
	
	protected function setErrorResult($data)
	{
		$this->setResult(self::STATE_ERROR, $data);
	}
		
	protected function setSuccessResult($data)
	{
		$this->setResult(self::STATE_SUCCESS, $data);
	}
		
	protected function setResult($state, $data)
	{
		/*
		$this->state = $state;
		$this->msg = $msg;*/
		if ($state !== self::STATE_ERROR && $state !== self::STATE_SUCCESS) {
			return;
		}
			
		$this->result = array('state' => $state, 'msg' => $data);
	}

	public function getResult()
	{
		return $this->result;
	}	
		
	protected function isEmpty()
	{
		return strlen($this->userName) === 0 || strlen($this->password) === 0 || strlen($this->environment) === 0;
	}
		
	protected function logining()
	{
		if (! $this->em ) {
			$this->setErrorResult('系统异常！');
			return false;
		}
			
		if ($this->isEmpty()) {
			$this->setErrorResult('账户或密码不能为空！');
			return false;
		}
			
		$user = $this->em->getRepository('User')
			         ->findOneBy(array(
						'userName'=>$this->userName, 
						'password'=>$this->password
					));
						
		if (! $user ) {
			$this->setErrorResult('账户或密码有误！');
			return false;
		}
			
		if (! $user->getActived()) {
			$this->setErrorResult('账户未激活！');
			return false;
		}
		
		if ( $this->environment === 'mobile' ) {
			if ($user->getPrivilege() == UserPrivilege::UP_ZHIDUI || $user->getPrivilege() == UserPrivilege::UP_ZHONGDUI) {
				$this->setErrorResult('请从PC端登录！');
				return false;
			}
		}
			
		$sessionId = session_id();
			
		$user->setLoginTime(new DateTime('NOW'));
		if ( $user->getIsOnline() ) {
			$user->setSessionId($sessionId);
		}
		else {
			$user->setSessionId((string)$sessionId);
			$user->setIsOnline(true);
		}
			
		$cityId = $user->getCity()->getId();
		$cityCode = $user->getCity()->getCityCode();		
		$this->em->persist($user);
		$this->em->flush();
			
		$_SESSION['sessionId'] = $sessionId;
		$_SESSION['cityId'] = $cityId;
		$_SESSION['userName'] = $this->userName;
		$_SESSION['password'] = $this->password;
			
		$md5 = MD5($this->userName. ''.$this->password);
		
		/*
		$this->setSuccessResult(array(
			'userName' => urlencode(base64_encode($this->userName)),
			'password' => urlencode(base64_encode($this->password)),
			'cityId' => urlencode(base64_encode($cityId)),
			'sessionId' => urlencode(base64_encode($sessionId)),
			'cityCode'  => urlencode(base64_encode($cityCode)),
			'url' => 'index.html',
			'userSignature' => $md5
		));*/
		
		$userInfoList = array(
			'u='.urlencode(base64_encode($this->userName)),
			'p='.urlencode(base64_encode($this->password)),
			'cid='.urlencode(base64_encode($cityId))
		);
		
		$data = array(
			'url' => 'index.html',
			'data' => array(
				'zzsoftuser' => implode('&', $userInfoList),
				'sid' => urlencode(base64_encode($sessionId)),
				'cityCode' => urlencode(base64_encode($cityCode)),
				'userSignature' => $md5,
				'url' => 'index.html'
			)
		);
		
		$this->setSuccessResult($data);
		
		return true;
	}
		
	public function login($params)
	{
		if (! $this->verifyProps(['userName', 'password', 'environment'], $params)) {
			return;
		}
		
		$this->userName = $params['userName'];
		$this->password = $params['password'];
		$this->environment = $params['environment'];
		
		$this->logining();
			
		//$this->output();
	}
		
	public function response()
	{
		//echo json_encode(array('state'=>$this->state, 'msg'=>$this->msg));
		echo json_encode($this->result, JSON_UNESCAPED_UNICODE);		
	}
}
	
$auth = new Auth($entityManager);
$auth->login($_POST);
$auth->response();
	