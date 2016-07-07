<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>mastercfg/profit_var_helper/js_platformlist"></script>
<!-- <script type="text/javascript" src="<?=base_url()?>mastercfg/courier/js_courierlist/w"></script>-->
<script type="text/javascript" language="javascript" src="<?=base_url()?>js/lytebox.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/lytebox.css" type="text/css" media="screen" />
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<?php
	$ars_status = array("4"=>$lang["partial_allocated"], "5"=>$lang["full_allocated"])
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="400" align="right" class="title">
		<input type="button" value="<?=$lang["post_order"]?>" class="button" onClick="Redirect('<?=site_url('order/courier_order/index/')?>')">&nbsp;
		<input type="button" value="<?=$lang["print_order"]?>" class="button" onClick="Redirect('<?=site_url('order/courier_order/print-order')?>')"></td>
	</tr>
	<tr>
		<td height="2" class="line"></td>
		<td height="2" class="line"></td>
	</tr>
</table>
<form name="fm" method="get" onSubmit="return CheckForm(this)">
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
	<tr>
		<td style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
		<td align="right" style="padding-right:8px;">
		<?php if($courierList){?>
			<select name="courier_id" id="courier_id" onchange="document.fm.submit();" >
				<option value=""><?=$lang["defualt_courier_option"]?></option>
			<?php foreach ($courierList as $courierObj) {?>
				<option value="<?=$courierObj->getCourierId();?>" <?php if($currentCourierId==$courierObj->getCourierId()){echo "selected";}?> >
					<?php echo $courierObj->getCourierName(). " <-> " .$courierObj->getAftershipId();?>
				</option>
			<?php } ?>
			 </select>
		<?php } ?>
		</td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
	<colgroup><col width="20">
	<col width="70">
	<col width="90">
	<col width="120">
	<col width="35">
	<col width="70">
	<col width="130">
	<col width="80">
	<col width="80">
	<col width="40">
	<col width="50">
	<col width="120">
	<col width="95">
	<col width="50">
	<col width="26">
	</colgroup>
	<tr class="header">	
		<td height="20">
				<img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));">
		</td>		
		<td title="<?=$lang["order_id"]?>"><a href="#" onClick="SortCol(document.fm, 'so_no', '<?=$xsort["so_no"]?>')"><?=$lang["order_id"]?> <?=$sortimg["so_no"]?></a></td>

		<td title="<?=$lang["courier_id"]?>"><a href="#" onClick="SortCol(document.fm, 'courier_id', '<?=$xsort["courier_id"]?>')"><?=$lang["courier_id"]?> <?=$sortimg["courier_id"]?></a></td>

		<td title="<?=$lang["prod_name"]?>"><?=$lang["prod_name"]?></td>

		<td title="<?=$lang["qty"]?>"><a href="#" onClick="SortCol(document.fm, 'qty', '<?=$xsort["qty"]?>')"><?=$lang["qty"]?> <?=$sortimg["qty"]?></a></td>

		<td title="<?=$lang["delivery_name"]?>"><a href="#" onClick="SortCol(document.fm, 'item_sku', '<?=$xsort["item_sku"]?>')"><?=$lang["delivery_name"]?> <?=$sortimg["delivery_name"]?></a></td>

		<td title="<?=$lang["email"]?>"><?=$lang["email"]?></td>
		<td title="<?=$lang["delivery_address"]?>"><?=$lang["delivery_address"]?></td>

		<td title="<?=$lang["delivery_city"]?>"><?=$lang["delivery_city"]?></td>

		<td title="<?=$lang["delivery_state"]?>"><?=$lang["delivery_state"]?></td>
		<td title="<?=$lang["delivery_phone"]?>"><?=$lang["delivery_phone"]?></td>

		<td title="<?=$lang["delivery_postcode"]?>"><a href="#" onClick="SortCol(document.fm, 'delivery_postcode', '<?=$xsort["delivery_postcode"]?>')"><?=$lang["delivery_postcode"]?> <?=$sortimg["delivery_postcode"]?></a></td>
		<td title="<?=$lang["delivery_country_id"]?>"><a href="#" onClick="SortCol(document.fm, 'delivery_country_id', '<?=$xsort["delivery_country_id"]?>')"><?=$lang["delivery_country_id"]?> <?=$sortimg["delivery_country_id"]?></a></td>

		<td title="<?=$lang["unit_price"]?>"><a href="#" onClick="SortCol(document.fm, 'unit_price', '<?=$xsort["unit_price"]?>')"><?=$lang["unit_price"]?></a></td>
		<td title="<?=$lang["declared_hscode"]?>"><?=$lang["declared_hscode"]?></td>
		<td title="<?=$lang["declared_desc"]?>"><?=$lang["declared_desc"]?></td>
		<td title="<?=$lang["declared_value"]?>"><a href="#" onClick="SortCol(document.fm, 'declared_value', '<?=$xsort["declared_value"]?>')"><?=$lang["declared_value"]?></a></td>
		<td title="<?=$lang["declared_currency"]?>"><?=$lang["declared_currency"]?></td>
		
		<td title="<?=$lang["check_all"]?>" align="center"><input type="checkbox" name="chkall" value="1" onClick="checkall(document.fm_edit, this, 1);"></td>
	</tr>
	<tr class="search" id="tr_search" <?=$searchdisplay?> >
		<td></td>
		<td><input name="so_no" class="input" value="<?=htmlspecialchars($this->input->get("so_no"))?>"></td>
		<td></td>
		<td></td>
		<td></td>
		<td><input name="delivery_name" class="input" value="<?=htmlspecialchars($this->input->get("delivery_name"))?>"></td>
		<td><input name="email" class="input" value="<?=htmlspecialchars($this->input->get("email"))?>"></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td><input name="delivery_postcode" class="input" value="<?=htmlspecialchars($this->input->get("delivery_postcode"))?>"></td>
		<td><input name="delivery_country_id" class="input" value="<?=htmlspecialchars($this->input->get("delivery_country_id"))?>"></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
	</tr>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<form name="fm_edit" method="post">
<?php
	if($objlist){
		$i=0;$currentSoNo="";$rowSpan="";
		foreach ($objlist as $obj){
			$rowSpan = "rowspan='{$itemTotal[$obj->getSoNo()]}'";
			if($currentSoNo!=$obj->getSoNo()){
				$i++; $rowStyle = "row".$i%2;
				$currentSoNo=$obj->getSoNo();
?>
	<tr name="row<?=$i?>" class="<?=$rowStyle?>" <?=$rowSpan?> onMouseOver="AddGroupClassName('row<?=$i?>', 'highlight')" onMouseOut="RemoveGroupClassName('row<?=$i?>', 'highlight')" valign="top">
		<td <?=$rowSpan?>></td>
		<td <?=$rowSpan?>><?=$obj->getSoNo()?></td>
		<td <?=$rowSpan?>><?=$obj->getCourierid()?></td>
		<td name="row<?=$i?>"><?=$obj->getProductName()?></td>
		<td name="row<?=$i?>"><?=$obj->getQty()?></td>		
		<td <?=$rowSpan?>><?=$obj->getDeliveryName()?></td>
		<td <?=$rowSpan?>><?=$obj->getEmail()?></td>
		<td <?=$rowSpan?>><?=$obj->getDeliveryAddress1()?></td>	
		<td <?=$rowSpan?>><?=$obj->getDeliveryCity()?></td>	
		<td <?=$rowSpan?>><?=$obj->getDeliveryState()?></td>
		<td <?=$rowSpan?>><?=$obj->getTel()?>
			<input name="delivery_phone[<?=$obj->getSoNo()?>]" class="input" value="<?=htmlspecialchars($this->input->post("delivery_phone[<?=$obj->getSoNo()?>]"))?>">
		</td>
		<td <?=$rowSpan?>><?=$obj->getDeliveryPostcode()?>
			<input name="delivery_postcode[<?=$obj->getSoNo()?>]" class="input" value="<?=htmlspecialchars($this->input->post("delivery_postcode[<?=$obj->getSoNo()?>]"))?>">
		</td>
		<td <?=$rowSpan?>><?=$obj->getDeliveryCountryId()?></td>		
		<td <?=$rowSpan?>><?=$obj->getUnitPrice()?></td>
		<td <?=$rowSpan?>><?=$obj->getDeclaredHsCode()?></td>
		<td <?=$rowSpan?>><?=$obj->getDeclaredDesc()?>
			<input name="declared_desc[<?=$obj->getSoNo()?>]" class="input" value="<?=htmlspecialchars($this->input->post("declared_desc[<?=$obj->getSoNo()?>]"))?>">
		</td>
		<td <?=$rowSpan?> ><?=$obj->getDeclaredValue()?>
			<input name="declared_value[<?=$obj->getSoNo()?>]" class="input" value="<?=$obj->getDeclaredValue()?>">
		</td>
		<td <?=$rowSpan?>><?=$declaredCurrency?></td>					
		<td <?=$rowSpan?> align="center"><input type="checkbox" name="check[]" value="<?=$obj->getSoNo()?>"></td>
	</tr>

<?php }else{ ?>
	<tr name="row<?=$i?>" class="<?=$rowStyle?>" <?=$rowSpan?> onMouseOver="AddGroupClassName('row<?=$i?>', 'highlight')" onMouseOut="RemoveGroupClassName('row<?=$i?>', 'highlight')" valign="top">
		<td name="row<?=$i?>"><?=$obj->getProductName()?></td>
		<td name="row<?=$i?>"><?=$obj->getQty()?></td>		
	</tr>					
<?php		}
			
		}
	}
?>
</table>
<input type="hidden" name="current_courier_id" value="<?=$currentCourierId?>" />
<input type="hidden" name="posted" value="1">
<input type="hidden" name="auto_add_order" value="1">
<?php if($courierService){ ?>
<label><?=$currentCourierId?></label>
<select name="service_type">
	<option value=""><?=$lang["default_service_type"];?></option>
	<?php foreach($courierService as $serviceType){?>
	<option value="<?=$serviceType?>"><?=$serviceType?></option>
	<?php }?>
</select>
<?php } ?>
<input type="button" value="Dispatch Selected" onclick="submit_fm_edit();" />
</form>
<div align="right" class="count_tag">&nbsp;Total order(s): <?=$totalOrder?> &nbsp; &nbsp;Total items(s): <?=$totalItem?> &nbsp; </div><?php //$this->paginationService->createLinksWithStyle()?>
<?=$notice["js"]?>
</div>
<script>
function submit_fm_edit() {
 
 	if (confirm("Are you sure to send order to Asendia!") == true) {
        document.fm_edit.submit();
    } else {
        return false;
    }
}
</script>

</body>
</html>
