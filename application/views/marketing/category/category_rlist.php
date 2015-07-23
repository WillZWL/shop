<?php
if ($total) {
    $content = "<div id='na" . $this->input->get('id') . "' style='display:block; " . ($thisobj->get_level() >= 3 && $total > 14 ? "height:200px; width:100%; overflow-y:scroll;" : "") . "'>";
    $nl = $thisobj->get_level() + 1;
    if ($thisobj->get_level() < 3) {
        foreach ($category_list as $obj) {

            $content .= "<div class='layer" . $nl . "' id='c" . $obj->get_id() . "'>\n";
            $content .= "<span id='a" . $obj->get_id() . "' class='aspan'><a href='#' onClick=\"showNextLayer('" . $obj->get_id() . "','" . $obj->get_level() . "','c" . $obj->get_id() . "');\">+</a></span>";
            $content .= $obj->get_name();
            $content .= "</div>\n";
        }
        $content .= "</div>";
    } else {
        foreach ($category_list as $obj) {
            $content .= "<div class='layerp'>" . $obj->get_sku() . " - " . $obj->get_name() . "</div>\n";
        }
    }
} else {
    $content = "<span id='na" . $this->input->get('id') . "'></span>";
}

echo $content;
?>