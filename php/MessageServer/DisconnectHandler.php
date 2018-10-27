<?php
date_default_timezone_set('PRC');
	
require_once 'Handler.php';
require_once 'HallManager.php';
	
/**************************************************************************
 *
 * 说明：断开链接消息处理器
 *
 *
 *************************************************************************/
class DisconnectHandler extends AMHandler
{
	protected $userItem = null;		
	protected $roomId = -1;
		
	public function __construct() 
	{
	}
	
	public function handle(array $arr , $room, $socket, $io, $em) 
	{		
		$existed = property_exists($socket,'addedUser');

		if (!$existed) {
			return false;
		}
		
		if (!$socket->addedUser) {
			return false;
		}
		
		$userName = base64_decode(urldecode($arr['userName']));
		$cityId   = base64_decode(urldecode($arr['cityId']));

		$userItem = $room->getUser($userName);

		if (! $userItem) {
			return false;
		}

		if (!isset($userItem['socketId']) || $userItem['socketId'] != $socket->id ) {
			return false;
		}			
	
		$this->userItem = $userItem;
			
		$user = $this->userItem['user'];
		$this->setSuccessResult(array('userName' => $user->getUserName(), 'department' => $user->getDepartment(), 'departmentId' => $user->getDepartmentId()));
			
		$this->roomId = $socket->roomId;
		//$room->removeUser($socket->userName);
		return true;
	}
		
	public function dispatch($socket, $io, $room, $em) 
	{
		$socket->broadcast->to($this->roomId)->emit('userLeft', $this->result);
	}
		
	public function error($socket, $io, $room, $em) 
	{
		$socket->emit('sysError', $this->result );
	}
}