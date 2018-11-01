<?php
    // MessageDispatcher.php
	
	require_once 'MessageManager.php';
	require_once 'HallManager.php';
	
	/**************************************************************************
	 * 说明：消息分发
	 *
	 *
	 *
	 *************************************************************************/
	 class MessageDispatcher
	 {
		private static $instance;
		
		private $handlers = array();
		
		protected function __construct() {}
		
		protected function __clone() {}
		
		public static function getSingleton() 
		{
			if (!self::$instance) {
				self::$instance = new self();
			}
			
			return self::$instance;
		}
		
		public function handle(array $arr, $socket, $io, $em) 
		{
			$type = $arr['msgType'];
			$handler = MessageManager::getSingleton()->getMessageHandler($type);
			if (!$handler) {
				return false;
			}
			
			$obj = new $handler();
			if (!$obj) {
				return false;
			}
			
			$cityId = base64_decode(urldecode($arr['cityId']));
			$room = HallManager::getSingleton()->getRoom($cityId);
			if ($obj->handle($arr , $room, $socket, $io, $em)) {
				$obj->dispatch($socket, $io, $room, $em);
				
				return true;
			} else {
				$obj->error($socket, $io, $room, $em);
				
				return false;
			}

			return false;
		}
		
		public function handleDisconnect($socket, $io, $em)
		{
			$data = array('msgType' => ServerMessage::DISCONNECT, 'cityId' => urlencode(base64_encode($socket->roomId)), 'userName' => urlencode(base64_encode($socket->userName)));
			$this->handle($data, $socket, $io, $em);
		}
	}