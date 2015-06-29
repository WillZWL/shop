<?php
for ($i = 1; $i < 8; $i++) {
    $obj = $image_list[$i];

    if (!empty($obj)) {
        ${"content" . $i} = "<a href=\"javascript:changeUpdate('" . $i . "');\"><img src=\"" . base_url() . "images/adbanner/preview/blank_1_" . $i . ".gif\" border=\"0\" height=\"" . $this->config->item('banner_height_1_".$i."') . "\" width=\"" . $this->config->item('banner_width_1_".$i."') . "\"></a>";
        if ($obj->get_image_file() != "" && file_exists($this->config->item('banner_local_path') . "preview/" . $obj->get_image_file())) {
            ${"content" . $i} = "<a href=\"javascript:changeUpdate('" . $i . "');\"><img src=\"" . base_url() . "images/adbanner/preview/" . $obj->get_image_file() . "?" . $obj->get_modify_on() . "\" border=\"0\" height=\"" . $this->config->item('banner_height_1_' . $i) . "\" width=\"" . $this->config->item('banner_width_1_' . $i) . "\"></a>";
        }
        if ($obj->get_flash_file() != "" && file_exists($this->config->item('banner_local_path') . "preview/" . $obj->get_image_file())) {
            ${"content" . $i} = "<button onclick=\"javascript:changeUpdate('" . $i . "');\"><object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-44455354000" . $i . "\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"" . $this->config->item('banner_width_1_' . $i) . "\" height=\"" . $this->config->item('banner_height_1_' . $i) . "\"><param name=\"movie\" value=\"" . $obj->get_flash_file() . "?" . $obj->get_modify_on() . "\"><param name=\"quality\" value=\"high\"><param name=\"wmode\" value=\"transparent\"><embed src=\"" . base_url() . "images/adbanner/preview/" . $obj->get_flash_file() . "\" width=\"" . $this->config->item('banner_width_1_' . $i) . "\" height=\"" . $this->config->item('banner_height_1_' . $i) . "\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" wmode=\"transparent\"></embed></object></button>";
        }
    } else {
        ${"content" . $i} = "<a href=\"javascript:changeUpdate('" . $i . "');\"><img src=\"" . base_url() . "images/adbanner/preview/blank_1_" . $i . ".gif\" border=\"0\" height=\"" . $this->config->item('banner_height_1_' . $i) . "\" width=\"" . $this->config->item('banner_width_1_' . $i) . "\"></a>";
    }
}
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center"
        "style><?= $lang["top_page"] ?></td>
    </tr>
    <tr>
        <td align="center"><?= $content1 ?></td>
    </tr>
    <tr>
        <td height="30">&nbsp;&nbsp;</td>
    </tr>
    <td align="center">
        <table border="0" cellpadding="0" cellspacing="0" width="940" height="120">
            <tr>
                <td height="120" width="150" align="center"><?= $content2 ?></td>
                <td width="8" align="center"></td>
                <td width="150" align="center"><?= $content3 ?></td>
                <td width="8" align="center"></td>
                <td width="308" align="center" height="120"<?= $content4 ?>><img border="0"
                                                                                 src="<?= base_url() ?>images/blank.gif"
                                                                                 height="8" width="306"><?= $content5 ?>
                </td>
                <td width="8" align="center"></td>
                <td width="150" align="center"><?= $content6 ?></td>
                <td width="8" align="center"></td>
                <td width="150" align="center"><?= $content7 ?></td>
            </tr>
        </table>
    </td>
</table>