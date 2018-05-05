<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/2
 * Time: 10:26 AM
 */

$result = array();
$result['status'] = "error";
$data = json_decode($_POST["tour"]);
$title = $_POST["title"];

$xmlfile = '../data/' . $title . '/vtour/tour.xml';
$tourDom = new DOMDocument();
$tourDom->load($xmlfile);
$sceneList = $tourDom->getElementsByTagName("scene");

foreach ($data as $key => $value) {
    $welcomeFlag = $value->welcomeFlag;
    if ($welcomeFlag == null) continue;
    $sceneIndex = $value->index;
    $sceneName = $value->name;
    $hotSpots = $value->hotSpots;
    $fov = $value->fov;

//    if ($fov == null && $hotSpots == null) continue;


    if (!is_int($sceneIndex)) {
        return json_encode($result);
    }
    $sceneItem = $sceneList->item($sceneIndex);

    if ($fov != null) {
        $viewList = $sceneItem->getElementsByTagName("view");
        $viewItem = $viewList->item(0);
        $viewItem->setAttribute("hlookat", $value->fov);
        $viewItem->setAttribute("hlookat", $value->initH);
        $viewItem->setAttribute("vlookat", $value->initV);
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