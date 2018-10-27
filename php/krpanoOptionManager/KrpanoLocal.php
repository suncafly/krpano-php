<?php
	

	/**
	* 本地相关操作封装
	*/
	class KrpanoLocal  extends KrOperation
	{
		/*
	     * origin 原地址
	     * dest 目标地址
	     * @return file
		 */
		public  function downloadFile($obj , $file){
			if (empty($obj)||empty($file)) {
				return null;
			}
			
			if(FileUtil::copyFile($obj,$file,true)){
				return $file;
			}
			return null;
		}
		/*
		*  上传文件到本地
		*	$local_file 本地文件
		*	$origin_file 远程的文件
		*/
		public function uploadFile($local_file , $origin_file){
			
			if (empty($local_file)) {
				return false;
			}
			if(FileUtil::moveFile($local_file,$origin_file)){
				return true;
			}
			return false;
		}
	}
?>