<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script language="javascript">
<!--

function addnewcomp()
{
	var newrows = document.getElementById('addcompetitor');
	var newcomp = document.getElementById('addcompbutton');
	var button = document.getElementById('addnewcomp_button');

	if(newrows.style.display == 'none')
	{
		newrows.style.display = 'block';
		button.value = 'Hide';
	}
	else if(newrows.style.display == 'block')
	{
		newrows.style.display = 'none';
		button.value = 'Show more';
	}

}

function CheckVal(fm)
{
	var total_comp_row = document.getElementById("updatecompetitor").rows.length;

	var k=0;
	for (var i = 1; i < total_comp_row; i++)
	{
		var reprice_min_margin = document.getElementById('comp['+k+'][reprice_min_margin]').value;
		var name = document.getElementById('comp['+k+'][name]').textContent;

		if(reprice_min_margin == "")
		{
			if(!confirm(name+" - reprice_min_margin is empty. It will be set to default 9%. Continue?"))
			{
				return false;
			}
		}

		var reprice_value = document.getElementById("comp["+k+"][reprice_value]").value;
		if(reprice_value == "")
		{
			if(!confirm(name+" - reprice_value is empty. It will be set to default 0. Continue?"))
			{
				return false;
			}
		}

		k++;
	}

	var total_addcomp_row = document.getElementById("addcompetitor").rows.length;
	var k=0;
	for (var i = 1; i < total_addcomp_row; i++)
	{
		var select_name = document.getElementById("add["+k+"][name]");
		var addnewcomp_name = select_name.options[select_name.selectedIndex].text;
		var selectedindex = select_name.selectedIndex;

		if(selectedindex != 0)
		{
			var reprice_min_margin = document.getElementById("add["+k+"][reprice_min_margin]").value;
			if(reprice_min_margin == "")
			{
				if(!confirm(addnewcomp_name+" - reprice_min_margin is empty. It will be set to default 9%. Continue?"))
				{
					return false;
				}
			}

			var reprice_value = document.getElementById("add["+k+"][reprice_value]").value;
			if(reprice_value =="")
			{
				if(!confirm(addnewcomp_name+" - reprice_value is empty. It will be set to default 0. Continue?"))
				{
					return false;
				}
			}
		}
		k++;
	}
	return true;

}

-->
</script>
</head>
<body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" class="frame_left">
<?=$notice["img"]?>
<div id="main" style="width:auto">
<?php
	$stock_status=array(0=>"In stock", 1=>"Out of stock", 2=>"Pre-order", 3=>"Arriving");
	$ar_status = array(0=>"Inactive", 1=>"Active");
	$ar_match = array(0=>"Ignore", 1=>"Active");

?>
<?php
if($sku != "")
{
	if($canedit)
	{
?>
<form name="list" action="<?=base_url()?>marketing/competitor_map/view/<?=$country_id?>/<?=$prod_obj->get_sku().($this->input->get('target') == ""?"":"?target=".$this->input->get('target'))?>" method="POST" onSubmit="return CheckForm(this)">
<input type="hidden" name="sku" value="<?=$sku?>">
<input type="hidden" name="posted" value="1">
<input type="hidden" name="target" value="<?=$target?>">
<?php
	}
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="60" align="left" style="padding-left:8px;">
		<div style="float:left"><img src='<?=get_image_file($prod_obj->get_image(),'s',$prod_obj->get_sku())?>'> &nbsp;</div>
		<b style="font-size: 12px; color: rgb(0, 0, 0);"><?=$lang["header"]?></b><br><?=$lang["header_message"]." - "?><b><a href="<?=$website_link."mainproduct/view/".$prod_obj->get_sku()?>" target="_blank"><font style="text-decoration:none; color:#000000; font-size:14px;"><?=$prod_obj->get_sku()." - ".$prod_obj->get_name()?><?=$prod_obj->get_clearance()?" <span style='color:#0072E3; font-size:14px;'>(Clearance)</span>":""?></font></a></b><br><?=$lang["master_sku"]." ".$master_sku?></td>
	</tr>
	<tr>
		<td height="10" class="field">&nbsp;</td>
	</tr>

	<tr class="header">
		<td height="20" align="left" style="padding-left:8px;"><b style="font-size:price_obj 12px; color: rgb(255, 255, 255);"><?=$lang["competitor_info"]?></b></td>
	</tr>
	<tr>
		<td width="100%">
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tb_main" id="updatecompetitor">
				<col width="3%"><col width="12%"><col width="6%"><col width="6%"><col width="23%"><col width="13%"><col width="13%"><col width="6%"><col width="8%"><col width="8%"><col width="10%"><col width="10%">
				<tr>
					<td class="field">&nbsp;</td>
					<td class="field"><b><?=$lang["competitor_name"]?></b></td>
					<td class="field"><b><?=$lang["price"]?></b></td>
					<td class="field" style="text-align:center"><b><?=$lang["comp_ship_charge"]?></b></td>
					<td class="field"><b><?=$lang["url"]?></b></td>
					<td class="field"><b><?=$lang["note_1"]?></b></td>
					<td class="field"><b><?=$lang["note_2"]?></b></td>
					<td class="field" style="text-align:center"><b><?=$lang["comp_stock_status"]?></b></td>
					<td class="field" align="centre" style="text-align:center"><b><?=$lang["status"]?></b><br/></td>
					<td class="field" align="centre" style="text-align:center"><b><?=$lang["match"]?></b><br/></td>
					<td class="field" align="centre" style="text-align:center"><b><?=$lang["reprice_min_margin"]?></b><br/></td>
					<td class="field" align="centre" style="text-align:center"><b><?=$lang["reprice_value"]?></b><br/></td>
				</tr>
			<?php
			if($active_compmap_list && $total_price_arr)
			{
				$i=0;
				$islowestmatch = TRUE;  # for highlighting row with lowest price AND match = 1

				# this loop will display competitors according to ascending price (incl shipping)
				foreach ($total_price_arr as $key => $price)
				{
					$competitor_obj = $active_compmap_list[$key];

					$competitor_id	= $competitor_obj["competitor_id"];
					$competitor_name = $competitor_obj["name"];
					$product_url 	= $competitor_obj["product_url"];
					$price 			= $competitor_obj["now_price"];
					$status 		= $competitor_obj["status"];
					$match 			= $competitor_obj["match"];
					$note_1 		= $competitor_obj["note_1"];
					$note_2 		= $competitor_obj["note_2"];
					$stock_status_name 		= $stock_status[$competitor_obj["comp_stock_status"]];
					$comp_ship_charge 		= $competitor_obj["comp_ship_charge"];
					$reprice_min_margin 	= number_format($competitor_obj["reprice_min_margin"], 2, '.', '');
					$reprice_value 	= number_format($competitor_obj["reprice_value"], 2, '.', '');

					if($islowestmatch == TRUE && $match == 1)
					{

			?>
				<tr>
					<td bgcolor="greenyellow" style="cursor:pointer;"><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$competitor_obj["create_on"]?>&#13;<?=$lang["create_at"]?>:<?=$competitor_obj["create_at"]?>&#13;<?=$lang["create_by"]?>:<?=$competitor_obj["create_by"]?>&#13;<?=$lang["modify_on"]?>:<?=$competitor_obj["modify_on"]?>&#13;<?=$lang["modify_at"]?>:<?=$competitor_obj["modify_at"]?>&#13;<?=$lang["modify_by"]?>:<?=$competitor_obj["modify_by"]?>'></td>
					<td bgcolor="greenyellow" id="comp[<?=$i?>][name]" ><b><?=htmlspecialchars($competitor_name)?></b></td>
					<td bgcolor="greenyellow"><input type="text" class="input" name="comp[<?=$i?>][price]" value="<?=$price?>"></td>
					<td bgcolor="greenyellow"><input type="text" class="input" name="comp[<?=$i?>][comp_ship_charge]" value="<?=htmlspecialchars($comp_ship_charge)?>"></td>
					<td bgcolor="greenyellow"><input type="url" class="input" name="comp[<?=$i?>][url]" value="<?=$product_url?>"></td>
					<td bgcolor="greenyellow"><input type="text" class="input" name="comp[<?=$i?>][note_1]" value="<?=htmlspecialchars($note_1)?>"></td>
					<td bgcolor="greenyellow"><input type="text" class="input" name="comp[<?=$i?>][note_2]" value="<?=htmlspecialchars($note_2)?>"></td>
					<td bgcolor="greenyellow" align="center"><?=htmlspecialchars($stock_status_name)?></td>
					<td bgcolor="greenyellow" <?=$status?"":"style=\"background-color:darkgray\""?> align="center">
						<select name="comp[<?=$i?>][status]">
			<?php
						foreach ($ar_status as $key => $value)
						{
			?>
							<option value="<?=$key?>" <?=$status==$key?"SELECTED":""?>><?=$value?></option>
			<?php
						}
			?>
						</select>
					</td>
					<td bgcolor="greenyellow" <?=$match?"":"style=\"background-color:darkgray\""?> align="center">
						<select name="comp[<?=$i?>][match]">
			<?php
						foreach ($ar_match as $key => $value)
						{
			?>
							<option value="<?=$key?>" <?=$match==$key?"SELECTED":""?>><?=$value?></option>
			<?php
						}
			?>
						</select>

						<input type="hidden" name="comp[<?=$i?>][competitor_id]" value="<?=$competitor_id?>">
					</td>
					<td bgcolor="greenyellow"><input isNumber type="text" class="input" name="comp[<?=$i?>][reprice_min_margin]" id="comp[<?=$i?>][reprice_min_margin]" value="<?=htmlspecialchars($reprice_min_margin)?>"></td>
					<td bgcolor="greenyellow"><input isNumber type="text" class="input" name="comp[<?=$i?>][reprice_value]" id="comp[<?=$i?>][reprice_value]" value="<?=htmlspecialchars($reprice_value)?>"></td>
				</tr>
			<?php
						$i++;
						$islowestmatch = FALSE;

					}
					else
					{
?>
				<tr>
					<td class="row1" style="cursor:pointer;"><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$competitor_obj["create_on"]?>&#13;<?=$lang["create_at"]?>:<?=$competitor_obj["create_at"]?>&#13;<?=$lang["create_by"]?>:<?=$competitor_obj["create_by"]?>&#13;<?=$lang["modify_on"]?>:<?=$competitor_obj["modify_on"]?>&#13;<?=$lang["modify_at"]?>:<?=$competitor_obj["modify_at"]?>&#13;<?=$lang["modify_by"]?>:<?=$competitor_obj["modify_by"]?>'></td>
					<td class="row1" id="comp[<?=$i?>][name]"><?=htmlspecialchars($competitor_name)?></td>
					<td class="row1"><input type="text" class="input" name="comp[<?=$i?>][price]" value="<?=$price?>"></td>
					<td class="row1"><input type="text" class="input" name="comp[<?=$i?>][comp_ship_charge]" value="<?=htmlspecialchars($comp_ship_charge)?>"></td>
					<td class="row1"><input type="url" class="input" name="comp[<?=$i?>][url]" value="<?=$product_url?>"></td>
					<td class="row1"><input type="text" class="input" name="comp[<?=$i?>][note_1]" value="<?=htmlspecialchars($note_1)?>"></td>
					<td class="row1"><input type="text" class="input" name="comp[<?=$i?>][note_2]" value="<?=htmlspecialchars($note_2)?>"></td>
					<td class="row1" align="center"><?=htmlspecialchars($stock_status_name)?></td>
					<td class="row1" <?=$status?"":"style=\"background-color:darkgray\""?> align="center">
						<select name="comp[<?=$i?>][status]">
			<?php
						foreach ($ar_status as $key => $value)
						{
			?>
							<option value="<?=$key?>" <?=$status==$key?"SELECTED":""?>><?=$value?></option>
			<?php
						}
			?>
						</select>

					</td>
					<td class="row1" <?=$match?"":"style=\"background-color:darkgray\""?> align="center">
						<select name="comp[<?=$i?>][match]">
			<?php
						foreach ($ar_match as $key => $value)
						{
			?>
							<option value="<?=$key?>" <?=$match==$key?"SELECTED":""?>><?=$value?></option>
			<?php
						}
			?>
						</select>

						<input type="hidden" name="comp[<?=$i?>][competitor_id]" value="<?=$competitor_id?>">
					</td>
					<td class="row1" ><input isNumber min="0" type="text" class="input" name="comp[<?=$i?>][reprice_min_margin]" id="comp[<?=$i?>][reprice_min_margin]"  value="<?=htmlspecialchars($reprice_min_margin)?>"></td>
					<td class="row1" ><input isNumber type="text" class="input" name="comp[<?=$i?>][reprice_value]" id="comp[<?=$i?>][reprice_value]" value="<?=htmlspecialchars($reprice_value)?>"></td>
				</tr>
<?php
						$i++;
					}
				}
			}
			if($inactive_compmap_list)
			{
				if(empty($i)) $i = 0; # if SKU only has inactive competitor_map

				foreach ($inactive_compmap_list as $competitor_obj)
				{
					$competitor_id	= $competitor_obj["competitor_id"];
					$competitor_name = $competitor_obj["name"];
					$product_url 	= $competitor_obj["product_url"];
					$price 			= $competitor_obj["now_price"];
					$status 		= $competitor_obj["status"];
					$match 			= $competitor_obj["match"];
					$note_1 		= $competitor_obj["note_1"];
					$note_2 		= $competitor_obj["note_2"];
					$stock_status_name 		= $stock_status[$competitor_obj["comp_stock_status"]];
					$comp_ship_charge 		= $competitor_obj["comp_ship_charge"];
					$reprice_min_margin 	= number_format($competitor_obj["reprice_min_margin"], 2, '.', '');
					$reprice_value 	= number_format($competitor_obj["reprice_value"], 2, '.', '');
?>
	<tr>
					<td bgcolor="lightgray" style="cursor:pointer;"><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$competitor_obj["create_on"]?>&#13;<?=$lang["create_at"]?>:<?=$competitor_obj["create_at"]?>&#13;<?=$lang["create_by"]?>:<?=$competitor_obj["create_by"]?>&#13;<?=$lang["modify_on"]?>:<?=$competitor_obj["modify_on"]?>&#13;<?=$lang["modify_at"]?>:<?=$competitor_obj["modify_at"]?>&#13;<?=$lang["modify_by"]?>:<?=$competitor_obj["modify_by"]?>'></td>
					<td bgcolor="lightgray" id="comp[<?=$i?>][name]"><font color="gray"><?=htmlspecialchars($competitor_name)?></font></td>
					<td bgcolor="lightgray"><input type="text" class="input" name="comp[<?=$i?>][price]" value="<?=$price?>"></td>
					<td bgcolor="lightgray"><input type="text" class="input" name="comp[<?=$i?>][comp_ship_charge]" value="<?=htmlspecialchars($comp_ship_charge)?>"></td>
					<td bgcolor="lightgray"><input type="url" class="input" name="comp[<?=$i?>][url]" value="<?=$product_url?>"></td>
					<td bgcolor="lightgray"><input type="text" class="input" name="comp[<?=$i?>][note_1]" value="<?=htmlspecialchars($note_1)?>"></td>
					<td bgcolor="lightgray"><input type="text" class="input" name="comp[<?=$i?>][note_2]" value="<?=htmlspecialchars($note_2)?>"></td>
					<td bgcolor="lightgray" align="center"><font color="darkslategray"><?=htmlspecialchars($stock_status_name)?></font></td>
					<td bgcolor="lightgray" align="center">
						<select name="comp[<?=$i?>][status]">
			<?php
								foreach ($ar_status as $key => $value)
								{
			?>
							<option value="<?=$key?>" <?=$status==$key?"SELECTED":""?>><?=$value?></option>
			<?php
								}
			?>
						</select>
					</td>
					<td bgcolor="lightgray" align="center">
						<select name="comp[<?=$i?>][match]">
			<?php
								foreach ($ar_match as $key => $value)
								{
			?>
							<option value="<?=$key?>" <?=$match==$key?"SELECTED":""?>><?=$value?></option>
			<?php
								}
			?>
						</select>

						<input type="hidden" name="comp[<?=$i?>][competitor_id]" value="<?=$competitor_id?>">
					</td>
					<td bgcolor="lightgray"><input isNumber min="0" type="text" class="input" name="comp[<?=$i?>][reprice_min_margin]"  id="comp[<?=$i?>][reprice_min_margin]" value="<?=htmlspecialchars($reprice_min_margin)?>"></td>
					<td bgcolor="lightgray"><input isNumber type="text" class="input" name="comp[<?=$i?>][reprice_value]" id="comp[<?=$i?>][reprice_value]" value="<?=htmlspecialchars($reprice_value)?>"></td>
				</tr>
<?php
					$i++;
				}
			}
			?>
			</table>
		</td>
	</tr>
	<tr class="header">
		<td id="addcompbutton" height="20" align="left" style="padding-left:8px;"><b style="font-size:price_obj 12px; color: rgb(255, 255, 255);"><?=$lang["map_competitor_info"]?></b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="addnewcomp_button" value="Show more" onClick="addnewcomp()"></td>
	</tr>
	<tr>
		<td width="100%">
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tb_main" id="addcompetitor" style="display:none;">
				<col width="3%"><col width="12%"><col width="6%"><col width="6%"><col width="23%"><col width="13%"><col width="13%"><col width="6%"><col width="8%"><col width="8%"><col width="10%"><col width="10%">
				<tr>
					<td class="field">&nbsp;</td>
					<td class="field"><b><?=$lang["competitor_name"]?></b></td>
					<td class="field"><b><?=$lang["price"]?></b></td>
					<td class="field" style="text-align:center"><b><?=$lang["comp_ship_charge"]?></b></td>
					<td class="field"><b><?=$lang["url"]?></b></td>
					<td class="field"><b><?=$lang["note_1"]?></b></td>
					<td class="field"><b><?=$lang["note_2"]?></b></td>
					<td class="field" style="text-align:center"><b><?=$lang["comp_stock_status"]?></b></td>
					<td class="field" align="centre" style="text-align:center"><b><?=$lang["status"]?></b><br/></td>
					<td class="field" align="centre" style="text-align:center"><b><?=$lang["match"]?></b><br/></td>
					<td class="field" align="centre" style="text-align:center"><b><?=$lang["reprice_min_margin"]?></b><br/></td>
					<td class="field" align="centre" style="text-align:center"><b><?=$lang["reprice_value"]?></b><br/></td>
				</tr>

				<?php
					for ($k=0; $k < $objcount2; $k++)
					{
				?>
					<tr>
						<td class="add_row">&nbsp;</td>
						<td class="add_row">
							<select name="add[<?=$k?>][name]" id="add[<?=$k?>][name]" >
								<option></option>

				<?php
						foreach ($unmapped_competitor_list as $unmapped_competitor_name)
						{
				?>
								<option value="<?=htmlspecialchars($unmapped_competitor_name)?>"><?=htmlspecialchars($unmapped_competitor_name)?></option>
				<?php
						}
				?>
							</select>
						</td>
						<td class="add_row"><input type="text" class="input" name="add[<?=$k?>][price]"></td>
						<td class="add_row"><input type="text" class="input" name="add[<?=$k?>][comp_ship_charge]"></td>
						<td class="add_row"><input type="url" class="input" name="add[<?=$k?>][url]"></td>
						<td class="add_row"><input type="text" class="input" name="add[<?=$k?>][note_1]"></td>
						<td class="add_row"><input type="text" class="input" name="add[<?=$k?>][note_2]"></td>
						<td class="add_row"></td>
						<td class="add_row" align="center">
							<select name="add[<?=$k?>][status]">
								<option value ="1">Active</option>
								<option value="0">Inactive</option>
							</select>
						</td>
						<td class="add_row" align="center">
							<select name="add[<?=$k?>][match]">
								<option value ="1">Active</option>
								<option value="0">Ignore</option>
							</select>
						</td>
						<td class="add_row"><input type="text" class="input" name="add[<?=$k?>][reprice_min_margin]" id="add[<?=$k?>][reprice_min_margin]"></td>
						<td class="add_row"><input type="text" class="input" name="add[<?=$k?>][reprice_value]" id="add[<?=$k?>][reprice_value]"></td>
				<?php
					}
				?>

			</table>
		</td>
	</tr>
</table>


<?php
		if($canedit)
		{
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_detail">
	<tr>
		<td align="right" style="padding-right:50px;" height="30"><input type="button" value="Update" class="button" onClick="if(CheckForm(this.form) && CheckVal(this.form)) this.form.submit();"></td>
	</tr>
</table>


</form>
<?php
		}

	if($inactive_mapped_list)
	{
?>

	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td class="bg_row">&nbsp;</td>
		</tr>
		<tr>
			<td class="bg_row">&nbsp;</td>
		</tr>
		<tr>
			<td class="bg_row">&nbsp;</td>
		</tr>
		<tr>
			<td class="xrow0">&nbsp;</td>
		</tr>
		<tr class="header">
			<td height="20" align="left" style="background-color:darkslategray;padding-left:8px;"><b style="font-size:price_obj 12px; color: lightgray;"><?=$lang["disabled_competitor_info"]?></b></td>
		</tr>
		<tr class="xrow0">
			<td height="20" align="left" style="padding-left:8px;"><?=$lang["disabled_note"]?></td>
		</tr>
		<tr>
			<td width="100%">
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tb_main">
					<col width="20%"><col width="10%"><col width="70%">
					<tr>
						<td style="background-color:darkgray;"><font color="darkslategray"><b><?=$lang["competitor_name"]?></b></font></td>
						<td style="background-color:darkgray;"><font color="darkslategray"><b><?=$lang["price"]?></b></font></td>
						<td style="background-color:darkgray;"><font color="darkslategray"><b><?=$lang["url"]?></b></</td>
					</tr>
	<?php

		// echo "<pre>";var_dump($inactive_mapped_list);die();
		foreach ($inactive_mapped_list as $inactive_competitor)
		{
	?>
					<tr>
						<td style="background-color:lightgray;"><font color="darkslategray"><?=htmlspecialchars($inactive_competitor["name"])?></font></td>
						<td style="background-color:lightgray;"><font color="darkslategray"><?=htmlspecialchars($inactive_competitor["price"])?></font></td>
						<td style="background-color:lightgray;"><font color="darkslategray"><?=htmlspecialchars($inactive_competitor["url"])?></font></td>
					</tr>
	<?php

		}
	}

	?>
				</table>
			</td>
		</tr>
	</table>
<?php
	}
?>
</div>
<?=$notice["js"]?>
<?php

	if($prompt_notice)
	{
?>
<script language="javascript">alert('<?=$lang["update_notice"]?>')</script>
<?php
	}
?>
<script language="javascript">
if(document.list)
{
	lockqty(document.list.status.value);
}
</script>
</body>
</html>