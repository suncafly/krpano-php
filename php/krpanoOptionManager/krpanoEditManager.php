<?php

/**************************************************************************
 *
 * 说明：全景发布生成编辑
 * 作者：李长明
 * 时间：20180926
 *
 *************************************************************************/

class krpanoEditManager extends krpanoBaseOpHandler
{
	
	
	public function __construct($em)
	{
		parent::__construct($em);
	}
	
	public function parse($params)
	{

		if (!$this->checkProps(['opCode'], $params)) {
			return false;
		}
		
			
		//根据操作
		$opCode = $params['opCode'];
		
		if (method_exists($this, $opCode)) {
			if (call_user_func_array(array($this, $opCode), array($params))) {
				return true;
			}
		}
		
		return true;
	}
	
	
	/**************************************************************************
	 *
	 * 说明：保存场景信息
	 * 作者：
	 * 时间：20181022
	 */
	function saveScene($xmlFile,$sceneData)
	{
		//判断文件是否存在
		if(!file_exists($xmlFile)) {
			
			return false;
		}
		
		//字符串是否为空
		if($sceneData == "") {
			return false;
		}
		
		//读文件
		$fileContent = file_get_contents($xmlFile);
		$fileContent = str_replace('</krpano>', '', $fileContent);
		
		//插入场景数据
		$fileContent=$fileContent.$sceneData;
		
		//重新设置文件结尾
		$fileContent = $fileContent.'</krpano>';
		
		//写入文件
		file_put_contents($xmlFile, $fileContent);
		
		return true;
	}
	
	/**************************************************************************
	 *
	 * 说明：移动全景资源文件并合并场景
	 * 作者：
	 * 时间：20181022
	 */
	private function movePanosAndMergeScene($selectedImginfoList,$disDir) {
		
				
		if(!$disDir) {
			return false;
		}
		
		if(count($selectedImginfoList) == 0) {
			return false;
		}

		
		//遍历获取全景资源路径已经场景内容	
		$panoPathAndSceneArray = array();	
		$sceneList = "";
		
		foreach($selectedImginfoList as $obj) {

			$conditions = array('id' => $obj);
			
			$panoImgResource = $this->em->getRepository('PanoImgResource')->findOneBy($conditions);
			
			if($panoImgResource) {
				
				$resPanoPathInServer = $panoImgResource->getResPanoPathInServer();
				$resSceneString = $panoImgResource->getResSceneString();
				
				$arr = array('panoPath'=>$resPanoPathInServer,'scene'=>$resSceneString);
				$panoPathAndSceneArray[] = $arr; 
				
			}
		}
		
		if(count($panoPathAndSceneArray) == 0) {
			return false;
		}

		//移动公用文件到目标目录下
		$oldDir = KRSTATIC.'/';
		FileUtil::copyDir($oldDir, $disDir);
		
		
		$scenes = "";
		foreach($panoPathAndSceneArray as $obj) {
			
			$panoPath = $obj['panoPath'];
			$sceneString = $obj['scene'];
			
			//移动全景资源
			$rpos = strrpos($panoPath,"/");
			$temp_name = substr($panoPath, $rpos==0?$rpos:$rpos+1);
			$disPanoDir = $disDir.'panos/'.$temp_name.'/';
			FileUtil::copyDir($panoPath, $disPanoDir);
			
			$scenes .= $sceneString;
			
		}
		
		//将场景内容写进目标xml文件中
		$disTourXmlFile = $disDir.'tour.xml';
		if($this->saveScene($disTourXmlFile,$scenes)) {
			return true;
		}
		else {
			return false;
		}
		
		return true;
		
	}
	
	
	/**************************************************************************
	 *
	 * 说明：编辑全景的时候追加全景资源
	 * 作者：李长明
	 * 时间：20180926
	 */
	protected function appendPanoToCurProject($params) {
		
			
		if (!$this->checkProps(['projectId','selectedImginfoList','projectLayerId'], $params)) {
			return false;	
		}
		
		//项目ID
		$projectId = $params['projectId'];
		
		//项目所属图层
		$projectLayerId = $params['projectLayerId'];
		
		//全景资源id集合
		$selectedImginfoList = $params['selectedImginfoList'];
		
		
		//移动全景资源目录到项目目录里 同时合并场景
		$disDir = KRODRION.'/'.$projectLayerId.'/'.$projectId.'/';


		if(count($selectedImginfoList) == 0) {
				
			$this->setErrorResult(array("请选择资源"));
			return false;
		}

		
		//遍历获取全景资源路径已经场景内容	
		$panoPathAndSceneArray = array();	
		$sceneList = "";
		
		foreach($selectedImginfoList as $obj) {

			$conditions = array('id' => $obj);
			
			$panoImgResource = $this->em->getRepository('PanoImgResource')->findOneBy($conditions);
			
			if($panoImgResource) {
				
				$resPanoPathInServer = $panoImgResource->getResPanoPathInServer();
				$resSceneString = $panoImgResource->getResSceneString();
				
				$arr = array('panoPath'=>$resPanoPathInServer,'scene'=>$resSceneString);
				$panoPathAndSceneArray[] = $arr; 
				
			}
		}
		
		if(count($panoPathAndSceneArray) == 0) {
				
			$this->setErrorResult(array("请选择资源"));
			return false;
		}

		
		$scenes = array();
		foreach($panoPathAndSceneArray as $obj) {
			
			$panoPath = $obj['panoPath'];
			$sceneString = $obj['scene'];
			
			
			//取出文件名
			$rpos = strrpos($panoPath,"/");
			$temp_name = substr($panoPath, $rpos==0?$rpos:$rpos+1);
			$panoFileName = FileUtil::getBaseFileName($temp_name);

			//全景资源路径
			$disPanoDir = $disDir.'panos/';
			$disPanoDir = $disPanoDir .$panoFileName.'.tiles';
			
			//移动全景资源
			FileUtil::copyDir($panoPath, $disPanoDir);
			$scenes[] = array('scenes'=>$sceneString);
		}
	
		$retArray = array('scenes'=>$scenes);
		
		$this->setSuccessResult($retArray);
			
		return true;
		
		
		
	}


	/**************************************************************************
	 *
	 * 说明：发布项目
	 * 作者：李长明
	 * 时间：20180926
	 */
	 
	protected function addPushProject($params)
	{

		if(!$this->checkProps(['selectedImginfoList','projectLayerId','projectName','projectTypeVal','projectTypeName'], $params)) {
			return false;
		}

		//全景资源id集合
		$selectedImginfoList = $params['selectedImginfoList'];

		//项目所属图层
		$projectLayerId = $params['projectLayerId'];

		//项目名称
		$projectName = $params['projectName'];
		
		//项目类型名
		$projectTypeName = $params['projectTypeName'];
		
		//项目类型Id
		$projectTypeId = $params['projectTypeVal'];

		//这里缺少项目图层 到时候补上 当下用默认图层代替
		$conditions = array('id' => $projectLayerId);
		
		$projectLayer = $this->em->getRepository('ProjectLayer')->findOneBy($conditions);

		if(!$projectLayer) {
			
			$this->setErrorResult(array());
			return true;
		}
		
		//项目所属类型标签
		$conditions = array('id' => $projectTypeId);
		
		$projectType = $this->em->getRepository('ProjectType')->findOneBy($conditions);

		if(!$projectType) {
			
			$this->setErrorResult(array());
			return true;
		}
		
		//新建项目插入到数据库中
		$dt = new DateTime('NOW');	
		$t = $dt->format('Y-m-d H:i:s');
 		$project = new Project();
        $project->setName($projectName);
		$project->setCreatePerson('李长明');
		$project->setCreateTime(new DateTime($t));
		$project->setProjectInfo('这是全景项目');
		$project->setProjectTypeName($projectTypeName);
		$project->setViewNumber('1');
		$project->setUploadedNumber('1');
		$project->setProjectPathInServer('');
		$project->setProjectLayerInfo($projectLayer);
		$project->setProjectTypeInfo($projectType);
		
		$this->em->persist($project);
		$this->em->flush();
		
		//新建成功之后返回的Id
		$projectId = $project->getId();
		if($projectId <= 0) {
			
			$this->setErrorResult(array());
			return true;
		}

		//移动全景资源目录到项目目录里 同时合并场景
		$disDir = KRODRION.'/'.$projectLayerId.'/'.$projectId.'/';
		$moveRet = $this->movePanosAndMergeScene($selectedImginfoList,$disDir);
			
		$conditions = array('id' => $projectId);
		$project = $this->em->getRepository('Project')->findOneBy($conditions);
		if(!$project) {
			
			$this->setErrorResult("没有找到该项目");
			return true;
		}
		
	
		//移动文件失败 
		if(!$moveRet) {

			$this->em->remove($project);
			
			$this->em->flush();

			$this->setErrorResult("发布项目失败");
			return true;
			
		}
		
		//成功
		else {
			
			$projectPath = 'data/krpano'.'/'.$projectLayerId.'/'.$projectId.'/'.'tour.html';
			
			$project->setProjectPathInServer($projectPath);
			$createTime = $project->getCreateTime();
			$createTime = $createTime->format("Y-m-d H:i:s");

			$this->em->flush();
			
			$retArray = array('projectId'=>$projectId,'projectPath'=>$projectPath,'createTime'=>$createTime);
			
			$this->setSuccessResult($retArray);
			
			return true;
			
		}
	}


	protected function slice($imgPathArray,$projectId,$projectLayerId,$bAdd=false) {
		
		if(count($imgPathArray) == 0) {
			return array('ret'=>'error',"info"=>"切图失败，没有获取到资源");
		}
		
		if($projectId <= 0) {
			return array('ret'=>'error',"info"=>"切图失败，项目没有生成成功");
		}
		if($projectLayerId <= 0) {
			return array('ret'=>'error',"info"=>"切图失败，项目所属图层不存在");
		}
		
		if(!function_exists('exec')){
			return array('ret'=>'error','info'=>'系统当前不支持exec方法，无法发布！');
		}
			
				
		$result = KrOperation::slice($imgPathArray,$projectId,$projectLayerId,$bAdd);
	
		return $result;
		
	}
	




	protected function saveProjectTour($params) {
		
		if (!$this->checkProps(['saveData'], $params)) {
			return false;
		}
		
		$saveData = $params['saveData'];
		
		
		$projectXml = $saveData['projectXml'];

		
		$curProjectPath = '../../' .$projectXml ;

		if(!$curProjectPath) {


			$this->setErrorResult(array('ret'=>'error',"info"=>"项目路径不正确"));
			return true;
		}
		
		//保存工作目录		
		saveProjectDataOptionManager::getSingleton()->setWorkDir($curProjectPath);

		//保存场景信息
		$ret = saveProjectDataOptionManager::getSingleton()->saveSceneInfo($saveData);
			
		$this->setSuccessResult($ret);
		
		return true;
	}

	//获取分组信息
	protected function getGroupInfo($params)
	{
		$groupDatas = array();
		if (!$this->checkProps(['projectXml'], $params)) {
			$this->setSuccessResult(array('groupData'=>$groupDatas));
			return false;
		}
		$projectXml = $params['projectXml'];
		$tourXmlFile = '../../' .$projectXml.'/tour.xml' ;

		if(file_exists($tourXmlFile)) 
		{
			$dom = new DOMDocument();
			$dom->load($tourXmlFile);
			//读取分组信息
			$config = $dom->getElementsByTagName("config");
			if($config->length>0)
			{
				$groupDatas['code'] = $config->item(0)->getAttribute("code");
				$groupDatas['groups'] = array();
				//存在分组信息
				$thumbs = $config->item(0)->getElementsByTagName("thumbs");
				if($thumbs->length>0)
				{
					$thumb = $thumbs->item(0);
					$categorys = $thumb->getElementsByTagName("category"); 
					if($categorys->length>0){
						foreach($categorys as $k => $category)
						{
							$groupData = array();
							$groupData['name'] = $category->getAttribute("name");
							$groupData['title'] = $category->getAttribute("title");
							$groupData['code'] = $category->getAttribute("code");
							//$groupData['scenes'] = array();
							$panos = $category->getElementsByTagName('pano');
							$scenes = array();
							foreach($panos as $p=>$pano)
							{
								$scene = array();
								$scene['name'] = $pano->getAttribute("name");
								$scene['title'] = $pano->getAttribute("title");
								$scene['thumburl'] = $pano->getAttribute("thumburl");
								$scene['index'] = $pano->getAttribute("index");
								$scenes[] = $scene;
							}
							$radars = $category->getElementsByTagName('radar');
							$radar = $radars->item(0);
							$radarData = array();
							$radarData['url'] = $radar->getAttribute("url");
							$radarData['width'] = $radar->getAttribute("width");
							$radarData['height'] = $radar->getAttribute("height");
							$scenepoints = $radar->getElementsByTagName('point');
							$points = array();
							foreach($scenepoints as $p=>$scenepoint)
							{
								$point = array();
								$point['name'] = $scenepoint->getAttribute("name");
								$point['x'] = $scenepoint->getAttribute("x");
								$point['y'] = $scenepoint->getAttribute("y");
								$point['rot'] = $scenepoint->getAttribute("rot");
								$point['text'] = $scenepoint->getAttribute("text");
								$point['scene'] = $scenepoint->getAttribute("scene");
								$points[] = $point;
							}
							$radarData['points'] = $points;
							$groupData['scenes'] = $scenes;
							$groupData['radar'] = $radarData;
							$groupDatas['groups'][] = $groupData;
						}
					}
				}
				else
				{
					$groupDatas['code'] = 1;
				}
			}
		}
		//获取雷达点位信息
		//$radarXmlFile = '../../' .$projectXml.'/skin/radar.xml' ;
		//合并
		//getRadarInfo($radarXmlFile,$groupDatas);
		//返回
		$this->setSuccessResult(array('groupData'=>$groupDatas));
		return true;
	}

	/*//读取雷达点位信息
	protected function getRadarInfo($params)
	{
		if (!$this->checkProps(['projectXml'], $params)) {
			return false;
		}
		$projectXml = $params['projectXml'];
		$radarXmlFile = '../../' .$projectXml.'/skin/radar.xml' ;

		$RadarData = array();
		if(file_exists($radarXmlFile)) 
		{
			$dom = new DOMDocument();
			$dom->load($tourXmlFile);
			//开始读取

		}
	}*/
}
