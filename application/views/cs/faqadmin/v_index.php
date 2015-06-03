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
<?php $status_arr = array("A"=>$lang["active"],"I"=>$lang["inactive"]);?>
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="400" align="right" class="title"><input type="button" value="<?=$lang["show_all_cc"]?>" class="button" onclick="Redirect('<?=site_url('cs/faqadmin/')?>')"></td>
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
	<col width="30"><col width="300"><col><col width="300">
	<form name="fm" method="get">
	<tr class="header">
		<td height="20"><img src="<?=base_url().'/images/expand.png'?>" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
		<td><a href="#" onClick="SortCol(document.fm, 'lang_id', '<?=$xsort["lang_id"]?>')"><?=$lang["lang_id"]?></a> <?=$sortimg["lang_id"]?></td>
		<td><a href="#" onClick="SortCol(document.fm, 'faq_ver', '<?=$xsort["faq_ver"]?>')"><?=$lang["faq_ver"]?></a> <?=$sortimg["faq_ver"]?></td>
		<td></td>
	</tr>
	<tr class="search" id="tr_search" >
		<td></td>
		<td><input name="lang_id" class="input" value="<?=$this->input->get("lang_id")?>" maxlength="2"></td>
		<td><select name="faq_ver"><option value=""></option><?php
		foreach($faq_version as $key=>$value)
		{
			?><option value="<?=$key?>" <?=$key == $this->input->get("faq_ver")?"SELECTED":""?>><?=$value?></option><?php
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
			if($obj->get_lang_id() != $eid || $edit != 1)
			{
?>
	<tr class="row<?=$i++%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" onClick='Redirect("<?=base_url()."cs/faqadmin/index/1/".$obj->get_lang_id()."/?".$_SERVER["QUERY_STRING"]?>");'>
		<td><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$obj->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$obj->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$obj->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$obj->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$obj->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$obj->get_modify_by()?>'></td>
		<td><?=$obj->get_lang_id()?></td>
		<td><?=$faq_version[$obj->get_faq_ver()]?></td>
		<td></td>
	</tr>
<?php
			}
			else
			{
?>
	<form name="fm_edit" action="<?=base_url()."cs/faqadmin/?".$_SERVER["QUERY_STRING"]?>"method="post" onClick="checkForm(this)";>
	<tr class="row<?=$i++%2?>">
		<td><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$obj->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$obj->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$obj->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$obj->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$obj->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$obj->get_modify_by()?>'></td>
		<td><?=$obj->get_lang_id()?><input name="lang_id" type="hidden" value="<?=$obj->get_lang_id()?>" class="input"></td>
		<td><select name="faq_ver" class="input"><?php
		foreach($faq_version as $k=>$v)
		{
		?><option value="<?=$k?>" <?=$k == $obj->get_faq_ver()?"SELECTED":""?>><?=$v?></option><?php
		}
		?></select></td>
		<td><input name="posted" type="hidden" value="1"><input name="action" type="hidden" value="<?=$obj->get_faq_ver()?"edit":"add"?>"><input type="button" value="<?=$obj->get_faq_ver()?$lang["update"]:$lang["add"]?>" onClick="if(CheckForm(this.form)) document.fm_edit.submit();" class="button">&nbsp;&nbsp;<input type="button" value="<?=$lang["back"]?>" onClick='Redirect("<?=base_url()."cs/faqadmin/?".$_SERVER["QUERY_STRING"]?>");' class="button"></td>
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
</body>
</html>