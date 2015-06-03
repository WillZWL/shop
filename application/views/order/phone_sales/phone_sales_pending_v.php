<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/lytebox.css" type="text/css" media="screen" />
</head>
<body>
<div id="main">
<?$ar_status = array("0" => $lang["inactive"], "1" => $lang["active"]);?>
<?=$notice["img"]?>
<script>
function Proc(sov, tv, reason)
{
	var f = document.fm_proc;
	f.so_no.value = sov;
	f.hold_reason.value = reason;
	f.type.value = tv;
	f.submit();
}
</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="400" align="right" class="title"><input type="button" value="<?=$lang["add_button"]?>" class="button" onclick="Redirect('<?=site_url('order/phone_sales')?>')"> &nbsp; <input type="button" value="<?=$lang["on_hold_button"]?>" class="button" onclick="Redirect('<?=site_url('order/phone_sales/on_hold')?>')"> &nbsp; <input type="button" value="<?=$lang["pending_button"]?>" class="button" onclick="Redirect('<?=site_url('order/phone_sales/pending')?>')"></td>
	</tr>
	<tr>
		<td height="2" class="line"></td>
		<td height="2" class="line"></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
	<tr>
		<td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
	</tr>
</table>

<form name="fm" method="get">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
	<col width="20"><col width="100"><col><col><col width="80"><col width="80"><col width="120"><col width="100"><col width="230">
	<tr class="header">
		<td height="20"><img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
		<td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'so_no', '<?=$xsort["so_no"]?>')"><?=$lang["order_id"]?> <?=$sortimg["so_no"]?></a></td>
		<td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'email', '<?=$xsort["email"]?>')"><?=$lang["client_email"]?> <?=$sortimg["email"]?></a></td>
		<td style="white-space:nowrap"><?=$lang["order_detail"]?></td>
		<td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'delivery_charge', '<?=$xsort["delivery_charge"]?>')"><?=$lang["delivery"]?> <?=$sortimg["delivery_charge"]?></a></td>
		<td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'offline_fee', '<?=$xsort["offline_fee"]?>')"><?=$lang["fee"]?> <?=$sortimg["offline_fee"]?></a></td>
		<td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'amount', '<?=$xsort["amount"]?>')"><?=$lang["order_amount"]?> <?=$sortimg["amount"]?></a></td>
		<td><?=$lang["t3m_result"]?></td>
		<td></td>
	</tr>
	<tr class="search" id="tr_search" <?=$searchdisplay?>>
		<td></td>
		<td><input name="so_no" class="input" value="<?=htmlspecialchars($this->input->get("so_no"))?>"></td>
		<td><input name="email" class="input" value="<?=htmlspecialchars($this->input->get("email"))?>"></td>
		<td></td>
		<td><input name="delivery_charge" class="input" value="<?=htmlspecialchars($this->input->get("delivery_charge"))?>"></td>
		<td><input name="offline_fee" class="input" value="<?=htmlspecialchars($this->input->get("offline_fee"))?>"></td>
		<td><input name="amount" class="input" value="<?=htmlspecialchars($this->input->get("amount"))?>"></td>
		<td></td>
		<td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
	</tr>
	<input type="hidden" name="showall" value='<?=$this->input->get("showall")?>'>
	<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
	<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
	</form>
<?php
	$i=0;
	if ($objlist)
	{
		foreach ($objlist as $obj)
		{
			$client_id = $obj->get_client_id();
			$t3m_order_no = $client_id."-".str_replace("SO","",$obj->get_so_no());
?>

	<tr class="row<?=$i%2?>">
		<td height="20"><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$obj->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$obj->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$obj->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$obj->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$obj->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$obj->get_modify_by()?>'></td>
		<td><a href="<?=base_url()."cs/quick_search/view/".$obj->get_so_no()?>" target="_blank"><?=$obj->get_so_no()?></a><br><?=$t3m_order_no?></td>
		<td><?=$obj->get_email()?></td>
		<td>
			<?php
				if ($obj->get_order_reason() || $obj->get_note())
				{
					if ($obj->get_order_reason())
					{
						echo $obj->get_order_reason()." : ";
					}
					if ($obj->get_note())
					{
					 echo $obj->get_note();
					}
					echo "<br>";
				}
				if ($obj->get_items())
				{
					$items = explode("||", $obj->get_items());
					foreach ($items as $item)
					{
						list($sku, $name, $qty, $u_p, $amount) = @explode("::", $item);
			?>
						<p class="normal_p">[<?=$sku?>] <?=$name?> x<?=$qty?> @<?=$u_p?> = <?=$amount?></p>
			<?php
					}
				}
			?>
		</td>
		<td><?=$obj->get_delivery_charge()?></td>
		<td><?=$obj->get_offline_fee()?></td>
		<td><?=$obj->get_currency_id()?> <?=$obj->get_amount()?></td>
		<td><?=$obj->get_t3m_result()?></td>
		<td><input type="button" value="<?=$lang["previous"]?>" onClick="if(confirm('<?=$lang["move_back_to_hold"]?>'))Proc('<?=$obj->get_so_no()?>', 'b','');"><br /><input type="button" value="<?=$lang["process_w_email"]?>" onClick="if(confirm('<?=$lang["proceed_fulfillment"]?>'))Proc('<?=$obj->get_so_no()?>', 'pe','');">&nbsp;<input type="button" value="<?=$lang["process_wo_email"]?>" onClick="if(confirm('<?=$lang["proceed_fulfillment"]?>'))Proc('<?=$obj->get_so_no()?>', 'p','');"><br>
		<select id="hold_action"><option value="cscc"><?=$lang["cscc"]?></option><option value="csvv"><?=$lang["csvv"]?></option><option value="confirm_fraud"><?=$lang["confirmed_fraud"]?></option></select>&nbsp;&nbsp;<input type="button" value="<?=$lang["hold_with_reason"]?>" onClick="if(confirm('<?=$lang["hold_the_order"]?>'))Proc('<?=$obj->get_so_no()?>', 'c',document.getElementById('hold_action').value);"> &nbsp; &nbsp;</td>
	</tr>
<?php
			$i++;
		}
	}
?>
<form name="fm_proc" method="post">
	<input type="hidden" name="hold_reason" value="">
	<input type="hidden" name="posted" value="1">
	<input type="hidden" name="so_no" value="">
	<input type="hidden" name="type" value="">
</form>
</table>
<?=$this->pagination_service->create_links_with_style()?>
</div>
<?=$notice["js"]?>
</body>
</html>