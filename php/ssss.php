<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/5/1
 * Time: 9:55 PM
 */

$result = array();
$result['status'] = "success";
$data = json_decode($_POST["tour"]);
$title = $_POST["title"];
$xmlfile = '../data/' . $title . '/vtour/tour.xml';
$dom = new DOMDocument();
$dom->load($xmlfile);
$krpano = $dom->getElementsByTagName("krpano");
foreach ($data as $key => $value) {
    $index = $value->index;
    $name = $value->name;
    $childNodes = $krpano->item(0)->childNodes;
    foreach ($childNodes as $node) {
        if ($node->nodeName == "#text" || $node->nodeName == "#comment") continue;
        $nameAttributeValue = $node->getAttribute("name");
        if ($nameAttributeValue == $name && $index == $_POST["page"]) {
            update($value, $node, $dom);
        }
    }
}


function update($value, $item, $dom)
{
    $hotSpots = $value->hotSpots;
    $fov = $value->fov;

    if ($fov != null) {
        $tempViewNode = $item->getElementsByTagName("view");
        foreach ($tempViewNode as $tempView) {
            $tempView->setAttribute("fov", $value->fov);
            $tempView->setAttribute("hlookat", $value->initH);
            $tempView->setAttribute("vlookat", $value->initV);
        }
    }

    if (!$_POST["isAddHotSpot"]) return;

    removeHotSpot($item);

    if ($hotSpots != null) {
        foreach ($hotSpots as $key => $value) {
            $node = $dom->createElement("hotspot");
            $node->setAttribute("ath", $value->ath);
            $node->setAttribute("atv", $value->atv);
            $node->setAttribute("linkedscene", $value->linkedscene);
            $node->setAttribute("style", $value->style);
            $node->setAttribute("dive", $value->dive);
            $node->setAttribute("name", $value->name);
            $item->appendChild($node);
        }
    }
}


function removeHotSpot($item)
{
    $tempHotSpotList = $item->getElementsByTagName("hotspot");
    if ($tempHotSpotList->length == 0) return ture;
    foreach ($tempHotSpotList as $tempHotSpot) {
        $item->removeChild($tempHotSpot);
    }
    removeHotSpot($item);
}

$dom->save($xmlfile);
echo json_encode($result);