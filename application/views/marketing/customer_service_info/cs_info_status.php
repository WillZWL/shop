<html>
<head>
    <meta http-equiv="Content-Language" content="en-gb">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= $lang["page_title"] ?></title>
    <meta http-equiv="imagetoolbar" content="no">
    <link rel="stylesheet" type="text/css" href="<?= base_url() . 'css/style.css' ?>">
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/js/control_mce.js"></script>
    <script>
        var a = new control_mce();
        a.load_default("simple");
    </script>
    <script language="javascript">
        function SaveChange(el) {
            el.form.submit();
        }

        function selectAll(f) {
            var st = getEle(document.tform, "input", "name", f);
            for (var key in st) {
                if (st[key].disabled == false) {
                    st[key].checked = true;
                }
                else {
                    st[key].checked = false;
                }
            }
        }

        function selectAllLang(f, checked) {
            var st = getEle(document.tform, "input", "name", f);
            for (var key in st) {
                if (st[key].disabled == false) {
                    st[key].checked = checked;
                }
                else {
                    st[key].checked = false;
                }
            }
        }

        function deSelectAll(f) {
            var st = getEle(document.tform, "input", "name", f);
            for (var key in st) {
                if (st[key].disabled == false) {
                    st[key].checked = false;
                }
                else {
                    st[key].checked = false;
                }
            }
        }

        function updateLanguage(l) {

        }
    </script>
    <style>
        .faq_clink {
            font-size: 14px;
            line-height: 25px;
            text-decoration: none;
            color: #444444;
        }

        .faq_qlink {
            list-style-type: none;
        }

        .faq_qlink a {
            font-size: 18px;
            text-decoration: none;
            color: #666666;
            list-style-type: none;
        }

        .faq_clink a:hover {
            text-decoration: underline;
            color: #444444;
        }

        .faq_qlink a:hover {
            text-decoration: underline;
            color: #666666;
        }
    </style>

</head>

<?php
$ar_status = array("1" => "active", "0" => "inactive");
$ar_color = array("1" => "#009900", "0" => "#0000CC");
?>
<body topmargin="0" leftmargin="0">
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><b style="font-size:16px;color:#000000"><?= $lang["page_title"] ?></b></td>
            <td width="400" align="right" class="title"><input type="button" value="<?= $lang["content_manage"] ?>"
                                                               class="button"
                                                               onclick="Redirect('<?= site_url('marketing/customer_service_info/index/') ?>')">
            </td>
        </tr>
        <tr>
            <td colspan="2" height="2" bgcolor="#000033"></td>
        </tr>
    </table>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="page_header">
        <tr>
            <td height="70" style="padding-left:8px;" align="left" valign="middle"><b
                    style="font-size:14px;"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?></td>
        </tr>
    </table>

    <form action="" method="POST" name="tform" style="padding:0; margin:0" onSubmit="return CheckForm(this)"
          enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $id ?>">
        <table align="center" border="0" cellpadding="0" cellspacing="1" height="20" class="tb_list" width="100%">
            <col width="10%">
            <col width="30%">
            <!--<col width="15%"><col width="15%">-->
            <col width="15%">
            <!--<col width="15%">-->
            <tr class="header">
                <td align="center"><?= $lang["country_id"] ?></td>
                <td align="center"><?= $lang["country_name"] ?></td>
                <!--
                <td align="center"><?= $lang["short_text_status_s"] ?></td>
                <td align="center"><?= $lang["long_text_status_s"] ?></td>
-->
                <td align="center"><?= $lang["short_text_status_w"] ?></td>
                <!--
                <td align="center"><?= $lang["long_text_status_w"] ?></td>
-->
            </tr>
            <tr class="add_header">
                <td></td>
                <td></td>
                <!--
                                <td style="text-align:center;color:#F0F0F0"><input type="button" value="Select All" style="font-size:11px" onClick="javascript:selectAll('st_status_s')">&nbsp;&nbsp;&nbsp;<input type="button" value="Deselect All" style="font-size:11px" onclick="deSelectAll('st_status_s')"></td>
                                <td style="text-align:center;color:#F0F0F0"><input type="button" value="Select All" style="font-size:11px" onClick="javascript:selectAll('lt_status_s')">&nbsp;&nbsp;&nbsp;<input type="button" value="Deselect All" style="font-size:11px" onclick="deSelectAll('lt_status_s')"></td>
                -->
                <td style="text-align:center;color:#F0F0F0"><input type="button" value="Select All"
                                                                   style="font-size:11px"
                                                                   onClick="javascript:selectAll('st_status_w')">&nbsp;&nbsp;&nbsp;<input
                        type="button" value="Deselect All" style="font-size:11px" onclick="deSelectAll('st_status_w')">
                </td>
                <!--
                                <td style="text-align:center;color:#F0F0F0"><input type="button" value="Select All" style="font-size:11px" onClick="javascript:selectAll('lt_status_w')">&nbsp;&nbsp;&nbsp;<input type="button" value="Deselect All" style="font-size:11px" onclick="deSelectAll('lt_status_w')"></td>
                -->
            </tr>
            <?php

            foreach ($country_list_by_lang as $lang_key => $country_list) {
                $i = 0;
                ?>
                <tr style="color:#F0F0F0;background-color:#41627E">
                    <td><b><?= $lang_list[$lang_key]->get_id() ?></b></td>
                    <td><b><?= $lang_list[$lang_key]->get_name() ?></b></td>
                    <!--
                <td style="text-align:center"><input type="checkbox" name="st_status_sk[<?= $lang_key ?>]" onClick="selectAllLang('st_status_s\\[<?= $lang_key ?>\\]', this.checked)" <?= $st_status_sk[$lang_key] ? "CHECKED" : "" ?>/></td>
                <td style="text-align:center"><input type="checkbox" name="lt_status_sk[<?= $lang_key ?>]" onClick="selectAllLang('lt_status_s\\[<?= $lang_key ?>\\]', this.checked)" <?= $lt_status_sk[$lang_key] ? "CHECKED" : "" ?>/></td>
-->
                    <td style="text-align:center"><input type="checkbox" name="st_status_we[<?= $lang_key ?>]"
                                                         onClick="selectAllLang('st_status_w\\[<?= $lang_key ?>\\]', this.checked)" <?= $st_status_we[$lang_key] ? "CHECKED" : "" ?>/>
                    </td>
                    <!--
                <td style="text-align:center"><input type="checkbox" name="lt_status_we[<?= $lang_key ?>]" onClick="selectAllLang('lt_status_w\\[<?= $lang_key ?>\\]', this.checked)" <?= $lt_status_we[$lang_key] ? "CHECKED" : "" ?>/></td>
-->
                </tr>
                <?php
                foreach ($country_list as $obj) {
                    ?>
                    <tr class="row<?= $i % 2 ?>" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')">
                        <td><?= $obj->get_id() ?></td>
                        <td><?= $obj->get_name() ?></td>
                        <!--
                <td style="text-align:center"><input type="checkbox" name="st_status_s[<?= $lang_key ?>][<?= $obj->get_id() ?>]" <?php if ($pbv['SKYPE'][$lang_key][$obj->get_id()] == 1) {
                            echo $st_status_s[$obj->get_id()] ? "CHECKED" : "";
                        } else {
                            echo "DISABLED";
                        } ?>/></td>
                <td style="text-align:center"><input type="checkbox" name="lt_status_s[<?= $lang_key ?>][<?= $obj->get_id() ?>]" <?php if ($pbv['SKYPE'][$lang_key][$obj->get_id()] == 1) {
                            echo $lt_status_s[$obj->get_id()] ? "CHECKED" : "";
                        } else {
                            echo "DISABLED";
                        } ?>/></td>
-->
                        <td style="text-align:center"><input type="checkbox"
                                                             name="st_status_w[<?= $lang_key ?>][<?= $obj->get_id() ?>]" <?php if ($pbv['WEBSITE'][$lang_key][$obj->get_id()] == 1) {
                                echo $st_status_w[$obj->get_id()] ? "CHECKED" : "";
                            } else {
                                echo "DISABLED";
                            } ?>/></td>
                        <!--
                <td style="text-align:center"><input type="checkbox" name="lt_status_w[<?= $lang_key ?>][<?= $obj->get_id() ?>]" <?php if ($pbv['WEBSITE'][$lang_key][$obj->get_id()] == 1) {
                            echo $lt_status_w[$obj->get_id()] ? "CHECKED" : "";
                        } else {
                            echo "DISABLED";
                        } ?>/></td>
-->
                    </tr>
                    <?php
                    $i++;
                }
            }
            ?>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" height="40" class="page_header" width="100%">
            <tr>
                <td align="right" style="padding-right:8px">
                    <input type="button" value="<?= $lang["update_var"] ?>" style="font-size:11px"
                           onClick="if(CheckForm(this.form)) this.form.submit();">
                </td>
            </tr>
        </table>
        <br>
        <input type="hidden" name="type" value="edithead">
        <input type="hidden" name="posted" value="1">
        <input type="hidden" name="lang_id" value='<?= $lang_id ?>'>
    </form>
</div>
</body>
</html>