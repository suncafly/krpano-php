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
    while ($hotSpotsList->length != 0) {
        $sceneItem->removeChild($hotSpotsList->item(0));
    }


    if ($hotSpots != null) {
        foreach ($hotSpots as $key => $value) {
            $node = $tourDom->createElement("hotspot");
            $node->setAttribute("ath", $value->ath);
            $node->setAttribute("atv", $value->atv);
            $node->setAttribute("linkedscene", $value->linkedscene);
            $node->setAttribute("style", $value->style);
            $node->setAttribute("dive", $value->dive);
            $node->setAttribute("name", $value->name);
            $sceneItem->appendChild($node);
        }
    }
}

$tourDom->save($xmlfile);
$result['status'] = "success";
echo json_encode($result);