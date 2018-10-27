<?php
/**************************************************************************
 *
 * 说明：全景切入数据
 * 作者：李长明
 * 时间：20181022
 *
 *************************************************************************/

class KrEmbed extends KrpanoSaveBaseOpHandler
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
	
	
}
