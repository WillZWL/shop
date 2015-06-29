<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
</head>
<body>
<div id="main" style="width:1024px;">
<?php
    $ar_status = array("N" => $lang["new"], "R" => $lang["ready_update_to_master"], "S" => $lang["success"], "F" => $lang["failed"], "I" => $lang["investigated"]);
    $ar_color = array("N" => "#000000", "R" => "#0000CC", "S" => "#009900", "F" => "#CC0000", "I" => "#440088");

    echo $notice["img"];?>
<table border="0" cellpadding="0" cellspacing="1" width="100%" bgcolor="#cccccc" class="tb_list">
<col width="150"><col width="150"><col width="100"><col width="100"><col width="100"><col width="120"><col><col width="100">
<tr class='header'>
    <td><?=$lang["line_number"]?></td>
    <td><?=$lang["ext_item_cd"]?></td>
    <td><?=$lang["sku"]?></td>
    <td><?=$lang["qty"]?></td>
    <td><?=$lang["unit_price"]?></td>
    <td><?=$lang["batch_status"]?></td>
    <td><?=$lang["failed_reason"]?></td>
    <td width="20"></td>
</tr>
<?php
    $i = 0;
    foreach($obj_list as $obj)
    {
        if($obj->get_line_no() == $this->input->get("line_no"))
        {
?>
<tr class="row<?=$i++?>">
    <form name="fm" action="<?=base_url().'integration/integration/examine_order/'.$batch_id.'/'.$trans_id.'/'?>" method="POST" onSubmit="return CheckForm(this);">
    <td><?=$obj->get_line_no()?></td>
    <td><?=$obj->get_ext_item_cd()?></td>
    <td><input name="prod_sku" value="<?=$obj->get_prod_sku()?>" notEmpty class="input"></td>
    <td><?=$obj->get_qty()?></td>
    <td><?=$obj->get_unit_price()?></td>
    <td><font color="<?=$ar_color[$obj->get_batch_status()]?>"><?=$ar_status[$obj->get_batch_status()]?></font></td>
    <td><?=$obj->get_failed_reason()?></td>
    <td><input type="button" value="<?=$lang["submit_change"]?>" onClick="if(CheckForm(this.form)) document.fm.submit();"></td>
    <input type="hidden" name="edit" value="1">
    <input type="hidden" name="ln" value="<?=$obj->get_line_no()?>">
    </form>
</tr>
<?php
        }
        else
        {
?>
<tr class="row<?=$i++?>">
    <td><?=$obj->get_line_no()?></td>
    <td><?=$obj->get_ext_item_cd()?></td>
    <td><?=$obj->get_prod_sku()?></td>
    <td><?=$obj->get_qty()?></td>
    <td><?=$obj->get_unit_price()?></td>
    <td><font color="<?=$ar_color[$obj->get_batch_status()]?>"><?=$ar_status[$obj->get_batch_status()]?></font></td>
    <td><?=$obj->get_failed_reason()?></td>
    <td><?if(in_array($obj->get_batch_status(),array("F","I"))){?><input type="button" value="<?=$lang["modify"]?>" onClick="Redirect('<?=base_url().'integration/integration/examine_order/'.$batch_id.'/'.$trans_id.'/?line_no='.$obj->get_line_no()?>')"><?}?></td>
</tr>
<?php
        }
    }
?>
</table>
</div>
<?=$notice["js"]?>
</body>
</html>