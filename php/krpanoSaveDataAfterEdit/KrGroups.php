<?php
/**************************************************************************
 *
 * 说明：全景雷达数据
 * 作者：李长明
 * 时间：20181022
 *
 *************************************************************************/

class KrGroups extends KrpanoSaveBaseOpHandler
{
	private $groupDom = null;
	

	public function __construct($em,$projectXml)
	{
		parent::__construct($em,$projectXml);
	}
	
	public function parse($params)
	{
		if (!$this->checkProps(['opCode'], $params)) {
			return false;
		}
		
		//这里是权限控制，如果没有选择则不拉取  暂时没有加权限所以暂时没有用到
		//if (!$this->hasPrivilege($this->userName)) {
		//		return false;
		//}
		
			
		//根据操作
		$opCode = $params['opCode'];
		
		if (method_exists($this, $opCode)) {
			if (call_user_func_array(array($this, $opCode), array($params))) {
				return true;
			}
		}
		
		return true;
	}
	
	public function checkParam($params)
	{
		if (!$this->checkProps(['projectXml','groupData'], $params)) {
			return false;
		}
		
		return true;
	}
	
	//加载雷达数据
	protected function loadData($params) {
			
		if (!$this->checkProps(['',''], $params)) {
			return array();
		}
		
		
		$this->setSuccessResult($retArray);
					
		return true;
	}
	
	//保存雷达数据
	protected function save($params) {
		//传下来$params['groupData']
		$this->projectXml = $params['projectXml'];
		$changeGroupData = $params['groupData'];
		$curOptionType =$params['curOptionType'];
		//打开xml文件
		$tourXmlFile = $this->baseUrl.$this->projectXml.'/tour.xml';
		$this->groupDom = parent::LoadXml($tourXmlFile);
		foreach($changeGroupData as $changeData){
			if ($this->checkProps(['opCode'], $changeData)) {
				$opCode = $changeData['opCode'];
				$data = $changeData['data'];
				if (method_exists($this, $opCode)) {
					if (call_user_func_array(array($this, $opCode), array($data))) {
						//return true;
					}
				}
			}
		}
		$ret = parent::saveXml($tourXmlFile,$this->groupDom);
		if($ret){
			$this->setSuccessResult($curOptionType);
		}
		else
		{
			$this->setErrorResult($curOptionType);
		}
		return $ret;
		//*****//
		/*$this->workDir = $this->baseUrl.$params['projectXml'];
		$this->tourXmlFile =$this->workDir .'/tour.xml';
		$ret = false;

		if($this->checkProps(['addScene'], $params)){
			$ret =	$this->saveAddScene($params['addScene']);
		}
		
		if($this->checkProps(['groupData'], $params)){
			$ret =	$this->saveGroupInfo($params['groupData']);
		}

		if(!$ret) {
			$this->setErrorResult("保存失败");
		}
		else {
			$this->setSuccessResult("保存成功");
		}*/	
	}

	//获取krpano结点
	function getkrpano(){
		if(null!=$this->groupDom){
			$krpano = $this->groupDom->getElementsByTagName("krpano");
			if($krpano->length>0){
				return $krpano->item(0);
			}
		}
		return null;
	}

	//获取场景结点
	function getScene($sceneName){
		if(null!=$this->groupDom){
			$scenes = $this->groupDom->getElementsByTagName("scene");
			if($scenes->length>0){
				foreach($scenes as $k => $scene){
					$curSceneName = $scene->getAttribute("name");
					if($sceneName==$curSceneName){
						return $scene;
					}
				}
			}
		}
		return null;
	}

	//获取config结点
	function getConfig(){
		if(null!=$this->groupDom){
			$config = $this->groupDom->getElementsByTagName("config");
			if($config->length>0){
				return $config->item(0);
			}
		}
		return null;
	}

	//获取thumbs结点
	function getThumbs(){
		$config = $this->getConfig();
		if(null!=$config){
			$thumbs = $config->getElementsByTagName("thumbs");
			if($thumbs->length>0){
				return $thumbs->item(0);
			}
		}
		return null;
	}

	//获取category结点
	function getCategory($groupTitle){
		$thumbs = $this->getThumbs();
		if(null!=$thumbs){
			$categorys = $thumbs->getElementsByTagName("category");
			if($categorys->length>0){
				foreach($categorys as $k => $category){
					$curTitle = $category->getAttribute("title");
					if($groupTitle==$curTitle){
						return $category;
					}
				}
			}
		}
		return null;
	}

	//获取雷达结点
	function getRadar($groupTitle){
		$category = $this->getCategory($groupTitle);
		if(null!=$category){
			$radars = $category->getElementsByTagName("radar");
			if($radars->length>0){
				return $radars->item(0);
			}
		}
		return null;
	}

	//获取雷达点结点
	function getPoint($groupTitle,$pointName){
		$radar = $this->getRadar($groupTitle);
		if(null!=$radar){
			$points = $radar->getElementsByTagName("point");
			if($points->length>0){
				foreach($points as $k => $point){
					$curPointName = $point->getAttribute("name");
					if($pointName==$curPointName){
						return $point;
					}
				}
			}
		}
		return null;
	}

	//获取场景结点
	function getPano($groupTitle,$panoName){
		$category=$this->getCategory($groupTitle);
		if(null!=$category){
			$panos = $category->getElementsByTagName("pano");
			if($panos->length>0){
				foreach($panos as $k => $pano){
					$curPanoName = $pano->getAttribute("name");
					if($panoName==$curPanoName){
						return $pano;
					}
				}
			}
		}
		return null;
	}

	//创建Thumbs结点
	function createThumbs(){
		if(null!=$this->groupDom){
			$thumbs = $this->groupDom->createElement("thumbs");
			$thumbs->setAttribute("title",'全景列表');
			$thumbs->setAttribute("show_thumb",1);
			return $thumbs;
		}
		return null;
	}

	//创建category结点
	function createCategory($group){
		if(null!=$this->groupDom){
			$category = $this->groupDom->createElement("category");
			$category->setAttribute("name",'category'.$group['code']);
			$category->setAttribute("title",$group['title']);
			$category->setAttribute("code",$group['code']);
			$category->setAttribute("thumb",'');
			$radar = $this->groupDom->createElement("radar");
			$radar->setAttribute("url",'');
			$radar->setAttribute("width",0);
			$radar->setAttribute("height",0);
			$category->appendChild($radar);
			return $category;
		}
		return null;
	}

	//复制城建category结点
	function createCopyCategory($groupTitle){
		$category = $this->getCategory($groupTitle);
		if($category){
			$group = array();
			$group['title'] = $category->setAttribute("title");
			$group['code'] = $category->getAttribute("code");
			$newCategory = $this->createCategory($group);
			if(null!=$newCategory){
				$radar = $this->getRadar($groupTitle);
				if(null!=$radar){
					$newCategory->appendChild($radar);
				}
				return $newCategory;
			}
		}
		return null;
	}

	//创建pano结点
	function createPano($scene){
		if(null!=$this->groupDom){
			$pano = $this->groupDom->createElement("pano");
			$pano->setAttribute("name",$scene['name']);
			$pano->setAttribute("title",$scene['title']);
			$pano->setAttribute("thumburl",$scene['thumburl']);
			$pano->setAttribute("index",$scene['index']);
			return $pano;
		}
		return null;
	}

	//创建point结点
	function createPoint($point){
		if(null!=$this->groupDom){
			$pointNode = $this->groupDom->createElement("point");
			$pointNode->setAttribute("name",$point['name']);
			$pointNode->setAttribute("text",$point['text']);
			$pointNode->setAttribute("scene",$point['scene']);
			$pointNode->setAttribute("x",$point['x']);
			$pointNode->setAttribute("y",$point['y']);
			$pointNode->setAttribute("rot",$point['rot']);
			return $pointNode;
		}
		return null;
	}



	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.26
	 * 		author: 	    
	 * 		info:		分组相关操作  begin
	 * 
	 ************************************************************************************************/

	/******************************************************************************
	 * Desc: 添加分组
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	*/
	function addGroup($params){
		$group = $params;
		if(null!=$this->groupDom){
			$config = $this->getConfig();
			if(null!=$config){
				$config->setAttribute("code",$group['code']+1);
			}
			$thumbs = $this->getThumbs();
			if(null!=$thumbs){
				$category = $this->createCategory($group);
				$thumbs->appendChild($category);
			}
		}
	}

	/******************************************************************************
	 * Desc: 删除分组
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	*/
	function subGroup($params){
		$group = $params;
		$thumbs = $this->getThumbs();
		if(null!=$thumbs){
			$category = $this->getCategory($group['title']);
			if(null!=$category){
				$thumbs->removeChild($category);
			}
		}
	}

	/******************************************************************************
	 * Desc: 排序分组
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	*/
	function sortGroup($params){
		$groups = $params;
		$config = $this->getConfig();
		if(null==$config){
			return;
		}
		$thumbs = $this->getThumbs();
		if(null==$thumbs){
			return;
		}
		$newThumbs =  $this->createThumbs();
		foreach($groups as $group){
			$category = $this->getCategory($group['title']);
			if(null!=$category){
				$newThumbs->appendChild($category);
			}
		}
		$config->removeChild($thumbs);
		$config->appendChild($newThumbs);
	}

	/******************************************************************************
	 * Desc: 重命名分组
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	*/
	function renameGroup($params){
		if($this->checkProps(['oldTitle','newTitle'], $params)){
			//oldTitle, newTitle
			$category = $this->getCategory($params['oldTitle']);
			if(null!=$category){
				$category->setAttribute("title",$params['newTitle']);
			}
		}
	}

	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.26
	 * 		author: 	    
	 * 		info:		分组相关操作  end
	 * 
	 ************************************************************************************************/


	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.26
	 * 		author: 	    
	 * 		info:		文件场景相关操作  begin
	 * 
	 ************************************************************************************************/

	/******************************************************************************
	 * Desc: 添加场景数组
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	*/
	function addTourScene($params){
		$tourXmlFile = $this->baseUrl.$this->projectXml.'/tour.xml';
		//保存
		parent::saveXml($tourXmlFile,$this->groupDom);
		//打开
		$con = file_get_contents($tourXmlFile);
		$con = str_replace('</krpano>', '', $con);
		$sceneData = $params;
		foreach($sceneData as $scene)
		{
			$con=$con.$scene;
		}
		$con = $con.'</krpano>';
		//保存
		file_put_contents($tourXmlFile, $con);
		//打开xml文件
		$this->groupDom = parent::LoadXml($tourXmlFile);
	}


	/******************************************************************************
	 * Desc: 删除场景数组
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	*/
	 function subTourScene($params){
		$scenes = $params;
		$krpano = $this->getkrpano();
		if(null!=$krpano){
			foreach($scenes as $scene){
				$tourScene = $this->getScene($scene['name']);
				if(null!=$tourScene){
					$krpano->removeChild($tourScene);
				}
			}
		}
	 }
	 
	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.26
	 * 		author: 	    
	 * 		info:		文件场景相关操作  end
	 * 
	 ************************************************************************************************/

	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.26
	 * 		author: 	    
	 * 		info:		分组场景相关操作  begin
	 * 
	 ************************************************************************************************/

	
	/******************************************************************************
	 * Desc: 添加分组场景数组
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	*/
	function addGroupScene($params){
		if ($this->checkProps(['groupTitle','scenes'], $params)) {
			$groupTitle = $params['groupTitle'];
			$scenes = $params['scenes'];
			$category = $this->getCategory($groupTitle);
			if(null!=$category){
				foreach($scenes as $scene){
					$pano = $this->createPano($scene);
					if(null!=$pano){
						$category->appendChild($pano);
					}
				}
			}
		}
	}

	/******************************************************************************
	 * Desc: 删除分组场景数组
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	*/
	function subGroupScene($params){
		if ($this->checkProps(['groupTitle','scenes'], $params)) {
			$groupTitle = $params['groupTitle'];
			$scenes = $params['scenes'];
			$category = $this->getCategory($groupTitle);
			if(null!=$category){
				foreach($scenes as $scene){
					$pano = $this->getPano($groupTitle,$scene['name']);
					if(null!=$pano){
						$category->removeChild($pano);
					}
				}
			}
		}
	}

	/******************************************************************************
	 * Desc: 排序分组场景
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	*/
	function sortScene($params){
		if ($this->checkProps(['groupTitle','scenes'], $params)) {
			$groupTitle = $params['groupTitle'];
			$scenes = $params['scenes'];
			$thumbs = $this->getThumbs();
			if(null!=$thumbs){
				$category = $this->getCategory($groupTitle);
				if(null!=$category){
					foreach($scenes as $scene){
						$pano = $this->getPano($groupTitle,$scene['name']);
						$category->removeChild($pano);
						$category->appendChild($pano);
					}
				}
			}
		}
	}
	 
	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.26
	 * 		author: 	    
	 * 		info:		分组场景相关操作  end
	 * 
	 ************************************************************************************************/


	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.26
	 * 		author: 	    
	 * 		info:		雷达相关操作  begin
	 * 
	 ************************************************************************************************/
	/******************************************************************************
	 * Desc: 编辑雷达图信息
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	*/
	function editRadar($params){
		if ($this->checkProps(['groupTitle','radar'], $params)) {
			$groupTitle = $params['groupTitle'];
			$radar = $params['radar'];
			$category = $this->getCategory($groupTitle);
			if(null!=$category){
				$code = $category->getAttribute('code');
				$radarNode = $this->getRadar($groupTitle);
				if(null!=$radarNode){
					//保存图片文件
					$workDir = $this->baseUrl.$this->projectXml;
					$imgName = basename($radar['url']);
					$curImgPath=$workDir. '/img/map/'.$code;
					$fileUrl = $this->baseUrl. $radar['url'];
					$aimUrl= $workDir. '/img/map/'.$code.'/'. $imgName;
					//删除目录
					FileUtil::unlinkDir($curImgPath);
					//文件拷贝
					FileUtil::copyFile($fileUrl,$aimUrl,true);
					$radarNode->setAttribute("url",'%SWFPATH%/img/map/'.$code.'/'. $imgName);
					//$radarNode->setAttribute("url",$radar['url']);
					$radarNode->setAttribute("width",$radar['width']);
					$radarNode->setAttribute("height",$radar['height']);
				}
			}

		}
	}

	/******************************************************************************
	 * Desc: 添加雷达点信息
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	*/
	function addPoint($params){
		if ($this->checkProps(['groupTitle','points'], $params)) {
			$groupTitle = $params['groupTitle'];
			$points = $params['points'];
			$radar = $this->getRadar($groupTitle);
			if(null!=$radar){
				foreach($points as $point){
					$pointNode = $this->createPoint($point);
					if(null!=$pointNode){
						$radar->appendChild($pointNode);
					}
				}
			}
		}
	}

	/******************************************************************************
	 * Desc: 删除雷达点信息
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	*/
	function subPoint($params){
		if ($this->checkProps(['groupTitle','points'], $params)) {
			$groupTitle = $params['groupTitle'];
			$points = $params['points'];
			$radar = $this->getRadar($groupTitle);
			if(null!=$radar){
				foreach($points as $point){
					$pointNode = $this->getPoint($groupTitle,$point['name']);
					if(null!=$pointNode){
						$radar->removeChild($pointNode);
					}
				}
			}
		}
	}

	/******************************************************************************
	 * Desc: 编辑雷达点信息
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	*/
	function editPoint($params){
		if ($this->checkProps(['groupTitle','points'], $params)) {
			$groupTitle = $params['groupTitle'];
			$points = $params['points'];
			foreach($points as $point){
				$pointNode = $this->getPoint($groupTitle,$point['name']);
				if(null!=$pointNode){
					$pointNode->setAttribute("text",$point['text']);
					$pointNode->setAttribute("scene",$point['scene']);
					$pointNode->setAttribute("x",$point['x']);
					$pointNode->setAttribute("y",$point['y']);
					$pointNode->setAttribute("rot",$point['rot']);
				}
			}
		}
	}

	 
	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.26
	 * 		author: 	    
	 * 		info:		雷达相关操作  end
	 * 
	 ************************************************************************************************/



	/**************************************************************************
	 *
	 * 说明：保存添加场景信息
	 * 作者：
	* 时间：20181016
	*/
	function saveAddScene($sceneData)
	{
		//获取根节点
		$con = file_get_contents($this->tourXmlFile);
		$con = str_replace('</krpano>', '', $con);// es
		foreach($sceneData as $scene)
		{
			$con=$con.$scene;
		}
		$con = $con.'</krpano>';
		file_put_contents($this->tourXmlFile, $con);
		return true;
	}

	//保存分组数据
	function saveGroupInfo($saveGroupData) {
		if(file_exists($this->tourXmlFile)){
			$dom = new DOMDocument();
			$dom->load($this->tourXmlFile);
			//获取当前xml里面的场景节点
			$config = $dom->getElementsByTagName("config");
			if($config->length>0)
			{
				$config = $config->item(0);
				$config->setAttribute("code",$saveGroupData['code']);
				//存在场景分组
				$thumbs = $config->getElementsByTagName("thumbs");
				if($thumbs->length>0)
				{
					$config->removeChild($thumbs->item(0));
				}
				$thumbs = $dom->createElement("thumbs");
				$thumbs->setAttribute("title",'全景列表');
				$thumbs->setAttribute("show_thumb",1);
				$config->appendChild($thumbs);
	
				$count = 0;
				foreach ($saveGroupData['groups'] as $group) 
				{
					//<category name="category0" title="146" thumb="">
					$category = $dom->createElement("category");
					$category->setAttribute("name",'category'.$count);
					$category->setAttribute("title",$group['title']);
					$category->setAttribute("code",$group['code']);
					$category->setAttribute("thumb",'');
					if(isset($group['scenes']))
					{
						foreach($group['scenes'] as $scene)
						{
							//<pano name="scene_CR-x8qqs"   title="scene_CR-x8qqs"/>
							$pano = $dom->createElement("pano");
							$pano->setAttribute("name",$scene['name']);
							$pano->setAttribute("title",$scene['title']);
							$pano->setAttribute("thumburl",$scene['thumburl']);
							$pano->setAttribute("index",$scene['index']);
							//
							$category->appendChild($pano);
						}
					}
		
					$radar = $dom->createElement("radar");
					if(strpos($group['radar']['url'],'SWFPATH%')||""==$group['radar']['url'])
					{
						$radar->setAttribute("url",$group['radar']['url']);
					}
					else
					{
						//保存图片文件
						$imgName = basename($group['radar']['url']);
						$curImgPath=$this->workDir. '/img/map/'.$group['code'];
						$fileUrl = $this->baseUrl. $group['radar']['url'];
						$aimUrl= $this->workDir. '/img/map/'.$group['code'].'/'. $imgName;
						//删除目录
						FileUtil::unlinkDir($curImgPath);
						//文件拷贝
						FileUtil::copyFile($fileUrl,$aimUrl,true);
						$radar->setAttribute("url",'%SWFPATH%/img/map/'.$group['code'].'/'. $imgName);
					}
					$radar->setAttribute("width",$group['radar']['width']);
					$radar->setAttribute("height",$group['radar']['height']);
					if(isset($group['radar']['points']))
					{
						foreach($group['radar']['points'] as $point)
						{
							//<pano name="scene_CR-x8qqs"   title="scene_CR-x8qqs"/>
							$pano = $dom->createElement("point");
							$pano->setAttribute("name",$point['name']);
							$pano->setAttribute("text",$point['text']);
							$pano->setAttribute("scene",$point['scene']);
							$pano->setAttribute("x",$point['x']);
							$pano->setAttribute("y",$point['y']);
							$pano->setAttribute("rot",$point['rot']);
							$radar->appendChild($pano);
						}
					}
					$category->appendChild($radar);
					$thumbs->appendChild($category);
					$count++;
			
				}

			}
			return $ret = $dom->save($this->tourXmlFile);
		}	
	}
}
