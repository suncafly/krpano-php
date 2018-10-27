<?php
/**
 * krpano 保存xml
 * 2018年10月12号
 * 李长明
 */

	class saveProjectDataOptionManager
	{
		
		
		private static $instance;
		
		private $projectXmlFile;
		
		private $tourDom;

		private $radarDom;
		
		private $workDir;
		
		private $baseUrl = '../../';

		private function __construct() {
			
		}
		
		public static function getSingleton() {
			if ( !self::$instance ) {
				self::$instance = new self();
			}
			
			return self::$instance;
		}

		function setWorkDir($workDir)
		{
			$this->workDir = $workDir;
		}
		
		function setProjectXml($projectFile) {

			$this->projectXmlFile = $projectFile;
		}
		
		protected function saveXml($tourXmlFile) {

			$ret = $this->tourDom->save($tourXmlFile);
			return $ret;
		}
		
		protected function LoadXml($projectXmlFile) {
				
			if(file_exists($projectXmlFile)) {
				$dom = new DOMDocument();
				$dom->load($projectXmlFile);
				return $dom;
			}

		}
		
		//=======================================================================================
		
		/** 
		 * saveBaseSettingInfo  
		 * 保存场景基础数据 
		 * 
		 * @access public 
		 * @param 
		 * @param 
		 * @param 
		 * @since 1.0 
		 * @return  
		*/ 
		
		function saveBaseSettingInfo($autorotate,$sceneItem) {
					
			//自动旋转
		    if ($autorotate) {
		        $enabled = $autorotate->enabled;
		        $v1 = $sceneItem->getElementsByTagName("autorotate");
		        $v2 = $v1->item(0);
		        if ($enabled) {
		            $v2->setAttribute("enabled", "true");
		        } else {
		            $v2->setAttribute("enabled", "false");
		        }
		    }
		}
		
		/** 
		 * saveHotspotInfo  
		 * 保存场景的热点数据 
		 * 
		 * @access public 
		 * @param 
		 * @param 
		 * @param 
		 * @since 1.0 
		 * @return  
		*/ 
		
		function saveHotspotInfo($hotSpots,$sceneItem) {
						
		    if ($hotSpots != null) {
		    	
		        foreach ($hotSpots as $key => $value) {
		        	
		            $name = $value->name;
					
		            $node =$this->tourDom->createElement("hotspot");
					$node->setAttribute("name", $name);
					$node->setAttribute("ath", $value->ath);
		            $node->setAttribute("atv", $value->atv);
		            $node->setAttribute("linkedscene", $value->linkedscene);
		            $node->setAttribute("style", $value->style);
		            $node->setAttribute("title", $value->title);
		            $node->setAttribute("curscenename", $value->curscenename);
					$node->setAttribute("typevalue", $value->typevalue);
					//todo 数据写死的
					$node->setAttribute("onclick", $value->onclick);
		            $sceneItem->appendChild($node);
					

		        }
		    }
		}
		
		
		

		//=======================================================================================
		
		/** 
		 * saveXmlAllData  
		 * 所有数据写入xml之后保存文件 
		 * 
		 * @access public 
		 * @param 
		 * @param 
		 * @param 
		 * @since 1.0 
		 * @return  array
		*/ 
		
		function saveXmlAllData($tourXmlFile) {
				
			//保存文件
			$ret = $this->saveXml($tourXmlFile);
			if($ret > 0) {
				return array('保存成功');
			}
			else {
				return array('保存失败');
			}
			
		}
		

		
		/**
		 * autor:lichangming 
		 * time: 20181012
		 * info: 保存内存数据
		 */
		function saveSceneInfo($saveSceneData) {
			
			
			$tourXmlFile = $this->workDir .'/tour.xml';
			
			if(isset($saveSceneData['sceneData'])) {

				$sceneData = $saveSceneData['sceneData'];
				if($sceneData)
				{
					$this->saveScene($tourXmlFile,$sceneData);
				}
			}

			//打开目录xml文件
			$this->tourDom = $this->LoadXml($tourXmlFile);
			
			if(!$this->tourDom) {
				return array();
			}
			
			//将数据转换为数组
			$sceneListHost = $saveSceneData['sceneListHost'];
			$data = json_decode($sceneListHost);
			
			
			//获取雷达数据
			if(isset($saveSceneData['radarData'])) {

				$radarData = $saveSceneData['radarData'];
				//保存雷达数据
				if($radarData) {

					$this->saveRadarInfo($radarData);
				}
			}

			
			//获取分组数据
			if(isset($saveSceneData['groupData']))
			{
				$groupData = $saveSceneData['groupData'];
				//保存分组数据
				if($groupData)
				{
					$this->saveGroupInfo($groupData);
				}
			}
			
			
			
			//获取当前xml里面的场景节点
			$sceneList = $this->tourDom->getElementsByTagName("scene");
			
			//循环遍历需要保存的数据
			foreach ($data as $key => $value) {
				
				//基础参数	
   				$sceneIndex = $value->index;
    			$welcomeFlag = property_exists($value, "welcomeFlag") ? $value->welcomeFlag : null;
   				$sceneName = $value->name;
    			$autorotate = property_exists($value, "autorotate") ? $value->autorotate : null;
    			$hotSpots = property_exists($value, "hotSpots") ? $value->hotSpots : null;
    			$fov = property_exists($value, "fov") ? $value->fov : null;
				
    			$sceneItem = $sceneList->item($sceneIndex);

    			if (!is_int($sceneIndex)) {
        			continue;
   				}
				
				//保存基础参数
				$this->saveBaseSettingInfo($autorotate,$sceneItem);
				
				//保存热点数据
				$this->saveHotspotInfo($hotSpots,$sceneItem);
				
			}
	
			return $this->saveXmlAllData($tourXmlFile);

		}
		
	/**************************************************************************
	 *
	 * 说明：保存雷达数据
	 * 作者：
	* 时间：20181011
	*/
	 function saveRadarInfo($radarData) {
	 		
		$radarXmlFile = $this->workDir .'/skin/radar.xml';
			
		//打开目录xml文件
		$this->radarDom = $this->LoadXml($radarXmlFile);

		 if(!$this->tourDom) {
			 return array();
		 }
	 	
		$maplayer = NULL;
		
		$layers = $this->radarDom->getElementsByTagName("layer");
		foreach ($layers as $layer) 
		{
			$lname = $layer->getAttribute("name");
			if ($lname == "map") 
			{
				$maplayer = $layer;
				break;
			}
		}
		
	
		if(isset($radarData['radarMap'] ))
		{
			
			$radarMap = $radarData['radarMap'];
			
			$ext = FileUtil::fileext($radarMap['path']);
			
			$imgPath = 'img/map/map.'.$ext;
			
			$curImgPath = $this->workDir. '/skin/img/map';
			
			$fileUrl = $this->baseUrl. $radarMap['path'];
			
			$aimUrl=	$this->workDir. '/skin/'. $imgPath;
			
			//删除目录
			FileUtil::unlinkDir($curImgPath);
			
			//文件拷贝
			FileUtil::copyFile($fileUrl,$aimUrl,true);
			
			$maplayer->setAttribute("url",$imgPath);
			$maplayer->setAttribute("width",$radarMap['width']);
			$maplayer->setAttribute("height",$radarMap['height']);
		}


		if(isset($radarData['radarList']))
		{
			$radarList = $radarData['radarList'];
			foreach ($radarList as $radar) 
			{
				if(1==$radar['add'])
				{
					//判断雷达点是否存在
					$bExit = false;
					foreach ($layers as $layer) 
					{
						$lname = $layer->getAttribute("name");
						if ($lname == $radar['name']) 
						{
							$layer->setAttribute("x", $radar['x']);
							$layer->setAttribute("y", $radar['y']);
							$bExit = true;
							break;
						}
					}

					if(!$bExit)
					{
						$node = $this->radarDom->createElement("layer");
						$node->setAttribute("name", $radar['name']);
						$node->setAttribute("x", $radar['x']);
						$node->setAttribute("y", $radar['y']);
						$node->setAttribute("zorder", "1");
						$node->setAttribute("style", "mapspot");
						$node->setAttribute("scene", $radar['sceneName']);
						$node->setAttribute("rot", $radar['rot']);
						$node->setAttribute("onhover", "showtext(".$radar['text'].", STYLE3);"); 
						$node->setAttribute("onclick", "mapspot_loadscene(".$radar['sceneName'].");");
						$maplayer->appendChild($node);
					}
				}
				else
				{
					foreach ($layers as $layer) 
					{
						$lname = $layer->getAttribute("name");
						if ($lname == $radar['name']) 
						{
							if(0==$radar['add'])
							{
								$layer->setAttribute("x", $radar['x']);
								$layer->setAttribute("y", $radar['y']);
							}
							else if(-1==$radar['add'])
							{
								$maplayer->removeChild($layer);
							}
						}
					}
				}
			}
		}

		$ret = $this->radarDom->save($radarXmlFile);
		return $ret;

	  }

	

	/**************************************************************************
	 *
	 * 说明：保存场景信息
	 * 作者：
	* 时间：20181016
	*/
	function saveScene($xmlFile,$sceneData)
	{
		//获取根节点
		$con = file_get_contents($xmlFile);
		$con = str_replace('</krpano>', '', $con);// es
		foreach($sceneData as $scene)
		{
			$con=$con.$scene;
		}
		$con = $con.'</krpano>';
		file_put_contents($xmlFile, $con);
		/*$krpanos = $this->tourDom->getElementsByTagName("krpano");
		//
		foreach($sceneData as $scene)
		{
			$nodeScene = $this->tourDom->createElement("scene");
			$nodeScene->setAttribute("name", 'scene_'.$scene['name']);
			$nodeScene->setAttribute("title", $scene['name']);
			$nodeScene->setAttribute("onstart", '');
			$nodeScene->setAttribute("thumburl", 'panos/'.$scene['name'].'.tiles/thumb.jpg');
			$nodeScene->setAttribute("lat", '');
			$nodeScene->setAttribute("lng", '');
			$nodeScene->setAttribute("heading", '');

			$nodeView = $this->tourDom->createElement("view");
			$nodeView->setAttribute("hlookat", 0.0);
			$nodeView->setAttribute("vlookat", 0.0);
			$nodeView->setAttribute("fovtype", 'MFOV');
			$nodeView->setAttribute("fov", 120);
			$nodeView->setAttribute("maxpixelzoom", 2.0);
			$nodeView->setAttribute("fovmin", 70);
			$nodeView->setAttribute("fovmax", 140);
			$nodeView->setAttribute("limitview", 'auto');
			$nodeScene->appendChild($nodeView);

			$nodePreview= $this->tourDom->createElement("preview");
			$nodePreview->setAttribute("url", 'panos/'.$scene['name'].'.tiles/preview.jpg');
			$nodeScene->appendChild($nodePreview);

			$nodeImage= $this->tourDom->createElement("image");
			$nodeImage->setAttribute("type", 'CUBE');
			$nodeImage->setAttribute("multires", 'true');
			$nodeImage->setAttribute("tilesize", '512');

			$nodeLevel= $this->tourDom->createElement("level");
			$nodeLevel->setAttribute("tiledimagewidth",$scene['size'] );
			$nodeLevel->setAttribute("tiledimageheight",$scene['size'] );

			$nodeCube= $this->tourDom->createElement("cube");
			$nodeCube->setAttribute("url",'panos/'.$scene['name'].'.tiles/%s/l1/%v/l1_%s_%v_%h.jpg');
			$nodeLevel->appendChild($nodeCube);
			$nodeImage->appendChild($nodeLevel);
			$nodeScene->appendChild($nodeImage);
			$krpanos->item(0)->appendChild($nodeScene);
		}*/
	}

	//保存分组数据
	function saveGroupInfo($saveGroupData) {
		//$tourXmlFile = $this->workDir .'/tour.xml';		
		//获取当前xml里面的场景节点
		$config = $this->tourDom->getElementsByTagName("config");
		if($config->length>0)
		{
			$config = $config->item(0);
			//存在场景分组
			$thumbs = $config->getElementsByTagName("thumbs");
			if($thumbs->length>0)
			{
				$config->removeChild($thumbs->item(0));
			}
		}
		else
		{
			//不存在场景分组
			$config = $this->tourDom->createElement("config");
			$config->setAttribute("thumb",'123');
			$this->tourDom->getElementsByTagName("krpano")->item(0)->appendChild($config);
		}
		$thumbs = $this->tourDom->createElement("thumbs");
		$thumbs->setAttribute("title",'全景列表');
		$thumbs->setAttribute("show_thumb",1);
		$config->appendChild($thumbs);
		//添加新分组
		//$saveGroupData group[]
		$count = 0;
		foreach ($saveGroupData as $group) 
		{
			//<category name="category0" title="146" thumb="">
			$category = $this->tourDom->createElement("category");
			$category->setAttribute("name",'category'.$count);
			$category->setAttribute("title",$group['title']);
			$category->setAttribute("thumb",'');
			if(isset($group['scenes']))
			{
				foreach($group['scenes'] as $scene)
				{
					//<pano name="scene_CR-x8qqs"   title="scene_CR-x8qqs"/>
					$pano = $this->tourDom->createElement("pano");
					$pano->setAttribute("name",$scene['name']);
					$pano->setAttribute("title",$scene['title']);
					//
					$category->appendChild($pano);
				}
			}
			$thumbs->appendChild($category);
			$count++;

		}
		
	}

}
