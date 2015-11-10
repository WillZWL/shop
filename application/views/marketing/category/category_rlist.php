<?php
if ($total) {
    $nl = 0;
    $content = "<div id='na" . $this->input->get('id') . "' style='display:block; " . ($thisobj->getLevel() >= 3 && $total > 14 ? "height:200px; width:100%; overflow-y:scroll;" : "") . "'>";
    $nl = $thisobj->getLevel() + 1;
    if ($thisobj->getLevel() < 3) {
        foreach ($category_list as $obj) {

            $content .= "<div class='layer" . $nl . "' id='c" . $obj->getId() . "'>\n";
            $content .= "<span id='a" . $obj->getId() . "' class='aspan'><a href='#' onClick=\"showNextLayer('" . $obj->getId() . "','" . $obj->getLevel() . "','c" . $obj->getId() . "');\">+</a></span>";
            $content .= $obj->getName();
            $content .= "</div>\n";
        }
        $content .= "</div>";
    } else {
        foreach ($category_list as $obj) {
            $content .= "<div class='layerp'>" . $obj->getSku() . " - " . $obj->getName() . "</div>\n";
        }
    }
} else {
    $content = "<span id='na" . $this->input->get('id') . "'></span>";
}

echo $content;
?>