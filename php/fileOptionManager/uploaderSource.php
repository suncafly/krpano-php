<?php
/**
 * 通用的上传资源类
 * 2018年9月18号
 * 李长明
 */
 
	require_once "../doctrine2/bootstrap.php";
	require_once "fileBaseOpHandler.php";
	require_once "BasicMaterialResourceUploader.php";
	require_once "PanoImgResourceUploader.php";
	require_once "VideoResourceUploader.php";
	require_once "VoiceResourceUploader.php";
	
	/**************************************************************************
	 *
	 * 说明：根据类型分发
	 * 作者：李长明
	 * 时间：20180918
	 *
	 *************************************************************************/
	 
	 class uploaderSourceDispatcher
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
			$post = $params['post'];
			
			if (!array_key_exists('curEntityClass', $post)) {
				return false;
			}
			
			
			$entityClass = $post['curEntityClass'];
			
			$this->opHandler = null;
			
			if ($entityClass == 'PanoImgLayer') {
				$this->opHandler = new PanoImgResourceUploader($em);
			} else if ($entityClass == 'BasicMaterialLayer') {
				$this->opHandler = new BasicMaterialResourceUploader($em);
			} else if ($entityClass === 'VoiceLayer') {
				$this->opHandler = new VoiceResourceUploader($em);
			} else if ($entityClass === 'VideoLayer') {
				$this->opHandler = new VideoResourceUploader($em);
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

	//将文件参数与post参数合并传递给具体操作类
	
	$parmes = array('post'=>$_POST,'files'=>$_FILES);
	
	uploaderSourceDispatcher::getSingleton()->parse($parmes,$entityManager);
	uploaderSourceDispatcher::getSingleton()->response();
?>