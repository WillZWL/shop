<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/colorbox.css" />
<script type="text/javascript" src="<?=base_url()?>js/jquery-colorbox.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>/marketing/complementary_acc/complementary_acc_js"></script>
<script language="javascript">

    function showHide(country)
    {
        if(country)
        {
            var ctry = 'c_'+country;
            var target = 'caprod_row_'+country;
            var sign = 'sign_'+country;
            var targetobj = document.getElementById(target);
            var ctry_header = document.getElementById(ctry);
            var signobj = document.getElementById(sign);
            if(targetobj && signobj)
            {
                if(targetobj.style.display == 'block')
                {
                    targetobj.style.display = 'none';
                    signobj.innerHTML = '+';
                    ctry_header.style.display = 'block';
                }
                else if(targetobj.style.display == 'none')
                {
                    targetobj.style.display = 'block';
                    signobj.innerHTML = '-';
                    ctry_header.style.display = 'none';
                }
                else
                {
                    return;
                }
            }
        }
    }

</script>

</head>
<body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" class="frame_left">
<script type="text/javascript">
    $(document).ready
    (
        function()
        {
            $(".iframe").colorbox({iframe:true, width:"40%", height:"80%"});
        }
    );
</script>

<?php
    $ca_status = array(0=>"inactive", 1=>"active");
?>
<div id="main" style="width:auto">
<?=$notice["img"]?>

<?php
if($sku != "")
{
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <td height="60" align="left" style="padding-left:8px;">
    <div style="float:left"><img src=''> &nbsp;</div>
    <b style="font-size: 12px; color: rgb(0, 0, 0);"><?=$lang["header"]?></b><br><?=$lang["header_message"]." - "?><b><a href="<?=$website_link."mainproduct/view/".$sku?>" target="_blank"><font style="text-decoration:none; color:#000000; font-size:14px;"><?=$sku." - ".$mainprod->get_name()?><?=$mainprod->get_clearance()?" <span style='color:#0072E3; font-size:14px;'>(Clearance)</span>":""?></font></a></b><br><?=$lang["master_sku"]." ".$master_sku?>
    <br><b>DESTINATION COUNTRIES:</b>
    </td>
</tr>
<?php
        if ($objcount)
        {
            # shows destination countries for each main product sku
            foreach($proddata as $country_id=>$value)
            {
                $country = $country_id;
                $calist = $value["caprodlist"];     # complementary accessory info
?>
<tr class="header">
    <td height="20" align="left" style="padding-left:8px;"><b style="font-size: 12px; color: rgb(255, 255, 255);"><a href="javascript:showHide('<?=$country?>');"><span style="padding-right:15px;" id='sign_<?=$country?>'>+</span></a><?=$country_id." - ".$value["ctryobj"]->get_name()?>
        </b>
    </td>
</tr>
<tr id="c_<?=$country?>" style="display:block;">
        <td height="5"></td>
</tr>
<tr id="caprod_row_<?=$country?>" style="display:none;">

    <td align="left">
<?
                # if mapped complementary accessory list exists for this SKU
                if(!empty($calist))
                {
?>
        <form name="update" action="<?=base_url()?>marketing/complementary_acc/view/<?=$sku?>" method="POST" onSubmit="return CheckForm(this)">
        <table border="0" cellpadding="0" cellspacing="0" width="1042px" class="tb_main" name="mapped_ca" id="mapped_ca">
            <tr class="header">
                <td width="5%"></td>
                <td width="15%">Accessory SKU</td>
                <td width="30%">Name</td>
                <td width="20%">Category</td>
                <td width="20%">Sub-category</td>
                <td width="10%">Status</td>
            </tr>
<?php
                    # loop through each complementary accessory mappeed to this SKU-dest_ctry
                    foreach ($calist as $k => $caobj)
                    {
                        $castatus_radio ="";
                        $casku = $caobj->get_accessory_sku();
                        $class = "field";
                        if($caobj->get_ca_status() == 0)    $class = "bvalue1";
?>
            <tr>
                <td class="field"  style="cursor:pointer;"><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$caobj->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$caobj->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$caobj->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$caobj->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$caobj->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$caobj->get_modify_by()?>'></td>
                <td class="field"><?=$caobj->get_accessory_sku()?></td>
                <td class="field"><?=$caobj->get_name()?></td>
                <td class="field"><?=$caobj->get_category()?></td>
                <td class="field"><?=$caobj->get_sub_cat()?></td>
                <td class="<?=$class?>" align="left">
<?php
                        # status for complementary acc mapping - user can deactivate/activate as they want
                        foreach ($ca_status as $ca_statusid => $value)
                        {
                            $checked = "";
                            if($ca_statusid == $caobj->get_ca_status())
                                $checked = " checked";
                            $castatus_radio .= <<<HTML
                                            <input type="radio" name="info[$casku][status]" value="$ca_statusid" $checked/>$value<br>
HTML;
                        }
                        echo $castatus_radio;
?>
                    <input type="hidden" name="info[<?=$casku?>][ctry_id]" value=<?=$country?>>
                </td>
            </tr>
<?php
                    }
?>
            <tr>
                <td class="field" colspan="6" align="right">
                    <input type="hidden" name="posted" id="posted" value="1">
                    <input type="hidden" name="action" id="action" value="update">
                    <input type="submit" value="Update">
                </td>
            </tr>
        </table>
        </form>
<?php
                }

        # add new complementary accessory below
?>
        <form name="add_new" action="<?=base_url()?>marketing/complementary_acc/view/<?=$sku?>" method="POST" onSubmit="return CheckForm(this)">
        <table border="0" cellpadding="0" cellspacing="0" width="1042px" class="tb_main" name="new_ca_<?=$country?>" id="new_ca_<?=$country?>">
            <col width="5%"><col width="15%"><col width="30%"><col width="20%"><col width="20%"><col width="10%">
            <tr>
                <td height="5" colspan="6" class="value"></td>
            </tr>
            <tr class="header">
                <td></td>
                <td colspan="5"><b>MAP NEW ACCESSORY</b> - Search by accessory SKU/name below and choose from dropdown list.</td>
            </tr>
            <tr>
                <td class="value">&nbsp;</td>
                <td class="value" colspan="3">
                    <input type="text" size = "90" name="new_acc_sku[<?=$country?>]" id="new_acc_sku[<?=$country?>]" onkeyup="showData('txtHint[<?=$country?>]', this.value, '<?=$country?>')" notEmpty>
                    <div id="txtHint[<?=$country?>]" name="txtHint[<?=$country?>]" style="display:none;background-color:#0F192A;overflow-y:scroll;max-height:300px;width:560px;"></div>
                    <input type="hidden" name="posted" id="posted" value="1">
                    <input type="hidden" name="action" id="action" value="insert">
                </td>
                <td class="value" colspan="3"><input type="submit" value="MAP"></td>
            </tr>
            <tr>
                <td height="7" colspan="6" class="value"></td>
            </tr>
        </table>
        </form>
    </td>
</tr>
<?          }
        }
?>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr class="header">
    <td height="20" align="left" style="padding-left:8px;"><b style="font-size: 12px; color: rgb(255, 255, 255);">BULK MAPPING / UPDATE
        </b>
    </td>
</tr>
<?php
        if($ca_country_list)
        {
            # existing mappings by countries for bulk update
?>
<tr>
    <td align="left">
        <form name="bulk_update" id="bulk_update" action="<?=base_url()?>marketing/complementary_acc/view/<?=$sku?>" method="POST" onSubmit="return CheckForm(this)">
        <table border="0" cellpadding="0" cellspacing="0" width="1042px" class="tb_main" name="bulk_mapped_ca" id="bulk_mapped_ca">
            <tr>
                <td colspan="7" height="10" colspan="6" class="value"></td>
            </tr>
            <tr class="header">
                <td width="3%"></td>
                <td width="15%">Accessory SKU</td>
                <td width="20%">Name</td>
                <td width="15%">Category</td>
                <td width="15%">Sub-category</td>
                <td width="15%">Applicable Countries</td>
                <td width="17%">Bulk Status Update</td>
            </tr>

<?php
            foreach ($ca_country_list as $ca_sku => $arr)
            {
                $caobj = $arr["ca_obj"];
                $country_list = $arr["country_list"];
?>
            <tr>
                <td class="field"  style="cursor:pointer;"></td>
                <td class="field"><?=$ca_sku?></td>
                <td class="field"><?=$caobj->get_name()?></td>
                <td class="field"><?=$caobj->get_category()?></td>
                <td class="field"><?=$caobj->get_sub_cat()?></td>
                <td class="field"><?=$country_list?></td>
                <td class="field">
                    <input type="hidden" name="ca_sku[<?=$ca_sku?>]" id="ca_sku" value="<?=$ca_sku?>">
                    <input type="hidden" name="country_list[<?=$ca_sku?>]" id="country_list[<?=$ca_sku?>]" value="<?=$country_list?>">
                    <input type="hidden" name="action" id="action" value="bulk_update">
                    <input type="hidden" name="posted" id="posted" value="1">
                    <input type="radio" name="status[<?=$ca_sku?>]" value="1"> Active<br>
                    <input type="radio" name="status[<?=$ca_sku?>]" value="0"> Inactive<br>

                </td>
            </tr>
<?php
            }
?>
            <tr>
                <td colspan="6" class="field" >&nbsp;</td>
                <td align="left" class="field" ><input type="submit" value="Submit"><br></td>
            </tr>
        </table>
        </form>
    </td>
</tr>
<?php
        }   # end of existing mappings for bulk update

        # bulk map CA sku to all countries below
?>
<tr>
    <td>
        <form name="bulk_new" action="<?=base_url()?>marketing/complementary_acc/view/<?=$sku?>" method="POST" onSubmit="return CheckForm(this)">
        <table border="0" cellpadding="0" cellspacing="0" width="1042px" class="tb_main" name="new_ca_bulk" id="new_ca_bulk">
            <col width="5%"><col width="15%"><col width="30%"><col width="20%"><col width="20%"><col width="10%">
            <tr>
                <td height="10" colspan="6" class="value"></td>
            </tr>
            <tr class="header">
                <td></td>
                <td colspan="5"><b>BULK MAP NEW ACCESSORY</b> - Search by accessory SKU/name below and choose from dropdown list.</td>
            </tr>
            <tr>
                <td class="value">&nbsp;</td>
                <td class="value" colspan="3">
                    <input type="text" size = "90" name="new_acc_sku[bulk]" id="new_acc_sku[bulk]" onkeyup="showData('txtHint[bulk]', this.value, 'bulk')" notEmpty>
                    <div id="txtHint[bulk]" name="txtHint[bulk]" style="display:none;background-color:#0F192A;overflow-y:scroll;max-height:300px;width:560px;"></div>
                    <input type="hidden" name="posted" id="posted" value="1">
                    <input type="hidden" name="action" id="action" value="bulk_insert">
                </td>
                <td class="value" colspan="3"><input type="submit" value="MAP"></td>
            </tr>
            <tr>
                <td height="15" colspan="6" class="value"></td>
            </tr>
        </table>
        </form>
    </td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
</table>
<?
}
?>
</div>
<?=$notice["js"]?>
<?php

if($prompt_notice)
{
?><script language="javascript">alert('<?=$lang["update_notice"]?>')</script>
<?php
}
?>
<script language="javascript">

</script>
</body>
</html>