<?php
	date_default_timezone_set('PRC');
	require_once "../doctrine2/bootstrap.php";


	
	/**************************************************************************
	 *
	 * 说明：抽象类型处理类
	 *
	 *************************************************************************/
	abstract class ResourceHandler
	{
		const STATE_ERROR = 'error';
		const STATE_SUCCESS = 'success';
		
		protected $em = null;
		protected $userName = '';
		protected $result = array("state" => "error", "data" => "调用有误!");
		
		public function __construct($em)
		{
			$this->em = $em;
//			$this->userName = $userName;
		}
		
		
		
		abstract public function parse($params);
		
		/**
		 * 说明：检测属性是否在对应数组或对象中
		 */
		protected function checkProps($props, $o) 
		{
			if (is_object($o)) {
				for ($i = 0, $len = count($props); $i < $len; $i++) {
					if (!array_key_exists($props[$i], $o)) {
						return false;
					}
				}
			} elseif (is_array($o)) {
				if (count($o) == 0) {
					return false;
				}
					
				$subElementsArrayCount = 0;
				foreach($o as $key => $val ) {
					if (is_array($val)) {
						++$subElementsArrayCount;
					}
				}
					
				if (count($o) == $subElementsArrayCount) {
					for ($j = 0,$oLen = count($o); $j < $oLen; ++$j) {
						for ($i = 0, $len = count($props); $i < $len; ++$i) {
							if (!array_key_exists($props[$i], $o[$j])) {
								return false;
							}
						}
					}
				} else {
					for ($i = 0, $len = count( $props ); $i < $len; $i++) {
						if (!array_key_exists($props[$i], $o)) {
							return false;
						}
					}
				}
			} else {
				return false;
			}
				
			return true;
		}
		
		public function getResult()
		{
			return $this->result;
		}
		
		protected function setResult($state, $data)
		{
			if ($state !== self::STATE_ERROR && $state !== self::STATE_SUCCESS) {
				return;
			}
			
			$this->result = array('state' => $state, 'msg' => $data);
		}
		
		/**
		 * 说明：设置响应结果  如果设置了 other这采用其他的方式
		 */
		protected function setSuccessResult($data) 
		{
			
			$this->setResult(self::STATE_SUCCESS, $data);
			
		}
		
		protected function setErrorResult($data) 
		{
			$this->setResult(self::STATE_ERROR, $data);
		}
		
				
		/**
		 * 说明：输出响应结果
		 *
		 * @param 
		 *        void
		 * @return
		 *        Object
		 */
		public function outputResponse() 
		{
			echo json_encode($this->result, JSON_UNESCAPED_UNICODE);
		}
		
		
		
	
	}