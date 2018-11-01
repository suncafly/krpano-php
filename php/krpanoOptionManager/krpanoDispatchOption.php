<?php
/**
 * 全景krpano操作路由
 * 2018年9月26号
 * 李长明
 */
 
	require_once "../doctrine2/bootstrap.php";
	require_once "krpanoBaseOpHandler.php";
	require_once "krpanoEditManager.php";
	
	/**************************************************************************
	 *
	 * 说明：全景krpano操作路由
	 * 作者：李长明
	 * 时间：20180926
	 *
	 *************************************************************************/
	 
	 class krpanoDispatchOption
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
			
			$this->opHandler = null;
			
			$this->opHandler = new krpanoEditManager($em);
			
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

	krpanoDispatchOption::getSingleton()->parse($_POST,$entityManager);
	krpanoDispatchOption::getSingleton()->response();
?>