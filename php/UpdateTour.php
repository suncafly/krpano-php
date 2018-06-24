<?php
/**
 * Created by PhpStorm.
 * User: Lmt
 * Date: 2018/5/2
 * Time: 10:26 AM
 */

$result = array();
$result['status'] = "error";
$data = json_decode($_POST["tour"]);
$title = $_POST["title"];
$scene_index = $_POST["scene_index"];
$xmlfile = '../data/' . $title . '/vtour/tour.xml';
$tourDom = new DOMDocument();
$tourDom->load($xmlfile);
$sceneList = $tourDom->getElementsByTagName("scene");

// 计算中文字符串长度
function utf8_strlen($string = null)
{
// 将字符串分解为单元
    preg_match_all("/./us", $string, $match);
// 返回单元个数
    return count($match[0]);
}
foreach ($data as $key => $value) {
    $sceneIndex = $value->index;
//    property_exists($value, "welcomeFlag")?
    $welcomeFlag = $value->welcomeFlag;
    $sceneName = $value->name;
    $autorotate = $value->autorotate;
    $hotSpots = $value->hotSpots;
    $fov = $value->fov;
    $sceneItem = $sceneList->item($sceneIndex);

    if (!is_int($sceneIndex)) {
        return json_encode($result);
    }


    //自动旋转
    if ($autorotate) {
        $enabled = $autorotate->enabled;
        $v1 = $sceneItem->getElementsByTagName("autorotate");
        $v2 = $v1->item(0);
        if ($enabled) {
            $v2->setAttribute("enabled", "true");
        } else {
            $v2->setAttribute("enabled", "false");
        }
    }

    //重新命名
    $oldSceneName = $sceneItem->getAttribute("name");
    if ($oldSceneName != $sceneName) {
        foreach ($sceneList as $t0) {
            $t1 = $t0->getElementsByTagName("hotspot");
            foreach ($t1 as $t2) {
                $t3 = $t2->getAttribute("linkedscene");
                if ($t3 == $oldSceneName) {
                    $t2->setAttribute("linkedscene", $sceneName);
                }
            }
        }
        $sceneItem->setAttribute("name", $sceneName);
    }


    //初始场景
    if ($welcomeFlag) {
        $actionList = $tourDom->getElementsByTagName("action");
        $actionItem = $actionList->item(0);
        $actionItem->nodeValue =
            "if(startscene === null OR !scene[get(startscene)], 
            copy(startscene,scene[" . $sceneIndex . "].name); );
            loadscene(get(startscene), null, MERGE);if(startactions !== null, startactions() );js('onready(" . $sceneIndex . ")');";
    }

    if ($sceneIndex != $scene_index) continue;


    if ($fov != null) {
        $viewList = $sceneItem->getElementsByTagName("view");
        $viewItem = $viewList->item(0);
        $viewItem->setAttribute("fov", $value->fov);
        $initH = $value->initH;
        $initV = $value->initV;
        if ($initH) $viewItem->setAttribute("hlookat", $initH);
        if ($initV) $viewItem->setAttribute("vlookat", $initV);
    }

    $flag = $_POST["isAddHotSpot"];

    if ($flag == "false") {
        continue;
    }

    $hotSpotsList = $sceneItem->getElementsByTagName("hotspot");
    $layerList = $sceneItem->getElementsByTagName("layer");//
    while ($hotSpotsList->length != 0) {
        $sceneItem->removeChild($hotSpotsList->item(0));
    }
    while ($layerList->length != 0) {
        $sceneItem->removeChild($layerList->item(0));
    }

    if ($hotSpots != null) {
        foreach ($hotSpots as $key => $value) {
            $tempName = $value->name;
            $oldTitle = $value->title;
            $size = intval(utf8_strlen($oldTitle) / 8);
            $mod = utf8_strlen($oldTitle) % 8;
            $newTitle = "";
            for ($i = 0; $i < $size; $i++) {
                if ($newTitle != "") {
                    $newTitle = $newTitle . "[br]";
                }
                $newTitle = $newTitle . mb_substr($oldTitle, $i * 8, 8, "utf-8");
            }

            if($newTitle != ""){
                $newTitle = $newTitle . "[br]";
            }

            if ($mod != 0) {
                $newTitle = $newTitle . mb_substr($oldTitle, $size * 8, $mod, "utf-8");
            }

            $node = $tourDom->createElement("hotspot");
            $node->setAttribute("ath", $value->ath);
            $node->setAttribute("atv", $value->atv);
            $node->setAttribute("linkedscene", $value->linkedscene);
            $node->setAttribute("style", $value->style);
            $node->setAttribute("dive", $value->dive);
            $node->setAttribute("name", $tempName);
            $sceneItem->appendChild($node);
            $player = $tourDom->createElement("layer");
            $clyaer = $tourDom->createElement("layer");
            $player->setAttribute("name", $tempName . "_1");
            $player->setAttribute("parent", "hotspot[" . $tempName . "]");
            $player->setAttribute("width", "200");
            $player->setAttribute("height", "200");
            $player->setAttribute("maskchildren", "true");
            $player->setAttribute("scalechildren", "false");
            $player->setAttribute("vcenter", "true");
            $player->setAttribute("visible", "true");
            $player->setAttribute("type", "container");
            $player->setAttribute("align", "centerbottom");
            $player->setAttribute("bgcolor", "0x1eb98f");
            $player->setAttribute("y", "-1");
            $player->setAttribute("origin", "cursor");
            $player->setAttribute("edge", "centertop");
            $player->setAttribute("textalign", "center");
            $player->setAttribute("padding", "0");
            $player->setAttribute("roundedge", "8");
            $clyaer->setAttribute("name", $tempName . "_2");
            $clyaer->setAttribute("html", $newTitle);
            $clyaer->setAttribute("backgroundalpha", "0");
            $clyaer->setAttribute("visible", "true");
            $clyaer->setAttribute("type", "container");
            $clyaer->setAttribute("css", "text-align:center; color:#FFFFFF; font-family:tahoma; font-weight:normal; font-size:25px;");
            $clyaer->setAttribute("origin", "cursor");
            $clyaer->setAttribute("url", "%SWFPATH%/plugins/textfield.swf");
            $player->appendChild($clyaer);
            $sceneItem->appendChild($player);
        }
    }
}


$tourDom->save($xmlfile);
$result['status'] = "success";
echo json_encode($result);