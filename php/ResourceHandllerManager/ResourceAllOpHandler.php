<?php
/**************************************************************************
 *
 * 说明：资源通用操作类 不需要传递具体实体类名
 * 作者：李长明
 * 时间：20180917
 *
 *************************************************************************/

class ResourceAllOpHandler extends ResourceHandler
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
	
	//-------------------------------------------------------------------------
	// 功能函数
	
	//获取当前资源类型
	protected function getResourceList($params)
	{
		
		$arr = array();
        if (!$this->em) {
            return $arr;
        }
		
        $resourceType = $this->em->getRepository('PanoramaResourceType')->findAll();
		
		
		if(!$resourceType) {
			return false;
		}
		
		$retArray = array();
		
		foreach ($resourceType as $obj) {
				
			$id = $obj->getId();
			$typeName = $obj->getTypeDesc();
			$entiyClass = $obj->getEntityClassName();
			
			$tmpArray = array('id' => $id, 'typeName' => $typeName, 'entityClass'=>$entiyClass);
			
			$retArray[] = $tmpArray;
		}
		
		$this->setSuccessResult($retArray);
			
		return true;	
		
	}
	
	//获取资源类型图层信息
	protected function getCurResourceLayerInfo($params) {

		if (!$this->checkProps(['curEntityClass','curResourceTypeId'], $params)) {
			return array();
		}
		
		$curEntityClass = $params['curEntityClass'];
		$curResourceTypeId = $params['curResourceTypeId'];
		
		$conditions = array('id' => $curResourceTypeId);
		
		$targetCurEntity = $this->em->getRepository('PanoramaResourceType')->findOneBy($conditions);
		
		if(!$targetCurEntity) {
			return array();
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
	
	//增加资源类型图层分类
	protected function addCurResourceLayer($params) {
		

		if (!$this->checkProps(['newName','curEntityClass'], $params)) {
			return array();
		}
		
		$newName = $params['newName'];
		
		$curEntityClass =  $params['curEntityClass'];
		
		$conditions = array('entityClassName' => $curEntityClass);

		$resourceEntity = $this->em->getRepository('PanoramaResourceType')->findOneBy($conditions);

		if(!$resourceEntity) {
			return array();
		}
		
 		$layerType = new $curEntityClass();
        $layerType->setName($newName);
        $layerType->setResourceType($resourceEntity);


		$this->em->persist($layerType);

		$this->em->flush();

		$this->setSuccessResult($layerType->getId());
		
		return true;
		
	}
	
	protected function delCurResourceLayerInfo($params) {
		
		if (!$this->checkProps(['layerId','curEntityClass'], $params)) {
			return array();
		}
		
		$layerId = $params['layerId'];
		
		$curEntityClass =  $params['curEntityClass'];
		
		$conditions = array('id' => $layerId);
		
		$resourceCurLevelEntity = $this->em->getRepository($curEntityClass)->findOneBy($conditions);
		
		
		if ($resourceCurLevelEntity) {


			$this->em->remove($resourceCurLevelEntity);
		}
		
		//删除之后清除资源
		$aimDir = '../../resource/' .$curEntityClass. '/' .$layerId;
		if($resourceCurLevelEntity) {
			FileUtil::unlinkDir($aimDir);
		}

		$this->em->flush();

		$ret = true;

		$this->setSuccessResult($ret);
		
		return true;
		
		
	}

	protected function renameCurResourceLayerInfo($params) {
		
			
		if (!$this->checkProps(['layerId','curEntityClass','newName'], $params)) {
			return array();
		}
		
		$layerId = $params['layerId'];
		
		$curEntityClass =  $params['curEntityClass'];
		
		$newName = $params['newName'];
		
		$conditions = array('id' => $layerId);
		
		$resourceCurLevelEntity = $this->em->getRepository($curEntityClass)->findOneBy($conditions);
		
		
		if ($resourceCurLevelEntity) {
			
			$resourceCurLevelEntity->setName($newName);
		}
		
		$this->em->flush();
		 
		$this->setSuccessResult(true);
					
		return true;
		
	}
	
	
	
	protected function moveCurResourceToOtherLayer($params) {
			
		if (!$this->checkProps(['targetLayerId','curEntityClass','ids','curLayerId'], $params)) {
			return array();
		}
		
		
		$targetLayerId = $params['targetLayerId'];
		
		$curEntityClass =  $params['curEntityClass'];
		
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
		
		$resourceEntity = $this->em->getRepository($curEntityClass)->findOneBy($conditions);


		
		if(!$resourceEntity) {
			
			$this->setErrorResult(false);
						
			return true;
		}

		$getFunctionName = 'get'.ucfirst($curEntityClass).'List';

		$curResourceAllArray = $resourceEntity->$getFunctionName();
		
		//目标层数据
		$conditions = array('id' => $targetLayerId);
		
		$targetLayerResourceEntity = $this->em->getRepository($curEntityClass)->findOneBy($conditions);
		
		if(!$targetLayerResourceEntity) {
			
			$this->setErrorResult(false);
						
			return true;
		}
		
		//获取类型中函数名				
		$functionName = 'set'.ucfirst($curEntityClass).'Info';
			
		
		$retArray = array();
		
		foreach($curResourceAllArray as $obj) {
			
			if($obj->getId() && in_array($obj->getId(), $ids)) {
				
				$aimDir = '../../resource/' .$curEntityClass. '/' .$curLayerId;
				
				
				$thumbFileUrl = $obj->getResThumbFilePathInServer();
				$resFileUrl = $obj->getResFilePathInServer();
				$resFileName = basename($resFileUrl);
				
				$distResUrl =  '../../resource/' .$curEntityClass. '/' .$targetLayerId .'/'. $resFileName;
				$distResThumbUrl =  '../../resource/' .$curEntityClass. '/' .$targetLayerId  . "/thumb" .'/'. $resFileName;
				
				
				//有缩率图
				if($curEntityClass == "PanoImgLayer") {
					
					//移动原图
					if(FileUtil::moveFile($resFileUrl, $distResUrl)) {
						$obj->setResFilePathInServer($distResUrl);
					}
					if(FileUtil::moveFile($thumbFileUrl, $distResThumbUrl)) {
						$obj->setResThumbFilePathInServer($distResThumbUrl);
					}
					
					$obj->$functionName($targetLayerResourceEntity);

				}
				//无缩率图
				else {
					
					//移动原图
					if(FileUtil::moveFile($resFileName, $distResUrl)) {
						$obj->setResFilePathInServer($distResUrl);
					}
					
					$obj->$functionName($targetLayerResourceEntity);
					
				}
				
			}

		}
		
		$this->em->flush();
		
		$this->setSuccessResult(true);
					
		return true;
	}
}
