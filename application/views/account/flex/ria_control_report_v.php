<html>
<head>
<title><?=$lang["title"]?></title>
<style type="text/css">
.button3{width: 170px;}
</style>
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
<form name="fm" action='<?=base_url()."account/flex/riaControlReport";?>' method="post">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
<tr height="80">
    <td align="right">
        <table border="0" cellpadding="0" cellspacing="0" style="line-height:8px;">
            <col><col width="5%"><col width="25%"><col width="10%"><col width="5%"><col width="25%"><col width="10px">
            <tr>
                <td></td>
                <td><b>From</b></td>
                <td><input id="oc_start_date" name="start_date" value='<?=htmlspecialchars($start_date)?>' class="pointer" onclick="showcalendar(event, document.getElementById('oc_start_date'), false, false, false, '2010-01-01')"><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.getElementById('oc_start_date'), false, false, false, '2010-01-01')" align="absmiddle"></td>
                <td></td>
                <td><b>To</b></td>
                <td><input id="oc_end_date" name="end_date" value='<?=htmlspecialchars($end_date)?>' class="pointer" onclick="showcalendar(event, document.getElementById('oc_end_date'), false, false, false, '2010-01-01')"><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.getElementById('oc_end_date'), false, false, false, '2010-01-01')" align="absmiddle"></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="10" align="right" style="padding-top:5px; padding-right:8px;">
                <input type="submit" value="Generate Report" class="button3">
            </td>
            </tr>
            <input type="hidden" name="is_query" value="1">
        </table>
    </td>
</tr>
</form>
</table>
<?=$notice["js"]?>
</div>
<?php print $content; ?>
</body>
</html>