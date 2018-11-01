<?php

/**************************************************************************
 *
 * 说明：全景特效数据
 * 作者：李长明
 * 时间：20181022
 *
 *************************************************************************/
 
class KrSpecialEffect extends KrpanoSaveBaseOpHandler
{
	
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
	
	protected function getCurLayerSourceInfo($params) {
			
		if (!$this->checkProps(['layerId','curEntityClass'], $params)) {
			return array();
		}
		
		$layerId = $params['layerId'];
		
		$curEntityClass =  $params['curEntityClass'];
		
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
	
}
