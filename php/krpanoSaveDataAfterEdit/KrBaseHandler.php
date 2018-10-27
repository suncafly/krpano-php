<?php

/**************************************************************************
 *
 * 说明：全景数据基类
 * 作者：李长明
 * 时间：20181022
 *
 *************************************************************************/

 
//	require_once "krpanoConfigConstant.php";
//	require_once '../fileOptionManager/fileOptionManager.php';
//	require_once 'saveProjectDataOptionManager.php';
	
	
	abstract class KrpanoSaveBaseOpHandler
	{
		const STATE_ERROR = 'error';
		const STATE_SUCCESS = 'success';
		
		protected $em = null;

		protected $baseUrl = '../../';

		protected $projectXml = '';
		
		protected $result = array("state" => "error", "data" => "调用有误!");
		
		
		public function __construct($em,$projectXml)
		{
			$this->em = $em;
			$this->projectXml = $projectXml;
		}
		
		abstract public function checkParam($params);
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
		
		/**
		 * 说明：保存xml文件
		 */
		protected function saveXml($file,$document) {
				
			if(!$document) {
				return false;
			}
			
			if(file_exists($file)) {
				$ret = $document->save($file);
				return $ret;
			}
			else {
				return false;
			}

			
		}
		
		/**
		 * 说明：加载xml文件
		 */
		protected function LoadXml($file) {
			
			if(file_exists($file)) {
				$dom = new DOMDocument();
				$dom->load($file);
				return $dom;
			}
			else {
				return false;
			}
		}
	}
