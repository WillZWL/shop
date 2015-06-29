<?php
    $cat_arr = array(0=>"Machine Only",1=>"Accessory Only",2=>"Machine and Accessories");
    $reason_arr = array(0=>"Needs Repair Under Warranty",1=>"Wrong Product Delivered",2=>"Wrong Product Purchased",3=>"Accidently Purchased (conditions apply)");
    $action_arr = array(0=>"Swap",1=>"Refund",2=>"Repair");
    $status_arr = array(0=>"New Case", 1=>"Case Close");
?>
<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
</head>
<style>
.bp{padding-bottom:30px;}
#maintb td{height:20px;}
#maintb .header1{background-color:#FFCCCC;}
#maintb .header2{background-color:#A4FFA4;}
#maintb .header3{background-color:#3399FF;}
#maintb .header4{background-color:#6A006A;}
#maintb .header5{background-color:#B6B6DA;}
#maintb .field1{background-color:#FFFF77; padding-left:8px;}
#maintb .field2{background-color:#CFFF9F; padding-left:8px;}
#maintb .field3{background-color:#84D7FF; padding-left:8px;}
#maintb .field4{background-color:#C99CC0; padding-left:8px;}
#maintb .value1{background-color:#FFFFCC; padding-left:8px;}
#maintb .value2{background-color:#DDFFDD; padding-left:8px;}
#maintb .value3{background-color:#C1FFFF; padding-left:8px;}
#maintb .value4{background-color:#D8CBDE; padding-left:8px;}
#maintb .refund_header{background-color:#ffafd6;}
#maintb .refund_header td{padding-left:4px;}
#maintb .refund_row0{background-color:#ffcdfe; }
#maintb .refund_row0 td{padding-left:4px;}
#maintb .refund_row1{background-color:#ffddfe;}
#maintb .refund_row1 td{padding-left:4px;}
</style>
<body>
<div id="main" <?=$viewtype==""?"":"style='width:1004px;'"?> class="bp">
<?=$notice["img"]?>
<?php
    $ar_status = array($lang["inactive"], $lang["active"]);
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="25" class="title"><?=$lang["subtitle"]?></td>
        <td class="title" align="right"><input type="button" value="<?=$lang["list_button"]?>" class="button" onclick="Redirect('<?=site_url('order/rma/')?>')"></td>
    </tr>
    <tr>
        <td height="2" colspan="2" bgcolor="#000033"></td>
    </tr>
</table>
<?php
    if(!$so_obj)
    {
?>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#BBBBFF" width="100%" id="maintb">
<tr><td style="padding-left:8px" class="header1"><b style="font-size:11px;color:#000000"></b>Invalid So Number: <?=$rma_obj->get_so_no()?></td></tr>
</table>
<?php
        exit;
    }
?>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#BBBBFF" width="100%" id="maintb">
<tr><td style="padding-left:8px" class="header1"><b style="font-size:11px;color:#000000"><?=$lang["purchase_detail"]?></b></td></tr>
<tr>
    <td>
    <table width="100%" cellpadding="0" cellspacing="1" border="0">
    <col width="15%"><col width="35%"><col width="15%"><col width="35%">
    <tr>
        <td align="left" class="field1"><?=strtoupper($lang["rma_no"])?></td>
        <td colspan="3" align="left" class="value1"><?=$rma_obj->get_id()?></td>
    </tr>
    <tr>
        <td span="2" align="left" class="field1"><?=strtoupper($lang["order_number"])?></td>
        <td colspan="3" align="left" class="value1"><a href="<?=base_url().'/cs/quick_search/view/'.$rma_obj->get_so_no();?>" target="quick_search"><?=implode("-", array($rma_obj->get_client_id(), $rma_obj->get_so_no()));?></a></td>
    </tr>
    <tr>
        <td span="2" align="left" class="field1"><?=strtoupper($lang["purchase_date"])?></td>
        <td colspan="3" align="left" class="value1"><?=$so_obj->get_order_create_date()?></td>
    </tr>
    </table>
    </td>
</tr>
<tr><td style="padding-left:8px" class="header2"><b style="font-size:11px;color:#000000"><?=$lang["personal_detail"]?></b></td></tr>
<tr>
    <td>
    <table width="100%" cellpadding="0" cellspacing="1" border="0">
    <tr>
        <td width="15%" align="left" class="field2"><?=strtoupper($lang["client_name"])?></td>
        <td width="35%" align="left" class="value2"><?=implode(" ", array($rma_obj->get_forename(), $rma_obj->get_surname()));?></td>
        <td width="15%" align="left" class="field2"><?=strtoupper($lang["postcode"])?></td>
        <td width="35%" align="left" class="value2"><?=$rma_obj->get_postcode()?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field2"><?=strtoupper($lang["addr_line_1"])?></td>
        <td width="35%" align="left" class="value2"><?=$rma_obj->get_address_1()?></td>
        <td width="15%" align="left" class="field2"><?=strtoupper($lang["city"])?></td>
        <td width="35%" align="left" class="value2"><?=$rma_obj->get_city()?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field2"><?=strtoupper($lang["addr_line_2"])?></td>
        <td width="35%" align="left" class="value2"><?=$rma_obj->get_address_2()?></td>
        <td width="15%" align="left" class="field2"><?=strtoupper($lang["state"])?></td>
        <td width="35%" align="left" class="value2"><?=$rma_obj->get_state()?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field2"></td>
        <td width="35%" align="left" class="value2"></td>
        <td width="15%" align="left" class="field2"><?=strtoupper($lang["country"])?></td>
        <td width="35%" align="left" class="value2"><?=$rma_obj->get_country_id()?></td>
    </tr>
    </table>
    </td>
</tr>
<tr><td style="padding-left:8px" class="header3"><b style="font-size:11px;color:#000000"><?=$lang["product_detail"]?></b>&nbsp;&nbsp;</td>
</tr>
<tr>
    <td>
    <table width="100%" cellpadding="0" cellspacing="1" border="0">
    <col width="15%"><col width="35%"><col width="15%"><col width="35%">
    <tr>
        <td align="left" class="field3"><?=strtoupper($lang["product_returned"])?></td>
        <td colspan="3" align="left" class="value3"><?=$rma_obj->get_product_returned()?></td>
    </tr>
    <tr>
        <td span="2" align="left" class="field3"><?=strtoupper($lang["serial_no"])?></td>
        <td colspan="3" align="left" class="value3"><?=$rma_obj->get_serial_no();?></td>
    </tr>
    <tr>
        <td span="2" align="left" class="field3"><?=strtoupper($lang["categories"])?></td>
        <td colspan="3" align="left" class="value3"><?=$cat_arr[$rma_obj->get_category()];?></td>
    </tr>
    </table>
    </td>
</tr>
<tr><td style="padding-left:8px" class="header4"><b style="font-size:11px;color:#ffffff"><?=$lang["return_detail"]?></b>&nbsp;&nbsp;</td>
</tr>
<tr>
    <td>
    <table width="100%" cellpadding="0" cellspacing="1" border="0">
    <col width="15%"><col width="35%"><col width="15%"><col width="35%">
    <tr>
        <td align="left" class="field4"><?=strtoupper($lang["return_reason"])?></td>
        <td colspan="3" align="left" class="value4"><?=$reason_arr[$rma_obj->get_reason()]?></td>
    </tr>
    <tr>
        <td span="2" align="left" class="field4"><?=strtoupper($lang["action_required"])?></td>
        <td colspan="3" align="left" class="value4"><?=$action_arr[$rma_obj->get_action_request()]?></td>
    </tr>
    <tr>
        <td span="2" align="left" class="field4"><?=strtoupper($lang["details"])?></td>
        <td colspan="3" align="left" class="value4"><?=$rma_obj->get_details();?></td>
    </tr>
    </table>
    </td>
</tr>
<tr><td style="padding-left:8px" class="header5"><b style="font-size:11px;color:#000000"><?=$lang["remark"]?></b></td></tr>
<tr>
    <td>
    <table width="100%" cellpadding="0" cellspacing="1" border="0" bgcolor="#cccccc">
    <tr bgcolor="#eeeeff">
        <td width="15%" align="left" style="padding-left:8px;" class="field5"><?=strtoupper($lang["order_notes"])?></td>
        <td colspan="3" width="85%" align="left" class="value5"><form action="<?=base_url()?>order/rma/add_note/<?=$rma_obj->get_id()?>" method="POST"><div style="width:100%; height:100px; overflow-y:scroll">
<?php
        if(count((array)$rma_notes_obj))
        {
            ?>
            <table width="100%" cellpadding="0" cellspacing="1" border="0" bgcolor="#eeeeff">
            <col width="50%"><col width="25%"><col width="25%">
            <?php
            foreach($rma_notes_obj as $obj)
            {
?>
                <tr>
                <td>
                    <div><b><?=$obj->get_note()?></b></div>
                </td>
                <td>
                    <div><i><?=$lang["create_by"]." ".$obj->get_modify_by()?></i></div>
                </td>
                <td>
                    <div><i><?=$lang["create_on"]." ".$obj->get_modify_on()?></i></div>
                </td>
                </tr>
<?php
            }
            ?>
            </table>
            <?php
        }
        else
        {
?>
        <div><b><i><?=$lang["no_order_note"]?></i></b><br><br></div>
<?php
        }
?>
        </div>
        <br>
        <div><b><?=$lang["create_note"]?></b><br><input type="text" name="note" class="input"><input type="hidden" name="addnote" value="1" ><br><input type="button" value="<?=$lang["add_note"]?>" onClick="this.form.submit();">
        </form></td>
    </tr>
    <tr bgcolor="#eeeeff">
        <td width="15%" align="left" style="padding-left:8px"  class="field5"><?=strtoupper($lang["order_status"])?></td>
        <td colspan="3" width="85%" align="left" class="value5"><form action="<?=base_url()?>order/rma/update_rma_status/<?=$rma_obj->get_id()?>" method="POST"><div style="width:100%; height:100px; overflow-y:scroll">
<?php
        if(count((array)$rma_history_obj))
        {
            ?>
            <table width="100%" cellpadding="0" cellspacing="1" border="0" bgcolor="#eeeeff">
            <col width="50%"><col width="25%"><col width="25%">
            <?php

            foreach($rma_history_obj as $obj)
            {
?>
                <tr>
                <td>
                    <div><b><?=$status_arr[$obj->get_status()]?></b></div>
                </td>
                <td>
                    <div><i><?=$lang["create_by"]." ".$obj->get_modify_by()?></i></div>
                </td>
                <td>
                    <div><i><?=$lang["create_on"]." ".$obj->get_modify_on()?></i></div>
                </td>
                </tr>
<?php
            }
            ?>
            </table>
            <?php
        }
        else
        {
?>
        <div><b><i><?=$lang["no_status_history"]?></i></b></div>
<?php
        }
?>
        </div>
        <div>
            <select name="rma_status" class="">
            <?php
            foreach($status_arr as $key=>$val)
            {
            ?>
                <option value="<?=$key?>"><?=$val?></option>
            <?php
            }
            ?>
            </select>

            <input type="hidden" name="update_status" value="1" ><br><br>
            <input type="button" value="<?=$lang["update_status"]?>" onClick="this.form.submit();">
        </form></td>
    </tr>
    </table>
    </td>
</tr>
</table>
</div>
<?=$notice["js"]?>
</body>
</html>