<html>
<head>
    <meta http-equiv="Content-Language" content="en-gb">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="keywords" content="">
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="imagetoolbar" content="no">
    <link rel="stylesheet" type="text/css" href="<?= base_url() . "css/style.css" ?>">
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>js/ext-js/resources/css/ext-all.css"/>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>js/ext-js/resources/css/tab-scroller-menu.css"/>
    <script type="text/javascript" src="<?= base_url() ?>js/ext-js/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/ext-js/ext-all.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/ext-js/TabScrollerMenu.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <style type="text/css">
        <?php
            $step = 5;
            $this_level = $cat_obj->getLevel();
            $j = 0;
            for($i = $this_level; $i < 4; $i++)
            {
        ?>
        div.layer<?=$i?> {
            padding-left: <?=$step * $j?>px;
        }

        span.aspan {
            margin-right: 8px;
        }

        <?php
                $j++;
            }
        ?>
        div.layerp {
            padding-left: <?=$step * $j?>px;
        }
    </style>
    <script type="text/javascript">
        <!--
        function showNextLayer(id, level, htmlid) {
            var xmlhttp = GetXmlHttpObject();
            var tlink = 'a' + id;
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4) {
                    document.getElementById(htmlid).innerHTML += xmlhttp.responseText;
                    if (document.getElementById('a' + id)) {
                        document.getElementById('a' + id).innerHTML = '<a href="#"' + ' onClick="remove(' + "'" + id + "'" + ')">-</a>';
                    }
                }
            }
            url = '<?=base_url()."marketing/category/getlayer/?id="?>' + id;
            xmlhttp.open("GET", url, true);
            xmlhttp.send(null);
        }

        function remove(id) {
            var hide = 'na' + id;
            document.getElementById(hide).style.display = 'none';
            var show = 'a' + id;
            document.getElementById(show).innerHTML = '<a href="#" onClick="show(' + "'" + id + "'" + ')">+</a>';
        }

        function show(id) {
            var show = 'na' + id;
            document.getElementById(show).style.display = 'block';
            var hide = 'a' + id;
            document.getElementById(hide).innerHTML = '<a href="#" onClick="remove(' + "'" + id + "'" + ')">-</a>';
        }

        function GetXmlHttpObject() {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                return new XMLHttpRequest();
            }
            if (window.ActiveXObject) {
                // code for IE6, IE5
                return new ActiveXObject("Microsoft.XMLHTTP");
            }
            return null;
        }
        -->
    </script>
</head>
<body topmargin="0" leftmargin="0" style="width:auto;">
<div id="main" style="width:auto;">
    <?= $notice["img"] ?>
    <form name="catview" id="catview" action="<?= $_SERVER["PHP_SELF"] ?>" method="POST" enctype="multipart/form-data">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="30" class="title"><b style="font-size:16px;color:#000000"><?= $lang["subtitle"] ?></b></td>
                <td width="400" align="right" class="title"><input type="button" value="Generate Menu" class="button"
                                                                   onClick="document.getElementById('update_menu').src='<?= base_url() ?>cron/cron_draw_menu/cron_multilanguage_menu'">
                </td>
            </tr>
            <tr>
                <td height="2" bgcolor="#000033"></td>
                <td height="2" bgcolor="#000033"></td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
            <tr>
                <td height="70" style="padding-left:8px"><b
                        style="font-size:14px"><?= $lang["title"] ?></b><br><?= $lang["subheader"] ?></td>
            </tr>
        </table>
        <table border="0" cellpadding="2" cellspacing="1" height="20" class="page_header" width="100%">
            <tr class="header">
                <td width="150" height="20"><?= $lang["category_info"] ?></td>
                <td height="20"><?= $lang["assoc_value"] ?></td>
                <?php
                if ($canadd && $cat_obj->getLevel() < 3) :
                ?>
                <td height="20" align="left" valign="middle" width="155">
                    <input type="button"
                    value="Add sub-category under this"
                    onclick=Redirect('<?= base_url() . "marketing/category/add/?level=" . ($cat_obj->getLevel() + 1) . "&parent=" . $cat_obj->getId() ?>')
                    style="width:150px;">
                </td>
                <?php
                endif;
                ?></font>
                </td>
            </tr>
            <tr>
                <td width="142" valign="top" class="field" align="right"><?= $lang["category_type"] ?></td>
                <td align="left" class="value" colspan="2"><?= $lang["type" . $cat_obj->getLevel()] ?></td>
            </tr>
            <tr>
                <td width="142" height="20" valign="top" class="field" align="right"><?= $lang["category_name"] ?></td>
                <td height="20" valign="top" class="value" align="left" colspan="2">
                    <input type="text" name="name" value="<?= $cat_obj->getName() ?>" notEmpty></td>
            </tr>
            <tr>
                <td width="142" height="20" valign="top" class="field"
                    align="right"><?= $lang["display_content"] ?></td>
                <td class="value" align="left" colspan="2">
                    <?php if ($lang_list) :
                        ?>
                        <div id="div_image_tabs" style="border:0px;padding:0px;">
                            <?php
                            foreach ($lang_list as $key => $lang_obj) :
                                $cur_lang_id = $lang_obj->getLangId();
                                @$cur_name = $cat_ext[$cat_id][$cur_lang_id] ? $cat_ext[$cat_id][$cur_lang_id]->getName() : "";
                                ?>
                                <div id="div_name_<?= $cur_lang_id ?>" class="x-tab" title="<?= $lang_obj->getLangName() ?>" style="border:0px">
                                    <table border="0" cellpadding="0" cellspacing="1" height="20" class="page_header"
                                           width="100%">
                                        <col width="20%">
                                        <col width="40%">
                                        <col width="40%">
                                        <tr>
                                            <td class="field"><?= $lang["display_name"] ?></td>
                                            <td class="value" colspan="2">
                                                <input name="lang_name[<?= $cur_lang_id ?>]" class="input" value="<?= htmlspecialchars($cur_name) ?>">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            <?php
                            endforeach;
                            ?>
                        </div>
                    <?php
                    endif;
                    ?>
                </td>
            </tr>
            <tr>
                <td width="142" valign="top" class="field" align="right"><?= $lang["category_desc"] ?></td>
                <td align="left" class="value" colspan="2">
                    <textarea cols="100" rows="5" name="description"><?= htmlspecialchars(stripslashes($cat_obj->getDescription())) ?></textarea>
                </td>
            </tr>
            <?php
            if ($cat_obj->getLevel() == 2) :
                ?>
                <tr>
                    <td width="142" valign="top" class="field" align="right"><?= $lang["add_colour_name"] ?></td>
                    <td align="left" class="value" colspan="2">
                        <select name="add_colour_name" style="width:120px;">
                            <option value="0" <?= ($cat_obj->getAddColourName() == 0 ? "SELECTED" : "") ?>><?= $lang["no"] ?></option>
                            <option value="1" <?= ($cat_obj->getAddColourName() == 1 ? "SELECTED" : "") ?>><?= $lang["yes"] ?></option>
                        </select>
                    </td>
                </tr>
            <?php
            endif;
            ?>
            <tr>
                <td width="142" valign="top" class="field" align="right"><?= $lang["category_status"] ?></td>
                <td align="left" class="value" colspan="2"><select name="status" style="width:120px;">
                        <?php
                        for ($i = 0; $i < 2; $i++) :
                            ?>
                            <option value="<?= $i ?>" <?= ($i == $cat_obj->getStatus() ? "SELECTED" : "") ?>><?= $lang["status" . $i] ?></option>
                        <?php
                        endfor;
                        ?>
                    </select></td>
            </tr>
            <tr>
                <td width="142" height="20" valign="top" class="field" align="right"><?= $lang["priority"] ?></td>
                <td height="20" valign="top" class="value" align="left" colspan="2">
                    <input type="text" name="priority" value="<?= $cat_obj->getPriority() ?>" isInteger min=1>
                </td>
            </tr>
            <?php
            if ($cat_obj->getLevel() == 2) :
                ?>
                <tr>
                    <td width="142" height="20" valign="top" class="field"
                        align="right"><?= $lang["bundle_discount"] ?></td>
                    <td height="20" valign="top" class="value" align="left" colspan="2">
                        <input type="int_input" name="bundle_discount" value="<?= $cat_obj->getBundleDiscount() ?>" class="int_input" min="0"> %
                    </td>
                </tr>
            <?php
            endif;
            ?>
            <tr>
                <td width="142" height="20" valign="top" class="field" align="right"><?= $lang["hs_code"] ?>
                <td height="20" valign="top" class="value" align="left" colspan="2">
                    <table>
                        <tr>
                            <td>
                                <?php
                                if (count($hs) == '21') : ?>
                                    <table>
                                        <tr>
                                            <?php $m = 0;
                                            for ($j = 0; $j < count($hs); $j++) :

                                                //echo  $hs[$j];
                                                $m++; ?>

                                                <td>
                                                    <table width="100%" style="padding-right: 10px;">
                                                        <tr>
                                                            <td width="20px">
                                                                <?= $hs[$j]['country_id'] ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if ($cat_obj->getLevel() > 1) :
                                                                 ?>
                                                                    <font
                                                                        style="font-size:10px">P: <?= $parcode[$j]['code'] ?></font>
                                                                    <br>
                                                                <?php
                                                                endif;
                                                                ?>

                                                                <select style="width: 120px;"
                                                                        name="hscode_<?= $hs[$j]['country_id'] ?>"
                                                                        id="hscode_<?= $hs[$j]['country_id'] ?>"
                                                                        onChange="fillduty($(this).val(), $(this).find(':selected').attr('duty'), $(this).find(':selected').attr('cid'))">
                                                                    <?php for ($i = 0; $i < count($optionhs); $i++) : ?>
                                                                        <?php if ($optionhs[$i]['country_id'] == $hs[$j]['country_id']) : ?>
                                                                            <option value="<?= $optionhs[$i]['code'] ?>"
                                                                                    duty="<?= $optionhs[$i]['duty_pcent'] ?>"
                                                                                    cid="<?= $optionhs[$i]['country_id'] ?>"<?= ($optionhs[$i]['code'] == $hs[$j]['code'] ? "SELECTED" : "") ?>><?= $optionhs[$i]['code'] ?>
                                                                                , <?= $optionhs[$i]['duty_pcent'] ?>
                                                                                %,  <?= $optionhs[$i]['country_id'] ?>
                                                                                , <?= $optionhs[$i]['description'] ?>
                                                                            </option>
                                                                        <?php endif; ?>
                                                                    <?php endfor;?>
                                                                </select>

                                                            </td>
                                                            <td style="text-align: center">
                                                                <?php if ($cat_obj->getLevel() > 1) : ?>
                                                                    <font
                                                                        style="font-size:10px"><?= $parcode[$j]['duty_pcent'] ?></font>
                                                                    <br>
                                                                <?php endif; ?>

                                                                <input type="int_input"
                                                                       name="duty_<?= $hs[$j]['country_id'] ?>"
                                                                       id="duty_<?= $hs[$j]['country_id'] ?>"
                                                                       value="<?= $hs[$j]['duty_pcent'] ?>"
                                                                       class="int_input" style="width:32px" min="0">%
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <?php
                                                if ($m == 3) :
                                                    echo "</tr><tr>";
                                                    +
                                                    $m = 0;
                                                endif;
                                            endfor;
                                            ?>
                                        </tr>
                                    </table>
                                <?php
                                else :

                                    $arrcountry = array("AU", "BE", "CH", "ES", "FI", "FR", "GB", "HK", "ID", "IE", "IT", "MT", "MX", "MY", "NZ", "PH", "PL", "PT", "RU", "SG", "TH", "US");
                                ?>
                                    <table>
                                        <tr>
                                            <?php $m = 0;
                                            for ($j = 0; $j < count($arrcountry); $j++) :
                                                $m++;
                                            ?>

                                                <td>
                                                    <table width="100%" style="padding-right: 10px;">
                                                        <tr>
                                                            <td width="20px">
                                                                <?= $arrcountry[$j] ?>
                                                            </td>
                                                            <td>
                                                                <?php if (strpos($psarr, $arrcountry[$j]) !== false) : ?>

                                                                    <?php if ($cat_obj->getLevel() > 1) : ?>
                                                                        <?php
                                                                            $rcode = "";
                                                                            for ($x = 0; $x < count($parcode); $x++) :
                                                                                if ($parcode[$x]['country_id'] == $arrcountry[$j]) :
                                                                                    $rcode = $parcode[$x]['code'];
                                                                                endif;
                                                                            endfor;
                                                                         ?>

                                                                        <font
                                                                            style="font-size:10px">P: <?= $rcode ?></font>
                                                                        <br>
                                                                    <?php endif ?>

                                                                    <select style="width: 120px;"
                                                                            name="hscode_<?= $arrcountry[$j] ?>"
                                                                            id="hscode_<?= $arrcountry[$j] ?>"
                                                                            onChange="fillduty($(this).val(), $(this).find(':selected').attr('duty'), $(this).find(':selected').attr('cid'))">
                                                                        <?php for ($i = 0; $i < count($optionhs); $i++) : ?>
                                                                            <?php if ($optionhs[$i]['country_id'] == $arrcountry[$j]) : ?>

                                                                                <?php
                                                                                    $pcode = "";
                                                                                    for ($x = 0; $x < count($hs); $x++) :
                                                                                        if ($hs[$x]['country_id'] == $arrcountry[$j]) :
                                                                                            $pcode = $hs[$x]['code'];
                                                                                        endif;
                                                                                    endfor;
                                                                                ?>

                                                                                <option
                                                                                    value="<?= $optionhs[$i]['code'] ?>"
                                                                                    duty="<?= $optionhs[$i]['duty_pcent'] ?>"
                                                                                    cid="<?= $optionhs[$i]['country_id'] ?>"<?= ($optionhs[$i]['code'] == $pcode ? "SELECTED" : "") ?>><?= $optionhs[$i]['code'] ?>
                                                                                    , <?= $optionhs[$i]['duty_pcent'] ?>
                                                                                    %, <?= $optionhs[$i]['country_id'] ?>
                                                                                    , <?= $optionhs[$i]['description'] ?></option>
                                                                            <?php endif; ?>
                                                                        <?php endfor; ?>
                                                                    </select>

                                                                <?php else : ?>

                                                                    <?php if ($cat_obj->getLevel() > 1) : ?>
                                                                        <?php
                                                                            $rcode = "";
                                                                            for ($x = 0; $x < count($parcode); $x++) :
                                                                                if ($parcode[$x]['country_id'] == $arrcountry[$j]) :
                                                                                    $rcode = $parcode[$x]['code'];
                                                                                endif;
                                                                            endfor;
                                                                        ?>

                                                                        <font
                                                                            style="font-size:10px">P: <?= $rcode ?></font>
                                                                        <br>
                                                                    <?php endif; ?>

                                                                    <select style="width: 120px;"
                                                                            name="hscode_<?= $arrcountry[$j] ?>"
                                                                            id="hscode_<?= $arrcountry[$j] ?>"
                                                                            onChange="fillduty($(this).val(), $(this).find(':selected').attr('duty'), $(this).find(':selected').attr('cid'))">

                                                                        <option value="" duty="" cid="">None</option>

                                                                        <?php for ($i = 0; $i < count($optionhs); $i++) : ?>
                                                                            <?php if ($optionhs[$i]['country_id'] == $arrcountry[$j]) : ?>
                                                                                <option
                                                                                    value="<?= $optionhs[$i]['code'] ?>"
                                                                                    duty="<?= $optionhs[$i]['duty_pcent'] ?>"
                                                                                    cid="<?= $optionhs[$i]['country_id'] ?>"><?= $optionhs[$i]['code'] ?>
                                                                                    , <?= $optionhs[$i]['duty_pcent'] ?>
                                                                                    %, <?= $optionhs[$i]['country_id'] ?>
                                                                                    , <?= $optionhs[$i]['description'] ?></option>
                                                                            <?php endif ?>
                                                                        <?php endfor; ?>
                                                                    </select>


                                                                <?php endif; ?>
                                                            </td>
                                                            <td style="text-align: center">
                                                                <?php if (strpos($psarr, $arrcountry[$j]) !== false) : ?>

                                                                    <?php if ($cat_obj->getLevel() > 1) : ?>
                                                                        <?php
                                                                            $rduty = "";
                                                                            for ($x = 0; $x < count($parcode); $x++) :
                                                                                if ($parcode[$x]['country_id'] == $arrcountry[$j]) :
                                                                                    $rduty = $parcode[$x]['duty_pcent'];
                                                                                endif;
                                                                            endfor;
                                                                        ?>

                                                                        <font
                                                                            style="font-size:10px"><?= $rduty ?></font>
                                                                        <br>
                                                                    <?php endif; ?>

                                                                    <?php if (count($hs) > 0) : ?>

                                                                        <?php
                                                                            $pduty = "";
                                                                            for ($g = 0; $g < count($hs); $g++) :
                                                                                if ($hs[$g]['country_id'] == $arrcountry[$j]) :
                                                                        ?>
                                                                                <?php $pduty = $hs[$g]['duty_pcent'];

                                                                                endif;
                                                                            endfor;
                                                                        ?>
                                                                        <input type="int_input"
                                                                               name="duty_<?= $arrcountry[$j] ?>"
                                                                               id="duty_<?= $arrcountry[$j] ?>"
                                                                               value="<?= $pduty ?>" class="int_input"
                                                                               style="width:32px" disabled min="0">%
                                                                    <?php endif; ?>

                                                                <?php else : ?>

                                                                    <?php if ($cat_obj->getLevel() > 1) : ?>
                                                                        <?php
                                                                            $rduty = "";
                                                                            for ($x = 0; $x < count($parcode); $x++) :
                                                                                if ($parcode[$x]['country_id'] == $arrcountry[$j]) :
                                                                                    $rduty = $parcode[$x]['duty_pcent'];
                                                                                endif;
                                                                            endfor;
                                                                        ?>

                                                                        <font
                                                                            style="font-size:10px"><?= $rduty ?></font>
                                                                        <br>
                                                                    <?php endif ?>

                                                                    <input type="int_input" name="duty_<?= $arrcountry[$j] ?>"
                                                                           id="duty_<?= $arrcountry[$j] ?>" value=""
                                                                           class="int_input" style="width:32px" disabled
                                                                           min="0">%
                                                                <?php endif; ?>

                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <?php if ($m == 3) :
                                                    echo "</tr><tr>";
                                                    $m = 0;
                                                endif;
                                            endfor;
                                            ?>
                                        </tr>
                                    </table>
                                <?php
                                endif;
                                ?>
                            </td>
                            <td width="270px">
                                Parent Code Uses
                                <table><? print_r($upcode);?>
                                    <?php for ($i = 0; $i < count($upcode); $i++) : ?>
                                        <tr>
                                            <td style="padding: 0px; border: none; line-height: 10px;">
                                                <?= $upcode[$i]['code'] ?>
                                            </td>
                                            <td style="padding: 0px; border: none; line-height: 10px;">
                                                - <?= $upcode[$i]['description'] ?>
                                            </td>
                                        </tr>
                                    <?php endfor; ?>
                                </table>
                                <br>

                                Code Use Currently:

                                <table>
                                    <?php for ($i = 0; $i < count($ucode); $i++) : ?>
                                        <tr>
                                            <td style="padding: 0px; border: none; line-height: 10px;">
                                                <?= $ucode[$i]['code'] ?>
                                            </td>
                                            <td style="padding: 0px; border: none; line-height: 10px;">
                                                - <?= $ucode[$i]['description'] ?>
                                            </td>
                                        </tr>
                                    <?php endfor; ?>
                                </table>
                                <br><br>

                                <?php if ($cat_obj->getLevel() > 1) : ?>
                                    <input type="button" name="inherit" value="Inherit Parent" style="font-size:11px; height: 30px; width: 100;text-align: center;" onClick="inheritparent(<?php $cat_obj->getParentCatId() ?>)">
                                <?php endif ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php
            if ($cat_obj->getLevel() > 1) :
                if ($cat_obj->getLevel() == 2) :
                    ?>
                    <tr>
                        <td width="142" height="20" valign="top" class="field" align="right"><?= $lang["category_cat"] ?></td>
                        <td height="20" valign="top" class="value" align="left" colspan="2">
                            <select name="subcat" style="width:300px;">
                            <?php foreach ($parent_list as $pobj) :
                                    ?>
                                    <option
                                    value="<?= $pobj->getId() ?>" <?= ($pobj->getId() == $cat_obj->getParentCatId() ? "SELECTED" : "") ?>><?= $pobj->getName() ?></option>
                            <?php endforeach;?>
                            </select>
                        </td>
                    </tr>
                <?php
                else :
                    ?>
                    <tr>
                        <td width="142" height="20" valign="top" class="field" align="right"><?= $lang["category_cat"] ?></td>
                        <td height="20" valign="top" class="value" align="left" colspan="2"><?= $subcat_list->getCatName() ?></td>
                    </tr>
                    <tr>
                        <td width="142" height="20" valign="top" class="field" align="right"><?= $lang["category_subcat"] ?></td>
                        <td height="20" valign="top" class="value" align="left" colspan="2">
                            <select name="subcat" style="width:300px;">
                            <?php
                                foreach ($parent_list as $pobj) :
                                    if ($pobj->getParentCatId() == $subcat_list->getCatId()) :
                            ?>
                                        <option value="<?= $pobj->getId() ?>" <?= ($pobj->getId() == $subcat_list->getSubCatId() ? "SELECTED" : "") ?>><?= $pobj->getName() ?></option>
                            <?php
                                    endif;
                                endforeach;
                            ?>
                            </select>
                        </td>
                    </tr>
                <?php
                endif;
            endif;
            ?>
            <tr>
                <td width="142" height="20" valign="top" class="field" align="right"><?= $lang["cat_prod_under"] ?></td>
                <td height="20" valign="top" class="value" align="left" colspan="2">
                    <div id='c<?= $cat_obj->getId() ?>' class="layer<?= $cat_obj->getLevel() ?>" style="margin-left:6px;">
                        <script language="javascript">showNextLayer('<?=$cat_obj->getId()?>', '<?=$cat_obj->getLevel()?>', 'c<?=$cat_obj->getId()?>');</script>
                    </div>
                </td>
            <tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" height="40" class="page_header" width="100%">
            <tr>
                <td align="left" style="padding-left:8px;" width="50%" style="padding-left:8px">
                    <input type="button" name="back" value="Back To Main Page" onClick="Redirect('<?= base_url() . "marketing/category/top/" ?>')">
                </td>
                <td align="right" style="padding-right:8px">
                    <input type="submit" name="submit" id="submit" value="<?= $lang["update_category"] ?>" style="font-size:11px">
                </td>
            </tr>
        </table>
        <input type="hidden" name="posted" value="1">
        <input type="hidden" name="level" value="<?= $cat_obj->getLevel() ?>">
        <?php if ($cat_obj->getLevel() == 2) : ?>
            <iframe name="frame2"
                    src="<?= base_url() . "marketing/ra_prod_cat/view/?sscat=" . $cat_obj->getId() ?>&locked=1"
                    scrolling="no" width="100%" frameborder="0" onLoad="SetFrameHeight(this)"></iframe>
            <iframe name="frame1"
                    src="<?= base_url() . "marketing/category/view_scpv/?subcat_id=" . $cat_obj->getId() ?>"
                    scrolling="no" width="100%" frameborder="0" onLoad="SetFrameHeight(this)"></iframe>
            <iframe name="frame3" src="<?= base_url() . "marketing/category/view_prod_spec/" . $cat_obj->getId() ?>"
                    scrolling="no" width="100%" frameborder="0" onLoad="SetFrameHeight(this)"></iframe>
        <?php endif;?>
        <iframe id="update_menu" src="" frameborder="0" name="left" id="left" marginwidth="0" marginheight="0" hspace=0
                vspace=0 width="0"></iframe>
    </form>
</div>
<script>
    $(document).ready(function () {

        $("#catview").submit(function (event) {
            var incheck = new Array("AU", "BE", "CH", "ES", "FI", "FR", "GB", "HK", "ID", "IE", "IT", "MT", "MY", "NZ", "PH", "PL", "PT", "RU", "SG", "TH", "US");
            var empty = false;
            for (var i = 0; i < incheck.length; i++) {
                if ($('#hscode_' + incheck[i]).val() == "") {
                    empty = true;
                }

                if ($('#duty_' + incheck[i]).val() == "") {
                    empty = true;
                }
            }

            if (empty) {
                alert('HS Code and Duty Percent cannot be empty. Please fill in all HS Code and Duty Percent.');
                event.preventDefault();
            }
        });
    });
</script>
<script>
    Ext.onReady(function () {

        var scrollerMenu = new Ext.ux.TabScrollerMenu({
            maxText: 64,
            pageSize: 1
        });

        var name_tabs = new Ext.TabPanel({
            applyTo: 'div_name_tabs',
            autoTabs: true,
            activeTab: 0,
            deferredRender: false,
            border: true,
            enableTabScroll: true,
            autoHeight: true,
            defaults: {autoScroll: true},
            width: '700px',
            plugins: [scrollerMenu]
        });
    });

    Ext.onReady(function () {

        var scrollerMenu = new Ext.ux.TabScrollerMenu({
            maxText: 64,
            pageSize: 1
        });

        var name_tabs = new Ext.TabPanel({
            applyTo: 'div_image_tabs',
            autoTabs: true,
            activeTab: 0,
            deferredRender: false,
            border: true,
            enableTabScroll: true,
            autoHeight: true,
            defaults: {autoScroll: true},
            width: '700px',
            plugins: [scrollerMenu]
        });
    });
</script>
<script>
    function inheritparent(parent_id) {
        var subc = $('select[name=subcat] option:selected').val();
        if (subc == '') {
            alert('There is no Category being selected. Inherit from Parents is not possible')
        } else {
            var answer = confirm('Are you sure you want inherit HS Code from Parent?');
            if (answer) {
                window.location = '/index.php/marketing/category/view/<?=$cat_obj->getId()?>-1';
            }
        }
    }
    function fillduty(ccode, duty, cid) {

        $('#duty_' + cid).val(duty);
    }
</script>
<?= $notice["js"] ?>
</body>
</html>