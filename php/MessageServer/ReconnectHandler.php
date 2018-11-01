<?php
date_default_timezone_set('PRC');
	
require_once 'Handler.php';
require_once 'HallManager.php';
	
/**************************************************************************
 *
 * 说明：重新连接消息处理器
 *
 *
 *************************************************************************/
class ReconnectHandler extends AMHandler
{
	protected $userItem = null;		
	protected $roomId = -1;
		
	public function __construct() 
	{
	}
	
	public function handle(array $arr, $room, $socket, $io, $em) 
	{
		$userName = base64_decode(urldecode($arr['userName']));
		$cityId   = base64_decode(urldecode($arr['cityId']));

		$user = $em->getRepository('User')->findOneBy(array('userName'=>$userName));
						 
		if (! $user) {
			return false;
		}
		
		$socket->userName = $userName;
		$socket->roomId   = $cityId;
			
		$socket->join($cityId);
		$socket->addedUser = true;
		
		$userItem = $room->getUser($userName);
			
		if ($userItem) {
			$socketId = $userItem['socketId'];

			unset($io->sockets->sockets[$socketId]);
			$room->setSocketid($userName, $socket->id);
		}
		else {
			$room->addUser($userName, $user, $socket->id);
		}
			
		return true;
	}
		
	public function dispatch($socket, $io, $room, $em) 
	{
		
	}
		
	public function error($socket, $io, $room, $em) 
	{
		$socket->emit('sysError', $this->result );
	}
}