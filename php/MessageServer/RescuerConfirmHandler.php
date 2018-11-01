<?php
date_default_timezone_set('PRC');
	
require_once 'Handler.php';
require_once 'HallManager.php';

/**************************************************************************
 *
 * 说明：营救人员确认接收消息
 *
 *
 *************************************************************************/
class RescuerConfirmHandler extends AMHandler 
{
	private $userName;
	private $cityId;
	private $user;
	
	private $receives = array();
		
	public function __construct() 
	{
		
	}
	
	public function handle(array $arr , $room, $socket, $io, $em) 
	{
		$this->userName = base64_decode(urldecode($arr['userName']));
		$this->cityId   = base64_decode(urldecode($arr['cityId']));
			
		$this->user = $em->getRepository('User')->findOneBy(array('userName'=>$this->userName));
						 
		if (!$this->user) {
			return false;
		}
		
		if ($this->user->getPrivilege() != UserPrivilege::UP_ZHONGDUI_STAFF) {
			return false;
		}
		
		$disasterId = $arr['disasterId'];
		$disaster = $em->getRepository('Disaster')->findOneBy(array('id'=>$disasterId));
		if (! $disaster) {
			return false;
		}
		
		$members = $disaster->getMembers();
		
		if (! array_key_exists($this->userName, $members) ) {
			return false;
		}

		if (! $members[$this->userName]['confirmed']) {
			$members[$this->userName]['confirmed'] = true;
			$disaster->setMembers($members);
			$em->persist($disaster);
			$em->flush();
		}
		
		// 构造发送数据
		$data = array(
			'msgType' => ClientMessage::RESCUER_NOTIFICATION_CONFIRMED,
			'data' => array(
				'id' => $disaster->getId()
			)
		);
		
		$this->setSuccessResult($data);
		
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
}