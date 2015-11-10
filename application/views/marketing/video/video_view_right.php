<?php
$v_src_arr = array("V" => "Vzaar", "Y" => "YouTube");
$v_type_arr = array("G" => $lang["guide"], "R" => $lang["review"]);
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script language="javascript">
    </script>
</head>
<body topmargin="0" leftmargin="0" class="frame_left" style="width:auto;">
<div id="main" style="width:auto;">
    <?= $notice["img"] ?>
    <form name="fm" method="post" action="<?= $_SERVER["PHP_SELF"] . "/" . $catid ?>">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td><br><?= $lang["total_num"] ?> <?= $sku ?> : <?= $num_rows ?><br></td>
            </tr>
        </table>
        <br>
        <table border="0" cellpadding="0" cellspacing="1" width="100%" class="tb_list">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="20%">
            <tr align="center" class="header">
                <td><b><?= $lang["country_id"] ?></b></td>
                <td><b><?= $lang["language"] ?></b></td>
                <td><b><?= $lang["video_type"] ?></b></td>
                <td><b><?= $lang["video_src"] ?></b></td>
                <td><b><?= $lang["ref_id"] ?></b></td>
                <td><b><?= $lang["description"] ?></b></td>
                <td><b><?= "" ?></b></td>
            </tr>
            <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                onMouseOut="RemoveClassName(this, 'highlight')">
                <td></td>
                <td>
                    <select name="add_lang" style="width:95%">
                        <?php
                        foreach ($lang_list as $obj) {
                            ?>
                            <option
                                value="<?= $obj->get_id() ?>" <?= ($obj->get_id() == $pbv->get_language_id()) ? "selected" : "" ?>><?= $obj->get_id() ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select name="add_type" style="width:95%">
                        <option value="G"><?= $lang["guide"] ?></option>
                        <option value="R"><?= $lang["review"] ?></option>
                    </select>
                </td>
                <td>
                    <select name="add_src" style="width:95%">
                        <option value="Y">YouTube</option>
                        <option value="V">Vzaar</option>
                    </select>
                </td>
                <td align="left"><input name="add_ref_id" class="input" type="text" value="" id="ref_id"></td>
                <td align="left"><input name="add_desc" class="input" type="text" value="" id="description"></td>
                <td align="center"><input type="submit" name="add" value="<?= $lang["add_video"] ?>"></td>
            </tr>
            <?php
            $i = 0;
            if (!empty($obj_list)) {
                foreach ($obj_list as $obj) {
                    $is_edit = ($cmd == "edit" && $id == $obj->get_id());
                    ?>
                    <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')" <?php if (!($is_edit)){
                    ?>onClick="Redirect('<?= site_url('marketing/video/view_right/' . $obj->get_sku() . '/' . $lang_id . '/' . $country . '/' . $obj->get_id()) ?>/?<?= $_SERVER['QUERY_STRING'] ?>')"<?php
                    }?>>
                        <?php
                        if ($is_edit) {
                            ?>
                        <form name="fm_edit"
                              action="<?= base_url() ?>marketing/video/view_right/<?= $obj->get_sku() ?>/<?= $lang_id ?>/<?= $country ?>/<?= $obj->get_id() ?>/?<?= $_SERVER['QUERY_STRING'] ?>"
                              method="post" onSubmit="return CheckForm(this)">
                            <input type="hidden" name="posted" value="1">
                            <input type="hidden" name="cmd" value="edit">
                            <input type="hidden" name="id" value="<?= $obj->get_id() ?>">
                            <?php
                            if ($this->input->post("posted")) {
                                ?>
                                <td>
                                </td>
                                <td>
                                    <select name="lang" style="width:95%">
                                        <?php
                                        foreach ($lang_list as $lang_obj) {
                                            ?>
                                            <option
                                                value="<?= $lang_obj->get_id() ?>" <?= ($lang_obj->get_id() == $pbv->get_language_id()) ? "selected" : "" ?>><?= $lang_obj->get_id() ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="type" class="input" style="width: 95%">
                                        <option
                                            value="G" <?= $this->input->post("type") == "G" ? "selected" : "" ?>><?= $lang["guide"] ?></option>
                                        <option
                                            value="R" <?= $this->input->post("type") == "R" ? "selected" : "" ?>><?= $lang["review"] ?></option>
                                    </select>
                                </td>
                                <td>
                                    <select name="src" class="input" style="width: 95%">
                                        <option value="Y" <?= $this->input->post("src") == "Y" ? "selected" : "" ?>>
                                            YouTube
                                        </option>
                                        <option value="V" <?= $this->input->post("src") == "V" ? "selected" : "" ?>>
                                            Vzaar
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <input name="ref_id" class="input" value="<?= $this->input->post("ref_id") ?>"
                                           notEmpty maxLen=64>
                                    <?php
                                    if ($this->input->post("src") == "Y") {
                                        ?>
                                        <object width="198" height="122">
                                            <param name="movie"
                                                   value="http://www.youtube.com/v/<?= $this->input->post("ref_id") ?>&amp;hl=en_US&amp;fs=1"></param>
                                            <param name="allowFullScreen" value="true"></param>
                                            <param name="allowscriptaccess" value="always"></param>
                                            <embed
                                                src="http://www.youtube.com/v/<?= $this->input->post("ref_id") ?>&amp;hl=en_US&amp;fs=1"
                                                type="application/x-shockwave-flash" allowscriptaccess="always"
                                                allowfullscreen="true" width="198" height="122"></embed>
                                        </object>
                                    <?php
                                    } elseif ($this->input->post("src") == "V") {
                                        ?>
                                        <!-- VZAAR START -->
                                        <div class="vzaar_media_player">
                                            <object id="video" width="198" height="122"
                                                    type="application/x-shockwave-flash"
                                                    data="http://view.vzaar.com/<?= $this->input->post("ref_id") ?>.flashplayer">
                                                <param name="movie"
                                                       value="http://view.vzaar.com/<?= $this->input->post("ref_id") ?>.flashplayer"/>
                                                <param name="allowScriptAccess" value="always"/>
                                                <param name="allowFullScreen" value="true"/>
                                                <param name="wmode" value="transparent"/>
                                                <param name="flashvars" value="autoplay=false"/>
                                                <embed
                                                    src="http://view.vzaar.com/<?= $this->input->post("ref_id") ?>.flashplayer"
                                                    type="application/x-shockwave-flash" wmode="transparent" width="198"
                                                    height="122" allowScriptAccess="always" allowFullScreen="true"
                                                    flashvars="autoplay=true"></embed>
                                                <video width="198" height="122"
                                                       src="http://view.vzaar.com/<?= $this->input->post("ref_id") ?>.mobile"
                                                       poster="http://view.vzaar.com/<?= $this->input->post("ref_id") ?>.image"
                                                       controls onclick="this.play();"></video>
                                            </object>
                                        </div>
                                        <!-- VZAAR END -->
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td><input name="description" class="input"
                                           value="<?= $this->input->post("description") ?>" notEmpty maxLen=255></td>
                            <?php
                            } else {
                                ?>
                                <td><?= $obj->get_country_id() ?></td>
                                <td>
                                    <select name="lang" style="width:95%">
                                        <?php
                                        foreach ($lang_list as $lang_obj) {
                                            ?>
                                            <option
                                                value="<?= $lang_obj->get_id() ?>" <?= ($lang_obj->get_id() == $pbv->get_language_id()) ? "selected" : "" ?>><?= $lang_obj->get_id() ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="type" class="input" style="width: 95%">
                                        <option
                                            value="G" <?= $obj->get_type() == "G" ? "selected" : "" ?>><?= $lang["guide"] ?></option>
                                        <option
                                            value="R" <?= $obj->get_type() == "R" ? "selected" : "" ?>><?= $lang["review"] ?></option>
                                    </select>
                                </td>
                                <td>
                                    <select name="src" class="input" style="width: 95%">
                                        <option value="Y" <?= $obj->get_src() == "Y" ? "selected" : "" ?>>YouTube
                                        </option>
                                        <option value="V" <?= $obj->get_src() == "V" ? "selected" : "" ?>>Vzaar</option>
                                    </select>
                                </td>

                                <td>
                                    <input name="ref_id" class="input" value="<?= $obj->get_ref_id() ?>" notEmpty
                                           maxLen=64>
                                    <?php
                                    if ($obj->get_src() == "Y") {
                                        ?>
                                        <object width="198" height="122">
                                            <param name="movie"
                                                   value="http://www.youtube.com/v/<?= $obj->get_ref_id() ?>&amp;hl=en_US&amp;fs=1"></param>
                                            <param name="allowFullScreen" value="true"></param>
                                            <param name="allowscriptaccess" value="always"></param>
                                            <embed
                                                src="http://www.youtube.com/v/<?= $obj->get_ref_id() ?>&amp;hl=en_US&amp;fs=1"
                                                type="application/x-shockwave-flash" allowscriptaccess="always"
                                                allowfullscreen="true" width="198" height="122"></embed>
                                        </object>
                                    <?php
                                    } elseif ($obj->get_src() == "V") {
                                        ?>
                                        <!-- VZAAR START -->
                                        <div class="vzaar_media_player">
                                            <object id="video" width="198" height="122"
                                                    type="application/x-shockwave-flash"
                                                    data="http://view.vzaar.com/<?= $obj->get_ref_id() ?>.flashplayer">
                                                <param name="movie"
                                                       value="http://view.vzaar.com/<?= $obj->get_ref_id() ?>.flashplayer"/>
                                                <param name="allowScriptAccess" value="always"/>
                                                <param name="allowFullScreen" value="true"/>
                                                <param name="wmode" value="transparent"/>
                                                <param name="flashvars" value="autoplay=false"/>
                                                <embed src="http://view.vzaar.com/<?= $obj->get_ref_id() ?>.flashplayer"
                                                       type="application/x-shockwave-flash" wmode="transparent"
                                                       width="198" height="122" allowScriptAccess="always"
                                                       allowFullScreen="true" flashvars="autoplay=true"></embed>
                                                <video width="198" height="122"
                                                       src="http://view.vzaar.com/<?= $obj->get_ref_id() ?>.mobile"
                                                       poster="http://view.vzaar.com/<?= $obj->get_ref_id() ?>.image"
                                                       controls onclick="this.play();"></video>
                                            </object>
                                        </div>
                                        <!-- VZAAR END -->
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td><input name="description" class="input" value="<?= $obj->get_description() ?>"
                                           notEmpty maxLen=255></td>
                            <?php
                            }
                            if (!($this->input->post('remove') && ($this->input->post('id') == $obj->get_id()))) {
                                ?>
                                <td align="center">
                                    <input type="submit" name="update" value="<?= $lang["update"] ?>">
                                    <input type="submit" name="remove" value="<?= $lang["remove"] ?>">
                                    <input type="button" value="<?= $lang["back"] ?>"
                                           onClick="Redirect('<?= site_url('marketing/video/view_right/' . $obj->get_sku() . '/' . $country . '/') ?>/?<?= $_SERVER['QUERY_STRING'] ?>')">
                                </td>
                                </form>
                            <?php
                            }
                        } else {
                            ?>
                            <td><?= $obj->get_country_id() ?></td>
                            <td><?= $obj->get_lang_id() ?></td>
                            <td><?= $v_type_arr[$obj->get_type()] ?></td>
                            <td><?= $v_src_arr[$obj->get_src()] ?></td>
                            <td>
                                <?= $obj->get_ref_id() ?><br>
                                <?php
                                if ($obj->get_src() == "Y") {
                                    ?>
                                    <object width="198" height="122">
                                        <param name="movie"
                                               value="http://www.youtube.com/v/<?= $obj->get_ref_id() ?>&amp;hl=en_US&amp;fs=1"></param>
                                        <param name="allowFullScreen" value="true"></param>
                                        <param name="allowscriptaccess" value="always"></param>
                                        <embed
                                            src="http://www.youtube.com/v/<?= $obj->get_ref_id() ?>&amp;hl=en_US&amp;fs=1"
                                            type="application/x-shockwave-flash" allowscriptaccess="always"
                                            allowfullscreen="true" width="198" height="122"></embed>
                                    </object>
                                <?php
                                } elseif ($obj->get_src() == "V") {
                                    ?>
                                    <!-- VZAAR START -->
                                    <div class="vzaar_media_player">
                                        <object id="video" width="198" height="122" type="application/x-shockwave-flash"
                                                data="http://view.vzaar.com/<?= $obj->get_ref_id() ?>.flashplayer">
                                            <param name="movie"
                                                   value="http://view.vzaar.com/<?= $obj->get_ref_id() ?>.flashplayer"/>
                                            <param name="allowScriptAccess" value="always"/>
                                            <param name="allowFullScreen" value="true"/>
                                            <param name="wmode" value="transparent"/>
                                            <param name="flashvars" value="autoplay=false"/>
                                            <embed src="http://view.vzaar.com/<?= $obj->get_ref_id() ?>.flashplayer"
                                                   type="application/x-shockwave-flash" wmode="transparent" width="198"
                                                   height="122" allowScriptAccess="always" allowFullScreen="true"
                                                   flashvars="autoplay=true"></embed>
                                            <video width="198" height="122"
                                                   src="http://view.vzaar.com/<?= $obj->get_ref_id() ?>.mobile"
                                                   poster="http://view.vzaar.com/<?= $obj->get_ref_id() ?>.image"
                                                   controls onclick="this.play();"></video>
                                        </object>
                                    </div>
                                    <!-- VZAAR END -->
                                <?php
                                }
                                ?>
                            </td>
                            <td><?= $obj->get_description() ?></td>
                            <td></td>
                        <?php
                        }
                        ?>
                    </tr>
                    <?php
                    $i++;
                }
            }
            ?>
            </tr>
            <input type="hidden" name="posted" value="1">
    </form>
</div>
<?= $notice["js"] ?>
</body>
</html>