<?php
if (count($objlist)) {
    $content = "";
    $content .= '<div id="na' . $this->input->get('id') . '">';
    foreach ($objlist as $obj) {
        if ($obj->getTotal() > 0) {
            $link = "<span id=\"a" . $obj->getId() . "\"><a href=\"javascript:showNextLayer('" . $obj->getId() . "','" . ($level + 1) . "','c" . $obj->getId() . "')\">+</a></span>&nbsp;&nbsp;";
        } else {
            $link = "&nbsp;";
        }

        $name = "<a href=\"" . base_url() . "marketing/category/view/" . $obj->getId() . "\" target=\"right\" class=\"vlink\">" . $obj->getName() . "</a>";

        $content .= "<div id=\"c" . $obj->getId() . "\" class=\"level" . $level . "\">" . $link . $name . "</div>";
    }
    $content .= '</div>';
    echo $content;
}
?>