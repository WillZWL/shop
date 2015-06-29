<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>js/ext-js/resources/css/ext-all.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>js/ext-js/resources/css/LockingGridView.css" />
<script type="text/javascript" src="<?=base_url()?>js/ext-js/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/ext-js/ext-all.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/ext-js/ux-all.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/ext-js/TableGridConfig.js"></script>
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["title"]?></td>
        <td width="400" align="right" class="title">
            <input type="button" value="<?=$lang["content_management"]?>" style="padding:0px 4px 0px 4px;" onclick="Redirect('<?=site_url('mastercfg/delivery')?>')">
             &nbsp; <input type="button" value="<?=$lang["region_management"]?>" style="padding:0px 4px 0px 4px;" onclick="Redirect('<?=site_url('mastercfg/delivery/region')?>')">
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
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list" id="tb_data">
        <thead>
        <tr class="header">
            <th height="22" locked><?=$lang["delivery"]?></th>
            <?php
                if ($delivery_list[$default_delivery] && $country_list)
                {
                    $display_country_list = array_keys($delivery_list[$default_delivery]);
                    $i==0;
                    foreach ($display_country_list as $country_id)
                    {
                        $cur_locked = $i==0?" locked":"";
            ?>
            <th width="90"<?=$cur_locked?> title="<?=$country_list[$country_id]?>"><?=$country_list[$country_id]?></th>
            <?php
                    $i++;
                    }
                }
            ?>
        </tr>
        </thead>
        <?php
            if ($delivery_type_list)
            {
                $i=0;
                foreach ($delivery_type_list as $dt_obj)
                {
        ?>
        <tbody>
        <tr class="row<?=$i%2?>" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')">
            <td nowrap style="white-space:nowrap;"><?=$dt_id = $dt_obj->get_id()?> - <?=$dt_obj->get_name()?></td>
        <?php
            foreach ($display_country_list as $country_id)
            {
                $checkbox = $cur_max = $cur_min = $checked = "";
                if (isset($delivery_list[$dt_id][$country_id]))
                {
                    $del_obj = $delivery_list[$dt_id][$country_id];
                    if ($cur_status = $del_obj->get_status())
                    {
                        $checked = " CHECKED";
                    }
                    $cur_min = $del_obj->get_min_day();
                    $cur_max = $del_obj->get_max_day();
                }
                if ($dt_id == $default_delivery)
                {
                    $checkbox = "<input type='hidden' name='del[{$dt_id}][{$country_id}][status][$cur_status]' value='1'>";
                }
                else
                {
                    $checkbox = "<label><input name='del[{$dt_id}][{$country_id}][status][$cur_status]' type='checkbox' value='1'{$checked}>{$lang['active']}</label><br>";
                }
        ?>
             <td nowrap style="white-space:nowrap;" align="center"><?=$checkbox?><input name="del[<?=$dt_id?>][<?=$country_id?>][min][<?=is_null($cur_min)?'null':$cur_min?>]" class="s_int_input" size="3" value="<?=$cur_min?>" min=1 max=255 maxlength=3 isNatural> - <input name="del[<?=$dt_id?>][<?=$country_id?>][max][<?=is_null($cur_max)?'null':$cur_max?>]" class="s_int_input" size="3" value="<?=$cur_max?>" min=1 max=255 maxlength=3 isNatural><br><?=$lang["working_days"]?></td>
        <?php
            }
        ?>
        </tr>
        <?php
                    $i++;
                }
            }
        ?>
        </tbody>
    </table>
    <div id="div_list" style="text-align:left"></div>
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
<?=$notice["js"]?>
<script>
function ChkActive(ele, delivery_type, reg)
{
    fm = document.fm_edit;
    min_ele = getEle(fm, 'input', 'name', 'del\\[' + delivery_type + '\\]\\[' + reg + '\\]\\[min\\]')[0];
    max_ele = getEle(fm, 'input', 'name', 'del\\[' + delivery_type + '\\]\\[' + reg + '\\]\\[max\\]')[0];
    if (ele.checked)
    {
        min_ele.disabled = false;
        max_ele.disabled = false;
    }
    else
    {
        min_ele.disabled = true;
        max_ele.disabled = true;
    }
}

Ext.onReady(function(){
var grid_config = new Ext.ux.grid.TableGridConfig("tb_data");

var grid = new Ext.grid.GridPanel({
    'ds': grid_config.ds,
    colModel: new Ext.ux.grid.LockingColumnModel(grid_config.cols),
    stripeRows: true,
    height: grid_config.height,
    width: '100%',
    view: new Ext.ux.grid.LockingGridView()
});
grid.render("div_list");
});
</script>
</div>
</body>
</html>