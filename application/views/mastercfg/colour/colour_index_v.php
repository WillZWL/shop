<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
</head>
<body onload="reset_action()">
<div id="main">
<?php $status_arr = array("1"=>$lang["active"],"0"=>$lang["inactive"]);?>
<?=$notice["img"]?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="300" align="right" class="title"><input type="button" value="<?=$lang["show_all_cc"]?>" class="button" onclick="Redirect('<?=site_url('mastercfg/colour/')?>')"></td>
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
<table border="0" cellpadding="0" cellspacing="0" height="70" class="tb_list" width="100%">
	<col width="30"><col width="100"><col width="350"><col width="100"><col width="300">
	<tr class="add_header">
		<td>&nbsp;</td>
		<td><?=$lang["code"]?></td>
		<td><?=$lang["name"]?></td>
		<td><?=$lang["status"]?></td>
		<td>&nbsp;</td>
	</tr>
	<form name="fm_add" id="fm_add" method="post" onSubmit="return CheckForm(this)">
	<tr class="add_row">
		<td>&nbsp;</td>
		<td><input name="id" class="input" value="<?=$colour_id?$colour_id:""?>" notEmpty maxLen=2 maxlength="2"></td>
		<td><input name="name" id="name" class="input" value="<?=$name?$name:""?>" notEmpty></td>
		<td><select name="status" class="input">
		<?php
			foreach($status_arr as $key=>$value)
			{
		?><option value="<?=$key?>"><?=$value?></option><?php
			}
		?></select></td>
		<td align="left">
			<input type="button" value="Translate for all languages" onclick="translate_colour()">
			<input type="submit" value="Add">
		</td>
	</tr>
<?php
	if ($lang_list)
	{
?>
		<tr class="add_header">
		<td>&nbsp;</td>
		<td><?=$lang["language"]?></td>
		<td><?=$lang["name_translate"]?></td>
		<td colspan="2">&nbsp;</td>
<?php
		foreach ($lang_list as $lang_obj)
		{
			$lang_id = $lang_obj->get_id();
			if($lang_id != 'en')
			{
				// var_dump($translate[$lang_id]);die();
?>
	<tr class="add_row">
		<td>&nbsp;</td>
		<td><?=$lang_obj->get_name()?></td>
		<td><input name="name_translate[<?=$lang_obj->get_id()?>]" class="input" value="<?=$translate[$lang_id]?ucfirst($translate[$lang_id]):""?>"></td>
		<td colspan="2">&nbsp;</td>
	</tr>

<?php
			}
		}
	}
?>
	<tr class="empty_row">
		<td colspan="6"><hr></hr></td>
	</tr>
	<input type="hidden" name="posted" value="1">
	<input type="hidden" id="action_add_translate" name="action" value="add">
	</form>



	<form name="fm" method="get">
	<tr class="header">
		<td height="20"><img src="<?=base_url().'/images/expand.png'?>" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
		<td><a href="#" onClick="SortCol(document.fm, 'id', '<?=$xsort["id"]?>')"><?=$lang["code"]?></a> <?=$sortimg["id"]?></td>
		<td><a href="#" onClick="SortCol(document.fm, 'name', '<?=$xsort["name"]?>')"><?=$lang["name"]?></a> <?=$sortimg["name"]?></td>
		<td><a href="#" onClick="SortCol(document.fm, 'status', '<?=$xsort["status"]?>')"><?=$lang["status"]?></a> <?=$sortimg["status"]?></td>
		<td></td>
	</tr>
	<tr class="search" id="tr_search" >
		<td></td>
		<td><input name="id" class="input" value="<?=$this->input->get("id")?>" maxlength="2"></td>
		<td><input name="name" class="input" value="<?=$this->input->get("name")?>"></td>
		<td><select name="status" class="input"><option value=""></option>
		<?php
			foreach($status_arr as $key=>$value)
			{
		?><option value="<?=$key?>" <?= $key === $this->input->get("status")?"SELECTED":""?>><?=$value?></option><?php
			}
		?></select></td>
		<td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()."images/find.gif"?>') no-repeat;"></td>
	</tr>
	<input type="hidden" name="sort" value="<?=$this->input->get("sort")?>">
	<input type="hidden" name="order" value="<?=$this->input->get("order")?>">
	</form>
<?php
	$i=0;
	if($list)
	{
		foreach($list as $obj)
		{
			if($obj->get_id() != $this->input->get("edit"))
			{
?>
	<tr class="row<?=$i++%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" onClick='Redirect("<?=base_url()."mastercfg/colour/?".$_SERVER["QUERY_STRING"]."&edit=".$obj->get_id()?>");'>
		<td><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$obj->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$obj->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$obj->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$obj->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$obj->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$obj->get_modify_by()?>'></td>
		<td><?=$obj->get_id()?></td>
		<td><?=$obj->get_name()?></td>
		<td><?=$status_arr[$obj->get_status()]?></td>
		<td></td>
	</tr>
<?php
			}
			else
			{
?>
	<form name="fm_edit" method="post" onClick="checkForm(this)";>
	<tr class="row<?=$i++%2?>">
		<td><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$obj->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$obj->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$obj->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$obj->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$obj->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$obj->get_modify_by()?>'></td>
		<td><input name="id" type="text" value="<?=$obj->get_id()?>" class="input"></td>
		<td><input name="name" type="text" value="<?=$obj->get_name()?>" class="input">
			<table width="100%" style="border:none;">
			<col width="10%">
<?php
	if($list_translate && $lang_list)
	{
		$colour_id = $obj->get_id();
		$colour_ext_obj = $list_translate[$colour_id];

		foreach ($colour_ext_obj as $translated_obj)
		{

			foreach ($lang_list as $lang_obj)
			{
				if($lang_obj->get_id() == $translated_obj->get_lang_id())
				{
					$lang_name = $lang_obj->get_name();
					$name_translated = $translated_obj->get_name();
?>

				<tr>
					<td style="border:none;"><?=$lang_name?></td>
					<td style="border:none;" width="70%"><input name="name_translate[<?=$lang_obj->get_id()?>]" type="text" value="<?=$name_translated?$name_translated:""?>" class="input"></td>
				</tr>


<?php
				}
			}
		}
	}
?>
			</table>
		</td>
		<td><select name="status" class="input"><?php
		foreach($status_arr as $key=>$value)
		{
		?><option value="<?=$key?>" <?=$key == $obj->get_status()?"SELECTED":""?>><?=$value?></option><?php
		}
		?></select></td>
		<td><input name="posted" type="hidden" value="1"><input name="action" type="hidden" value="edit"><input type="button" value="<?=$lang["update"]?>" onClick="if(CheckForm(this.form)) document.fm_edit.submit();" class="button">&nbsp;&nbsp;<input type="button" value="<?=$lang["back"]?>" onClick='Redirect("<?=base_url()."mastercfg/colour/?".$_SERVER["QUERY_STRING"]?>");' class="button"></td>
	</tr>
	</form>
<?php
			}
		}
	}

?>
</table>
<?=$this->pagination_service->create_links_with_style()?>
</div>
<?=$notice["js"]?>
<script language="javascript">


function reset_action()
{
	var action = document.getElementById('action_add_translate');

	if (action.value = 'translate')
	{
		action.value = 'add';
	}

}

function translate_colour()
{
	var action = document.getElementById('action_add_translate');
	action.value = 'translate';

	if (CheckForm(document.getElementById('fm_add')))
	{
		document.getElementById('fm_add').submit();
	}
}
</script>
</body>
</html>