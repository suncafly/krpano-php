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

        $includeList = $this->tourDom->getElementsByTagName("include");
        $firstInclude = $includeList->item(0);
        $url = $firstInclude->getAttribute("url");
        if(!strstr($url,"common.xml")){
            $node = $this->tourDom->createElement("include");
            $Path =str_replace('php/krpanoSaveDataAfterEdit/KrHotsopt.php','',str_replace('\\', '/', __FILE__));
            $node->setAttribute("url", $Path."plugins/common.xml");
            $this->tourDom->getElementsByTagName("krpano")->item(0)->insertBefore($node, $firstInclude);
        }
		//获取当前xml里面的场景节点
		$sceneList = $this->tourDom->getElementsByTagName("scene");

		//循环遍历需要保存的数据
		foreach ($sceneListHost as $key => $value) {

			//基础参数
			$sceneIndex = $value['index'];
			$hotSpots = $value['hotspots'];

			$sceneIndex  = intval($sceneIndex);
			$sceneItem = $sceneList->item($sceneIndex);

            //删除一个场景下的所有热点数据
            $childNodes = $sceneItem->childNodes;
            $size = $childNodes->length;
            for ($x = 0; $x <= $size; $x++) {
                $tempNode = $childNodes->item($x);
                if ($tempNode->nodeName == "hotspot") {
                    $sceneItem->removeChild($tempNode);
                }
            }

			if (!is_int($sceneIndex)) {
    			continue;
			}

			//保存热点数据
			if ($hotSpots != null) {

		        foreach ($hotSpots as $key => $value) {


//		            $name = $value['name'];
//
//		            $node =$this->tourDom->createElement("hotspot");
//					$node->setAttribute("name", $name);
//					$node->setAttribute("ath", $value['ath']);
//		            $node->setAttribute("atv", $value['atv']);
//		            $node->setAttribute("linkedscene", $value['linkedscene']);
//		            $node->setAttribute("style", $value['style']);
//		            $node->setAttribute("title", $value['title']);
//		            $node->setAttribute("curscenename", $value['curscenename']);
//					$node->setAttribute("typevalue", $value['typevalue']);
//					$node->setAttribute("hotspotlink", $value['hotspotlink']);
//					if($value['typevalue'] == '2') {
//						$node->setAttribute("onclick", "js(window.open(get(hotspotlink),'_blank'));");
//					}else if($value['typevalue'] == '1') {
//						$node->setAttribute("onclick", "looktohotspot(get(linkedscene));loadscene(get(linkedscene),null,MERGE,BLEND(1));lookat(320.22, 1.05, 48.15);wait(BLEND);oninterrupt(break);lookto(202.65, 8.12, 105.5, smooth(100,100,200));");
//					}
//					else {
//						$node->setAttribute("onclick","");
//					}
//		            $sceneItem->appendChild($node);

                    $name = $value['name'];
                    $node = $this->tourDom->createElement("hotspot");
                    $node->setAttribute("name", $name);
                    $node->setAttribute("ath", $value['ath']);
                    $node->setAttribute("atv", $value['atv']);
                    $node->setAttribute("linkedscene", $value['linkedscene']);
                    $node->setAttribute("style", $value['style']);
                    $node->setAttribute("title", $value['title']);
                    $node->setAttribute("curscenename", $value['curscenename']);

                    //热点类型 1:全景切换;2:超链接;3:图片热点;4:视屏热点;5:文本热点;6:音频热点
                    $typeValue = $value['typevalue'];
                    if ($typeValue) {
                        $node->setAttribute("typevalue", $typeValue);
                        if ($typeValue == 1) {//全景切换
//                            $node->setAttribute("onclick", "looktohotspot(get(linkedscene));loadscene(get(linkedscene),null,MERGE,BLEND(1));lookat(320.22, 1.05, 48.15);wait(BLEND);oninterrupt(break);lookto(202.65, 8.12, 105.5, smooth(100,100,200));");
                        } else if ($typeValue == 2) { //超链接
                            //1:本窗口打开;2:新窗口打开;3:弹出层
                            $linkOpenType = $value['linkopentype'];
                            $hotSpotLink = $value['hotspotlink'];
                            if ($hotSpotLink) {
                                $node->setAttribute("hotspotlink", $hotSpotLink);
                                $node->setAttribute("linkopentype", $linkOpenType);
                                //本窗口打开
                                if ($linkOpenType == 1) {
                                    $node->setAttribute("onclick", 'openurl(' . $hotSpotLink . ',_self)');
                                } else if ($linkOpenType == 2) {
                                    $node->setAttribute("onclick", 'openurl(' . $hotSpotLink . ',_blank)');
                                }
                            }
                        } else if ($typeValue == 5) {
                            $content = $value['content'];
                            $node->setAttribute("content", $content);
//                            $node->setAttribute("content", '收了定金发链接发生劳动法案发时吉利[br]丁粉加上来发的说法第十六届发');
                            $node->setAttribute("onclick", 'print_hotspot_message()');
                        } else if ($typeValue == 3) {
//                             $imageurl = $value['imageurl'];
//                            $node->setAttribute("imageurl", $imageurl);
                            $node->setAttribute("imageurl", "images/IMG_1688.jpg");
                            $node->setAttribute("onclick", 'print_hotspot_pic()');
                        } else if ($typeValue == 4) {
                            $vedio = $value['vedio'];
                            $node->setAttribute("vedio", $vedio);
                            $node->setAttribute("onclick", "looktohotspot(get(name),90); youtubeplayer_open('x1F3X-f9roo');");
                        } else if ($typeValue == 6) {


                        }
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
