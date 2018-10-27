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
	 * 说明：全景切割
	 * 作者：李长明
	 * 时间：20180926
	 */
	 
	protected function addPushProject($params)
	{

		if (!$this->checkProps(['selectedImginfoList','projectName','projectTypeVal','projectTypeName','projectLayerId'], $params)) {
			return false;
		}
		
		//全景资源id集合
		$selectedImginfoList = $params['selectedImginfoList'];
		
		//项目名称
		$projectName = $params['projectName'];
		
		//项目类型名
		$projectTypeName = $params['projectTypeName'];
		
		//项目类型Id
		$projectTypeId = $params['projectTypeVal'];
		
		//项目所属图层
		$projectLayerId = $params['projectLayerId'];
		
		
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
		
		//获取全景资源路径
		$imgPathArray = array();
		foreach($selectedImginfoList as $obj) {

			$conditions = array('id' => $obj);
			
			$panoImgResource = $this->em->getRepository('PanoImgResource')->findOneBy($conditions);
			
			if($panoImgResource) {
				
				$resFilePathInServer = $panoImgResource->getResFilePathInServer();
				
				$arr = array('imgPath'=>$resFilePathInServer);
				$imgPathArray[] = $arr; 
				
			}
		}
		
		
		//执行切图
		$ret = $this->slice($imgPathArray,$projectId,$projectLayerId);
		
		
		
		//执行切图失败 
		if($ret == "error") {
			
			$conditions = array('id' => $projectId);
		
			$project = $this->em->getRepository('Project')->findOneBy($conditions);
			if(!$project) {
				
				$this->setErrorResult($ret['info']);
				return true;
			}
			
			$this->em->remove($project);
			
			$this->em->flush();
			
			
			$this->setErrorResult($ret['info']);
			return true;
			
		}
		
		//执行切图成功 这里预留修改xml
		else {
			
			//项目所属类型标签
			$conditions = array('id' => $projectId);
		
			$project = $this->em->getRepository('Project')->findOneBy($conditions);
			if(!$project) {
				$this->setErrorResult($ret['info']);
				return true;
			}
			
			$project->setProjectPathInServer($ret['info']);
			$createTime = $project->getCreateTime();
			$createTime = $createTime->format("Y-m-d H:i:s");

			$this->em->flush();
			
			$retArray = array('projectId'=>$projectId,'projectPath'=>$ret['info'],'createTime'=>$createTime);
			
			$this->setSuccessResult($retArray);
			return true;
			
		}
	}


	protected function slice($imgPathArray,$projectId,$projectLayerId) {
		
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
			
				
		$result = KrOperation::slice($imgPathArray,$projectId,$projectLayerId);
	
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
}
