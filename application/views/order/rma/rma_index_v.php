<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>order/rma/rma_addr_js"></script>
</head>
<body>
<div id="main">
<?php
	$cat_list = array($lang["cat_0"], $lang["cat_1"], $lang["cat_2"]);
	$reason_list = array($lang["reason_0"], $lang["reason_1"], $lang["reason_2"], $lang["reason_3"]);
	$action_list = array($lang["action_0"], $lang["action_1"], $lang["action_2"]);
	$status_list = array($lang["new"]);
?>
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="400" align="right" class="title"><input type="button" value="<?=$lang["list_button"]?>" class="button" onclick="Redirect('<?=site_url('order/rma/')?>')"></td>
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
	<col width="20"><col width="50"><col width="80"><col width="80"><col><col width="150"><col width="200"><col width="150"><col width="80"><!--<col width="150">--><col width="26">
	<tr class="header">
		<td height="20"><img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
		<td><a href="#" onClick="SortCol(document.fm, 'id', '<?=$xsort["id"]?>')"><?=$lang["id"]?> <?=$sortimg["id"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'so_no', '<?=$xsort["so_no"]?>')"><?=$lang["so_no"]?> <?=$sortimg["so_no"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'client_id', '<?=$xsort["client_id"]?>')"><?=$lang["client_id"]?> <?=$sortimg["client_id"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'product_returned', '<?=$xsort["product_returned"]?>')"><?=$lang["product_returned"]?> <?=$sortimg["product_returned"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'category', '<?=$xsort["category"]?>')"><?=$lang["category"]?> <?=$sortimg["category"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'reason', '<?=$xsort["reason"]?>')"><?=$lang["reason"]?> <?=$sortimg["reason"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'action_request', '<?=$xsort["action_request"]?>')"><?=$lang["action_request"]?> <?=$sortimg["action_request"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'status', '<?=$xsort["status"]?>')"><?=$lang["status"]?> <?=$sortimg["status"]?></a></td>
		<!--<td><?=$lang["shipto"]?></td>-->
		<td></td>
	</tr>
	<tr class="search" id="tr_search" <?=$searchdisplay?>>
		<td></td>
		<td><input name="id" class="input" value="<?=htmlspecialchars($this->input->get("id"))?>"></td>
		<td><input name="so_no" class="input" value="<?=htmlspecialchars($this->input->get("so_no"))?>"></td>
		<td><input name="client_id" class="input" value="<?=htmlspecialchars($this->input->get("client_id"))?>"></td>
		<td><input name="product_returned" class="input" value="<?=htmlspecialchars($this->input->get("product_returned"))?>"></td>
		<td>
			<?php
				if ($this->input->get("category") != "")
				{
					$c_selected[$this->input->get("category")] = " SELECTED";
				}
			?>
			<select name="category" class="input">
				<option value="">
				<?php
					foreach ($cat_list as $rskey=>$rsvalue)
					{
				?>
				<option value="<?=$rskey?>"<?=$c_selected[$rskey]?>><?=$cat_list[$rskey]?>
				<?php
					}
				?>
			</select>
		</td>
		<td>
			<?php
				if ($this->input->get("reason") != "")
				{
					$r_selected[$this->input->get("reason")] = " SELECTED";
				}
			?>
			<select name="reason" class="input">
				<option value="">
				<?php
					foreach ($reason_list as $rskey=>$rsvalue)
					{
				?>
				<option value="<?=$rskey?>"<?=$r_selected[$rskey]?>><?=$reason_list[$rskey]?>
				<?php
					}
				?>
			</select>
		</td>
		<td>
			<?php
				if ($this->input->get("action") != "")
				{
					$a_selected[$this->input->get("action")] = " SELECTED";
				}
			?>
			<select name="action" class="input">
				<option value="">
				<?php
					foreach ($action_list as $rskey=>$rsvalue)
					{
				?>
				<option value="<?=$rskey?>"<?=$a_selected[$rskey]?>><?=$action_list[$rskey]?>
				<?php
					}
				?>
			</select>
		</td>
		<td>
			<?php
				if ($this->input->get("status") != "")
				{
					$s_selected[$this->input->get("status")] = " SELECTED";
				}
			?>
			<select name="status" class="input">
				<option value="">
				<?php
					foreach ($status_list as $rskey=>$rsvalue)
					{
				?>
				<option value="<?=$rskey?>"<?=$s_selected[$rskey]?>><?=$status_list[$rskey]?>
				<?php
					}
				?>
			</select>
		</td>
		<!--<td></td>-->
		<td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
	</tr>
<?php
	$i=0;
	if ($objlist)
	{
		foreach ($objlist as $obj)
		{
?>

	<tr class="row<?=$i%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" onClick="Redirect('<?=site_url('order/rma/view/'.$obj->get_id())?>')">
		<td height="20"><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$obj->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$obj->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$obj->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$obj->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$obj->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$obj->get_modify_by()?>'></td>
		<td><?=$obj->get_id()?></td>
		<td><?=$obj->get_so_no()?></td>
		<td><?=$obj->get_client_id()?></td>
		<td><?=$obj->get_product_returned()?></td>
		<td><?=$cat_list[$obj->get_category()]?></td>
		<td><?=$reason_list[$obj->get_reason()]?></td>
		<td><?=$action_list[$obj->get_action_request()]?></td>
		<td><?=$status_list[$obj->get_status()]?></td>
		<!--<td><script language="javascript">get_content('<?=$obj->get_shipfrom()?>')</script></td>-->
		<td align="center"></td>
	</tr>
<?php
			$i++;
		}
	}
?>
</table>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
</body>
</html>