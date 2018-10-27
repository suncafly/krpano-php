<?php
	
	/**************************************************************************
	 *
	 * 说明：资源类型管理类
	 * 作者：李长明
	 * 时间：20180917
	 *
 *************************************************************************/
	class ResourceTypes {
		
		/*
		 * 资源管理类 当前资源类型  类型与实体类名同步  其中0表示所有类型通用的处理函数， 不需要分开路由到各个类实体中去处理
		 */
		const ResourceAll                     		   = 0;
		const PanoImgResource                          = 1;
		const BasicMaterialResource                    = 2;
		const VoiceResource                      	   = 3;
		const VideoResource                     	   = 4;
	
		
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