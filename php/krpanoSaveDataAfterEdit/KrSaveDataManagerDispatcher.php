<?php
    // ResourceManagerDispatcher.php
	require_once "../doctrine2/bootstrap.php";
	require_once 'SaveTypeManager.php';
	
	/**************************************************************************
	 *
	 * 说明：保存全景数据路由类
	 * 作者：李长明
	 * 时间：20180917
	 *
	 *************************************************************************/
	 
	 class KrSaveDataManagerDispatcher
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
			//获取数据
			if (!array_key_exists('saveData', $params)) {
				return false;
			}
			$data = $params['saveData'];

			if (!array_key_exists('projectXml', $data)) {
				return false;
			}
			
			if (!array_key_exists('curOptionType', $data)) {
				return false;
			}
			
			if (!array_key_exists('curOptionType', $data)) {
				return false;
			}
			
			$projectXml = $data['projectXml'];
			
			$curOptionType = $data['curOptionType'];
			
			$opCode = $data['opCode'];
			
			
			$handler = saveTypeManager::getSingleton()->getMessageHandler($curOptionType);
			if (!$handler) {
				return false;
			}
			
			$this->opHandler = null;
	
			$this->opHandler = new $handler($em,$projectXml);
			if (!$this->opHandler) {
				return false;
			}
			
			//不同数据结构校验不同参数
			if($this->opHandler) {
					
				$ret = $this->opHandler->checkParam($data);
				//根据当前操作方式判断所调用的函数
				if($ret) {
					
					$this->opHandler->parse($data);
				}
				else {
					return false;
				}
				
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


	KrSaveDataManagerDispatcher::getSingleton()->parse($_POST,$entityManager);
	KrSaveDataManagerDispatcher::getSingleton()->response();