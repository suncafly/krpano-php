<?php
	
/**************************************************************************
 *
 * 说明：保存的数据类型
 * 作者：李长明
 * 时间：20181022
 *
 *************************************************************************/
	class saveTypes {
		
	
		//视角
		const BaseSet 		= 'baseSet';
		//热点
		const hotsopt       = 'hotsopt';
		//雷达
		const groups         = 'groups';
		//嵌入
		const embed         = 'embed';
		//音乐
		const music         = 'music';
		//特效
		const specialEffect = 'specialEffect';
	
		
		private static $instance;
		
		private function __construct() {
			
		}
		
		public static function getSingleton() {
			if ( !self::$instance ) {
				self::$instance = new self();
			}
			
			return self::$instance;
		}
		
	}
?>