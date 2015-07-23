<?php
if (count($objlist)) {
    $content .= '<div id="na' . $this->input->get('id') . '">';
    foreach ($objlist as $obj) {
        if ($obj->get_count_row() > 0) {
            $link = "<span id=\"a" . $obj->get_id() . "\"><a href=\"javascript:showNextLayer('" . $obj->get_id() . "','" . ($level + 1) . "','c" . $obj->get_id() . "')\">+</a></span>&nbsp;&nbsp;";
        } else {
            $link = "&nbsp;";
        }

        $name = "<a href=\"" . base_url() . "marketing/banner/view/" . $obj->get_id() . "\" target=\"cview\" class=\"vlink\">" . $obj->get_name() . "</a>";

        if (!$obj->get_status()) {
            $style = "inactive";
        } else {
            if ($obj->get_pv_cnt()) {
                $style = "pv";
            }
            if ($obj->get_pb_cnt()) {
                $style = "pb";
            }
        }

        $content .= "<div id=\"c" . $obj->get_id() . "\" class=\"level" . $level . " " . $style . "\">" . $link . $name . "</div>";
    }
    $content .= '</div>';
    echo $content;
}
?>