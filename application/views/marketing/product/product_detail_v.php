<html>
<head>
    <title><?=$lang["title"]?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
    <script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/picklist.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/jquery.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/jquery.dg-magnet-combo.js"></script>
    <script type="text/javascript" src="<?=base_url()?>marketing/category/js_catlist"></script>
    <script type="text/javascript" src="<?=base_url()?>mastercfg/FreightHelper/js_freight_cat"></script>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>js/ext-js/resources/css/ext-all.css" />
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>js/ext-js/resources/css/tab-scroller-menu.css" />
    <script type="text/javascript" src="<?=base_url()?>js/ext-js/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/ext-js/ext-all.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/ext-js/TabScrollerMenu.js"></script>
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/tinymce/tinymce.min.js"></script>
    <!--<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>-->
    <script type="text/javascript" src="<?=base_url()?>js/calendar.js"></script>
    <link rel="stylesheet" href="<?=base_url()?>css/calendar.css" type="text/css" media="all"/>
    <script type="text/javascript">

//$(document).ready(function(){
//  $("#prod_type").dgMagnetCombo();
//});

</script>

<style>
    .warranty_country_section
    {
        margin-right:20px;
    }
    .ui-dialog .ui-dialog-content
    {
        position: relative;
        border: 0;
        padding: .5em 1em;
        background: none;
        overflow: auto;
    }
    .ui-dialog .ui-dialog-title
    {
        width:100% !important;
    }
    .ui-widget-overlay
    {
        background: #000000 url(images/ui-bg_flat_0_aaaaaa_40x100.png) 0% 1000% repeat-x;
        opacity: .6;
        filter: Alpha(Opacity=60);
    }
    .mce-container, .mce-container *, .mce-widget, .mce-widget *, .mce-reset {
        font-size: 11px !important;
    }
    .mce-btn button{
        padding: 1px 4px !important;
        line-height: 14px !important;
    }

</style>

</head>
<body>
    <div id="main">
        <?=$notice["img"]?>
        <?php
        $ar_status = array($lang["inactive"], $lang["created"], $lang["listed"]);
        $ar_ws_status = array("I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]);
        $ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
        $warranty_type_list = array(
            '1' => 'Accessories',
            '2' => 'Waterproof',
            '3' => 'Main items',
            '4' => 'Action Camera',
            '5' => 'Drones',
            '6' => 'Refurbished',
            '7' => 'No Warranty',
        );
        $master_sku_check = 'MatchRegExI="^(\\d{1,5})-([A-Z]{2})-([A-Z]{2})$" warningMsg="wrong master sku format"';
        ?>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="30" class="title"><?=$lang["title"]?></td>
                <td width="600" align="right" class="title">
                    <input type="button" value="<?=$lang["list_button"]?>" class="button" onclick="Redirect('<?=site_url('marketing/product/')?>')">
                    &nbsp; <input type="button" value="<?=$lang["add_button"]?>" class="button" onclick="Redirect('<?=site_url('marketing/product/add/')?>')">
                    <?php
                    if ($prod_grp_cd)
                    {
                        ?>
                        &nbsp; <input type="button" value="<?=$lang["add_colour"]?>" class="button" onclick="Redirect('<?=site_url('marketing/product/add_colour/'.$prod_grp_cd.'/'.$colour_id)?>')"> &nbsp; <input type="button" value="<?=$lang["add_version"]?>" class="button" onclick="Redirect('<?=site_url('marketing/product/add_version/'.$prod_grp_cd.'/'.$version_id)?>')">
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td height="2" class="line"></td>
                <td height="2" class="line"></td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
            <tr>
                <td height="70" style="padding-left:8px;">
                    <?php
                    if ($cmd == "add")
                    {
                        ?>
                        <b><?=$lang["header"]?></b><br><?=$lang["header_message"]?>
                        <?php
                    }
                    else
                    {
                        ?>
                        <div style="float:left"><img src='<?=getImageUrl($product->get_image(), 's', $product->get_sku())?>'> &nbsp;</div>
                        <?=$lang["header"]?><br><a href="<?=$website_link."mainproduct/view/".$product->get_sku()?>" target="_blank"><b style="font-size:14px; color:#000000;"><?=$product->get_name()?><?=$product->get_clearance()?" <span style='color:#0072E3; font-size:14px;'>(Clearance)</span>":""?></b></a><br><?=$lang["sku"]?>: <?=$product->get_sku()?>
                        <?php
                    }
                    ?>
                </td>
            </tr>
        </table>
        <form name="fm" method="post" onSubmit="return CheckForm(this);" enctype="multipart/form-data">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
                <col width="200"><col width="410"><col width="200"><col>
                <tr class="header">
                    <td height="20" colspan="4"><?=$lang["table_header"]?></td>
                </tr>
                <tr>
                    <td class="field"><?=$lang["product_name"]?></td>
                    <td class="value"><input name="name" class="input" value="<?=htmlspecialchars($product->get_name())?>" notEmpty></td>
                    <td class="field"><?=$lang["brand"]?></td>
                    <td class="value">
                        <select name="brand_id" class="input" notEmpty>
                            <option value=""></option>
                            <?php
                            if ($brand_list)
                            {
                                $selectedb[$product->get_brand_id()]="SELECTED";
                                foreach ($brand_list as $brand)
                                {
                                    ?>
                                    <option value="<?=$brand->get_id()?>" <?=$selectedb[$brand->get_id()]?>><?=$brand->get_brand_name()?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="field"><?=$lang["category"]?></td>
                    <td class="value">
                        <select name="cat_id" class="input" onChange="ChangeCat(this.value, this.form.sub_cat_id, this.form.sub_sub_cat_id)" notEmpty>
                            <option value="">
                            </select>
                        </td>
                        <td class="field"><?=$lang["freight_category"]?></td>
                        <td class="value">
                            <select name="freight_cat_id" class="input" onChange="ChangeFCat(this.value, this.form.freight_weight)">
                                <option value="">
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="field"><?=$lang["sub_cat"]?></td>
                            <td class="value">
                                <select name="sub_cat_id" class="input" onChange="ChangeCat(this.value, this.form.sub_sub_cat_id)" notEmpty>
                                    <option value="">
                                    </select>
                                </td>
                                <td class="field"><?=$lang["freight_weight"]?></td>
                                <td class="value"><input name="freight_weight" class="int_input read" READONLY> kg</td>
                            </tr>
                            <tr>
                                <td class="field"><?=$lang["sub_sub_cat"]?></td>
                                <td class="value">
                                    <select name="sub_sub_cat_id" class="input">
                                        <option value="">
                                        </select>
                                    </td>

                                    <td class="field"><?=$lang["hs_code"]?><br>
                                        <table>
                                            <?php for($i=0;$i<count($ucode);$i++){ ?>
                                            <tr>
                                                <td style="padding: 0px; border: none; line-height: 10px;">
                                                    <?=$ucode[$i]['code'] ?>
                                                </td>
                                                <td style="padding: 0px; border: none; line-height: 10px;">
                                                    - <?=$ucode[$i]['description'] ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </table>
                                    </td>
                                    <!--<td class="value"><input type="button" name="edit" id="edit" value="Click to View/Edit HS Code" size="60" style="width:150px" onClick="hscode_edit(event,'<?=$product->get_sku()?>')"></td>-->
                                    <td class="value">
                                        <table>
                                            <tr>
                                                <td style="padding: 0px; border: none;">
                                                    <?php
                 //var_dump($hs[3]['country_id']); die();
                //var_dump($parcode[1]['code']); die();
                                                    if(count($phs) == '21' ){ ?>
                                                    <table>
                                                        <tr>
                                                           <?php $m = 0;
                                                           for($j=0; $j < count($phs); $j++) {

                            //echo  $hs[$j];
                                                            $m++; ?>

                                                            <td style="padding: 0px; border: none;">
                                                                <table width="100%" style="padding-right: 5px;">
                                                                    <tr>
                                                                        <td width="20px" style="padding: 0px; border: none;">
                                                                            <?=$phs[$j]['country_id']?>
                                                                        </td>
                                                                        <td style="padding: 0px; border: none;">

                                                                            <select style="width: 80px;" name="hscode_<?=$phs[$j]['country_id']?>" id="hscode_<?=$phs[$j]['country_id']?>" onchange="fillduty($(this).val(), $(this).find(':selected').attr('duty'), $(this).find(':selected').attr('cid'))">
                                                                                <?php for($i=0; $i<count($optionhs);  $i++){ ?>
                                                                                <?php if($optionhs[$i]['country_id'] == $phs[$j]['country_id']) { ?>
                                                                                <option value="<?=$optionhs[$i]['code']?>" duty="<?=$optionhs[$i]['duty_pcent']?>" cid="<?=$optionhs[$i]['country_id']?>"<?=($optionhs[$i]['code'] == $phs[$j]['code']?"SELECTED":"")?>><?=$optionhs[$i]['code']?>, <?=$optionhs[$i]['duty_pcent']?>%, <?=$optionhs[$i]['country_id']?>, <?=$optionhs[$i]['description']?></option>
                                                                                <?php } ?>
                                                                                <?php }?>
                                                                            </select>
                                                                        </td>
                                                                        <td style="padding: 0px; border: none; text-align: center">

                                                                            <input type="int_input" name="duty_<?=$phs[$j]['country_id']?>" id="duty_<?=$phs[$j]['country_id']?>" value="<?=$phs[$j]['duty_pcent']?>" class="int_input" style="width:30px" disabled min="0"></td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                                <?php  if($m==3){
                                                                    echo "</tr><tr>";+
                                                                    $m=0;
                                                                }
                                                            }
                                                            ?>
                                                        </tr>
                                                    </table>
                                                    <?php } else {

                                                        $arrcountry = array("AU", "BE", "CH", "ES", "FI", "FR", "GB", "HK", "ID", "IE", "IT", "MT", "MY", "NZ", "PH", "PL", "PT", "RU", "SG", "TH", "US");
                //var_dump($arrcountry[0]); die();
                                                        ?>
                                                        <table>
                                                            <tr>
                                                               <?php $m = 0;
                                                               for($j=0; $j < count($arrcountry); $j++) {

                            //echo  $hs[$j];
                                                                $m++; ?>

                                                                <td style="padding: 0px; border: none;">
                                                                    <table width="100%" style="padding-right: 5px;">
                                                                        <tr>
                                                                            <td width="20px" style="padding: 0px; border: none;">
                                                                                <?=$arrcountry[$j]?>
                                                                            </td>

                                                                            <td style="padding: 0px; border: none;">

                                                                                <?php if(strpos($psarr, $arrcountry[$j] ) !==false) {?>
                                                                                <select style="width: 80px;" name="hscode_<?= $arrcountry[$j]?>" id="hscode_<?= $arrcountry[$j]?>" onchange="fillduty($(this).val(), $(this).find(':selected').attr('duty'), $(this).find(':selected').attr('cid'))">
                                                                                    <?php for($i=0; $i<count($optionhs);  $i++){ ?>
                                                                                    <?php if($optionhs[$i]['country_id'] == $arrcountry[$j]) { ?>

                                                                                    <?php for($x=0; $x<count($phs);$x++) {
                                                                                        if($phs[$x]['country_id'] == $arrcountry[$j]) {
                                                                                            $pcode = $phs[$x]['code'];
                                                                                        }
                                                                                    } ?>

                                                                                    <option value="<?=$optionhs[$i]['code']?>" duty="<?=$optionhs[$i]['duty_pcent']?>" cid="<?=$optionhs[$i]['country_id']?>"<?=($optionhs[$i]['code'] == $pcode?"SELECTED":"")?>><?=$optionhs[$i]['code']?>, <?=$optionhs[$i]['duty_pcent']?>%, <?=$optionhs[$i]['country_id']?>, <?=$optionhs[$i]['description']?></option>
                                                                                    <?php } ?>
                                                                                    <?php }?>
                                                                                </select>

                                                                                <?php }else{ ?>

                                                                                <select style="width: 80px;" name="hscode_<?= $arrcountry[$j]?>" id="hscode_<?= $arrcountry[$j]?>" onchange="fillduty($(this).val(), $(this).find(':selected').attr('duty'), $(this).find(':selected').attr('cid'))">

                                                                                    <option value="" duty="" cid="">None</option>

                                                                                    <?php for($i=0; $i<count($optionhs);  $i++){ ?>
                                                                                    <?php if($optionhs[$i]['country_id'] == $arrcountry[$j]) { ?>
                                                                                    <option value="<?=$optionhs[$i]['code']?>" duty="<?=$optionhs[$i]['duty_pcent']?>" cid="<?=$optionhs[$i]['country_id']?>"><?=$optionhs[$i]['code']?>, <?=$optionhs[$i]['duty_pcent']?>%, <?=$optionhs[$i]['country_id']?>, <?=$optionhs[$i]['description']?></option>
                                                                                    <?php } ?>
                                                                                    <?php }?>
                                                                                </select>


                                                                                <?php } ?>
                                                                            </td>
                                                                            <td style="text-align: center; padding: 0px; border: none;">

                                                                                <?php if(strpos($psarr, $arrcountry[$j] ) !==false) {?>

                                                                                <?php if(count($phs)>0) {
                                            //var_dump($phs); die();?>

                                            <?php for ($g=0; $g<count($phs); $g++){
                                                if($phs[$g]['country_id'] == $arrcountry[$j]) { ?>
                                                <?php $pduty = $phs[$g]['duty_pcent'];

                                            } ?>

                                            <?php }?>
                                            <input type="int_input" name="duty_<?=$arrcountry[$j]?>" id="duty_<?=$arrcountry[$j]?>" value="<?=$pduty?>" class="int_input" style="width:30px" disabled min="0">
                                            <?php //} ?>
                                            <?php } ?>

                                            <?php }else{ ?>

                                            <input type="int_input" name="duty_<?=$arrcountry[$j]?>" id="duty_<?=$arrcountry[$j]?>" value="" class="int_input" style="width:30px" disabled min="0">

                                            <?php } ?>

                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <?php  if($m==3){
                                echo "</tr><tr>";
                                $m=0;
                            }

                        }
                        ?>
                    </tr>
                </table>
                <?php } ?>
            </td>
        </tr>
    </table>
</td>
</tr>
<tr>
    <td class="field"><?=$lang["product_warranty_type"]?></td>
    <td class="value">
        <select name='product_warranty_type' notempty>
            <option></option>
            <?php
                foreach ($warranty_type_list as $key => $value) {
            ?>
                <option value='<?= $key ?>'><?= $value ?></option>
            <?php
                }
            ?>
        </select>
    </td>
    <td class="field"></td>
    <td class="value"></td>
</tr>
<tr>
    <td class="field"><?=$lang["inventory"]?></td>
    <td class="value"><?=$inventory?$inventory->get_inventory():"0"?></td>
    <td class="field"><?=$lang["cost"]?></td>
    <td class="value">
        <?php
        if($cmd == 'add')
        {
            ?>
            <?=htmlspecialchars(@call_user_func(array($supp_prod, "get_currency_id")))?>
            <input name="cost" value="<?=htmlspecialchars(@call_user_func(array($supp_prod, "get_cost")))?>" notEmpty isNumber>
            <?php
        }
        else
        {
            ?>
            <?=htmlspecialchars(@call_user_func(array($supp_prod, "get_currency_id")))?>
            <?=htmlspecialchars(@call_user_func(array($supp_prod, "get_cost")))?>
            <?php
        }
        ?></td>

    </tr>

    <?php
    if ($cmd == "add")
    {
        if(!isset($add_type) || isset($add_type) && $add_type == "colour")
        {
            ?>
            <tr>
                <td class="field" valign="top"><?=$lang["colours"]?></td>
                <td class="value" colspan="3">
                    <table border="0" cellpadding="0" cellspacing="0" class="tb_noborder">
                        <tr>
                            <td align="center"><?=$lang["existing_colours"]?><br>
                                <select name="full_list[]" multiple='multiple' class="multi_select">
                                    <?php
                                    if ($colour_list) {
                                        foreach ($colour_list as $colour) {
                                            $colour_lists[$colour->getColourId()] = $colour->getColourName();
                                        }
                                        ksort($colour_lists);
                                        foreach ($colour_lists as $key => $value) {
                                            ?>
                                            <option value="<?=$key?>::<?=$value?>"><?=$value?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td align="center">
                                <input type="button" value=">" onclick="AddOne(document.fm.elements['full_list[]'], document.fm.elements['joined_list[]']);" class="button2"><br><br>
                                <input type="button" value=">>" onclick="AddAll(document.fm.elements['full_list[]'], document.fm.elements['joined_list[]']);" class="button2"><br><br><br>
                                <input type="button" value="<" onclick="DelOne(document.fm.elements['full_list[]'], document.fm.elements['joined_list[]']);" class="button2"><br><br>
                                <input type="button" value="<<" onclick="DelAll(document.fm.elements['full_list[]'], document.fm.elements['joined_list[]']);" class="button2">
                            </td>
                            <td align="center"><?=$lang["selected_colours"]?><br>
                                <select name="joined_list[]" multiple='multiple' class="multi_select" selectAll notEmpty>
                                    <?php
                                    foreach ($joined_list as $colour)
                                    {
                                        ?>
                                        <option value="<?=$colour->getColourId()?>::<?=$colour->getColourName()?>"><?=$colour->getColourName()?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php
        }
        if(!isset($add_type) || (isset($add_type) && $add_type == "version"))
        {
            ?>
            <tr>
                <td class="field" valign="top" style="padding-right:8px;"><?=$lang["versions"]?></td>
                <td class="value" colspan="3">
                    <table border="0" cellpadding="0" cellspacing="0" class="tb_noborder">
                        <tr>
                            <td align="center"><?=$lang["existing_versions"]?><br>
                                <select name="full_vlist[]" multiple='multiple' class="multi_select">
                                    <?php
                                    if ($version_list)
                                    {
                                        foreach ($version_list as $version)
                                        {
                                            ?>
                                            <option value="<?=$version->get_id()?>::<?=$version->get_desc()?>"><?=$version->get_desc()?>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td align="center">
                                    <input type="button" value=">" onclick="AddOne(document.fm.elements['full_vlist[]'], document.fm.elements['joined_vlist[]']);" class="button2"><br><br>
                                    <input type="button" value=">>" onclick="AddAll(document.fm.elements['full_vlist[]'], document.fm.elements['joined_vlist[]']);" class="button2"><br><br><br>
                                    <input type="button" value="<" onclick="DelOne(document.fm.elements['full_vlist[]'], document.fm.elements['joined_vlist[]']);" class="button2"><br><br>
                                    <input type="button" value="<<" onclick="DelAll(document.fm.elements['full_vlist[]'], document.fm.elements['joined_vlist[]']);" class="button2">
                                </td>
                                <td align="center"><?=$lang["selected_versions"]?><br>
                                    <select name="joined_vlist[]" multiple='multiple' class="multi_select" selectAll notEmpty>
                                        <?php
                                        foreach ($joined_vlist as $version)
                                        {
                                            ?>
                                            <option value="<?=$version->get_id()?>::<?=$version->get_desc()?>"><?=$version->get_desc()?>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?php
                }
            }
            else
            {
                ?>
                <tr>
                    <td class="field"><?=$lang["website_status"]?></td>
                    <td class="value">
                        <select name="website_status" class="input">
                            <?php
                            $selected_wss[$product->get_website_status()] = "SELECTED";
                            foreach ($ar_ws_status as $rskey=>$rsvalue)
                            {
                                ?>
                                <option value="<?=$rskey?>" <?=$selected_wss[$rskey]?>><?=$rsvalue?>
                                    <?php
                                }
                                ?>
                            </select>
                            <input type="hidden" id="before_update_website_status" name="before_update_website_status" value="<?=$product->get_website_status()?>"/>
                            <input type="hidden" id="before_expected_delivery_date" name="before_expected_delivery_date" value="<?=htmlspecialchars($product->get_expected_delivery_date())?>"/>
                        </td>
                        <td class="field"><?=$lang["status"]?></td>
                        <td class="value">
                            <select name="status" class="input">
                                <?php
                                $selected_s[$product->get_status()] = "SELECTED";
                                foreach ($ar_status as $rskey=>$rsvalue)
                                {
                                    ?>
                                    <option value="<?=$rskey?>" <?=$selected_s[$rskey]?>><?=$rsvalue?>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td class="field"><?=$lang["image_file"]?></td>
                            <td class="value">
                                <?php
                                if ($prod_image[0])
                                {
                                    $img_f = $prod_image[0]->get_sku().".".$prod_image[0]->get_image();
                                    if (file_exists(IMG_PH.$img_f))
                                    {
                                        ?>
                                        <a href="<?=base_url()?>images/product/<?=$img_f?>" target="preview"><?=$img_f?></a>
                                        <?php
                                    }
                                }
                                ?>
                                <!-- <input type="file" name="image_file" size="60" accept="jpg,jpeg,gif,png" isSquare minHeight="400" onChange="checkAccept(this);set_image(this);"> -->
                                <div id="image_file_h" style="position:absolute;left:-100000px;top:-100000px;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='image');height:1px;width:1px;"></div>
                            </td>
                            <td class="field"><?=$lang["expected_delivery_date"]?></td>
                            <td class="value"><input id="expected_delivery_date" name="expected_delivery_date" value='<?=htmlspecialchars($product->get_expected_delivery_date())?>'><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.getElementById('expected_delivery_date'), false, false, false, '2013-04-01')" align="absmiddle"></td>
                        </tr>


                        <tr>
                            <td class="field"><?=$lang["flash_file"]?></td>
                            <td class="value">
                                <?php
                                if ($product->get_flash())
                                {
                                    $flash_f = $product->get_sku().".".$product->get_flash();
                                    if (file_exists(IMG_PH.$flash_f))
                                    {
                                        ?>
                                        <a href="<?=base_url()?>images/product/<?=$flash_f?>" target="preview"><?=$flash_f?></a>
                                        <?php
                                    }
                                }
                                ?>
                                <input type="file" name="flash_file" size="50" accept="swf" onChange="checkAccept(this)">
                                <input type="hidden" id="remove_flash" name="remove_flash" value="0">
                                <input type="button" name="remove" value="Remove" size="60" onClick="document.getElementById('remove_flash').value=1;document.fm.submit()">
                            </td>
                            <td class="field"><?=$lang["youtube_id"]?></td>
                            <td class="value"><input name="youtube_id" class="input" value="<?=htmlspecialchars($product->get_youtube_id())?>"></td>
                        </tr>
                        <tr>
                            <td class="field"><?=$lang["clearance"]?></td>
                            <td class="value"><input type="checkbox" name="clearance" value="1" <?=$product->get_clearance()?"CHECKED":""?>></td>
                            <td class="field"><?=$lang["ean"]?></td>
                            <td class="value"><input name="ean" class="input" value="<?=htmlspecialchars($product->get_ean())?>"></td>
                        </tr>
                        <tr>
                            <td class="field"><?=$lang["ex_demo"]?></td>
                            <td class="value"><input type="checkbox" name="ex_demo" value="1" <?=$product->get_ex_demo()?"CHECKED":""?>></td>
                            <td class="field"><?=$lang["mpn"]?></td>
                            <td class="value"><input name="mpn" class="input" value="<?=htmlspecialchars($product->get_mpn())?>"></td>
                        </tr>
                        <tr>
                            <td class="field"><?=$lang["china_oem"]?></td>
                            <td class="value"><input type="checkbox" name="china_oem" value="1" <?=$product->get_china_oem()?"CHECKED":""?>></td>
                            <td class="field"><?=$lang["upc"]?></td>
                            <td class="value"><input name="upc" class="input" value="<?=htmlspecialchars($product->get_upc())?>"></td>
                        </tr>
                        <tr>
                            <td class="field"><?=$lang["create_on"]?></td>
                            <td class="value"><?=$product->get_create_on()?></td>
                            <td class="field"><?=$lang["modify_on"]?></td>
                            <td class="value"><?=$product->get_modify_on()?></td>
                        </tr>
                        <tr>
                            <td class="field"><?=$lang["create_at"]?></td>
                            <td class="value"><?=$product->get_create_at()?></td>
                            <td class="field"><?=$lang["modify_at"]?></td>
                            <td class="value"><?=$product->get_modify_at()?></td>
                        </tr>
                        <tr>
                            <td class="field"><?=$lang["create_by"]?></td>
                            <td class="value"><?=$product->get_create_by()?></td>
                            <td class="field"><?=$lang["modify_by"]?></td>
                            <td class="value"><?=$product->get_modify_by()?></td>
                        </tr>
                        <tr>
                            <td class="field"><?=$lang["accelerator_salesrpt_bd"]?></td>
                            <td class="value"><input type="checkbox" name="accelerator_salesrpt_bd" value="1" <?=$product->get_accelerator_salesrpt_bd()?"CHECKED":""?>></td>
                            <td class="field"><?=$lang["accelerator"]?></td>
                            <td class="value"><input type="checkbox" name="accelerator" value="1" <?=$product->get_accelerator()?"CHECKED":""?>></td>
                        </tr>

                        <tr>
                            <td class="field"><?=$lang["master_sku"]?></td>
                            <td class="value"><input name="master_sku" class="input" value="<?=$master_sku?htmlspecialchars($master_sku->get_ext_sku()):""?>" <?=$lock_master_sku?"DISABLED":""?> onblur="check_master_sku(this);" <?=$master_sku_check?>>
                                <?php
                                if ($allow_edit_master_sku)
                                {
                                    ?>
                                    <input type="button" value="<?=$lang["edit_master_sku"]?>" onClick="edit_master_sku(this);this.style.visibility='hidden';">
                                    <?php
                                }
                                ?>
                            </td>

                            <td class="field"><?=$lang["warranty_in_month"]?></td>
                            <td class="value" id="warranty_value">
                                <select name="warranty_in_month" class="input" style="width:90%;" notEmpty>
                                    <option value=''></option>
                                    <?php
                                    foreach($warranty_list as $warranty_period)
                                    {
                                        if ($warranty_period == $product->get_warranty_in_month())
                                            $selected = "SELECTED";
                                        else
                                            $selected = "";
                                        print "<option value='" . $warranty_period . "' " . $selected . ">" . $warranty_period ."</option>";
                                    }
                                    ?>
                                </select>
                                <img src="/images/add_sign.png" id="warranty_add_sign_btn" style="cursor:pointer;">
                                <?php
                                $warranty_field_counter = 0;
                                $warranty_country_list = $this->warranty_model->get_country_warranty_list(array('sku' => $product->get_sku()));
                                foreach ($warranty_country_list as $warranty_country_obj)
                                {
                                    echo '<span class="warranty_country_section"><select  class="warranty_country" class="warranty_country_section" id="warranty_country_'.$warranty_field_counter.'" name="warranty_country_'.$warranty_field_counter.'">';
                                    foreach ($selling_platform_list as $country_obj)
                                    {
                                        $platform_id = $country_obj->getSellingPlatformId();

                                        if ($platform_id == $warranty_country_obj->get_platform_id())
                                        {
                                            $selected = "SELECTED";
                                        }
                                        else
                                        {
                                            $selected = "";
                                        }
                                        echo "<option value='".$platform_id."' ".$selected.">".$platform_id."</option>";
                                    }

                                    echo "</select>";

                                    echo '<select name="warranty_in_month_'. $warranty_field_counter .'" >';
                                    echo "<option value=''>Remove</option>";
                                    foreach($warranty_list as $warranty_period)
                                    {
                                        if ($warranty_period == $warranty_country_obj->get_warranty_in_month())
                                            $selected = "SELECTED";
                                        else
                                            $selected = "";
                                        echo "<option value='" . $warranty_period . "'" . $selected . '>' . $warranty_period ."</option>";
                                    }
                                    echo '</select></span><br/>';
                                    $warranty_field_counter++;
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="field"><?=$lang["cat_upselling"]?></td>
                            <td class="value">
                                <input type="checkbox" name="cat_upselling" value="1" <?=$product->get_cat_upselling()?"CHECKED":""?> style="vertical-align:text-top;">
                            </td>
                            <td class="field"><?=$lang["series"]?></td>
                            <td class="value">
                                <input class="input" name="series" id="series" value="<?=$series?$series:""?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="field"><?=$lang["lang_restricted"]?></td>
                            <td class="value">
                                <select name="lang_restricted[]" id="lang_restricted[]" multiple="multiple" size="8" style="width:100px;">
                                    <?php
                                    foreach($osd_lang_list as $osd_lang_id => $bit)
                                    {
                                        print "<option value='" . $bit . "' " . (($product->get_lang_restricted() & (1 << $bit)) ?"selected":"") . ">" . $osd_lang_id . " (bit:" . $bit . ")". "</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td class="field"><?=$lang["shipment_restricted"]?></td>
                            <td class="value">
                                <select name="shipment_restricted_type" id="shipment_restricted_type">
                                    <option value="0" <?=(($product->get_shipment_restricted_type() == 0)?"selected":"")?>><?=$lang["shipment_no_restriction"]?></option>
                                    <option value="1" <?=(($product->get_shipment_restricted_type() == 1)?"selected":"")?>><?=$lang["shipment_battery_restricted"]?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="field"><?=$lang["model_1"]?></td>
                            <td class="value"><input name="model_1" id="model_1" value="<?=$model_1?htmlspecialchars($model_1):""?>" class="input" notEmpty></td>
                            <td class="field"><?=$lang["model_2"]?></td>
                            <td class="value"><input name="model_2" id="model_2" value="<?=$model_2?htmlspecialchars($model_2):""?>" class="input"></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <?php
                    if ($cmd == "edit")
                    {
                        ?>
                        <tr class="header">
                            <td height="20"><?=$lang["product_content_header"]?></td>
                            <td><?php if($language_id != '' && $language_id != 'en'){?><input type="button" onClick="confirmTranslate('<?=$sku?>','<?=$language_id?>');" value="<?=$lang["translate_product_content"]?>"><?php }?>
                                <?php if($language_id != '' && $language_id == 'en'){?><input type="button" onClick="confirmTranslate('<?=$sku?>','<?=$lang_list_str?>');" value="<?=$lang["translate_product_content_all_in_one"]?>">
                                <?php } ?>
                            </td>
                            <td><?=$lang["language"]?></td>
                            <td>
                                <select name="lang_id" onchange='SaveChange(this);gotoPage("<?=base_url()."marketing/product/view/".$product->get_sku()."/"?>",this.value)' class="input" notEmpty>
                                    <?php
                                    if ($lang_list)
                                    {
                                        $selectedb[$language_id]="SELECTED";
                                        foreach ($lang_list as $language)
                                        {
                                            ?>
                                            <option value="<?=$language->get_lang_id()?>" <?=$selectedb[$language->get_lang_id()]?>><?=strtoupper($language->get_lang_id()).' - '.$language->get_lang_name()?>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td class="field"><?=$lang["model_3"]?></td>
                                <td class="value"><input name="model_3" id="model_3" value="<?=$model_3?htmlspecialchars($model_3):""?>" class="input"></td>
                                <td class="field"><?=$lang["model_4"]?></td>
                                <td class="value"><input name="model_4" id="model_4" value="<?=$model_4?htmlspecialchars($model_4):""?>" class="input"></td>
                            </tr>
                            <tr>
                                <td class="field"><?=$lang["model_5"]?></td>
                                <td class="value"><input name="model_5" id="model_5" value="<?=$model_1?htmlspecialchars($model_5):""?>" class="input"></td>
                                <td class="field">&nbsp;</td>
                                <td class="value">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="field">
                                    <?=$lang["website_display_name"]?><br>
                                    <div><label for="prod_name_original" style="font-weight:bold;"><?=$lang["manual_translation"]?></label>
                                        <input type="hidden" name="prod_name_original" value="0">
                                        <input type="checkbox" name="prod_name_original" value="1" onChange="changeOriginalContent(this)" <?=$prod_cont->get_prod_name_original()?"CHECKED":""?> style="vertical-align:text-top;margin-left:10px;"></div>
                                    <div>
                                        <label for="stop_sync" style="font-weight:bold;"><?=$lang["stop_sync"]?></label>
                                        <input type="checkbox" name="stop_sync_pc[0]" value="2" onChange="StopSync(this)" <?=($prod_cont->get_stop_sync() & (1 << 1))?"CHECKED":""?> style="vertical-align:text-top;margin-left:10px;"></div>
                                    </td>
                                    <td class="value"><input name="prod_name" value="<?=htmlspecialchars($prod_cont->get_prod_name())?>" class="input"></td>
                                    <td class="field">
                                        <?=$lang["keywords"]?><br>(<?=$lang["separated"]?>)
                                        <div><label for="keywords_original" style="font-weight:bold;"><?=$lang["manual_translation"]?></label>
                                            <input type="hidden" name="keywords_original" value="0">
                                            <input type="checkbox" name="keywords_original" value="1" onChange="changeOriginalContent(this)" <?=$prod_cont->get_keywords_original()?"CHECKED":""?> style="vertical-align:text-top;margin-left:10px;"></div>
                                        <div>
                                            <label for="stop_sync" style="font-weight:bold;"><?=$lang["stop_sync"]?></label>
                                            <input type="checkbox" name="stop_sync_pc[2]" value="8" onChange="StopSync(this)" <?=($prod_cont->get_stop_sync() & (1 << 3))?"CHECKED":""?> style="vertical-align:text-top;margin-left:10px;"></div>
                                        </td>
                                        <td class="value">
                                            <textarea name="keywords" class="input" rows="8"><?=$keywords?></textarea>
                                            <?php
                                            if($language_id == 'en')
                                            {
                                                ?>
                                                <input type="button" value="Generate <?=strtoupper($language_id)?> keywords" onclick="generate_keywords('<?=$sku?>')">
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="field"><?=$lang["youtube_id_1"]?></td>
                                        <td class="value"><input name="youtube_id_1" class="input" value="<?=htmlspecialchars($prod_cont->get_youtube_id_1())?>"></td>
                                        <td class="field"><?=$lang["youtube_caption_1"]?></td>
                                        <td class="value"><input name="youtube_caption_1" class="input" value="<?=htmlspecialchars($prod_cont->get_youtube_caption_1())?>"></td>
                                    </tr>
                                    <tr>
                                        <td class="field"><?=$lang["youtube_id_2"]?></td>
                                        <td class="value"><input name="youtube_id_2" class="input" value="<?=htmlspecialchars($prod_cont->get_youtube_id_2())?>"></td>
                                        <td class="field"><?=$lang["youtube_caption_2"]?></td>
                                        <td class="value"><input name="youtube_caption_2" class="input" value="<?=htmlspecialchars($prod_cont->get_youtube_caption_2())?>"></td>
                                    </tr>
                                    <tr>
                                        <td class="field" valign="top"><?=$lang["short_desc"]?></td>
                                        <td class="value"><textarea name="short_desc" class="input" rows="5"><?=htmlspecialchars(trim($prod_cont->get_short_desc()))?></textarea></td>
                                        <td class="field" valign="top">
                                            <?=$lang["detail_desc"]?>
                                            <div><label for="detail_desc_original" style="font-weight:bold;"><?=$lang["manual_translation"]?></label>
                                                <input type="hidden" name="detail_desc_original" value="0">
                                                <input type="checkbox" name="detail_desc_original" value="1" onChange="changeOriginalContent(this)" <?=$prod_cont->get_detail_desc_original()?"CHECKED":""?> style="vertical-align:text-top;margin-left:10px;"></div>
                                            <div>
                                                <label for="stop_sync" style="font-weight:bold;"><?=$lang["stop_sync"]?></label>
                                                <input type="checkbox" name="stop_sync_pc[3]" value="16" onChange="StopSync(this)" <?=($prod_cont->get_stop_sync() & (1 << 4))?"CHECKED":""?> style="vertical-align:text-top;margin-left:10px;"></div>
                                            </td>
                                            <td class="value"><textarea name="detail_desc" class="input" rows="5"><?=htmlspecialchars(trim($prod_cont->get_detail_desc()))?></textarea></td>
                                        </tr>
                                        <tr>
                                            <td class="field" valign="top">
                                                <?=$lang["contents"]?>
                                                <div><label for="contents_original" style="font-weight:bold;"><?=$lang["manual_translation"]?></label>
                                                    <input type="hidden" name="contents_original" value="0">
                                                    <input type="checkbox" name="contents_original" value="1" onChange="changeOriginalContent(this)" <?=htmlspecialchars($prod_cont->get_contents_original())?"CHECKED":""?> style="vertical-align:text-top;margin-left:10px;"></div>
                                                <div>
                                                    <label for="stop_sync" style="font-weight:bold;"><?=$lang["stop_sync"]?></label>
                                                    <input type="checkbox" name="stop_sync_pc[1]" value="4" onChange="StopSync(this)" <?=($prod_cont->get_stop_sync() & (1 << 2))?"CHECKED":""?> style="vertical-align:text-top;margin-left:10px;"></div>
                                                </td>
                                                <td class="value"><textarea name="contents" class="input" rows="11"><?=htmlspecialchars($prod_cont->get_contents())?></textarea></td>
                                                <td class="field" valign="top">
                                                    <?=$lang["feature"]?>
                                                    <div><label for="feature_original" style="font-weight:bold;"><?=$lang["manual_translation"]?></label>
                                                        <input type="hidden" name="feature_original" value="0">
                                                        <input type="checkbox" name="feature_original" value="1" onChange="changeOriginalContent(this)" <?=htmlspecialchars($prod_cont_ext->get_feature_original())?"CHECKED":""?> style="vertical-align:text-top;margin-left:10px;"></div>
                                                    <div>
                                                        <label for="stop_sync_pce" style="font-weight:bold;"><?=$lang["stop_sync"]?></label>
                                                        <input type="checkbox" name="stop_sync_pce[0]" value="2" onChange="StopSync(this)" <?=($prod_cont_ext->get_stop_sync() & (1 << 1))?"CHECKED":""?> style="vertical-align:text-top;margin-left:10px;"></div>
                                                    </td>
                                                    <td class="value"><textarea name="feature" class="input" rows="11"><?=htmlspecialchars($prod_cont_ext->get_feature())?></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td class="field" valign="top">
                                                        <?=$lang["specification"]?>
                                                        <div><label for="spec_original" style="font-weight:bold;"><?=$lang["manual_translation"]?></label>
                                                            <input type="hidden" name="spec_original" value="0">
                                                            <input type="checkbox" name="spec_original" value="1" onChange="changeOriginalContent(this)" <?=htmlspecialchars($prod_cont_ext->get_spec_original())?"CHECKED":""?> style="vertical-align:text-top;margin-left:10px;"></div>
                                                        <div>
                                                            <label for="stop_sync_specification" style="font-weight:bold;"><?=$lang["stop_sync"]?></label>
                                                            <input type="checkbox" name="stop_sync_pce[1]" value="4" onChange="StopSync(this)" <?=($prod_cont_ext->get_stop_sync() & (1 << 2))?"CHECKED":""?> style="vertical-align:text-top;margin-left:10px;"></div>
                                                        </td>
                                                        <td class="value"><textarea name="specification" class="input" rows="5"><?=htmlspecialchars(@call_user_func(array($prod_cont_ext, "get_specification")))?></textarea></td>
                                                        <td class="field" valign="top"><?=$lang["requirement"]?></td>
                                                        <td class="value"><textarea name="requirement" class="input" rows="5"><?=htmlspecialchars(@call_user_func(array($prod_cont_ext, "get_requirement")))?></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="field" valign="top"><?=$lang["instruction"]?></td>
                                                        <td class="value"><textarea name="instruction" class="input" rows="11"><?=htmlspecialchars(@call_user_func(array($prod_cont_ext, "get_instruction")))?></textarea></td>
                                                        <td class="field" valign="top"><?=$lang["website_status_long_text"]?></td>
                                                        <td class="value"><textarea name="website_status_long_text" class="input" rows="11"><?=htmlspecialchars($prod_cont->get_website_status_long_text())?></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="field" valign="top"><?=$lang["website_status_short_text"]?></td>
                                                        <td class="value"><input type="text" class="input" name="website_status_short_text" value="<?=htmlspecialchars($prod_cont->get_website_status_short_text())?>"></td>
                                                        <td class="field" valign="top">
                                                            <?=$lang["enhanced_listing"]?>
                                                            <div>
                                                                <label for="apply_enhanced_listing" style="font-weight:bold;"><?=$lang["apply"]?></label>
                                                                <input type="hidden" name="apply_enhanced_listing" style="margin-left:60px" value="N">
                                                                <input type="checkbox" name="apply_enhanced_listing" style="margin-left:60px" value="Y" <?=($prod_cont_ext->get_apply_enhanced_listing() == 'Y') ? 'checked' : ''?> style="vertical-align:text-top;margin-left:10px;">

                                                                <!-- jerry codes for HTML Editor -->
                                                                <br>
                                                                <!--<label for="apply_enhanced_listing" style="font-weight:bold;">Edit</label>-->
                                                                <input type="button" name="edit" id="edit" value="Edit" size="60" style="width:50px" onClick="edit_enhanced(event,'<?=$product->get_sku()?>')">
                                                                <!--<br>-->
                                                                <!--<label for="apply_enhanced_listing" style="font-weight:bold;">Full Preview</label>-->
                                                                <input type="button" name="preview_full_enhance" id="preview_full_enhance" value="Preview" size="60" style="margin-left:4px; width:54px" onClick="preview_enhanced(event)">
                                                                <!--<br>-->
                                                                <!--<label for="apply_enhanced_listing" style="font-weight:bold;">Full Preview</label>-->

                                                                <?php if($language_id != '' && $language_id == 'en'){?><input type="button" onClick="confirmEnhanceTranslate('<?=$sku?>','<?=$lang_list_str?>');" style="margin-left:4px; width:60px" value="Translate"><?php }?>

                                                                    <!--<input type="button" name="preview_full_enhance" id="preview_full_enhance" value="Translate" size="60" style="margin-left:4px; width:60px" onClick="preview_enhanced(event)">-->
                                                                    <!-- end jerry codes for HTML Editor -->

                                                                </div>
                                                                <div id = "preview_full_enhance_modal" style="display:none">hello</div>
                                                                <div id = "edit_enhance_modal" style="display:none"><?=$edit_enhance_content?></div>
                                                            <div>
                                                                <label for="stop_sync_pce" style="font-weight:bold;"><?=$lang["stop_sync"]?></label>
                                                                <input type="checkbox" name="stop_sync_pce[2]" value="8" onChange="StopSync(this)" <?=($prod_cont_ext->get_stop_sync() & (1 << 3))?"CHECKED":""?> style="vertical-align:text-top;margin-left:10px;"></div>
                                                            </td>
                                                            <td class="value"><textarea name="enhanced_listing" id="enhanced_listing_modal" class="input" rows="5"><?=htmlspecialchars(@call_user_func(array($prod_cont_ext, "get_enhanced_listing")))?></textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="field" valign="top"><?=$lang["extra_info"]?></td>
                                                            <td class="value" colspan="3"><input type="text" class="input" name="extra_info" value="<?=$prod_cont->get_extra_info()?>">&nbsp;<input type="button" onClick="confirmErase('<?=$sku?>','<?=$language_id?>');" value="<?=$lang["clear_extra_info"]?>"></td>
                                                        </tr>
                                                        <tr class="header">
                                                            <td height="20" colspan="3"><?=$lang["website_image_header"]?></td>
                                                            <td height="20" align="center"><?=$lang["website_image_preview"]?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="field">
                                                                <?=$lang["website_image"]?><br><br>
                                                                Dimension: 200px(W) X 200px(H)
                                                                Format: jpg, jpeg, gif, png
                                                            </td>
                                                            <td colspan="2" class="value">
                                                                <?php
                                                                $prod_image = (array)$prod_image;
                                                                ?>
                                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
                                                                    <tr class="header2">
                                                                        <td width="200px"><?=$lang["t_image_link"]?></td>
                                                                        <td width="50px"><?=$lang["t_priority"]?></td>
                                                                        <td width="50%"><?=$lang["t_file_upload"]?></td>
                                                                        <td width="40px"><?=$lang["t_status"]?></td>
                                                                        <td width="40px"><?=$lang["t_stop_sync"]?></td>
                                                                    </tr>

                                                                    <?php
                                                                    for($i=0; $i<5; $i++)
                                                                    {
                                                                        ?>
                                                                        <tr class="row<?=$i%2?>">
                                                                            <td>
                                                                                <?php
                                                                                if($prod_image[$i])
                                                                                {
                                                                                    $img_f = $prod_image[$i]->get_sku()."_".$prod_image[$i]->get_id().".".$prod_image[$i]->get_image();
                                                                                    if (file_exists(IMG_PH.$img_f))
                                                                                    {
                                                                                        ?>
                                                                                        <a href="<?=base_url()?>images/product/<?=$img_f?>" target="preview"><?=$img_f?></a><br>
                                                                                        <?php
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        echo $lang["no_image"];
                                                                                    }
                                                                                }
                                                                                else
                                                                                {
                                                                                    echo $lang["no_image"];
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php
                                                                                if ($prod_image[$i] && $prod_image[$i]->get_status()== "1")
                                                                                {
                                                                                    $p[$i] = $prod_image[$i]->get_priority()?$prod_image[$i]->get_priority():"1";
                                                                                }
                                                                                ?>
                                                                                <input name="priority[]" dname="Priority" size="3" value="<?=$p[$i]?>" isInteger min=1>
                                                                            </td>
                                                                            <td>
                                                                                <!--                <input type="file" name="image_file<?=$i?>" dname="Image File" size="50" accept="jpg,jpeg,gif,png" isSquare minHeight="200" onChange="checkAccept(this);set_image(this);"><br> -->
                                                                                <input type="file" name="image_file<?=$i?>" dname="Image File" size="50" accept="jpg,jpeg,gif,png" isSquare onChange="checkAccept(this);set_image(this);"><br>
                                                                                <label for="alt_text" style="width:15%"><?=$lang["alt_text"]?></label>:&nbsp;<input name="image_alt_text[]" value="<?=$prod_image[$i]?$prod_image[$i]->get_alt_text():"";?>" style="width:85%">
                                                                            </td>
                                                                            <td align="center">
                                                                                <input type="checkbox" name="im_status[]" value="1" <?=($prod_image[$i] && $prod_image[$i]->get_status()==1)?"CHECKED":""?>>
                                                                            </td>
                                                                            <td align="center">
                                                                                <input type="checkbox" name="im_stop_sync_pc[]" value="1" <?=($prod_image[$i] && $prod_image[$i]->get_stop_sync_image()==1)?"CHECKED":""?>>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td>
                                                                            Update Category Page Image?   <input style="vertical-align:middle" type="checkbox" name="update_image_file" value="1">

                                                                        </td>
                                                                        <td></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td class="value">
                                                                <table align="center">
                                                                    <tr>
                                                                        <td colspan=4 style="border: 0px; padding:0px 0px 0px 0px">
                                                                            <?php
                                                                            if ($prod_image[0] && $prod_image[0]->get_status()== "1")
                                                                            {
                                                                                ?>
                                                                                <img id='p_img' alt='<?=$prod_image[0]?$prod_image[0]->get_alt_text():""?>' src='<?=getImageUrl($prod_image[0]->get_image(), "l", $prod_image[0]->get_sku(), $prod_image[0]->get_id())?>'>
                                                                                <?php
                                                                            }
                                                                            else
                                                                            {
                                                                                ?>
                                                                                <!-- <img id='p_img' src='<?=base_url()?>images/product/imageunavailable_l.jpg'>  -->
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr align="center">
                                                                        <?php
                                                                        for($i=1; $i<5; $i++)
                                                                        {
                                                                            if($prod_image[$i] && $prod_image[$i]->get_status()== "1")
                                                                            {
                                                                                echo "<td style='border:0px; padding:2px'>";
                        //echo "<img style='padding:2px' alt='".$prod_image[$i]->get_alt_text()."' src='".base_url()."/images/product/".$prod_image[$i]->get_sku()."_".$prod_image[$i]->get_id()."_s.".$prod_image[$i]->get_image()."?".$prod_image[$i]->get_modify_on()."' onmouseover=\"this.style.border='2px solid blue';this.style.padding='0px 0px 0px 0px';\" onmouseout=\"this.style.border='0px'; this.style.padding='2px'\" onclick=\"changeImage(this);\" >";
                                                                                echo "<img style='padding:2px' alt='".$prod_image[$i]->get_alt_text()."' src='" . getImageUrl($prod_image[$i]->get_image(), "s", $prod_image[$i]->get_sku(), $prod_image[$i]->get_id()) ."'>";
                                                                                echo "</td>";
                                                                            }
                                                                            else
                                                                            {
                        /*
                        echo "<td style='border:0px; padding:2px'>";
                        echo "<img style='padding:2px' src='".base_url()."/images/product/imageunavailable_s.jpg' onmouseover=\"this.style.border='2px solid blue';this.style.padding='0px 0px 0px 0px';\" onmouseout=\"this.style.border='0px'; this.style.padding='2px'\" >";
                        echo "</td>";
                        */
                    }
                }

                ?>
            </tr>
        </table>
    </td>
</tr>

<tr class="header">
    <td height="20" colspan="4"><?=$lang["promo_banner"]?></td>
</tr>
<tr>
    <td class="value" align="left" colspan="4">
        <?php
        if($country_list)
        {
            ?>
            <div id="div_promo_banner_tabs">
                <?php
                // $prod_banner = (array)$prod_banner;
                foreach ($country_list as $country_obj)
                {
                    $country_id = ($country_obj->get_id());
                    $country_name = $country_obj->get_name();
                    ?>
                    <div id="div_tab_<?=$country_id?>" class="x-tab" title="<?=$country_name?>">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
                            <col width="192"><col>
                            <tr>
                                <td class="field"><?=$lang['promo_banner_image']?><br /><br />
                                    Dimension: 920px(W) X 156px(H)
                                    Format: jpg, jpeg, gif, png
                                </td>
                                <td class="value">
                                    <?php
                                    foreach ($prod_banner_obj as $pb_country_id=>$prod_banner)
                                    {
                                        if($pb_country_id == $country_id && !empty($prod_banner))
                                        {
                                            $prod_banner_file = $prod_banner->get_sku()."_".$country_id.".".$prod_banner->get_image();
                                            if (file_exists(PROD_BANNER_PH.$prod_banner_file))
                                            {

                                                ?>
                                                <a href="<?=base_url()?>images/product_banner/<?=$prod_banner_file?>" target="preview"><?=$prod_banner_file?></a><br>
                                                <?php
                                            }
                                            else
                                            {
                                                echo $lang["no_image"];
                                            }

                                            $pb_alt_text = $prod_banner->get_alt_text();
                                            $target_url = '';
                                            $pb_url = $prod_banner->get_target_url();
                                            $target_type = $prod_banner->get_target_type();
                                            $banner_status = $prod_banner->get_status();
                                            if($pb_url)
                                            {
                                                $pos = strpos($pb_url, 'http');

                                                if($pos === false)
                                                {
                                                    $target_url = base_url().$pb_url;
                                                }
                                                else
                                                {
                                                    $target_url = $pb_url;
                                                }
                                            }
                                            if($target_type == "E")
                                            {
                                                $pb_link_type = "_blank";
                                            }
                                            else
                                            {
                                                $pb_link_type = "";
                                            }

                                            if (file_exists(PROD_BANNER_PH.$prod_banner_file))
                                            {
                                                if($pb_url)
                                                {
                                                    ?>
                                                    <a href="<?=$target_url?>" target="<?=$pb_link_type?>">
                                                        <?php
                                                    }
                                                    ?>
                                                    <img width="920px" height="156px" src='<?=base_url()?>images/product_banner/<?=$prod_banner_file?>'><br /><br />
                                                    <?php
                                                    if($pb_url)
                                                    {
                                                        ?>
                                                    </a>
                                                    <?php
                                                }
                                            }
                                        }
                                        if($pb_country_id == $country_id)
                                        {
                                            ?>
                                            <input type="file" name="prod_banner_<?=$country_id?>" size="60" accept="jpg,jpeg,gif,png" onChange="checkAccept(this);set_image(this);">
                                            <br><label for="banner_alt_text" style="width:15%"><?=$lang["banner_alt_text"]?></label>:&nbsp;<input name="banner_alt_text_<?=$country_id?>" value="<?=$prod_banner?$prod_banner->get_alt_text():"";?>" style="width:25%">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="field"><?=$lang['target_url']?></td>
                                        <td class="value"><input name="banner_target_url_<?=$country_id?>" class="input" value="<?=$prod_banner?$prod_banner->get_target_url():"";?>"></td>
                                    </tr>
                                    <tr>
                                        <td class="field"><?=$lang["target_type"]?></td>
                                        <td class="value">
                                            <select id="link_type" name="banner_target_type_<?=$country_id?>">
                                                <option value="E" <?=$target_type=="E"?"SELECTED":""?>><?=$lang["external"]?></option>
                                                <option value="I" <?=$target_type=="I"?"SELECTED":""?>><?=$lang["internal"]?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="field"><?=$lang["status"]?></td>
                                        <td class="value">
                                            <select name="banner_status_<?=$country_id?>">
                                                <option value="0" <?=$banner_status=="0"?"SELECTED":""?>><?=$lang["inactive"]?></option>
                                                <option value="1" <?=$banner_status=="1"?"SELECTED":""?>><?=$lang["active"]?></option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <?php
                        }
                    }

                }
                ?>
            </div>
            <?php
        }
        ?>
    </td>
</tr>







<tr class="header">
    <td height="20" colspan="4"><?=$lang["product_banner"]?></td>
</tr>
<tr>
    <td class="value" align="left" colspan="4">
        <?php
        if($lang_list)
        {
            ?>
            <div id="div_banner_tabs">
                <?php
                foreach ($lang_list as $lang_obj)
                {
                    $cur_lang_id = $lang_obj->get_lang_id();
                    $cur_name = $lang_obj->get_description();
                    ?>
                    <div id="div_tab_<?=$cur_lang_id?>" class="x-tab" title="<?=$cur_name?>">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
                            <col width="192"><col>
                            <tr>
                                <td class="field"><?=$lang['product_banner_image']?><br /><br />
                                    Dimension: 534px(W) X 170px(H)
                                    Format: jpg, jpeg, gif, png
                                </td>
                                <td class="value">
                                    <?php
                                    if($product_banner[$cur_lang_id])
                                    {
                                        $result_link = '';
                                        $link = $product_banner[$cur_lang_id]->get_link();
                                        $link_type = $product_banner[$cur_lang_id]->get_link_type();
                                        $status = $product_banner[$cur_lang_id]->get_status();

                                        $pos = strpos($link, 'http');
                                        if($pos === false)
                                        {
                                            $result_link = base_url().$link;
                                        }
                                        else
                                        {
                                            $result_link = $link;
                                        }
                                        if($link_type == "E")
                                        {
                                            $type = "_blank";
                                        }
                                        else
                                        {
                                            $type = "";
                                        }
                                    }

                                    if ($prod_banner_w_graphic[$cur_lang_id])
                                    {
                                        if (file_exists("images/".$prod_banner_w_graphic[$cur_lang_id]->get_location().$prod_banner_w_graphic[$cur_lang_id]->get_file()))
                                        {
                                            if($link)
                                            {
                                                ?>
                                                <a href="<?=$result_link?>" target="<?=$type?>">
                                                    <?php
                                                }
                                                ?>
                                                <img width="534px" height="170px" src='<?=base_url()?>images/<?=$prod_banner_w_graphic[$cur_lang_id]->get_location().$prod_banner_w_graphic[$cur_lang_id]->get_file()?>'><br /><br />
                                                <?php
                                                if($link)
                                                {
                                                    ?>
                                                </a>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                    <input type="file" name="banner_file_<?=$cur_lang_id?>" size="60" accept="jpg,jpeg,gif,png" minWidth="534" minHeight="170" onChange="checkAccept(this);set_image(this);">
                                </td>
                            </tr>
                            <tr>
                                <td class="field"><?=$lang['link']?></td>
                                <td class="value"><input name="banner_link_<?=$cur_lang_id?>" class="input" value="<?php if($product_banner[$cur_lang_id]){echo $product_banner[$cur_lang_id]->get_link();}?>"></td>
                            </tr>
                            <tr>
                                <td class="field"><?=$lang["link_type"]?></td>
                                <td class="value">
                                    <select id="link_type" name="link_type_<?=$cur_lang_id?>">
                                        <option value="E" <?=$link_type=="E"?"SELECTED":""?>><?=$lang["external"]?></option>
                                        <option value="I" <?=$link_type=="I"?"SELECTED":""?>><?=$lang["internal"]?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="field"><?=$lang["status"]?></td>
                                <td class="value">
                                    <select name="status_<?=$cur_lang_id?>">
                                        <option value="0" <?=$status=="0"?"SELECTED":""?>><?=$lang["inactive"]?></option>
                                        <option value="1" <?=$status=="1"?"SELECTED":""?>><?=$lang["active"]?></option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        ?>
    </td>
</tr>
<tr class="header">
    <td height="20" colspan="4"><?=$lang["product_spec"]?></td>
</tr>
<tr>
    <td class="value" align="left" colspan="4">
        <?php
        if ($lang_list)
        {
            ?>
            <div id="div_tabs">
                <?php
                foreach ($lang_list as $lang_obj)
                {
                    $cur_lang_id = $lang_obj->get_lang_id();
                    $cur_name = $lang_obj->get_description();
                    ?>
                    <div id="div_tab_<?=$cur_lang_id?>" class="x-tab" title="<?=$cur_name?>">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
                            <col width="200"><col width="100"><col width="200"><col width="200"><col><col width="100">
                            <?php
                            if($psd_list[$cur_lang_id])
                            {
                                foreach($psd_list[$cur_lang_id] as $psg_name=>$psd_obj_list)
                                {
                                    ?>
                                    <tr class="header">
                                        <td colspan='6'><?=$psg_name?></td>
                                    </tr>
                                    <tr class="field">
                                        <td rowspan='2'>Product Specification</td>
                                        <td rowspan='2'>Unit</td>
                                        <td colspan='2' align='center'>Input Minimum Value for Fixed Value</td>
                                        <td rowspan='2'>Result in Website</td>
                                        <td rowspan='2'>Populate for ALL language</td>
                                    </tr>
                                    <tr class="field">
                                        <td>Minimum Value (Fixed Value)</td>
                                        <td>Maximum Value</td>
                                    </tr>
                                    <?php
                                    if ($psd_obj_list)
                                    {
                                        foreach($psd_obj_list AS $ps_name=>$psd_obj)
                                        {
                                            $unit_id = $psd_obj->get_unit_id();
                                            $ps_id = $psd_obj->get_ps_id();
                                            $cat_id = $psd_obj->get_cat_id();
                                            $text = $psd_obj->get_text();
                                            if($psd_obj->get_start_value())
                                            {
                                                $start_value = $psd_obj->get_start_value() * 1;
                                            }
                                            if($psd_obj->get_end_value())
                                            {
                                                $end_value = $psd_obj->get_end_value() * 1;
                                            }
                                            $start_standardize_value = $psd_obj->get_start_standardize_value();
                                            $end_standardize_value = $psd_obj->get_end_standardize_value();
                                            ?>
                                            <tr>
                                                <td class="value"><?=$ps_name?></td>
                                                <td class="value"><?=$psd_obj->get_unit_id()?></td>
                                                <?php
                                                if($unit_id == 'txt')
                                                {
                                                    ?>
                                                    <td class="value" colspan = '2'><input name="ps[<?=$cur_lang_id?>][<?=$ps_id?>][<?=$unit_id?>][text]" class="input" value="<?=$text?>"></td>
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                    <td class="value"><input name="ps[<?=$cur_lang_id?>][<?=$ps_id?>][<?=$unit_id?>][start_value]" class="input" value="<?=$start_value?>"></td>
                                                    <td class="value"><input name="ps[<?=$cur_lang_id?>][<?=$ps_id?>][<?=$unit_id?>][end_value]" class="input" value="<?=$end_value?>"></td>
                                                    <input type="hidden" name="ps[<?=$cur_lang_id?>][<?=$ps_id?>][<?=$unit_id?>][start_standardize_value]" value="<?=$start_standardize_value?>">
                                                    <input type="hidden" name="ps[<?=$cur_lang_id?>][<?=$ps_id?>][<?=$unit_id?>][end_standardize_value]" value="<?=$end_standardize_value?>">
                                                    <?php
                                                }
                                                ?>
                                                <td class="value">
                                                    <?php
                                                    if($text || $start_value)
                                                    {
                                                        if($unit_id == 'txt')
                                                        {
                                                            echo $text;
                                                        }
                                                        else
                                                        {
                                                            if($end_value)
                                                            {
                                                                echo $start_value." - ".$end_value." (".$unit_id.")";
                                                            }
                                                            else
                                                            {
                                                                echo $start_value." (".$unit_id.")";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td class="value">
                                                    <input type="checkbox" name="populate[<?=$ps_id?>]" value="<?=$cur_lang_id?>"<?=$prod_feed[$feed]["status"]?"CHECKED":""?>>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                            }
                            ?>
                        </table>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        ?>
    </td>
</tr>
<tr class="header">
    <td height="20" colspan="4"><?=$lang["price_comparison_feeds"]?></td>
</tr>
<tr>
    <td colspan="4" class="value">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="60"><col width="150"><col>
            <tr class="header2">
                <td><?=$lang["in_feed"]?></td>
                <td><?=$lang["feed"]?></td>
                <td><?=$lang["feed_specific"]?></td>
            </tr>
            <?php
            for ($i=0; $i<count($ar_feed); $i++)
            {
                $feed = $ar_feed[$i];
                ?>
                <tr class="row<?=$i%2?>">
                    <td><input type="checkbox" name="prod_feed[<?=$feed?>][status]" value="1"<?=$prod_feed[$feed]["status"]?"CHECKED":""?>></td>
                    <td><?=$feed?></td>
                    <td>
                        <?php
                        switch ($feed)
                        {
                            case "KELKOO":
                            ?>
                            <?=$lang["category"]?>:
                            <select name="prod_feed[<?=$feed?>][value_1]" onChange="ChangeFeedCat('KELKOO', document.fm.elements['prod_feed[KELKOO][value_2]'], document.fm.elements['prod_feed[KELKOO][value_3]'], this.value)">
                                <option value=""><?=$lang["please_select"]?>
                                </select> &nbsp;
                                <?=$lang["type"]?>:
                                <select name="prod_feed[<?=$feed?>][value_2]" onChange="ChangeFeedCat('KELKOO', document.fm.elements['prod_feed[KELKOO][value_3]'], '', document.fm.elements['prod_feed[KELKOO][value_1]'].value, this.value)">
                                    <option value=""><?=$lang["please_select"]?>
                                    </select> &nbsp;
                                    <?=$lang["offer_type"]?>:
                                    <select name="prod_feed[<?=$feed?>][value_3]">
                                        <option value=""><?=$lang["please_select"]?>
                                        </select>
                                        <?php
                                        break;
                                        case "PRICERUNNER":
                                        ?>
                                        <?=$lang["category"]?>:
                                        <input name="prod_feed[<?=$feed?>][value_1]" size="60" value="<?=htmlspecialchars($prod_feed[$feed]["value_1"])?>"> &nbsp;
                                        <?=$lang["link"]?>:
                                        <input name="prod_feed[<?=$feed?>][value_2]" size="60" value="<?=htmlspecialchars($prod_feed[$feed]["value_2"])?>"> &nbsp;
                                        <?php
                                        break;
                                        case "PRICEGRABBER":
                                        ?>
                                        <?=$lang["category"]?>:
                                        <input name="prod_feed[<?=$feed?>][value_1]" size="60" value="<?=htmlspecialchars($prod_feed[$feed]["value_1"])?>">
                                        <?php
                                        break;
                                        case "PRICEMINISTER":
                                        ?>
                                        <?=$lang["ad_text"]?>:
                                        <input name="prod_feed[<?=$feed?>][value_1]" size="100" value="<?=htmlspecialchars($prod_feed[$feed]["value_1"])?>">
                                        <?php
                                        break;
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </td>
            </tr>
        </tr>
        <tr class="header">
            <td height="20" colspan="4"><?=$lang["google_base_feeds"]?></td>
        </tr>
        <tr>
            <td colspan="4" class="value">
                <div id="div_googlebase_tabs">
                    <?php
                    foreach ($country_list as $country_obj)
                    {
                        $valid_country = $google_feed_arr;
                        if(in_array($country_obj->get_country_id(), $valid_country))
                        {
                            $cur_country_id = $country_obj->get_country_id();
                            $cur_name = $country_obj->get_name();
                            ?>
                            <div id="div_tab_<?=$cur_country_id?>" class="x-tab" title="<?=$cur_name?>">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
                                    <col width="192"><col>
                                    <tr>
                                        <td class="field"><?=$lang["google_prod_cat"]?></td>
                                        <td class="value">
                                            <?=isset($google_cat_w_produc_name[$cur_country_id])?$google_cat_w_produc_name[$cur_country_id]->get_ext_name():""?>
                                        </td>
                                    </tr>
                                </tr>
                                <td class="field"><?=$lang["google_product_name"]?></td>
                                <td class="value">
                                    <input type="text" maxlength=70 name="google_product_name_<?=$cur_country_id?>" size=100 value="<?=isset($google_cat_w_produc_name[$cur_country_id])?$google_cat_w_produc_name[$cur_country_id]->get_product_name():""?>"> (Maximum 70 characters)
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php
                }
            }
            ?>
        </td>
    </tr>
    <tr class="header">
        <td height="20" colspan="4"><?=$lang["bundle_list"]?></td>
    </tr>
    <?php
    if ($bundle_list)
    {
        foreach ($bundle_list as $bundle)
        {
            ?>
            <tr class="value">
                <td class="value" colspan="4"><a href="<?=base_url()?>marketing/bundle/view/<?=$bundle->get_prod_sku()?>" target="bundle">[<?=$bundle->get_prod_sku()?>] - <?=$bundle->get_bundle_name()?></a></td>
            </tr>
            <?php
        }
    }
    ?>
    <?php
}
?>
<td colspan="2" height="40" style="border-right:0px;" class="tb_detail"><input type="button" name="back" value="<?=$lang['back_list']?>" onClick="Redirect('<?=isset($_SESSION['LISTPAGE'])?$_SESSION['LISTPAGE']:base_url().'/marketing/product'?>')"></td>
<td colspan="2" align="right" style="border-left:0px; padding-right:8px;" class="tb_detail">
    <input type="submit" name="submit" id="submit" value="<?=$lang['cmd_button']?>">
</td>
</tr>
</table>
<input name="sku" type="hidden" value="<?=$product->get_sku()?>">
<input name="colour_id" type="hidden" value="<?=$product->get_colour_id()?>">
<input type="hidden" name="posted" value="1">
</form>
<form name="gen_keywords" id="gen_keywords"  method="POST" onSubmit="return">
    <input type="hidden" name="gen_post" id="gen_post" value="">
    <input type="hidden" name="gen_model_1" id="gen_model_1" value="">
    <input type="hidden" name="gen_model_2" id="gen_model_2" value="">
    <input type="hidden" name="gen_model_3" id="gen_model_3" value="">
    <input type="hidden" name="gen_model_4" id="gen_model_4" value="">
    <input type="hidden" name="gen_model_5" id="gen_model_5" value="">
    <input type="hidden" name="gen_brand_id" id="gen_brand_id" value="">
    <input type="hidden" name="gen_colour_id" id="gen_colour_id" value="">
    <input type="hidden" name="gen_series" id="gen_series" value="">
    <input type="hidden" name="lang_id" id="lang_id" value="<?=$language_id?>">
</form>
<script language="javascript">

    <!--
    InitFCat(document.fm.freight_cat_id);
    ChangeCat('0', document.fm.cat_id);
    <?php
    if ($cmd == "edit" || $this->input->post("posted") || $prod_grp_cd)
    {
        ?>
        document.fm.cat_id.value = '<?=$product->get_cat_id()?>';
        ChangeCat('<?=$product->get_cat_id()?>', document.fm.sub_cat_id);
        document.fm.sub_cat_id.value = '<?=$product->get_sub_cat_id()?>';
        ChangeCat('<?=$product->get_sub_cat_id()?>', document.fm.sub_sub_cat_id);
        document.fm.sub_sub_cat_id.value = '<?=$product->get_sub_sub_cat_id()?>';
//ChangeFCat(document.fm.freight_cat_id.value, document.fm.freight_weight);
document.fm.freight_cat_id.value = '<?=$product->get_freight_cat_id()?>';
ChangeFCat(document.fm.freight_cat_id.value, document.fm.freight_weight);
var scrollerMenu = new Ext.ux.TabScrollerMenu({
    maxText  : 64,
    pageSize : 1
});



var name_tabs = new Ext.TabPanel({
    applyTo: 'div_tabs',
    autoTabs:true,
    activeTab:0,
    deferredRender:false,
    border:false,
    enableTabScroll:true,
    autoHeight: true,
    defaults: {autoScroll:true},
    width: '1245px',
    plugins         : [ scrollerMenu ]
});

var name_tabs = new Ext.TabPanel({
    applyTo: 'div_banner_tabs',
    autoTabs:true,
    activeTab:0,
    deferredRender:false,
    border:false,
    enableTabScroll:true,
    autoHeight: true,
    defaults: {autoScroll:true},
    width: '1245px',
    plugins         : [ scrollerMenu ]
});

var name_tabs = new Ext.TabPanel({
    applyTo: 'div_promo_banner_tabs',
    autoTabs:true,
    activeTab:0,
    deferredRender:false,
    border:false,
    enableTabScroll:true,
    autoHeight: true,
    defaults: {autoScroll:true},
    width: '1245px',
    plugins         : [ scrollerMenu ]
});

var name_tabs = new Ext.TabPanel({
    applyTo: 'div_googlebase_tabs',
    autoTabs:true,
    activeTab:0,
    deferredRender:false,
    border:false,
    enableTabScroll:true,
    autoHeight: true,
    defaults: {autoScroll:true},
    width: '1245px',
    plugins         : [ scrollerMenu ]
});

var warranty_new_field_counter;

//SBF 2701
jQuery(function(){
    var lang_id = "<?=$language_id?>";
    var lang_to_country = {};
    lang_to_country['en'] = 'En';
    lang_to_country['fr'] = 'Fr';
    lang_to_country['it'] = 'It';
    lang_to_country['es'] = 'Sp';
    jQuery(".x-tab-strip-text:contains('"+lang_to_country[lang_id]+"')").click();

        // SBF 4402 warranty for different countries

        <?php if($selling_platform_list) {?>
            warranty_new_field_counter = 0 + <?php echo $this->warranty_model->product_warranty_service->get_dao()->get_num_rows(array('sku' => $product->get_sku())); ?>;

            document.getElementById('warranty_add_sign_btn').addEventListener('click',function(){
                var new_warranty_field = document.createElement("span");
                new_warranty_field.className = 'warranty_country_section';
                var inner_html = '<select class="warranty_country" id="warranty_country_'+warranty_new_field_counter+'" name="warranty_country_'+warranty_new_field_counter+'">';

                <?php
                foreach ($selling_platform_list as $country_obj)
                {
                    $platform_id_list[] = $country_obj->getSellingPlatformId();
                }
                ?>
                var platform_id_list = ["<?php echo implode('\',\'', $platform_id_list) ?>"];
                var existing_platform_list = document.getElementsByClassName('warranty_country');

                if(existing_platform_list.length ==  platform_id_list.length)
                {
                    alert("No more platform");
                    return false;
                }

                for(i=0;i<platform_id_list.length;i++)
                {
                    for(k=0;k<existing_platform_list.length;k++)
                    {
                        var skip = false;
                        if(existing_platform_list[k].value.trim() == platform_id_list[i])
                        {
                            skip = true;
                            break;
                        }
                    }
                    if(!skip)
                    {
                        inner_html = inner_html + "<option value='"+ platform_id_list[i] +"'>"+platform_id_list[i]+"</option>";
                    }
                }

                <?php
                echo 'inner_html += "</select>";';
                echo 'inner_html += "<select name=\"warranty_in_month_"+ warranty_new_field_counter +"\" >";';
                echo 'inner_html += "<option value=\'\'>Remove</option>";';

                foreach($warranty_list as $warranty_period)
                {
                    echo 'inner_html += "<option value=\'' . $warranty_period . '\'>' . $warranty_period .'</option>";';
                }
                ?>
                inner_html += '</select>';
                new_warranty_field.innerHTML = inner_html;
                document.getElementById('warranty_value').appendChild(new_warranty_field);
                warranty_new_field_counter++;
            });
<?php }?>

});

<?php
}
?>


<?=$edit_enhance_js?>
function preview_enhanced(event)
{
    var b = $("#enhanced_listing_modal").val();
    var content = $.parseHTML(b);
    //alert(content);
    $("#preview_full_enhance_modal").html(content).dialog({
        width: 650,
        height: 600,
        title: "Full Preview of HTML Codes",
        resizable: false,
        modal: true,
        position: {my: "center", at: "center"},
        buttons: {
            "Close Preview": function() {
                $(this).dialog("close");
            }
        }

    });
//$("#enhanced_listing_modal").html(b).dialog("open");
}

function hscode_edit(event, sku)
{
    alert('Hello');
    var b = $("#enhanced_listing_modal").val();
    var content = $.parseHTML(b);
    //alert(content);
    $("#preview_full_enhance_modal").html(content).dialog({
        width: 650,
        height: 600,
        title: "HS Code and Duty Percent",
        resizable: false,
        modal: true,
        position: {my: "center", at: "center"},
        buttons: {
            "Close Preview": function() {
                $(this).dialog("close");
            }
        }

    });
//$("#enhanced_listing_modal").html(b).dialog("open");
}

function fillduty(ccode, duty, cid){
    $('#duty_'+cid).val(duty);
}

function generate_keywords(sku)
{
    var model_1 = document.getElementById('model_1').value;

    if(model_1 == "" || model_1 == " ")
    {
        alert('Model_1 cannot be blank.');
        window.location.reload();
    }
    else
    {
        document.getElementById("gen_post").value = 1;
        document.getElementById("gen_model_1").value = model_1;

        var brand_id = "<?=$product->get_brand_id()?>";
        document.getElementById("gen_brand_id").value = brand_id;

        var model_2 = document.getElementById('model_2').value;
        document.getElementById("gen_model_2").value = model_2;

        var model_3 = document.getElementById('model_3').value;
        document.getElementById("gen_model_3").value = model_3;

        var model_4 = document.getElementById('model_4').value;
        document.getElementById("gen_model_4").value = model_4;

        var model_5 = document.getElementById('model_5').value;
        document.getElementById("gen_model_5").value = model_5;

        var colour_id = "<?=$product->get_colour_id()?>";
        document.getElementById("gen_colour_id").value = colour_id;

        var series = document.getElementById('series').value;
        document.getElementById("gen_series").value = series;

        document.getElementById("gen_keywords").submit();
    }

}

function SaveChange(el)
{
    if (!(confirm("<?=$lang["save_change"]?>")))
    {
        el.checked = false;
    }
}

function confirmErase(sku, lang_id)
{
    if(confirm("<?=$lang["erase_all_extra_info"]?>"))
    {
        document.location.href = '<?=base_url()?>marketing/product/empty_extra_info/'+sku+'/'+lang_id;
    }
    else
    {
        return false;
    }
}

function confirmTranslate(sku, lang_id)
{
    if(confirm("<?=$lang['translate_all_content']?>"))
    {
        var google_cat_id_str = '';
        var valid_country = <?=json_encode($google_feed_arr)?>;
        for(var i = 0; i< valid_country.length; i++)
        {
            var google_cat_id = jQuery("#google_cat_"+valid_country[i]).val();
            google_cat_id_str += valid_country[i]+"="+google_cat_id+"&";
        }
        document.location.href = '<?=base_url()?>marketing/product/translate_product_content/'+sku+'/'+lang_id+'?'+ google_cat_id_str;
    }
    else
    {
        return false;
    }
}

function confirmEnhanceTranslate(sku, lang_id)
{
    if(confirm("Confirm Translate Enhance Section to all Language"))
    {
        var google_cat_id_str = '';
        var valid_country = <?=json_encode($google_feed_arr)?>;
        for(var i = 0; i< valid_country.length; i++)
        {
            var google_cat_id = jQuery("#google_cat_"+valid_country[i]).val();
            google_cat_id_str += valid_country[i]+"="+google_cat_id+"&";
        }
        document.location.href = '<?=base_url()?>marketing/product/translate_product_enhance_content/'+sku+'/'+lang_id+'?'+ google_cat_id_str;
    }
    else
    {
        return false;
    }
}

function confirmGenerate(sku, lang_id)
{
    if(confirm("<?=$lang['translate_all_keywords']?>"))
    {
        document.location.href = '<?=base_url()?>marketing/product/gen_translate_keywords/'+sku+'/'+lang_id;
    }
    else
    {
        return false;
    }
}

function check_master_sku(ele)
{
    regex = '^(\\d{1,5})-([A-Z]{2})-([A-Z]{2})$';
    if (ele.value != "")
    {
        if (!regExCheck(regex, ele.value, 'i'))
        {
            alert("'"+ ele.value+"' " + 'wrong master sku format');
            ele.focus();
            return false;
        }
    }
}

function edit_master_sku(ele)
{
    document.getElementsByName('master_sku')[0].removeAttribute('disabled');
}

function changeImage(im)
{
    p = document.getElementById('p_img');

    temp = p.src;
    p.src = im.src.replace("_s","_l");
    im.src = temp.replace("_l","_s");
}

function changeOriginalContent(ele)
{
    if(ele.checked)
    {
        var r = confirm("Is this manually translated?");
        if(r == false)
        {
            ele.checked = false;
        }
    }
    else
    {
        var r = confirm("Is this Machine translated?");
        if(r == false)
        {
            ele.checked = true;
        }
    }
}


</script>
<?=$notice["js"]?>
</div>
</body>
</html>
