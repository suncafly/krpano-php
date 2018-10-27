<?php
    // ResourceManagerDispatcher.php
	
	require_once 'ResourceTypeManager.php';
	
	/**************************************************************************
	 *
	 * 说明：类型对应操作类分发
	 * 作者：李长明
	 * 时间：20180917
	 *
	 *************************************************************************/
	 
	 class ResourceManagerDispatcher
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
			
			if (!array_key_exists('entityClass', $params)) {
				return false;
			}
			
			$entityClass = $params['entityClass'];
			
			
			$handler = ResourceTypeManager::getSingleton()->getMessageHandlerByEntityClass($entityClass);
			if (!$handler) {
				return false;
			}
			
			$this->opHandler = null;
	
			$this->opHandler = new $handler($em);
			if (!$this->opHandler) {
				return false;
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


	ResourceManagerDispatcher::getSingleton()->parse($_POST,$entityManager);
	ResourceManagerDispatcher::getSingleton()->response();