<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
</head>
<body>
<div id="main">
<?php
	$status_array = array("en"=>array("N"=>"New","PS"=>"Partially Delivered","FS"=>"Fully Delivered","C"=>"Completed","CL"=>"Cancelled"));
	$dlvry_mode = array("en"=>array("P"=>"Self Pickup","D"=>"Supplier's Delivery"));
?>
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["subtitle"]?></td>
		<td width="400" align="right" class="title"><input type="button" value="<?=$lang["add_button"]?>" class="button" onclick="Redirect('<?=site_url('order/supplier_order/add/')?>')"></td>
	</tr>
	<tr>
		<td height="2" bgcolor="#000033"></td>
		<td height="2" bgcolor="#000033"></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" bgcolor="#BBBBFF" width="100%">
	<tr>
		<td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["subheader"]?></td>
		<td width="200" valign="top" align="right" style="padding-right:8px"><br><?=$lang["po_found"]?> <b><?=$total?></b><br><br></td>
	</tr>
</table>
<form name="fm" method="get">
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#000000" width="100%" class="tb_pad">
	<col width="20"><col width="170"><col width="170"><col width="200"><col width="150"><col width="100"><col>
	<tr class="header">
		<td height="20"><img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
		<td><a href="#" onClick="SortCol(document.fm, 'po_number', '<?=$xsort["po_number"]?>')"><?=$lang["po_number"]?> <?=$sortimg["po_number"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'name', '<?=$xsort["name"]?>')"><?=$lang["name"]?> <?=$sortimg["name"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'supplier_invoice_number', '<?=$xsort["supplier_invoice_number"]?>')"><?=$lang["supplier_invoice_number"]?> <?=$sortimg["supplier_invoice_number"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'delivery_mode', '<?=$xsort["delivery_mode"]?>')"><?=$lang["delivery_mode"]?> <?=$sortimg["delivery_mode"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'status', '<?=$xsort["status"]?>')"><?=$lang["status"]?> <?=$sortimg["status"]?></a></td>
		<td></td>
	</tr>
	<tr class="search" id="tr_search" <?=$searchdisplay?>>
		<td></td>
		<td><input name="po_number" class="input" value="<?=htmlspecialchars($this->input->get("po_number"))?>"></td>
		<td><input name="name" class="input" value="<?=htmlspecialchars($this->input->get("name"))?>"></td>
		<td><input name="supplier_invoice_number" class="input" value="<?=htmlspecialchars($this->input->get("supplier_invoice_number"))?>"></td>
		<td><select name="level" class="input"><option value="">-- Please Select --</option><?php

			foreach($dlvry_mode[$syslang] as $key=>$value)
			{
			?><option value="<?=$key?>" <?=($key == $this->input->get('delivery_mode')?"SELECTED":"")?>><?=$value?></option><?php
			}
		?></select></td>
		<td><select name="status" class="input"><option value="">-- Please Select --</option><?php
			foreach($status_array[$syslang] as $key=>$value)
			{
			?><option value="<?=$key?>" <?=($key == $this->input->get('status') && $this->input->get('status')!="")?"SELECTED":""?>><?=$value?></option><?php
			}
		?></select></td>
		<td align="left"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#BBBBFF" width="100%" class="tb_pad">
	<col width="20"><col width="170"><col width="170"><col width="200"><col width="150"><col width="100"><col>
<?php
	$row_color = array("#EEEEFF", "#DDDDFF");
	$i=0;
	if (!empty($purchase_order_list))
	{
		if($total > 0)
		{
			foreach ($purchase_order_list as $po)
			{
				$cur_color = $row_color[$i%2];
?>

	<tr bgcolor="<?=$cur_color?>" onmouseover="ChgBg(this, '#DDFFDD')" onmouseout="ChgBg(this, '<?=$cur_color?>')" class="pointer" >
		<td height="20"><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$po->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$po->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$po->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$po->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$po->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$po->get_modify_by()?>'></td>
		<td><?=$po->get_po_number()?></td>
		<td><?=$po->get_supplier_name()?></td>
		<td><?=$po->get_supplier_invoice_number()?></td>
		<td><?=$dlvry_mode[$syslang][$po->get_delivery_mode()]?></td>
		<td><?=$status_array[$syslang][$po->get_status()]?></td>
		<td><input type="button" value="View PO Detail" OnClick="Redirect('<?=site_url('order/supplier_order/view/'.$po->get_po_number())?>')" <?=($po->get_status() == "CL"?"DISABLED":"")?>>&nbsp;&nbsp<input type="button" value="View PO Shipment" OnClick="Redirect('<?=site_url('order/supplier_order/view_ship/'.$po->get_po_number())?>')" <?=($po->get_status() == "CL"?"DISABLED":"")?>></td>
	</tr>
	<tr class="detail">
		<td height="20" colspan="2" align="left" valign="middle"><?=$lang["order_detail"]?></td>
		<td height="20" colspan="5" style="padding-left:30px;">
		<table width="600" border="0" cellpadding="0" cellspacing="2">
		<col width="120"><col width="280"><col width="80"><col width="120">
		<tr>
			<td><b><?=$lang["sku"]?></b></td>
			<td><b><?=$lang["prodname"]?></b></td>
			<td><b><?=$lang["qty"]?></b></td>
			<td><b><?=$lang["unit_price"]."(".$po->get_currency().")"?></b></td>
		</tr>

<?php
		$order_string = $po->get_purchase_detail();
		$os_arr = explode("::",$order_string);
		foreach($os_arr as $value)
		{
			$total = "0.00";
			$detail = explode("||",$value);
?>
		<tr>
			<td><?=$detail[0]?></td>
			<td><?=$detail[1]?></td>
			<td><?=$detail[2]?></td>
			<td><?=$detail[3]?></td>
		</tr>
<?php

		}
?>
		<tr>
			<td colspan="3" align="right" style="padding-right:10px; font-weight:bold;"><?=$lang["total"]?></td>
			<td><?=$po->get_currency()." ".$po->get_amount()?></td>
		</tr>
		</table>
		</td>
        <td align="left" style="padding-left:10px;" valign="top">
                <?=$lang["recent_message"].":<br>"?>
<?php

        $content = "<div style='width: 100%; height: 70px; overflow-y: scroll;'>";
        if($po->get_po_message() != "")
        {
                $message_list = explode('||',$po->get_po_message());
                foreach($message_list as $message)
                {
                        $msg_detail = explode('::',$message);
                        $content .= $msg_detail[0]."<br /><i>".$lang["msg_created_by"].$msg_detail[1].$lang["msg_created_on"].$msg_detail[2]."</i><br /><br />";
                }
        }
        else
        {
                $content .= $lang["currently_no_message"];
        }
        $content .= "</div>";

        echo $content;
?><?=$lang["add_message"]?><br><form method="post" onSubmit="return CheckForm(this);"><input type="text" name="message" class="input" maxlength="255" notEmpty>
<br/><input type="hidden" name="pm_number" value="<?=$po->get_po_number()?>"><input type="hidden" name="add_message" value="1"><input type="button" value="<?=$lang["submit_message"]?>" onClick="if(Chec
kForm(this.form)) this.form.submit();"></form>
                </td>

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
<input type="hidden" name="showall" value='<?=$this->input->get("showall")?>'>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<?=$this->pagination_service->create_links_with_style()?>
</div>
<?=$notice["js"]?>
</body>
</html>