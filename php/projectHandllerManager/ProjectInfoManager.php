<?php

/**************************************************************************
 *
 * 说明：作品管理
 * 作者：李长明
 * 时间：20180925
 *
 *************************************************************************/

class ProjectInfoManager extends projectBaseOpHandler
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
	 * 说明：获取项目数据
	 * 作者：李长明
	 * 时间：20180925
	 */
	 
	protected function getProjectInfo($params)
	{
		
		//获取当前所有图层
		$projectLayerList = $this->em->getRepository('ProjectLayer')->findAll();
		
		if(!$projectLayerList) {
			$this->setSuccessResult(array());
			return true;
		}
		
		//数据返回列表
		$retArray = array();
		
		foreach ($projectLayerList as $obj) {
			
			//获取项目图层Id
			$layerId = $obj->getId();
			//获取项目图层名称
			$layerName = $obj->getName();
			//获取当前图层下面的所有项目
			$projectList = $obj->getProjectLayerList();
			
			
			//图层项目数据
			$curProjecArray = array();
			
			//项目列表
			$projecArray = array();
			
			if(!$projectList) {
				
			}
			
			else {
				
				$arr = array();
				foreach($projectList as $project) {
						
					//获取项目唯一Id
					$projectId = $project->getId();
					//获取项目名称
					$projectName = $project->getName();
					//获取项目类型信息
					$projectType = $project->getProjectTypeName();
					//获取项目在服务器上的路径
					$projectPath = $project->getProjectPathInServer();
					//获取项目创建时间
					$projectCreateTime = $project->getCreateTime();
					$projectCreateTime = $projectCreateTime->format("Y-m-d H:i:s");
					
					//数据打包
					$arr = array('projectId'=>$projectId,'projectName'=>$projectName,'projectType'=>$projectType,'projectPath'=>$projectPath,'projectCreateTime'=>$projectCreateTime,'layerId'=>$layerId);
					
					$projecArray[] = $arr;
					
				}
			}
			
			
			$curProjecArray = array('layerId'=>$layerId,'layerName'=>$layerName,'projectList'=>$projecArray);
			
			$retArray[] = $curProjecArray;
		}

		$this->setSuccessResult($retArray);
					
		return true;
		
	}

	protected function addCurProjectLayer($params) {
		
		//校验数据
		if (!$this->checkProps(['newName'], $params)) {
				
			$this->setErrorResult(array());
			return true;
		}
		
		$newName = $params['newName'];
		
		
		$projectLayer = new ProjectLayer();
		
        $projectLayer->setName($newName);


		$this->em->persist($projectLayer);

		$this->em->flush();

		$this->setSuccessResult($projectLayer->getId());
		
		return true;
		
	}
	
	protected function getCurProjectLayerInfo($params) {
			
		//校验数据
		if (!$this->checkProps(['layerId'], $params)) {
				
			$this->setErrorResult(array());
			return true;
		}
		
		$layerId = $params['layerId'];
		

		$conditions = array('id' => $layerId);
		
		$projectLayer = $this->em->getRepository('ProjectLayer')->findOneBy($conditions);

		if(!$projectLayer) {
			
			$this->setErrorResult(array());
			return true;
		}
		
		
		$projectLayerArray = $projectLayer->getProjectLayerList();
		
		if(!$projectLayerArray) {
			
			$this->setErrorResult(array());
			return true;
		}
		
		$projecArray = array();
		
		foreach($projectLayerArray as $project) {
				
			//获取项目唯一Id
			$projectId = $project->getId();
			//获取项目名称
			$projectName = $project->getName();
			//获取项目类型信息
			$projectType = $project->getProjectTypeName();
			//获取项目在服务器上的路径
			$projectPath = $project->getProjectPathInServer();
			//获取项目创建时间
			$projectCreateTime = $project->getCreateTime();
			$projectCreateTime = $projectCreateTime->format("Y-m-d H:i:s");
			
			//数据打包
			$arr = array('projectId'=>$projectId,'projectName'=>$projectName,'projectType'=>$projectType,'projectPath'=>$projectPath,'projectCreateTime'=>$projectCreateTime,'layerId'=>$layerId);
			
			$projecArray[] = $arr;
					
		}
		
		
		$this->setSuccessResult($projecArray);
					
		return true;
	}

	protected function delCurProjectLayerInfo($params) {
			
		//校验数据
		if (!$this->checkProps(['layerId'], $params)) {
				
			$this->setErrorResult(array());
			return true;
		}
		
		$layerId = $params['layerId'];
		
		
		$conditions = array('id' => $layerId);
		
		$projectLayerInfo = $this->em->getRepository('ProjectLayer')->findOneBy($conditions);
		
		
		if ($projectLayerInfo) {


			$this->em->remove($projectLayerInfo);
		}
		
		//删除之后清除资源
		$aimDir = '../../data/krpano/'.$layerId;
		if($projectLayerInfo) {
			FileUtil::unlinkDir($aimDir);
		}

		$this->em->flush();

		$ret = true;

		$this->setSuccessResult($ret);
		
		return true;
	}
	
	protected function renameCurProjectLayerInfo($params) {
			
		if (!$this->checkProps(['layerId','newName'], $params)) {
			return array();
		}
		
		$layerId = $params['layerId'];
		
		$newName = $params['newName'];
		
		$conditions = array('id' => $layerId);
		
		$projectLayer = $this->em->getRepository('ProjectLayer')->findOneBy($conditions);
		
		
		if ($projectLayer) {
			
			$projectLayer->setName($newName);
		}
		
		$this->em->flush();
		 
		$this->setSuccessResult(true);
					
		return true;
	}
	
	protected function moveCurProjectToOtherLayer($params) {
		
		
		if (!$this->checkProps(['targetLayerId','ids','curLayerId'], $params)) {
			return array();
		}
		
		
		$targetLayerId = $params['targetLayerId'];
		
		$ids = json_decode($params['ids']);
		
		$curLayerId = $params['curLayerId'];
		
		//不能移动到当前图层
		if($targetLayerId < 0) {
				
			$this->setErrorResult(false);
			return true;
		}
		
		//不能移动到当前图层
		if($curLayerId == $targetLayerId) {
				
			$this->setErrorResult(false);
			return true;
		}
		
		//取的当前需要移动图层的资源
		$conditions = array('id' => $curLayerId);
		
		$curProjectLayer = $this->em->getRepository('ProjectLayer')->findOneBy($conditions);


		
		if(!$curProjectLayer) {
			
			$this->setErrorResult(false);
						
			return true;
		}


		$curProjectLayerArray = $curProjectLayer->getProjectLayerList();
		
		//目标层数据
		$conditions = array('id' => $targetLayerId);
		
		$targetProjectLayerArray = $this->em->getRepository('ProjectLayer')->findOneBy($conditions);
		
		if(!$targetProjectLayerArray) {
			
			$this->setErrorResult(false);
						
			return true;
		}
		
		
		$retArray = array();
		
		foreach($curProjectLayerArray as $obj) {
			
			if($obj->getId() && in_array($obj->getId(), $ids)) {
				
				$aimDir = '../../data/krpano'.'/'.$curLayerId.'/' .$obj->getId();
				
				$disDir = '../../data/krpano'.'/'.$targetLayerId.'/' .$obj->getId();
				
				//移动文件
				if(FileUtil::moveDir($aimDir, $disDir)) {
					$projectPath = 'data/krpano'.'/'.$targetLayerId.'/'.$obj->getId().'/'.'tour.html';
					$obj->setProjectPathInServer($projectPath);
				}
		
				$obj->setProjectLayerInfo($targetProjectLayerArray);
		
			}

		}
		
		$this->em->flush();
		
		$this->setSuccessResult(true);
					
		return true;
		
	}
}
