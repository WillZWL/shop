<?php
if (count($catlist)) {
    $content .= '<div id="na' . $this->input->get('id') . '">';
    foreach ($catlist as $obj) {
        if ($obj->getCountRow() > 0) {
            $link = "<span id=\"a" . $obj->getId() . "\"><a href=\"javascript:showNextLayer('17','" . $obj->getId() . "','" . ($level + 1) . "','c" . $obj->getId() . "')\">+</a></span>&nbsp;&nbsp;";
        } else {
            $link = "&nbsp;";
        }

        $name = "<a href=\"" . base_url() . "marketing/display_banner/view/" . $banner_obj->getId() . "?catid=" . $obj->getId() . "\" target=\"cview\" class=\"vlink\">" . $obj->getName() . "</a>";

        if (!$obj->getStatus()) {
            $style = "inactive";
        } else {
            if ($obj->getPvCnt()) {
                $style = "pv";
            }
            if ($obj->getPbCnt()) {
                $style = "pb";
            }
        }

        $content .= "<div id=\"c" . $obj->getId() . "\" class=\"level" . $level . " " . $style . "\">" . $link . $name . "</div>";
    }
    $content .= '</div>';
    echo $content;
}
?>