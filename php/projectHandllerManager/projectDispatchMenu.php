<?php
/**
 * 通用的上传资源类
 * 2018年9月18号
 * 李长明
 */
 
	require_once "../doctrine2/bootstrap.php";
	require_once "projectBaseOpHandler.php";
	require_once "ProjectInfoManager.php";
	require_once "PushProjectManager.php";
	require_once '../fileOptionManager/fileOptionManager.php';
	
	/**************************************************************************
	 *
	 * 说明：根据类型分发
	 * 作者：李长明
	 * 时间：20180918
	 *
	 *************************************************************************/
	 
	 class projectDispatchMenu
	 {
		private static $instance;
		
		protected $opHandler = null;
		
		
		
		protected $result = array("state" => "error", "data" => "调用有误!");
		
		protected function __construct() {}
		
		protected function __clone() {}
		
		public static function getSingleton() 
		{
			if (!self::$instance) {
				self::$instance = new self();
			}
			
			return self::$instance;
		}
		
		public function parse(array $params, $em) 
		{
			
			if (!array_key_exists('curOptionMenu', $params)) {
				return false;
			}
			
			
			$curOptionMenu = $params['curOptionMenu'];
			
			$this->opHandler = null;
			
			if ($curOptionMenu == 'projectInfoManager') {
				$this->opHandler = new ProjectInfoManager($em);
			} else if ($curOptionMenu == 'pushProjectManager') {
				$this->opHandler = new PushProjectManager($em);
			}
			
			if ($this->opHandler) {
				$this->opHandler->parse($params);
			}
			
		}


		public function response()
		{
			if ($this->opHandler) {
				$this->result = $this->opHandler->getResult();
			}
			
			echo json_encode($this->result, JSON_UNESCAPED_UNICODE);
		}
		
	}

	projectDispatchMenu::getSingleton()->parse($_POST,$entityManager);
	projectDispatchMenu::getSingleton()->response();
?>