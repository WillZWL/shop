<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/calendar.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/calendar.css" type="text/css" media="all"/>
</head>
<body onResize="SetFrameFullHeight(document.getElementById('report'));">
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
<form name="fm" method="post" onSubmit="return CheckForm(this)">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
<tr height="70">
    <td align="left" style="padding-left:8px;">
        <b style="font-size: 14px; color: rgb(0, 0, 0);"><?=$lang["header"]?></b><br><?=$lang["header_message"]?>
        <?php
            if ($posted)
            {
        ?>
        <?=$lang["total_payment_attempts"]?>: <?=$total_attempt?> &nbsp; &nbsp; <?=$lang["mb_failure_response"]?>: <?=$total_fail?> &nbsp; &nbsp; <?=$lang["mb_successful_response"]?>: <?=$total_success?>
        <?php
            }
        ?>
    </td>
    <td align="right">
    <table border="0" cellpadding="0" cellspacing="0" width="340" style="line-height:8px;">
    <col width="120"><col width="200"><col width="150">
    <tr>
        <td><b><?=$lang["start_date"]?></b></td>
        <td><input name="start_date" value='<?=htmlspecialchars($start_date)?>' notEmpty><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.fm.start_date, false, false, false, '2010-01-01')" align="absmiddle"></td>
        <td align="center" rowspan="2"><input type="button" value="<?=$lang["display_on_screen"]?>" class="button" onClick="this.form.display_type.value='view';if (CheckForm(this.form)){this.form.submit();}"><br><br><input type="button" value="<?=$lang["download_as_csv"]?>" class="button" onClick="this.form.display_type.value='csv';if (CheckForm(this.form)){this.form.submit();}"></td>
    </tr>
    <tr>
        <td><b><?=$lang["end_date"]?></b></td>
        <td><input name="end_date" value='<?=htmlspecialchars($end_date)?>' notEmpty><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.fm.end_date, false, false, false, '2010-01-01')" align="absmiddle"><input type="hidden" name="is_query" value="1"><input type="hidden" name="display_type" value=""></td>
    </tr>
    </table>
    </td>
</tr>
<tr>
    <td height="2" bgcolor="#000033" colspan="3"></td>
</tr>
</table>
</form>
<?php
    $url = "";
    if ($posted)
    {
        $url = base_url()."report/failed_transaction/".($display_type=="csv"?"query":"view")."/{$start_date}/{$end_date}";
    }
?>
<iframe name="report" id="report" src="<?=$url?>" width="1259" noresize frameborder="0" marginwidth="0" marginheight="0" hspace=0 vspace=0 onLoad="SetFrameFullHeight(this)"></iframe>

<?=$notice["js"]?>
</div>
</body>
</html>