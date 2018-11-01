<?php

require_once 'BaseResourceTypeHandler.php';
require_once 'ResourceTypes.php';
require_once 'BasicMaterialResourceOpHandler.php';
require_once 'PanoImgResourceOpHandler.php';
require_once 'VideoResourceOpHandler.php';
require_once 'VoiceResourceOpHandler.php';
require_once 'ResourceAllOpHandler.php';
require_once '../fileOptionManager/fileOptionManager.php';

/**************************************************************************
 *
 * 说明：资源类型管理器，负责类型信息注册
 * 作者：李长明
 * 时间：20180917
 *
 *************************************************************************/
 
class ResourceTypeManager 
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
		
	public function registerHandler($EntityId, $TargetTypeText, $handleClassName) 
	{
		if (!class_exists($handleClassName)) {
			return;
		}
			
		if (array_key_exists($TargetTypeText, $this->msgMap)) {
			return;
		}
			
		$this->msgMap[$TargetTypeText] = $EntityId;
			
		$this->handlers[$EntityId] = $handleClassName;
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
		
	public function getMessageHandler($EntityId)
	{
		return array_key_exists($EntityId, $this->handlers) ? $this->handlers[$EntityId] : null;
	}
	
	public function getMessageHandlerByEntityClass($EntityClass)
	{
		if (array_key_exists($EntityClass, $this->msgMap)) {
				
			$EntityId = $this->msgMap[$EntityClass];
			return $this->getMessageHandler($EntityId);
		}
		else {
			return;
		}
		
	}
}
	
ResourceTypeManager::getSingleton()->batchRegisterHandler(
	
	array(
	
		array(ResourceTypes::ResourceAll,      			'ResourceAllOpHandler',       			ResourceAllOpHandler::class),
		array(ResourceTypes::PanoImgResource,      		'PanoImgLayer',       		PanoImgResourceOpHandler::class),
		array(ResourceTypes::BasicMaterialResource,   	'BasicMaterialLayer',   		BasicMaterialResourceOpHandler::class),
		array(ResourceTypes::VoiceResource, 			'VoiceLayer', 				VoiceResourceOpHandler::class),
		array(ResourceTypes::VideoResource,        		'VideoLayer',       			VideoResourceOpHandler::class),
	)
);
	
