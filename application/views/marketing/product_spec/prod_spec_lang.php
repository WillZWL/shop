<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>js/ext-js/resources/css/ext-all.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>js/ext-js/resources/css/tab-scroller-menu.css" />
<script type="text/javascript" src="<?=base_url()?>js/ext-js/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/ext-js/ext-all.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/ext-js/TabScrollerMenu.js"></script>
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="400" align="right" class="title">
			<input type="button" value="<?=$lang["content_management"]?>" style="padding:0px 4px 0px 4px;" onclick="Redirect('<?=site_url('marketing/product_spec/')?>')">
		</td>
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
<form name="fm_edit" method="post" onSubmit="return CheckForm(this)">
<?php if ($lang_list)
{
?>
<div id="div_tabs">
	<?php
		foreach ($lang_list as $lang_obj)
		{
			$cur_lang_id = $lang_obj->get_id();
			$cur_name = $cat_ext[$cat_id][$cur_lang_id]?$cat_ext[$cat_id][$cur_lang_id]->get_name():"";
			$cur_title = (isset($func_opt_list[$cur_lang_id]))?$lang_obj->get_name():"<font color='red'>{$lang_obj->get_name()}</font>";
	?>
	<div id="div_tab_<?=$cur_lang_id?>" class="x-tab" title="<?=$cur_title?>">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
			<col width="300"><col>
			<?php
				if ($psg_list)
				{
					foreach($psg_list as $psg_obj)
					{
						$psg_func_id = $psg_obj->get_func_id();
						$psg_id = $psg_obj->get_id();
						?>
						<tr class="header">
							<td><?=$psg_obj->get_name()?></td>
							<td><input name="func_opt[<?=$cur_lang_id?>][<?=$psg_func_id?>][<?=($exists=isset($func_opt_list[$cur_lang_id][$psg_func_id]))?$func_opt_list[$cur_lang_id][$psg_func_id]->get_id():"new"?>]" class="input" value="<?=$exists?htmlspecialchars($func_opt_list[$cur_lang_id][$psg_func_id]->get_text()):""?>"></td>
						</tr>
						<?php
						if ($prod_spec_list[$psg_id])
						{
							foreach($prod_spec_list[$psg_id] AS $ps_obj)
							{
								$ps_func_id = $ps_obj->get_id();
			?>
			<tr>
				<td class="field"><?=$func_opt_list["en"][$ps_func_id]->get_text()?></td>
				<td class="value"><input name="func_opt[<?=$cur_lang_id?>][<?=$ps_func_id?>][<?=($exists=isset($func_opt_list[$cur_lang_id][$ps_func_id]))?$func_opt_list[$cur_lang_id][$ps_func_id]->get_id():"new"?>]" class="input" value="<?=$exists?htmlspecialchars($func_opt_list[$cur_lang_id][$ps_func_id]->get_text()):""?>"></td>
			</tr>
			<?php
							}
						}
					}
				}
			?>
		</table>
	</div>
	<?php
		}
	?>
</div>
<?php
	}
?>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
		<tr>
			<td align="right" style="padding-right:8px;" height="40" class="tb_detail">
				<input type="submit" value="<?=$lang['update']?>">
			</td>
		</tr>
	</table>
	<?=_form_ru()?>
	<input type="hidden" name="posted" value="1">
</form>
<script>
var scrollerMenu = new Ext.ux.TabScrollerMenu({
	maxText  : 64,
	pageSize : 1
});

var name_tabs = new Ext.TabPanel({
	applyTo: 'div_tabs',
	autoTabs:true,
	activeTab:0,
	deferredRender:false,
	border:true,
	enableTabScroll:true,
	autoHeight: true,
	defaults: {autoScroll:true},
	plugins         : [ scrollerMenu ]
	});
</script>
<?=$notice["js"]?>
</div>
</body>
</html>