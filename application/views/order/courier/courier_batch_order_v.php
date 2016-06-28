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
<?
	$ars_status = array("4"=>$lang["partial_allocated"], "5"=>$lang["full_allocated"])
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="400" align="right" class="title">
		<input type="button" value="<?=$lang["post_order"]?>" class="button" onClick="Redirect('<?=site_url('order/courier_order/index/')?>')">&nbsp;
		<input type="button" value="<?=$lang["print_order"]?>" class="button" onClick="Redirect('<?=site_url('order/courier_order/print_order')?>')"></td>
	</tr>
	<tr>
		<td height="2" class="line"></td>
		<td height="2" class="line"></td>
	</tr>
</table>
<form name="fm" method="post" onSubmit="return CheckForm(this)">
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
	<tr>
		<td style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
		<td align="right" style="padding-right:8px;">

		</td>
	</tr>
	<tr>
		<td style="padding-left:8px"><?=$lang["batch_id"]?> <?=$batch_id;?> : <?=$_SESSION["NOTICE"] ;?></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
	<colgroup>
	<col width="20">
	<col width="70">
	<col width="70">
	<col width="80">
	<col width="80">
	<col width="70">
	<col width="70">
	<col width="130">
	<col width="50">
	<col width="100">
	<col width="26">
	</colgroup>
	<tr class="header">	
		<td height="20">
				<img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));">
		</td>		
		
		<td title="<?=$lang["batch_id"]?>"><a href="#" onClick="SortCol(document.fm, 'batch_id', '<?=$xsort["batch_id"]?>')"><?=$lang["batch_id"]?> <?=$sortimg["batch_id"]?></a></td>

		<td title="<?=$lang["courier_id"]?>"><a href="#" onClick="SortCol(document.fm, 'courier_id', '<?=$xsort["courier_id"]?>')"><?=$lang["courier_id"]?> <?=$sortimg["courier_id"]?></a></td>

		<td title="<?=$lang["order_id"]?>"><a href="#" onClick="SortCol(document.fm, 'so_no', '<?=$xsort["so_no"]?>')"><?=$lang["order_id"]?> <?=$sortimg["so_no"]?></a></td>

		<td title="<?=$lang["service_type"]?>"><?=$lang["service_type"]?></td>
		<td title="<?=$lang["email"]?>"><?=$lang["email"]?></td>

		<td title="<?=$lang["delivery_name"]?>"><a href="#" onClick="SortCol(document.fm, 'item_sku', '<?=$xsort["item_sku"]?>')"><?=$lang["delivery_name"]?> <?=$sortimg["delivery_name"]?></a></td>

		<td title="<?=$lang["delivery_address"]?>"><?=$lang["delivery_address"]?></td>

		<td title="<?=$lang["delivery_city"]?>"><?=$lang["delivery_city"]?></td>

		<td title="<?=$lang["delivery_state"]?>"><?=$lang["delivery_state"]?></td>

		<td title="<?=$lang["delivery_postcode"]?>"><a href="#" onClick="SortCol(document.fm, 'delivery_postcode', '<?=$xsort["delivery_postcode"]?>')"><?=$lang["delivery_postcode"]?> <?=$sortimg["delivery_postcode"]?></a></td>

		<td title="<?=$lang["delivery_country_id"]?>"><a href="#" onClick="SortCol(document.fm, 'delivery_country_id', '<?=$xsort["delivery_country_id"]?>')"><?=$lang["delivery_country_id"]?> <?=$sortimg["delivery_country_id"]?></a></td>

		<td title="<?=$lang["delivery_phone"]?>"><a href="#" onClick="SortCol(document.fm, 'delivery_phone', '<?=$xsort["delivery_phone"]?>')"><?=$lang["delivery_phone"]?></a></td>
		
		<td title="<?=$lang["declared_value"]?>"><a href="#" onClick="SortCol(document.fm, 'declared_value', '<?=$xsort["declared_value"]?>')"><?=$lang["declared_value"]?></a></td>

		<td title="<?=$lang["status"]?>"><a href="#" onClick="SortCol(document.fm, 'courier_order_status', '<?=$xsort["courier_order_status"]?>')"><?=$lang["status"]?> <?=$sortimg["courier_order_status"]?></a></td>

		<td title="<?=$lang["tracking_no"]?>"><a href="#" onClick="SortCol(document.fm, 'tracking_no', '<?=$xsort["tracking_no"]?>')"><?=$lang["tracking_no"]?> <?=$sortimg["tracking_no"]?></a></td>

		<td title="<?=$lang["error_message"]?>"><a href="#" onClick="SortCol(document.fm, 'error_message', '<?=$xsort["error_message"]?>')"><?=$lang["error_message"]?> <?=$sortimg["error_message"]?></a></td>
		
		<td title="<?=$lang["check_all"]?>" align="center"><input type="checkbox" name="chkall" value="1" onClick="checkall(document.fm_edit, this, 1);"></td>
	</tr>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<form name="fm_edit" method="post">
<?
	if ($objlist){

		$i=0;
		foreach ($objlist as $obj){

			$rowStyle="row".$i%2;
			$obj->getTrackingNo() ? $allowEdit=false :$allowEdit=true;
			
?>		
<tr name="row<?=$i?>" class="<?=$rowStyle?>" onMouseOver="AddGroupClassName('row<?=$i?>', 'highlight')" onMouseOut="RemoveGroupClassName('row<?=$i?>', 'highlight')" valign="top">
		<td></td>
		<td><?=$obj->getBatchId()?></td>
		<td><?=$obj->getCourierid()?></td>
		<td><?=$obj->getSoNo()?></td>
		<td><?=$obj->getServiceType()?></td>
		<td><?=$obj->getEmail()?></td>
		<td><?=$obj->getDeliveryName()?>
			<?php if($allowEdit){ ?>
				<input type="text" value="" name="order[<?=$obj->getSoNo()?>][delivery_name]">
			<?php } ?>
		</td>
		<td><?=$obj->getDeliveryAddress()?>
			<?php if($allowEdit){ ?>
				<input type="text" value="" name="order[<?=$obj->getSoNo()?>][delivery_address]">
			<?php } ?>
		</td>
		<td><?=$obj->getDeliveryCity()?>
			<?php if($allowEdit){ ?>
				<input type="text" value="" name="order[<?=$obj->getSoNo()?>][delivery_city]">
			<?php } ?>
		</td>
		<td><?=$obj->getDeliveryState()?>
			<?php if($allowEdit){ ?>
				<input type="text" value="" name="order[<?=$obj->getSoNo()?>][delivery_state]">
			<?php } ?>
		</td>
		<td><?=$obj->getDeliveryPostcode()?>
			<?php if($allowEdit){ ?>
				<input type="text" value="" name="order[<?=$obj->getSoNo()?>][delivery_postcode]">
			<?php } ?>
		</td>
		<td><?=$obj->getDeliveryCountryId()?>
			<?php if($allowEdit){ ?>
				<input type="text" value="" name="order[<?=$obj->getSoNo()?>][delivery_country_id]">
			<?php } ?>
		</td>
		<td><?=$obj->getDeliveryPhone()?></td>
		<td><?=$obj->getDeclaredValue()?>
			<?php if($allowEdit){ ?>
				<input type="text" value="" name="order[<?=$obj->getSoNo()?>][declared_value]">
			<?php } ?>
		</td>
		<td><?=$obj->getCourierOrderStatus()?></td>
		<td><?=$obj->getTrackingNo()?></td>
		<td><?=$obj->getErrorMessage()?></td>			
		<td align="center">
			<?php if($allowEdit){ ?>
			<input type="checkbox" name="check[]" value="<?=$obj->getSoNo()?>">
			<?php } ?>
		</td>
	</tr>
<?php		
			$i++;	
		}
	}
?>
</table>
<input type="hidden" name="posted" value="1">
<input type="hidden" name="batch_id" value="<?=$batchId?>">
<input type="hidden" name="dispatch_type" value="" id="dispatch_type">
<input type="button" value="Update Error Order Info" onclick="document.getElementById('dispatch_type').value='u';document.fm_edit.submit();" />
<input type="button" value="Resend Order" onclick="document.getElementById('dispatch_type').value='s';document.fm_edit.submit();" />
<input type="button" value="Get Exit TrackingNo" onclick="document.getElementById('dispatch_type').value='g';document.fm_edit.submit();" />
</form>

</body>
</html>
