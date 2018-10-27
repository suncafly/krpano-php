<?php
date_default_timezone_set('PRC');
	
require_once 'Handler.php';
require_once 'HallManager.php';
require_once '/../Utils/XDate.php';
/**************************************************************************
 *
 * 说明：聊天消息
 *
 *
 *************************************************************************/
class ChatMessageHandler extends AMHandler 
{
	protected $userName  = '';
	protected $cityId = -1;
	protected $user = null;
	protected $receivers = array();
		
	public function __construct() 
	{
	}

	public function handle(array $params , $room, $socket, $io, $em) 
	{
		if (!$this->verifyProps(['disasterId', 'departmentId', 'userId', 'dataType'], $params)) {
			return false;
		}
		
		$disasterId = $params['disasterId'];
		$disaster = $em->find('Disaster', $disasterId);
		if (!$disaster) {
			return false;
		}
		
		$this->userName = base64_decode(urldecode($params['userName']));
		$this->cityId   = base64_decode(urldecode($params['cityId']));
			
		$this->user = $em->getRepository('User')->findOneBy(array('userName'=>$this->userName));
						 
		if (!$this->user) {
			return false;
		}
		
		$departmentId = $params['departmentId'];
		$userId = $params['userId'];
		
		$chatReceivers = array();
		$members = $disaster->getMembers();
		
		if ($departmentId == -1) {
			$chatReceivers = array_merge($members);
		} else {
			if ($userId == -1) {
				foreach ($members as $userName => $detail) {
					if ($detail['departmentId'] == $departmentId) {
						$chatReceivers[$userName] = $detail;
					}
				}
			}
			else {
				foreach ($members as $userName => $detail) {
					if ($detail['departmentId'] == $departmentId && $detail['id'] == $userId) {
						$chatReceivers[$userName] = $detail;
					}
				}
			}
			
			if (array_key_exists($this->userName, $members)) {
				$chatReceivers[$this->userName] = $members[$this->userName];
			}
		}
		
		foreach ($chatReceivers as $userName => $detail){ $this->receivers[] = $userName; }
		
		$chatMessage = new ChatMessage();
		
		$chatMessage->setTime(XDate::getTime());
		$chatMessage->setReceivers($chatReceivers);
		
		$dataType = $params['dataType'];
		
		$chatMessage->setMsgType($dataType);
		
		$chatMessage->setSender($this->userName);

		$disaster->addChatMessage($chatMessage);
		$chatMessage->setDisaster($disaster);

		if ($dataType == "text") {
			$content = $params['content'];
			$chatMessage->setContent($content);
		} elseif ($dataType == "image") {
			$uploadFileUrl = $params['uploadFileUrl'];
			$chatMessage->setUploadFileUrl($uploadFileUrl);
		} elseif ($dataType == "uploadfile") {
			$uploadFileUrl = $params['uploadFileUrl'];
			$chatMessage->setUploadFileUrl($uploadFileUrl);
			$fileLength = $params['fileLength'];
			$chatMessage->setFileLength($fileLength);
		} elseif ($dataType == "plan") {
			
		} elseif ($dataType == "audio") {
			$uploadFileUrl = $params['uploadFileUrl'];
			$chatMessage->setUploadFileUrl($uploadFileUrl);
			$fileLength = $params['fileLength'];
			$chatMessage->setFileLength($fileLength);
		} elseif ($dataType == "video") {
			$uploadFileUrl = $params['uploadFileUrl'];
			$chatMessage->setUploadFileUrl($uploadFileUrl);
			$fileLength = $params['fileLength'];
			$chatMessage->setFileLength($fileLength);
		} else {
			
			return false;
		}
		
		$em->persist($disaster);
		$em->flush();

		$this->setSuccessResult(array('msgType' => ClientMessage::CHAT_MESSAGE, 'data' => $chatMessage->getDesc()));
			
		return true;
	}
		
	public function dispatch($socket, $io, $room, $em) 
	{
		for ($i = 0, $len = count($this->receivers); $i < $len; ++$i) {
			$userItem = $room->getUser($this->receivers[$i]);
			if (!$userItem) {
				continue;
			}
			
			$socketId = $userItem['socketId'];
				
			if (!isset($io->sockets->sockets[$socketId])) {
				continue;
			}
				
			$io->sockets->sockets[$socketId]->emit('message', $this->result);
		}
		
		//$socket->emit('message', $this->result);
	}
		
	public function error($socket, $io, $room, $em) 
	{
		$socket->emit('sysError', $this->result);
	}
}