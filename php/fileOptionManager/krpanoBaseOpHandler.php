<?php
/**
 * 全景krpano操作基类
 * 2018年9月26号
 * 李长明
 */
 
 	define('IN_T',true);
 
	require_once "krpanoConfigConstant.php";
	require_once "krpanoRegister.php";	
	require_once "KrpanoCommonOperation.php";
	require_once '../fileOptionManager/fileOptionManager.php';
	require_once 'saveProjectDataOptionManager.php';
	
	
	abstract class krpanoBaseOpHandler
	{
		const STATE_ERROR = 'error';
		const STATE_SUCCESS = 'success';
		
		protected $em = null;
		
		protected $result = array("state" => "error", "data" => "调用有误!");
		
		
			
		
		
		public function __construct($em)
		{
			$this->em = $em;
		}
		
		abstract public function parse($params);
		
		public function getResult()
		{
			return $this->result;
		}
		
		protected function hasPrivilege($userName) 
		{
			return true;
		}
		
		protected function setResult($state, $data)
		{
			if ($state !== self::STATE_ERROR && $state !== self::STATE_SUCCESS) {
				return;
			}
				
			$this->result = array('state' => $state, 'status' => $state, 'msg' => $data);
		}
			
		/**
		 * 说明：设置响应结果
		 */
		protected function setSuccessResult($data) 
		{
			$this->setResult(self::STATE_SUCCESS, $data);
		}
			
		protected function setErrorResult($data) 
		{
			$this->setResult(self::STATE_ERROR, $data);
		}
		
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
	}
