<?php
	
/**************************************************************************
 *
 * 说明：作品发布
 * 作者：李长明
 * 时间：20180925
 *
 *************************************************************************/

// 作品发布
class PushProjectManager extends projectBaseOpHandler
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
	 * 说明：获取项目创建的时候的类型
	 * 作者：李长明
	 * 时间：20180925
	 */
	 
	protected function getProjectType($params) {
			
		//获取当前所有类型
		$projectTypeList = $this->em->getRepository('ProjectType')->findAll();
		
		if(!$projectTypeList) {
			$this->setSuccessResult(array());
			return true;
		}
		
		//获取当前所有图层
		$projectLayerList = $this->em->getRepository('ProjectLayer')->findAll();
		
		if(!$projectLayerList) {
			$this->setSuccessResult(array());
			return true;
		}
		
		$retProjectLayerArray = array();
		$retProjectTypeArray = array();
		
		foreach ($projectLayerList as $obj) {
				
			
			$id = $obj->getId();
			
			$name = $obj->getName();
			
			$arr = array('id'=>$id,'name'=>$name);
			
			$retProjectLayerArray[] = $arr;
				
		}
		
		foreach ($projectTypeList as $obj) {
				
			
			$id = $obj->getId();
			
			$name = $obj->getName();
			
			$arr = array('id'=>$id,'name'=>$name);
			
			$retProjectTypeArray[] = $arr;
				
		}
		
		$retArray = array('projectTypeList'=>$retProjectTypeArray,'projectLayerList'=>$retProjectLayerArray);
		
		$this->setSuccessResult($retArray);
		
		return true;
		
	}
	
	protected function getPanoImgLayer($params) {
			
		//获得所有图层
		if (!$this->checkProps(['curEntityClass','curResourceTypeId'], $params)) {
				
			$this->setSuccessResult(array());
			return true;
		}
		
		$curEntityClass = $params['curEntityClass'];
		$curResourceTypeId = $params['curResourceTypeId'];
		
		$conditions = array('id' => $curResourceTypeId);
		
		$targetCurEntity = $this->em->getRepository('PanoramaResourceType')->findOneBy($conditions);
		
		if(!$targetCurEntity) {
			
			$this->setSuccessResult(array());
			return true;
		}
		
		//获取类型中函数名				
		$functionName = 'get'.ucfirst($curEntityClass).'List';
			
		//获取当前类型下所有的图层
		$layerArray = $targetCurEntity->$functionName();
		
		$retArray = array();
		
		foreach($layerArray as $obj) {
			
			$id = $obj->getId();
			$name = $obj->getName();
			
			$retArray[] = array('id'=>$id,'layerName'=>$name);
		}
		
		$this->setSuccessResult($retArray);
			
		return true;	
	}
	
	protected function getCurLayerSourceInfo($params) {
			
		if (!$this->checkProps(['layerId','entityClass'], $params)) {
			return array();
		}
		
		$layerId = $params['layerId'];
		
		$curEntityClass =  $params['entityClass'];
		
		//保存信息到数据库
		$conditions = array('id' => $layerId);
		
		$resourceEntity = $this->em->getRepository($curEntityClass)->findOneBy($conditions);

		if(!$resourceEntity) {
			
			$this->setErrorResult(false);
						
			return true;
		}
		
		//获取类型中函数名				
		$functionName = 'get'.ucfirst($curEntityClass).'List';
			
			
		$resourceArray = $resourceEntity->$functionName();
		
		if(!$resourceArray) {
			
			$this->setErrorResult(false);
						
			return true;
		}
		
		$retArray = array();
		
		foreach($resourceArray as $obj) {


			
			$tempArray = array(
		
				'id' => $obj->getId(),
				'fileName' => $obj->getResFileServerName(), 
				'filePath' => $obj->getResFilePathInServer(), 
				'fileThumbPath' => $obj->getResThumbFilePathInServer()
			);
			$retArray [] = $tempArray;
		}
		
		
		$this->setSuccessResult($retArray);
					
		return true;
	}

	protected function addPushProject($params) {
		
	}
}
