<?php
session_start();
date_default_timezone_set('PRC');
	
require_once 'Handler.php';
require_once 'HallManager.php';
	
	
/**************************************************************************
 *
 * 说明：登录消息处理类
 *
 *
 *************************************************************************/
class UserLoginHandler extends AMHandler 
{
	private $userName;
	private $cityId;
	private $sid;
	private $user;
		
	public function __construct() 
	{
	}
		
	public function handle(array $arr , $room, $socket, $io, $em) 
	{
		$this->userName = base64_decode(urldecode($arr['userName']));
		$this->cityId   = base64_decode(urldecode($arr['cityId']));
		$userSignature  = $arr['userSignature'];
		$this->user = $em->getRepository('User')
			             ->findOneBy(array('userName'=>$this->userName));
						 
		if (! $this->user) {
			return false;
		}
			
		$md5 = MD5($this->user->getUserName(). ''.$this->user->getPassword());
		if ($md5 != $userSignature) {
			return false;
		}
		
		if(isset($arr['token'])) {
					
			$this->token  = $arr['token'];
			$this->user->setToken($this->token);
			$em->persist($this->user);
			$em->flush();
		}
			
		return true;
	}
		
	public function dispatch($socket, $io, $room, $em) 
	{
		$userInRoom = $room->getUser($this->userName);
		if ($userInRoom) {
			$oldSocketId = $userInRoom['socketId'];
			if (isset($io->sockets->sockets[$oldSocketId])) {
				$this->setErrorResult('该账户在别处登录，您被迫下线！');
				$io->sockets->sockets[$userInRoom['socketId']]->addedUser = false;
				$io->sockets->sockets[$userInRoom['socketId']]->emit('forceExit', $this->result);
			}
		}		
		
		$socket->userName = $this->userName;
		$socket->roomId   = $this->cityId;
			
		$socket->join($this->cityId);
		$socket->addedUser = true;
		$room->addUser($this->userName, $this->user, $socket->id);

		$cityCode = $this->user->getCity()->getCityCode();			
		$adCode = $this->user->getCity()->getAdCode();
			
		$msgMap = MessageManager::getSingleton()->getMessageMap();
			
		$center = $this->getCurUserLoc($em);
			
		$userInfo = array(
			'userName' => $this->userName,
			'department' => $this->user->getDepartment(),
			'departmentId' => $this->user->getDepartmentId(),
			'privilege' => $this->user->getPrivilege(),
			'cityCode' => $cityCode,
			'adCode' => $adCode,
			'center'   =>$center,
			'name'     =>$this->user->getName(),
			'post'     =>$this->user->getPost()
		);
			
		$this->setSuccessResult(array_merge($userInfo, array('msgTypes' => $msgMap)));
			
		$socket->emit('login', $this->result);
	}
		
	public function error($socket, $io, $room, $em) 
	{
		$socket->emit('sysError', $this->result );
	}
		
	public function getCurUserLoc($em) 
	{	
		$loc = array();
			
		if(! $em) {
			return array("lon"=>"1", "lati"=>"1");
		}
			
		//权限	
		$privilege = $this->user->getPrivilege();
		//中队或者支队ID
		$departmentId = $this->user->getDepartmentId();
			
		$tableEntityName = "";
		if ($privilege == 100) {
			$tableEntityName = 'FireBranchTeam';
		}
		else {
			$tableEntityName = 'fireSquadronTeam';
		}
		
		$row = $em->getRepository($tableEntityName)->findOneBy(array('id'=>$departmentId));
			//$row = $em->find($tableEntityName, $departmentId);//
			
		if($row) {
   			$lon = $row->getLon();
			$lati = $row->getLati();
				
			$ret = array("lon"=>$lon, "lati"=>$lati);
				
			return $ret;
		}
		else { 
			return $loc;
		}
	}
}