<?php
// MessageDispatcher.php
require_once 'UserLoginHandler.php';
require_once 'TodayAccidentHandler.php';
require_once 'HistoryAccidenthandler.php';
require_once 'DisconnectHandler.php';
require_once 'NewDisasterHandler.php';
require_once 'TodayAccidentHandler.php';
require_once 'DisasterHandler.php';	
require_once 'AlarmStepsHandler.php';
require_once 'CloseAlarmHandler.php';
require_once 'ChatMessageHandler.php';
require_once 'RescuerConfirmHandler.php';
require_once 'ReconnectHandler.php';
require_once 'ForceExitHandler.php';
require_once 'HistoryValidAccidentListHandler.php';

class ServerMessage
{
	const USER_LOGIN       = 1000;
	const TODAY_ACCIDENT   = 2000;
	const HISTORY_ACCIDENT = 3000;
	const DISCONNECT       = 4000;
	const NEW_DISASTER     = 5000;
	const DISASTER         = 6000;
	const ALARM_STEPS      = 7000;
	const CLOSE_ALARM      = 8000;
	const CHAT_MESSAGE     = 9000;
	const RESCUER_CONFIRM  = 10000;
	const RECONNECT        = 11000;
	const FORCEEXIT        = 12000;
	const HISTORY_VALID_ACCIDENT_LIST = 13000;
}
	
class ClientMessage
{
	const NEW_DISASTER   = 1000;
	const TODAY_ACCIDENT = 2000;
	const DISASTER       = 3000;
	const HISTORY_ACCIDENT = 4000;
	const ALARM_STEP_TYPE_1_RECEIVE_ALARM     = 5000;
	const ALARM_STEP_TYPE_2_UPLOAD_POWER      = 5010;
	const ALARM_STEP_TYPE_3_ARRIVE_SCENE      = 5020;
	const ALARM_STEP_TYPE_4_DISASTER_FINISHED = 5030;
	const ALARM_STEP_TYPE_5_GO_BACK           = 5040;
	const ALARM_STEP_TYPE_6_IN_DEPARTMENT     = 5050;
	const CLOSE_ALARM                         = 6000;
	const CHAT_MESSAGE                        = 7000;
	const RESCUER_NOTIFICATION_CONFIRMED      = 8000;
	const HISTORY_VALID_ACCIDENT_LIST         = 9000;
}
	
	
/**************************************************************************
 * 说明：消息管理器，负责消息注册
 *
 *
 *
 *************************************************************************/
class MessageManager 
{
	private static $instance;
		
	private $handlers = array();
	private $msgMap = array();
		
	protected function __construct() {}
		
	protected function __clone() {}
		
	public static function getSingleton() 
	{
		if (!self::$instance) {
			self::$instance = new self();
		}
			
		return self::$instance;
	}
		
	public function registerHandler($msgTypeId, $msgTypeText, $handleClassName) 
	{
		if (!class_exists($handleClassName)) {
			return;
		}
			
		if (array_key_exists($msgTypeText, $this->msgMap)) {
			return;
		}
			
		$this->msgMap[$msgTypeText] = $msgTypeId;
			
		$this->handlers[$msgTypeId] = $handleClassName;
	}
		
	public function batchRegisterHandler($arr) {
		if ( !is_array($arr) ) {
			return;
		}
			
		for ($i = 0, $len = count($arr); $i < $len; ++$i) {
			if (!is_array($arr[$i]) || (count($arr[$i]) !=3) ) {
				continue;
			}
				
			$this->registerHandler($arr[$i][0], $arr[$i][1], $arr[$i][2]);
		}
	}
		
	public function getMessageMap()
	{
		return $this->msgMap;
	}
		
	public function getMessageHandler($msgTypeId)
	{
		return array_key_exists($msgTypeId, $this->handlers) ? $this->handlers[$msgTypeId] : null;
	}
}
	
	/*
	MessageManager::getSingleton()->registerHandler(1000, 'MSG_TYPE_USER_LOGIN',       UserLoginHandler::class);
	MessageManager::getSingleton()->registerHandler(2000, 'MSG_TYPE_TODAY_ACCIDENT',   TodayAccidentHandler::class);
	MessageManager::getSingleton()->registerHandler(3000, 'MSG_TYPE_HISTORY_ACCIDENT', HistoryAccidentHandler::class);
	MessageManager::getSingleton()->registerHandler(4000, 'MSG_TYPE_DISCONNECT',       DisconnectHandler::class);
	MessageManager::getSingleton()->registerHandler(5000, 'MSG_TYPE_NEW_DISASTER',     NewDisasterHandler::class);
	*/
	
MessageManager::getSingleton()->batchRegisterHandler(
	array(
		array(ServerMessage::USER_LOGIN,       'MSG_TYPE_USER_LOGIN',       UserLoginHandler::class),
		array(ServerMessage::TODAY_ACCIDENT,   'MSG_TYPE_TODAY_ACCIDENT',   TodayAccidentHandler::class),
		array(ServerMessage::HISTORY_ACCIDENT, 'MSG_TYPE_HISTORY_ACCIDENT', HistoryAccidentHandler::class),
		array(ServerMessage::DISCONNECT,       'MSG_TYPE_DISCONNECT',       DisconnectHandler::class),
		array(ServerMessage::NEW_DISASTER,     'MSG_TYPE_NEW_DISASTER',     NewDisasterHandler::class),
		array(ServerMessage::DISASTER,         'MSG_TYPE_DISASTER',         DisasterHandler::class),
		array(ServerMessage::ALARM_STEPS,      'MSG_TYPE_ALARM_STEPS',      AlarmStepsHandler::class),
		array(ServerMessage::CLOSE_ALARM,      'MSG_TYPE_CLOSE_ALARM',      CloseAlarmHandler::class),
		array(ServerMessage::CHAT_MESSAGE,     'MSG_TYPE_CHAT_MESSAGE',     ChatMessageHandler::class),
		array(ServerMessage::RESCUER_CONFIRM,  'MSG_TYPE_RESCUER_CONFIRM',  RescuerConfirmHandler::class),
		array(ServerMessage::RECONNECT,        'MSG_TYPE_RECONNECT',        ReconnectHandler::class),
		array(ServerMessage::FORCEEXIT,        'MSG_TYPE_FORCEEXIT',        ForceExitHandler::class),
		array(ServerMessage::HISTORY_VALID_ACCIDENT_LIST, 'MSG_TYPE_HISTORY_VALID_ACCIDENT_LIST', HistoryValidAccidentListHandler::class)
	)
);
	
