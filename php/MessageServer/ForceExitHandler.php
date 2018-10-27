<?php
date_default_timezone_set('PRC');
	
require_once 'Handler.php';
require_once 'HallManager.php';
	
/**************************************************************************
 *
 * 说明：退出操作
 *
 *
 *************************************************************************/
class ForceExitHandler extends AMHandler
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
		
		$token = $user->getToken();
		
		if($token != "") {
			$user->setToken("");
			$em->persist($user);
			$em->flush();
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