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
		public static function slice($imgs,$projectId,$projectLayerId,$bAdd=false){
			
			//生成临时目录路径
			$temp_dir = KRTEMP."/".date('Ymd',Common::gmtime()).Common::get_rand_number()."/";
			
			//创建临时目录 
			Common::make_dir($temp_dir);
			
			//创建处理逻辑的继承类
			$krpano = null;
			
			$krpano = new KrpanoLocal();
			
			
			if($krpano == null) {
				return null;
			}
			else {

				$origin_dir = KRODRION.'/'.$projectLayerId.'/'.$projectId.'/';
				
				return $krpano->slicing($imgs,$temp_dir,$origin_dir,$projectId,$projectLayerId,$bAdd);
			}
			
		}
		
		
		//真正的切割函数,设为私有保证不被外部访问,做好封装
		private function slicing($imgs,$temp_dir,$origin_dir,$projectId,$projectLayerId,$bAdd=false){
	
			$imgsmain = array();
			
			
			//循环所有资源 移动到临时目录下
			foreach ($imgs as $img) {
				
				//取出文件名
				$obj = $img['imgPath'];
				$rpos = strrpos($obj,"/");
				
				$temp_name = substr($obj, $rpos==0?$rpos:$rpos+1);
				
				$info = getimagesize($obj);
				
				//全景图片必须满足2:1
				if(($info['0']/$info['1']==2)&&( (strpos("image/jpeg",$info['mime'])===0)||(strpos("image/tif", $info['mime'])===0))){
					
					//将资源copy到临时目录下面
					$file = $this->downloadFile($obj,$temp_dir.$temp_name);
					
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

			}
			
			$scenes = array();
			//移动完成之后开始切图
			if ($temp_dir!="") 
			{
				//执行切图
				exec(KRPANO_MULTI . " " . $temp_dir . "*.jpg", $log, $status);

				if ($status == 0) {
					if(!$bAdd)
					{
						//上传切好图的整个目录到服务器
						$dir = $temp_dir . "vtour/";
						$this->upload($dir, $origin_dir);
					}
					else
					{
						//仅仅是上传切的场景图
						$dir = $temp_dir . "vtour/panos/";
						$this->upload($dir, $origin_dir.'panos/');
						//读取xml获取场景信息
						$scenes = $this->getSceneInfo($temp_dir ."vtour/tour.xml");
					}

				} else {
					$result = array('ret' => 'error', 'info' => $log);
					return $result;
				}

				//删除文件夹
				FileUtil::unlinkDir($temp_dir);


			}

			$projectPath = 'data/krpano'.'/'.$projectLayerId.'/'.$projectId.'/'.'tour.html';
			$result = array('ret'=>'success','info'=>$projectPath,'scenes'=>$scenes);
			
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
			//preg_match_all("/<".$x.">.*<\/".$x.">/", $con, $temp);
			//"/\<humans\>(.*?)\<\/humans\>/s",
			//$rets = array();
			preg_match_all("/\<scene(.*?)\<\/scene\>/s", $con, $temp);
			$rets = $temp[0];
			//$arr[] = $temp[0];
			/*$dom = new DOMDocument();
			$dom->load($radarXmlFile);
			$rets = array();
			if($dom)
			{
				$scenes = $dom->getElementsByTagName("scene");
				foreach ($scenes as $scene) 
				{
					$name = $scene->getAttribute("title");
					$level = $scene->getElementsByTagName("level")->item(0);
					$size = 512;
					if($level)
					{
						$size = $level->getAttribute("tiledimagewidth");
					}

					$ret = array();
					$ret['name'] = $name;
					$ret['size'] = $size;
					$rets[] = $ret;
				}
				//$dom->save($radarXmlFile);
			}*/
			return $rets;
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