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
	private $radarDom = null;
	
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
		if (!$this->checkProps(['radarData'], $params)) {
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
	protected function saveData($params) {
		
		//获取雷达数据
		$radarData = $params['radarData'];
		
		$radarXmlFile = $this->baseUrl . $this->projectXml .'/skin/radar.xml';
		//保存雷达地图数据
		if($radarData) {

			
				
			//打开目录xml文件
			$this->radarDom = $this->LoadXml($radarXmlFile);
	
			 if(!$this->radarDom) {
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
			
			//保存底图
			if(isset($radarData['radarMap'] ))
			{
				
				$radarMap = $radarData['radarMap'];
				
				$ext = FileUtil::fileext($radarMap['path']);
				
				$imgPath = 'img/map/map.'.$ext;
				
				$curImgPath = $this->baseUrl . $this->projectXml . '/skin/img/map';
				
				$fileUrl = $this->baseUrl. $radarMap['path'];
				
				$aimUrl=	$this->baseUrl . $this->projectXml . '/skin/'. $imgPath;
				
				//删除目录
				FileUtil::unlinkDir($curImgPath);
				
				//文件拷贝
				FileUtil::copyFile($fileUrl,$aimUrl,true);
				
				$maplayer->setAttribute("url",$imgPath);
				$maplayer->setAttribute("width",$radarMap['width']);
				$maplayer->setAttribute("height",$radarMap['height']);
			}
	
			//保存雷达标记点
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
							$layerName = $layer->getAttribute("name");
							if ($layerName == $radar['name']) 
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
	
			
		}
		
		//保存文件
		$ret = $this->saveXml($radarXmlFile,$radarDom);
		if(!$ret) {
			$this->setErrorResult("保存失败");
		}
		else {
			
			$this->setSuccessResult("保存失败");;
		}	
		
		return true;
	}
}
