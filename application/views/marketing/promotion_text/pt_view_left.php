<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script language="javascript">
        <
        !--
            function changeRightFrame(platform_type, lang_id, platform_id, sku) {
                parent.changeRightFrame(platform_type, lang_id, platform_id, sku);
            }
        function changeRightFrame2(platform_type) {
            parent.changeRightFrame2(platform_type);
        }
        function changeRightFrame3(platform_type, lang_id) {
            parent.changeRightFrame3(platform_type, lang_id);
        }
        function changeRightFrame4(platform_type, lang_id, platform_id) {
            parent.changeRightFrame4(platform_type, lang_id, platform_id);
        }
    </script>
    <script type="text/javascript">
        function toggleDetail(id) {
            var detail = id + '_detail';

            if (document.getElementById(id).checked) {
                document.getElementById(detail).style.display = '';
                if (id == 'type') {
                    document.getElementById('language').disabled = false;
                    document.getElementById('platform').disable = true;
                    document.getElementById('sku1').disable = true;
                }
                if (id == 'language') {
                    document.getElementById('type').disabled = false;
                    document.getElementById('platform').disabled = false;
                    document.getElementById('sku1').disable = true;
                }
                if (id == 'platform') {
                    document.getElementById('type').disable = false;
                    document.getElementById('language').disable = false;
                    document.getElementById('sku1').disable = false;
                }
                if (id == 'sku1') {
                    document.getElementById('note').style.display = '';
                }
            }
            else {
                document.getElementById(detail).style.display = 'none';
                if (id == 'type') {
                    document.getElementById('language').disabled = true;
                    document.getElementById('language').checked = false;
                    document.getElementById('language_detail').style.display = 'none';
                    document.getElementById('platform').disabled = true;
                    document.getElementById('platform').checked = false;
                    document.getElementById('platform_detail').style.display = 'none';
                    document.getElementById('sku1').disabled = true;
                    document.getElementById('sku1').checked = false;
                    document.getElementById('sku1_detail').style.display = 'none';
                    document.getElementById('note').style.display = 'none';
                }
                if (id == 'language') {
                    document.getElementById('platform').disabled = true;
                    document.getElementById('platform').checked = false;
                    document.getElementById('platform_detail').style.display = 'none';
                    document.getElementById('sku1').disabled = true;
                    document.getElementById('sku1').checked = false;
                    document.getElementById('sku1_detail').style.display = 'none';
                    document.getElementById('note').style.display = 'none';
                }
                if (id == 'platform') {
                    document.getElementById('sku1').disabled = true;
                    document.getElementById('sku1').checked = false;
                    document.getElementById('sku1_detail').style.display = 'none';
                    document.getElementById('note').style.display = 'none';
                }
                if (id == 'sku1') {
                    document.getElementById('note').style.display = 'none';
                }
            }
        }

        function updateTypeOnChange() {
            if (document.getElementById('s_type').value != "") {
                document.getElementById("language").checked = true;
                document.getElementById("language_detail").style.display = "";
            }
            else {
                document.getElementById("language").checked = false;
                document.getElementById("language_detail").style.display = "none";
            }
        }

        function updateLanguageOnChange() {
            if (document.getElementById('s_lang').value != "") {
                document.getElementById("platform").checked = true;
                document.getElementById("platform_detail").style.display = "";
            }
            else {
                document.getElementById("platform").checked = false;
                document.getElementById("platform_detail").style.display = "none";
                document.getElementById("note").style.display = "none";
            }
        }

        function updatePlatformOnChange() {
            if (document.getElementById('s_plat').value != "") {
                document.getElementById("sku1").checked = true;
                document.getElementById("sku1_detail").style.display = "";
                document.getElementById("note").style.display = "";
            }
            else {
                document.getElementById("sku1").checked = false;
                document.getElementById("sku1_detail").style.display = "none";
                document.getElementById("note").style.display = "none";
            }
        }
    </script>
</head>
<body class="frame_left" style="width:auto;">
<div id="main" style="width:auto;">
    <form name="fm" method="get" action="<?= base_url() ?>marketing/promotion_text/view_left/">
        <input type="hidden" name="platform_type" value="<?= $platform_type; ?>">
        <input type="hidden" name="lang_id" value="<?= $lang_id; ?>">
        <input type="hidden" name="platform_id" value="<?= $platform_id; ?>">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <col width="5">
            <col width="40">
            <col width="120">
            <col width="35">
            <tr>
                <td class="value" colspan="4" align="left" style="padding-left:10px">Platform Type <input
                        type="checkbox" name="type" id="type" checked onclick="toggleDetail(this.id);"></td>
            </tr>
            <tr id='type_detail'>
                <td class="value" colspan="4" align="center">
                    <select id='s_type'
                            onChange='updateTypeOnChange();changeRightFrame2(this.value);gotoPage("<?= base_url() . "marketing/promotion_text/view_left/" ?>",this.value);'
                            style="width:150px">
                        <option value=""> -- Please Select --</option>
                        <option
                            value="all" <?= $platform_type == "all" ? "SELECTED" : "" ?>><?= $lang['all'] ?></option>
                        <option
                            value="skype" <?= $platform_type == "skype" ? "SELECTED" : "" ?>><?= $lang['skype'] ?></option>
                        <option
                            value="website" <?= $platform_type == "website" ? "SELECTED" : "" ?>><?= $lang['website'] ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="value" colspan="4" align="left" style="padding-left:10px">Language <input type="checkbox"
                                                                                                     name="type"
                                                                                                     id="language" <?= $platform_type ? "checked" : "" ?>
                                                                                                     onclick="toggleDetail(this.id);">
                </td>
            </tr>
            <tr id='language_detail' <?php  if ($platform_type){ ?>style="display:''"
                <?php  }else{ ?>style="display:none"<?php  } ?>>
                <td class="value" colspan="4" align="center">
                    <select id="s_lang"
                            onChange='updateLanguageOnChange();changeRightFrame3(document.getElementById("s_type").value,this.value);gotoPage("<?= base_url() . "marketing/promotion_text/view_left/$platform_type/" ?>",this.value);'
                            style="width:150px">
                        <option value=""> -- Please Select --</option>
                        <?php
                        foreach ($language_list as $obj) {
                            ?>
                            <option value="<?= $obj->get_id() ?>"<?= ($obj->get_id() == $lang_id ? "SELECTED" : "") ?>><?= $obj->get_name() ?></option><?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="value" colspan="4" align="left" style="padding-left:10px">Platform <input type="checkbox"
                                                                                                     name="type"
                                                                                                     id="platform" <?= $lang_id ? "checked" : "" ?>
                                                                                                     onclick="toggleDetail(this.id);">
                </td>
            </tr>
            <tr id='platform_detail' <?php  if ($lang_id){ ?>style="display:''" <?php  }else{ ?>style="display:none"<?php  } ?>>
                <td class="value" colspan="4" align="center">
                    <select id="s_plat"
                            onChange='updatePlatformOnChange();changeRightFrame4(document.getElementById("s_type").value,document.getElementById("s_lang").value,this.value);gotoPage("<?= base_url() . "marketing/promotion_text/view_left/$platform_type/$lang_id/" ?>",this.value)'
                            style="width:150px">
                        <option value=""> -- Please Select --</option>
                        <option value="all" <?= $platform_id == "all" ? "SELECTED" : "" ?>><?= $lang['all'] ?></option>
                        <?php
                        foreach ($platform_list as $obj) {
                            ?>
                            <option value="<?= $obj->get_selling_platform_id() ?>"<?= ($obj->get_selling_platform_id() == $platform_id ? "SELECTED" : "") ?>><?= $obj->get_platform_name() ?></option><?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="value" colspan="4" align="left" style="padding-left:10px">SKU <input type="checkbox"
                                                                                                name="sku1"
                                                                                                id="sku1" <?= $platform_id ? "checked" : "" ?>
                                                                                                onclick="toggleDetail(this.id);">
                </td>
            </tr>
        </table>
<span id='sku1_detail' <?php  if ($platform_id){ ?>style="display:''" <?php  }else{ ?>style="display:none"<?php  } ?>>
<table>
    <tr>
        <td>&nbsp;</td>
        <td colspan="3" height="40" valign="middle" align="left"><b
                style="font-size:14px; color:#000000;"><?= $lang["product_search"] ?></b></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td align="left"><?= $lang["sku"] ?></td>
        <td><input type="text" name="sku" value="<?= $this->input->get('sku') ?>" class="input"></td>
        <td rowspan="2"><input type="submit"
                               onclick='gotoPage("<?= base_url() . "marketing/promotion_text/view_left/$platform_type/$lang_id/$platform_id" ?>")'
                               style="background: rgb(204, 204, 204) url('<?= base_url() ?>/images/find.gif') no-repeat scroll center center; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; width: 30px; height: 25px;"
                               class="search_button" value=""/></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td align="left"><?= $lang["name"] ?></td>
        <td><input type="text" name="name" value="<?= $this->input->get('name') ?>" class="input"></td>
    </tr>
</table>
    <?php
    if ($search) {
        ?>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="50">
            <col>
            <tr class="header">
                <td><a href="#"
                       onClick="SortCol(document.fm, 'sku', '<?= $xsort["sku"] ?>')"><?= $lang["sku"] ?> <?= $sortimg["sku"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'name', '<?= $xsort["name"] ?>')"><?= $lang["name"] ?> <?= $sortimg["name"] ?></a>
                </td>
            </tr>
            <?php
            $i = 0;
            if ($objlist) {
                foreach ($objlist as $obj) {
                    ?>
                    <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')"
                        onClick="changeRightFrame(document.getElementById('s_type').value,document.getElementById('s_lang').value,document.getElementById('s_plat').value,'<?= $obj->get_sku() ?>');">
                        <td nowrap style="white-space:nowrap;"><?= $obj->get_sku() ?></td>
                        <td><?= $obj->get_name() ?></td>
                    </tr>
                    <?php
                    $i++;
                }
            }
            ?>
        </table>
        <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
        <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
        <?= $this->pagination_service->create_links_with_style() ?>
    <?php
    }
    ?>
</span>
        </table>
    </form>
    <br><br>
<span id='note' <?php  if ($platform_id){ ?>style="display:''" <?php  }else{ ?>style="display:none"<?php  } ?>>
<div style="padding-top:10px; padding-left:5px; width:100%; text-align:left;">
    <?= $lang["notes"] ?>
</div>
</span>
</div>
</body>
</html>
