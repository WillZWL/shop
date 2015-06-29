<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/calendar.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/calendar.css" type="text/css" media="all"/>
<script language="javascript">
<!--
/*function prepareSubmit()
{
    var keyword = document.getElementById('prod_name').value;
    var sku = document.getElementById('psku').value;
    plistframe.list.keyword.value = keyword;
    plistframe.list.sku.value = sku;
    plistframe.list.submit();
}*/
-->
</script>
</head>
<body onResize="SetFrameFullHeight(document.getElementById('report'));">
<div id="main">
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
    <td align="left" class="title" height="30"><b style="font-size: 16px; color: rgb(0, 0, 0);"><?=$lang["title"]?></b></td>
</tr>
<tr>
    <td height="2" bgcolor="#000033"></td>
</tr>
</table>
<form name="fm" action="<?=base_url()."report/order_score_activity_log/query"?>" method="post" target="report">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
<tr height="70">
    <td align="left" style="padding-left:8px;"><b style="font-size: 14px; color: rgb(0, 0, 0);"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
    <td align="right">
    <table border="0" cellpadding="0" cellspacing="0" width="340">
    <col width="120"><col width="180"><col width="40">
    <tr>
        <td><b><?=$lang["start_date"]?></b></td>
        <td><input name="start_date" value='<?=$start_date?>' notEmpty><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.fm.start_date, false, false, false, '2010-01-01')" align="absmiddle"></td>
        <td rowspan="4" align="center"><input type="submit" value="" class="search_button" style="background: url('<?=base_url()?>/images/find.gif') #CCCCCC no-repeat center; width: 30px; height: 25px;"> &nbsp; </td>
    </tr>
    <tr>
        <td><b><?=$lang["end_date"]?></b></td>
        <td><input name="end_date" value='<?=$end_date?>' notEmpty><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.fm.end_date, false, false, false, '2010-01-01')" align="absmiddle"><input type="hidden" name="is_query" value="1"><input type="hidden" name="display_type" value=""></td>
    </tr>
    <tr>
        <td><b><?=$lang["order_score"]?></b></td>
        <td><input name="order_score" class="input" value=''></td>
    </tr>
    <tr>
        <td><b><?=$lang["order_status"]?></b></td>
        <td><select name="order_status" class="input"><option value=""><?=$lang["please_select"]?></option>
        <?php
            foreach($lang["status_name"] as $key=>$value)
            {
        ?>
                <option value="<?=$key?>"><?=$value?></option>
        <?php
            }
        ?>
                </select><input type="hidden" name="is_query" value="1"></td>
    </tr>
    </table>
    </td>
</tr>
<tr>
    <td height="2" bgcolor="#000033" colspan="3"></td>
</tr>
</table>
</form>
<iframe name="report" id="report" src="<?=base_url()?>report/order_score_activity_log/query" width="1259" style="float:left;border-right:1px solid #000000;" noresize frameborder="0" marginwidth="0" marginheight="0" hspace=0 vspace=0 onLoad="SetFrameFullHeight(this)"></iframe>
</div>
</body>
</html>