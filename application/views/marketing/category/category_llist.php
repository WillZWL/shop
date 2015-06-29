<?php
if (count($objlist)) {
    $content .= '<div id="na' . $this->input->get('id') . '">';
    foreach ($objlist as $obj) {
        if ($obj->get_total() > 0) {
            $link = "<span id=\"a" . $obj->get_id() . "\"><a href=\"javascript:showNextLayer('" . $obj->get_id() . "','" . ($level + 1) . "','c" . $obj->get_id() . "')\">+</a></span>&nbsp;&nbsp;";
        } else {
            $link = "&nbsp;";
        }

        $name = "<a href=\"" . base_url() . "marketing/category/view/" . $obj->get_id() . "\" target=\"right\" class=\"vlink\">" . $obj->get_name() . "</a>";

        $content .= "<div id=\"c" . $obj->get_id() . "\" class=\"level" . $level . "\">" . $link . $name . "</div>";
    }
    $content .= '</div>';
    echo $content;
}
?>