<?php

/**************************************************************************
 *
 * 说明：全景热点数据
 * 作者：李长明
 * 时间：20181022
 *
 *************************************************************************/

class KrHotsopt extends KrpanoSaveBaseOpHandler
{
	
	private $tourDom = null;
	
	public function __construct($em,$projectXml)
	{
		parent::__construct($em,$projectXml);
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
	
	public function checkParam($params)
	{
		if (!$this->checkProps(['sceneListHost'], $params)) {
			return false;
		}
		
		return true;
	}
	
	
	//保存热点数据
	protected function save($params) {
		
		$sceneListHost = $params['sceneListHost'];
	
		$tourXmlFile = $this->baseUrl . $this->projectXml .'/tour.xml' ;
		
		$this->tourDom = $this->LoadXml($tourXmlFile);
		if(!$this->tourDom) {
			return array();
		}

		//获取当前xml里面的场景节点
		$sceneList = $this->tourDom->getElementsByTagName("scene");
		
		$retArray = array();
		//循环遍历需要保存的数据
		foreach ($sceneListHost as $key => $value) {
			
			//基础参数	
			$sceneIndex = $value['index'];
			$hotSpots = $value['hotspots'];

			$sceneIndex  = intval($sceneIndex);
			$sceneItem = $sceneList->item($sceneIndex);


			if (!is_int($sceneIndex)) {
    			continue;
			}
			
			//保存热点数据
			if ($hotSpots != null) {
		    	
		        foreach ($hotSpots as $key => $value) {


		            $name = $value['name'];
					
		            $node =$this->tourDom->createElement("hotspot");
					$node->setAttribute("name", $name);
					$node->setAttribute("ath", $value['ath']);
		            $node->setAttribute("atv", $value['atv']);
		            $node->setAttribute("linkedscene", $value['linkedscene']);
		            $node->setAttribute("style", $value['style']);
		            $node->setAttribute("title", $value['title']);
		            $node->setAttribute("curscenename", $value['curscenename']);
					$node->setAttribute("typevalue", $value['typevalue']);
					$node->setAttribute("hotspotlink", $value['hotspotlink']);
					if($value['typevalue'] == '2') {
						$node->setAttribute("onclick", "js(window.open(get(hotspotlink),'_blank'));");
					}else if($value['typevalue'] == '1') {
						$node->setAttribute("onclick", "looktohotspot(get(linkedscene));loadscene(get(linkedscene),null,MERGE,BLEND(1));lookat(320.22, 1.05, 48.15);wait(BLEND);oninterrupt(break);lookto(202.65, 8.12, 105.5, smooth(100,100,200));");	
					}
					else {
						$node->setAttribute("onclick","");
					}
					
		            $sceneItem->appendChild($node);
		        }
		    }
			
		

		}
		
		//保存文件
		$ret = $this->saveXml($tourXmlFile,$this->tourDom);
		if(!$ret) {
			$this->setErrorResult("保存失败");
		}
		else {
			
			$this->setSuccessResult("保存成功");;
		}	
		
		return true;
	}

}
