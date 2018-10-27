<?php

require_once "fileOptionManager.php";
require_once "thumpImgOption.php";

abstract class fileBaseOpHandler
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
	
	protected function uploader($dir,$files,$type)
	{
		
		if(!$dir || !$files) {
			
			$this->setErrorResult("参数错误");
			return;
		}
		
		$result = array();
		
		
		$a = strtolower(FileUtil::fileext($files['file']['name']));
		
		
		//判断文件类型 
		if(!in_array(strtolower(FileUtil::fileext($files['file']['name'])), $type))
		{ 
		    $text=implode(",",$type); 
			$this->setErrorResult($text);
		    return;
		} 
	
		//生成目标文件的文件名 
		else
		{
			if(!FileUtil::make_dir($dir)) {
				
				$this->setErrorResult("创建目录失败");
		    	return;
			}
			
		    $filename = $files['file']['name'];
			
			$filename = FileUtil::random(5);
			
			$filePath = $dir .'/' .$filename .'.'.$a;
			
			if(is_uploaded_file($files['file']['tmp_name']))  {
 					
 				if (move_uploaded_file($files['file']['tmp_name'],$filePath)) {
		       			    
		       		$this->setSuccessResult("上传成功");
				}
		       
			    else {
			    	
					$this->setErrorResult("上传失败");
					return;
			    }    
		    } 
			else {
				
				$this->setErrorResult("文件不存在");
				return;
			}
		   
		
		}
		
		return array('filePath'=>$filePath,'fileName'=>$filename,'type'=>$a);
	}

	protected function createThumb($files,$dir,$targetFileName,$targetType) {
			
		if(!$files || !$dir) {
			return false;
		}
		
		
		if(!FileUtil::createDir($dir)) {
				
			$this->setErrorResult("创建目录失败");
	    	return false;
		}
		
		$a = strtolower(FileUtil::fileext($files['file']['name']));
		
				
		$source = $dir .'/'. $targetFileName . '.' .$targetType;

		$dst_img_dir = $dir . "/thumb";
		
		$dst_img = $dir . "/thumb" .'/'. $targetFileName . '.' .$targetType;

		//创建目录
		if(!FileUtil::make_dir($dst_img_dir)) {
			return false;
		}
		
		$percent = 0.5;
		
		
		$flag = (new imgcompress($source,$percent))->compressImg($dst_img);
		
		if($flag) {
			return  $dst_img;
		}
		
		return false;
	}
}
