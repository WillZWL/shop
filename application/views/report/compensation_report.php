<html>
<head>
<title><?=$lang["title"]?></title>
<STYLE type="text/css">
.button3{
WIDTH: 170px;}
</STYLE>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/calendar.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/calendar.css" type="text/css" media="all"/>
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
    <td align="left" class="title" height="30"><b style="font-size: 16px; color: rgb(0, 0, 0);"><?=$lang["title"]?></b></td>
</tr>
<tr>
    <td height="2" bgcolor="#000033"></td>
</tr>
</table>
<form name="fm" action='<?=base_url()."report/compensation_report/export_csv";?>' method="post" onSubmit="return verfiy_date(this)">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
<tr height="80">
    <td align="left" style="padding-left:8px;">
        <b style="font-size: 14px; color: rgb(0, 0, 0);"><?=$_title?></b><br>
    </td>
    <td align="right">
    <table border="0" cellpadding="0" cellspacing="0" style="line-height:8px;">
    <col><col width="5%"><col width="25%"><col width="10%"><col width="5%"><col width="25%"><col width="10px">
    <tr>
        <td></td>
        <td><b>From</b></td>
        <td><input id="oc_start_date" name="start_date[order_create]" value='<?=htmlspecialchars($start_date)?>'><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.getElementById('oc_start_date'), false, false, false, '2010-01-01')" align="absmiddle"></td>
        <td></td>
        <td><b>To</b></td>
        <td><input id="oc_end_date" name="end_date[order_create]" value='<?=htmlspecialchars($end_date)?>'><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.getElementById('oc_end_date'), false, false, false, '2010-01-01')" align="absmiddle"></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="10" align="right" style="padding-top:5px; padding-right:8px;">
        <input type="submit" value="Export CSV" class="button3">
    </td>
    </tr>
    <input type="hidden" name="is_query" value="1">
    </table>
    </td>
</tr>
</form>

<form name="fm_2" action='<?=base_url()."report/compensation_report/index";?>' method="post" onSubmit="return getDateInfo(this)">
<tr>
    <td style='padding:0;padding-right:8px;' colspan='7' align='right' >
        <input type="submit" value="Show Report" class="button3">
        <input type="hidden" value="1"  name="display_report">
        <input type='hidden' value='' name='compensation_approve_start_date'>
        <input type='hidden' value='' name='compensation_approve_end_date'>
    </td>
</tr>
</form>
</table>


<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list" style="text-align:center">
    <col width="20"><col width="70"><col width="70"><col width="70"><col width="180"><col width="180"><col width="120"><col width="70"><col width="70"><col width="70"><col width="10">
<form name="fm_3" method="get" onSubmit="return CheckForm(this)">
    <tr class="header">
        <td height="20"></td>
        <td><a href="#" onClick="SortCol(document.fm_3, 'so.so_no', '<?=$xsort["so.so_no"]?>')"><?=$lang["order_number"]?><?=$sortimg["so.so_no"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm_3, 'so.platform_id', '<?=$xsort["so.platform_id"]?>')"><?=$lang["platform_id"]?> <?=$sortimg["so.platform_id"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm_3, 'soc.item_sku', '<?=$xsort["soc.item_sku"]?>')"><?=$lang["sku"]?><?=$sortimg["soc.item_sku"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm_3, 'p.name', '<?=$xsort["p.name"]?>')"><?=$lang["product_name"]?><?=$sortimg["p.name"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm_3, 'soch.note', '<?=$xsort["soch.note"]?>')"><?=$lang["reason"]?><?=$sortimg["soch.note"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm_3, 'soc.create_on', '<?=$xsort["soc.create_on"]?>')"><?=$lang["request_date"]?><?=$sortimg["soc.create_on"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm_3, 'soc.create_by', '<?=$xsort["soc.create_by"]?>')"><?=$lang["request_by"]?><?=$sortimg["soc.create_by"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm_3, 'soc.modify_on', '<?=$xsort["soc.modify_on"]?>')"><?=$lang["approve_date"]?><?=$sortimg["soc.modify_on"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm_3, 'soc.modify_by', '<?=$xsort["soc.modify_by"]?>')"><?=$lang["approve_by"]?><?=$sortimg["soc.modify_by"]?></a></td>
        <td></td>
    </tr>
    <tr class="search" id="tr_search" <?=$searchdisplay?>>
        <td></td>
        <td><input name="so_no" type="text" class="input" value="<?=$this->input->get("so_no")?>"></td>
        <td><input name="platform_id" type="text" class="input" value="<?=$this->input->get("platform_id")?>"></td>
        <td><input name="sku" type="text" class="input" value="<?=$this->input->get("sku")?>"></td>
        <td><input name="product_name" type="text" class="input" value="<?=$this->input->get("product_name")?>"></td>
        <td><input name="reason" type="text" class="input" value="<?=$this->input->get("reason")?>"></td>
        <td><input name="request_date" type="text" class="input" value="<?=$this->input->get("request_date")?>"></td>
        <td><input name="request_by" type="text" class="input" value="<?=$this->input->get("request_by")?>"></td>
        <td><input name="approved_date" type="text" class="input" value="<?=$this->input->get("approved_date")?>"></td>
        <td><input name="approved_by" type="text" class="input" value="<?=$this->input->get("approved_by")?>"></td>
        <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
    </tr>
<input type="hidden" name="search" value=1>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<?php
    $i=0;
    if (!empty($list))
    {
        foreach ($list as $obj)
        {
?>
    <tr class="row<?=$i%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" >
        <td height="20"></td>
        <td><?=$obj->get_so_no()?></td>
        <td><?=$obj->get_platform_id()?></td>
        <td><?=$obj->get_item_sku()?></td>
        <td><?=$obj->get_prod_name()?></td>
        <td><?=$obj->get_reason()?></td>
        <td><?=$obj->get_request_date()?></td>
        <td><?=$obj->get_request_by()?></td>
        <td><?=$obj->get_approval_date()?></td>
        <td><?=$obj->get_approved_by()?></td>
        <td>&nbsp;</td>
<?php
            $i++;
        }
    }
?>
    <tr class="header">
        <td></td>
        <td colspan="10"></td>
    </tr>
</table>

<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
<?php print $content; ?>

<script>
    function getDateInfo(fm_2)
    {
        //alert(fm_2.name);
        var start_date = document.getElementById("oc_start_date").value.trim();
        var end_date = document.getElementById("oc_end_date").value.trim();
        fm_2.compensation_approve_start_date.value= start_date;
        fm_2.compensation_approve_end_date.value= end_date;

        if(!start_date.match(/^\d{4}-\d{2}-\d{2}$/) || !end_date.match(/^\d{4}-\d{2}-\d{2}$/))
        {
            alert("please input the correct date");
            return false;
        }
    }

    function verfiy_date()
    {
        var start_date = document.getElementById("oc_start_date").value.trim();
        var end_date = document.getElementById("oc_end_date").value.trim();

        if(!start_date.match(/^\d{4}-\d{2}-\d{2}$/) || !end_date.match(/^\d{4}-\d{2}-\d{2}$/))
        {
            alert("please input the correct date");
            return false;
        }
    }

</script>
</body>
</html>