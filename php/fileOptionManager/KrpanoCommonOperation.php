<?php
	
	require_once('KrpanoCommon.php');
	require_once('KrpanoLocal.php');
	abstract class KrOperation{
	
	
		abstract protected function downloadFile($origin_url , $dest_file);
		abstract protected function uploadFile($local_file , $origin_file);
		
		/*
		 * $imgs 图片在存储服务器的路径
		 * $projectId 项目Id
		 * $origin_dir 	切图完成后，存储图片的目录
		*/
		public static function slice($fileNamePath,$dst_panos_dir){
			
			//生成临时目录路径
			$temp_dir = KRTEMP."/".date('Ymd',Common::gmtime()).Common::get_rand_number()."/";
			
			//创建临时目录 
			Common::make_dir($temp_dir);
					
			//创建处理逻辑的继承类
			$krpano = null;
			
			$krpano = new KrpanoLocal();
			
			if($krpano == null) {
				return array('ret'=>'error','info'=>"创建krpano对象失败");;
			}
			else {

				return $krpano->slicing($fileNamePath,$temp_dir,$dst_panos_dir);
			}
			
		}
		
		
		//真正的切割函数,设为私有保证不被外部访问,做好封装
		private function slicing($fileNamePath,$temp_dir,$dst_panos_dir){
	
			$imgsmain = array();
				
			//取出文件名
			$rpos = strrpos($fileNamePath,"/");
			
			$temp_name = substr($fileNamePath, $rpos==0?$rpos:$rpos+1);
			
			$info = getimagesize($fileNamePath);
			
			//全景图片必须满足2:1
			if(($info['0']/$info['1']==2)&&( (strpos("image/jpeg",$info['mime'])===0)||(strpos("image/tif", $info['mime'])===0))){
				
				//将资源copy到临时目录下面
				$file = $this->downloadFile($fileNamePath,$temp_dir.$temp_name);
				
				//判断是否copy成功
				if($file == null){
					
					$result = array('ret'=>'error','info'=>"移动文件失败");
					return $result;
				}
			}
			else {
				
				$result = array('ret'=>'error','info'=>"需要发布的资源中有不满足2:1的图片");
				return $result;
			}

			
			$scenes = array();
			$panosPath = "";

			//移动完成之后开始切图
			if ($temp_dir!="") 
			{
				//执行切图
				exec(KRPANO_MULTI . " " . $temp_dir . "*.jpeg", $log, $status);
				
				//切图成功
				if ($status == 0) {
						
					$dir = $temp_dir . "vtour/panos/";
					$this->upload($dir, $dst_panos_dir);

					$panoFileName = FileUtil::getBaseFileName($temp_name);

					//全景资源路径
					$panosPath = $dst_panos_dir .$panoFileName.'.tiles';
					
					//读取xml获取场景信息
					$scenes = $this->getSceneInfo($temp_dir ."vtour/tour.xml");
					
					
				}
				
				//切图失败 
				else {
					$result = array('ret' => 'error', 'info' => $log);
					return $result;
				}

				//删除文件夹
				FileUtil::unlinkDir($temp_dir);


			}

			$result = array('ret'=>'success','scenes'=>$scenes,'panosPath'=>$panosPath);
			
			return  $result;
			
		
		}

		/**************************************************************************
		 *
		 * 说明：获取场景信息
		 * 作者：
		* 时间：20181011
		*/
		private function getSceneInfo($radarXmlFile)
		{
			//打开目录xml文件
			$con = file_get_contents($radarXmlFile);
			preg_match_all("/\<scene(.*?)\<\/scene\>/s", $con, $temp);
			$rets = $temp[0];
			if($rets) {
				return $rets[0];
			}
			else {
				return "";
			}
			
		}
		
		private function upload($dir,$origin_file){
			if(is_dir($dir))
			{
				if ($dh = opendir($dir)) 
				{
					while (($file = readdir($dh)) !== false)
					{
						if((is_dir($dir.$file)) && $file!="." && $file!="..")
						{	
		
							//目录
							$this->upload($dir.$file."/",$origin_file.$file."/");
						}
						else
						{
							if($file!="." && $file!="..")
							{	
								//上传文件
								$this->uploadFile($dir.$file ,$origin_file.$file);
							}
						}
					}
					closedir($dh);
				}
			}
		}
	
	}
?>