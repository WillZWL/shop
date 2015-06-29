<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>/mastercfg/warehouse/js_warehouse"></script>
<script language="javascript">
<!--
function write_wh()
{
    for(var i in warehouse)
    {
        selected = "";
        if (warehouse[i][0] == '<?=$wh?>')
        {
            selected = "SELECTED";
        }
        document.write('<option value="'+warehouse[i][0]+'"  '+selected+'>'+warehouse[i][1]+'</option>');
    }
}

function gotoPage(value)
{
    if(value != "")
    {
        document.location.href = "<?=base_url()?>/order/supplier_order/confirm_shipment/"+value;
    }
}

function checkQty(index)
{
    var sq = document.getElementById('sq['+index+']').value;
    var rq = document.getElementById('rqty['+index+']').value;

    if(rq > sq)
    {
        alert("Received Quantity should be equal to or less than the order quantity");
        document.getElementById('rqty['+index+']').value = sq;
        document.getElementById('reason['+index+']').disabled = true;
    }
    else if (rq == sq)
    {
        document.getElementById('reason['+index+']').disabled = true;
    }
    else
    {
        document.getElementById('reason['+index+']').disabled = false;
    }
}
-->
</script>
</head>
<body>
<div id = "main">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["subtitle"]?></td>
        <td width="400" align="right" class="title"></td>
    </tr>
    <tr>
        <td height="2" bgcolor="#000033"></td>
        <td height="2" bgcolor="#000033"></td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" bgcolor="#BBBBFF" width="100%">
    <tr>
        <td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["subheader"]?><br><?=$lang["select_wh"]?><select onChange="gotoPage(this.value)"><option value=""><?=$lang["please_select"]?></option><script language="javascript">write_wh()</script></select></td>
        <td width="200" valign="top" align="right" style="padding-right:8px"><br><?=$lang["shipment_found"]?> <b><?=$total?></b><br><br></td>
    </tr>
</table>
<?php if($showcontent){?>
<form name="fm" method="get">
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#000000" width="100%" class="tb_pad">
    <col width="20"><col width="120"><col width="120"><col width="150"><col width="100"><col width="150"><col width="100"><col width="120"><col><col width="40">
    <tr class="header">
        <td height="20"><img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
        <td><a href="#" onClick="SortCol(document.fm, 'shipment_id', '<?=$xsort["shipment_id"]?>')"><?=$lang["shipment_id"]?> <?=$sortimg["shipment_id"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'sku', '<?=$xsort["sku"]?>')"><?=$lang["sku"]?> <?=$sortimg["sku"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'prod_name', '<?=$xsort["prod_name"]?>')"><?=$lang["prod_name"]?> <?=$sortimg["prod_name"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'qty', '<?=$xsort["qty"]?>')"><?=$lang["qty"]?> <?=$sortimg["qty"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'supplier_name', '<?=$xsort["supplier_name"]?>')"><?=$lang["supplier_name"]?> <?=$sortimg["supplier_name"]?></a></td>
        <td><?=$lang["received_qty"]?></td>
        <td><?=$lang["reason"]?></td>
        <td><?=$lang["remarks"]?></td>
        <td></td>
    </tr>
    <tr class="search" id="tr_search" <?=$searchdisplay?>>
        <td></td>
        <td><input name="shipment_id" class="input" value="<?=htmlspecialchars($this->input->get("ship_ref"))?>"></td>
        <td><input name="sku" class="input" value="<?=htmlspecialchars($this->input->get("sku"))?>"></td>
        <td><input name="prod_name" class="input" value="<?=htmlspecialchars($this->input->get("prod_name"))?>"></td>
        <td>&nbsp;</td>
        <td><input name="supplier_name" class="input" value="<?=htmlspecialchars($this->input->get("supplier_name"))?>" ></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="left"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
    </tr>
</table>
<input type="hidden" name="showall" value='<?=$this->input->get("showall")?>'>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>

<form action="<?=$_SERVER["PHP_SELF"]?>" method="POST" name="confirmForm">
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#BBBBFF" width="100%" class="tb_pad">
    <col width="20"><col width="120"><col width="120"><col width="150"><col width="100"><col width="150"><col width="100"><col width="120"><col><col width="40">
<?php
    $row_color = array("#EEEEFF", "#DDDDFF");
    $i=0;
    if (!empty($result))
    {
        if($total > 0)
        {
            foreach ($result as $inv_obj)
            {
                $cur_color = $row_color[$i%2];
?>

    <tr bgcolor="<?=$cur_color?>" onmouseover="ChgBg(this, '#DDFFDD')" onmouseout="ChgBg(this, '<?=$cur_color?>')" class="pointer" >
        <td height="20" width="16"></td>
        <td><?=$inv_obj->get_shipment_id()?><input name="shipment_id[<?=$i?>]" id="shipment_id[<?=$i?>]" type="hidden" value="<?=$inv_obj->get_shipment_id()?>"></td>
        <td><?=$inv_obj->get_sku()?></td>
        <td><?=$inv_obj->get_prod_name()?></td>
        <td><?=$inv_obj->get_shipped_qty()?><input name="sq[<?=$i?>]" id="sq[<?=$i?>]" type="hidden" value="<?=$inv_obj->get_shipped_qty()?>"></td>
        <td><?=$inv_obj->get_supplier_name()?></td>
        <td><input class="input" type="text" name="rqty[<?=$i?>]" id="rqty[<?=$i?>]" value="<?=$inv_obj->get_shipped_qty()?>" onKeyUp="checkQty('<?=$i?>')"></td>
        <td><select class="input" name="reason[<?=$i?>]"id="reason[<?=$i?>]" DISABLED><option value="OTH">Others</option></select></td>
        <td><input class="input" type="text" name="remarks[<?=$i?>]" id="remarks[<?=$i?>]" value=""></td>
        <td>&nbsp;</td>
    </tr>
<?php
                $i++;
            }
        }
        else
        {
?>
        <tr bgcolor="<?=$row_color[0]?>">
            <td colspan="7" align="center"><?=$lang["po_not_found"]?></td>
        </tr>
<?php
        }
    }
?>
</table>
<input type="hidden" value="1" name="posted">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="tb_pad" bgcolor="#333333">
<tr>
    <td height="30" align="right"><input type="button" value="<?=$lang["confirm_quantity"]?>" onClick="if(CheckForm(this.form)) document.confirmForm.submit();"></td>
    <td width="10">&nbsp;</td>
</tr>
</table>
</form>
<?=$this->pagination_service->create_links_with_style()?>
<?}?>
</div>
</body>
</html>