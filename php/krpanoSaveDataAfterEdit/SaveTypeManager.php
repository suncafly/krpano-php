<?php

require_once 'KrBaseHandler.php';
require_once 'SaveTypes.php';
require_once 'KrBaseSet.php';
require_once 'KrHotsopt.php';
require_once 'KrGroups.php';
require_once 'KrEmbed.php';
require_once 'KrMusic.php';
require_once 'KrSpecialEffect.php';
require_once '../fileOptionManager/fileOptionManager.php';

/**************************************************************************
 *
 * 说明：资源类型管理器，负责类型信息注册
 * 作者：李长明
 * 时间：20180917
 *
 *************************************************************************/
 
class saveTypeManager 
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
		
	public function registerHandler($TargetTypeText, $handleClassName) 
	{
		if (!class_exists($handleClassName)) {
			return;
		}
		
		$this->handlers[$TargetTypeText] = $handleClassName;
	}
		
	public function batchRegisterHandler($arr) {
		
		if ( !is_array($arr) ) {
			return;
		}
			
		for ($i = 0, $len = count($arr); $i < $len; ++$i) {
			if (!is_array($arr[$i]) || (count($arr[$i]) !=2) ) {
				continue;
			}
				
			$this->registerHandler($arr[$i][0], $arr[$i][1]);
		}
	}
			
	public function getMessageHandler($TargetTypeText)
	{
		return array_key_exists($TargetTypeText, $this->handlers) ? $this->handlers[$TargetTypeText] : null;
	}

}

saveTypeManager::getSingleton()->batchRegisterHandler(
	
	array(
	
		array(saveTypes::BaseSet,      	KrBaseSet::class),
		array(saveTypes::hotsopt,      	KrHotsopt::class),
		array(saveTypes::groups,   		KrGroups::class),
		array(saveTypes::embed, 		KrEmbed::class),
		array(saveTypes::music,        	KrMusic::class),
		array(saveTypes::specialEffect, KrSpecialEffect::class),
	)
);
	
