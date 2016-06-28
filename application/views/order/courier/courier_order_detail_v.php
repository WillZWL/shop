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
	if($createDate){
		$today = $createDate;
	}else{
		$today = getdate();
	}
	$ars_status = array("4"=>$lang["partial_allocated"], "5"=>$lang["full_allocated"]);
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
<form name="search_form" id="search_form" method="post" onSubmit="return CheckForm(this)">
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
	<tr>
		<td style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
		
		<td align='right'><b><?=$lang["create_date"]?></b></td>
		<td>
			 <select name="create_day">
				<?php
					for ($i = 1; $i <= 31; $i++){
				?>
					<option value="<?php echo ($i<10)? "0$i" : "$i";?>" <?php echo ($i == $today['mday']) ? "selected" : "";?>>
						<?php echo $i;?>
					</option>
				<?php } ?>
			 </select>
			<select name="create_month">
				<?php
					for ($i = 1; $i <= 12; $i++){ ?>
					<option value="<?php echo ($i<10)? "0$i" : "$i";?>" <?php echo ($i == $today['mon']) ? "selected" : "";?>>
						<?php echo $i;?>
					</option>
				<?php } ?>
			</select>
			<select name="create_year">
			<?php
				$thisYear = $today['year'];
				$start = $thisYear - 3;
				$end = $thisYear + 3;
				for ($i = $start; $i <= $end; $i++){
			?>
					<option value="<?php echo $i;?>" <?php echo ($i == $thisYear) ? "selected" : "";?>>
						<?php echo $i;?>
					</option>
			<?php } ?>
			</select>
		</td>
		<td><?=$lang["coureir"]?></td>
		<td align="right" style="padding-right:8px;">
			<select name="courier_id" id="courier_id"  >
				<option value=""><?=$lang["defualt_courier_option"]?></option>
			<?php foreach ($courierList as $courierObj) {?>
					<option value="<?=$courierObj->getCourierId();?>" <?php if($selectedCourierId==$courierObj->getCourierId()){echo "selected";}?> >
						<?php echo $courierObj->getCourierName(). " <-> " .$courierObj->getAftershipId();?>
					</option>
			<?php } ?>
			 </select>
		</td>
		<td>
			<input type="button" value="search" name="search" onclick="document.getElementById('download').value='';document.getElementById('search_form').submit();" />
		</td>
		<td><input type="button" value="Download CSV" name="download_csv" 
		onclick="document.getElementById('download').value='csv';document.getElementById('search_form').submit()"></td>
	</tr>
	<tr>
		<td colspan="5" style="padding-left:8px"><?=$_SESSION["NOTICE"] ;?></td>
		<td></td>
		<td></td>
	</tr>
</table>
<input type="hidden" name="download" id="download" value="">
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<form name="fm_edit" method="post">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
	<colgroup>
	<col width="50">
	<col width="60">
	<col width="60">
	<col width="60">
	<col width="90">
	<col width="50">
	<col width="50">
	<col width="50">
	<col width="50">
	<col width="80">
	<col width="90">
	<col width="90">
	<col width="90">
	<col width="60">
	<col width="120">
	<col width="50">
	<col width="100">
	<col width="26">
	</colgroup>
	<tr class="header">	
		<td height="20">Delete</td>		
		<td title="<?=$lang["batch_id"]?>"><a href="#" onClick="SortCol(document.fm, 'batch_id', '<?=$xsort["batch_id"]?>')"><?=$lang["batch_id"]?> <?=$sortimg["batch_id"]?></a></td>

		<td title="<?=$lang["courier_id"]?>"><a href="#" onClick="SortCol(document.fm, 'courier_id', '<?=$xsort["courier_id"]?>')"><?=$lang["courier_id"]?> <?=$sortimg["courier_id"]?></a></td>

		<td title="<?=$lang["courier_order_id"]?>"><?=$lang["courier_order_id"]?></td>
		<td title="<?=$lang["declared_desc"]?>"><?=$lang["declared_desc"]?></td>
		<td title="<?=$lang["declared_value"]?>"><?=$lang["declared_value"]?></td>
		<td title="<?=$lang["delivery_country_id"]?>"><?=$lang["delivery_country_id"]?></td>
		<td title="<?=$lang["weight"]?>"><?=$lang["weight"]?></td>
		<td title="<?=$lang["currency"]?>"><?=$lang["currency"]?></td>
		<td title="<?=$lang["courier_order_status"]?>"><?=$lang["courier_order_status"]?></td>

		<td title="<?=$lang["courier_parcel_id"]?>"><a href="#" onClick="SortCol(document.fm, 'courier_parcel_id', '<?=$xsort["qty"]?>')"><?=$lang["courier_parcel_id"]?> <?=$sortimg["courier_parcel_id"]?></a></td>

		<td title="<?=$lang["tracking_no"]?>"><a href="#" onClick="SortCol(document.fm, 'tracking_no', '<?=$xsort["tracking_no"]?>')"><?=$lang["tracking_no"]?> <?=$sortimg["tracking_no"]?></a></td>
		<td title="<?=$lang["ref_id"]?>"><?=$lang["ref_id"]?></td>
		<td title="<?=$lang["manifest_id"]?>"><?=$lang["manifest_id"]?></td>
		<td title="<?=$lang["error_message"]?>"><?=$lang["error_message"]?></td>
		<td title="<?=$lang["print_nums"]?>"><?=$lang["print_nums"]?></td>
		
		<td title="<?=$lang["create_on"]?>"><?=$lang["create_on"]?></td>
		
		<td title="<?=$lang["check_all"]?>" align="center"><input type="checkbox" name="chkall" value="1" onClick="checkall(document.fm_edit, this, 1);"></td>
	</tr>
	<tr class="search" id="tr_search" <?=$searchdisplay?> >
		<td></td>
		<td></td>
		<td></td>
		<td><input name="so_no" id="so_no" class="input" value="<?=htmlspecialchars($this->input->post("so_no"))?>"></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td align="center"><input type="button" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;" onclick="searchCourierOrder()"></td>
	</tr>
</td>
</tr>

<?
	if ($objlist){
		$i=0;
		foreach ($objlist as $obj){
		$row_style="row".$i%2;
?>		
<tr name="row<?=$i?>" class="<?=$row_style?>" <?=$row_span?> onMouseOver="AddGroupClassName('row<?=$i?>', 'highlight')" onMouseOut="RemoveGroupClassName('row<?=$i?>', 'highlight')" valign="top">
		<td align="center"><input type="checkbox" name="courier_order_id[]" value="<?=$obj->getSoNo()?>"></td>
		<td><?=$obj->getBatchId()?></td>
		<td><?=$obj->getCourierId()?></td>
		<td><?=$obj->getSoNo()?></td>
		<td><?=$obj->getDeclaredDesc()?></td>
		<td><?=$obj->getDeclaredValue()?></td>
		<td><?=$obj->getDeliveryCountryId()?></td>
		<td><?=$obj->getProdWeight()?></td>
		<td><?=$obj->getDeclaredCurrency()?></td>
		<td><?=$obj->getCourierOrderStatus()?></td>
		<td><?=$obj->getCourierParcelId()?></td>
		<td><?=$obj->getTrackingNo()?></td>
		<td><?=$obj->getRealTrackingNo()?></td>
		<td><?=$obj->getManifestId()?></td>
		<td><?=$obj->getErrorMessage()?></td>
		<td><?=$obj->getPrintNums()?></td>
		<td><?=$obj->getCreateDate()?></td>
		<?php if($_batchId!=$obj->getBatchId()){
			$_batchId=$obj->getBatchId();
		?>		
		<td align="center"><input type="checkbox" name="check[]" value="<?=$obj->getBatchId()?>"></td>
		<?php }else{ ?>
		<td align="center"></td>
		<?php }?>
	</tr>
<?php		
			$i++;	
		}
	}
?>
</table>
<input type="hidden" name="posted" value="1">
<input type="hidden" name="dispatch_type"  id="dispatch_type" value="">
<input type="hidden" name="current_courier_id" value="<?=$selectedCourierId?>">
<input type="button" value="Print Order" onclick="document.getElementById('dispatch_type').value='p';document.fm_edit.submit();" />
<input type="button" value="GET TrackingNo" onclick="document.getElementById('dispatch_type').value='g';document.fm_edit.submit();" />
<input type="button" value="ADD Manifest" onclick="document.getElementById('dispatch_type').value='m';document.fm_edit.submit();" />
<input type="button" value="Print Manifest Label" onclick="document.getElementById('dispatch_type').value='pm';document.fm_edit.submit();" />
<input type="button" value="Delete Order" onclick="document.getElementById('dispatch_type').value='d';document.fm_edit.submit();" />
<!--input type="button" value="Delete Manifest" onclick="document.getElementById('dispatch_type').value='dm';document.fm_edit.submit();" /-->
</form>
<div align="right" class="count_tag">&nbsp;Total order(s): <?=$totalOrder?> &nbsp; &nbsp;<?php //$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
<script type="text/javascript">
	
	function searchCourierOrder(){

		document.getElementById('dispatch_type').value='s';
		var searchOrderId=document.getElementById('so_no').value;
		if(searchOrderId ){
			document.fm_edit.submit();
		}else{
			alert("Please input order id to search.");
			return false;
		}
	}
</script>
</body>
</html>
