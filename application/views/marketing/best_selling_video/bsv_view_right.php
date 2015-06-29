<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all">
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script language="javascript"></script>
<script language="javascript">
<!--
count = 1;

function addRow(sku,name,ref,lang)
{
    if(count > <?=$limit?>)
    {
        alert('<?=$lang["only_up_to_".$limit]?>');
    }

    for(var i = 1; i <= <?=$limit?> ; i++)
    {
        c = "cat"+i;

        if(document.getElementById(c).value == ref)
        {
            alert('<?=$lang["duplicates_not_allowed"]?>');
            return false;
        }
    }

    var o = "cat" + count;
    var r = "rid" + count;
    var n = "sku" + count;
    var l = "language" + count;
    document.getElementById(o).value = ref;
    document.getElementById(r).innerHTML = "<br>"+sku+" - "+name+"<br>"+"<object width='300' height='225><param name='movie' value='http://www.youtube.com/v/"+ref+"&amp;hl=en_US&amp;fs=1'><param><param name='allowFullScreen' value='true'></param><param name='allowscriptaccess' value='always'></param><embed src='http://www.youtube.com/v/"+ref+"&amp;hl=en_US&amp;fs=1' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='300' height='225'></embed></object>";
    document.getElementById(n).value = sku;
    document.getElementById(l).value = lang;
    count++;
}


function resetForm()
{
    for(var i = 1; i <=<?=$limit?>; i++)
    {
        o = "nid"+i;
        document.getElementById(o).innerHTML = "";
    }
}

function SaveChange(el)
{
    el.form.submit();
}
-->
</script>
</head>
<body topmargin="0" leftmargin="0" class="frame_left" style="width:auto;">
<div id="main" style="width:auto;">
<?=$notice["img"]?>

<form name="fm" method="post" action="<?=$_SERVER["PHP_SELF"]."/".$catid?>">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <td height="32" style="padding-left:20px; font-weight:bold; font-size:12px;"><?=$lang["current_list"]?></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1" width="100%" class="tb_list">
<col width="15%"><col width="35%"><col width="15%"><col width="35%">
<tr class="header">
    <td width="50%" align="center" colspan="2" ><b><?=$lang["current_auto_listing"]?></b></td>
    <td width="50%" align="center" colspan="2" ><b><?=$lang["current_overall_listing"]?></b></td>
</tr>
<?php
    for($i = 1; $i <= $limit; $i++)
    {
?>
<tr height="20">
    <td class="field" align="right" style="padding-right:5px;"><?=$lang["aoption"]." ".$i?></td>
    <td class="value" align="left">&nbsp;&nbsp;<?=$asku[$i].($asku[$i]==""?"":" - ").$aname[$i]?></br>
    <?php
        if($avalue[$i] != $lang["not_assigned"])
        {
    ?>
        <object width="300" height="225"><param name="movie" value="http://www.youtube.com/v/<?=$avalue[$i]?>&amp;hl=en_US&amp;fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/<?=$avalue[$i]?>&amp;hl=en_US&amp;fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="300" height="225"></embed></object>
    <?php
        }
    ?>
    </td>
    <td class="field" align="right" style="padding-right:5px;"><?=$lang["over_option"]." ".$i?></td>
    <td class="value" align="left">&nbsp;&nbsp;<?=$osku[$i].($osku[$i]==""?"":" - ").$oname[$i]?></br>
    <?php
        if($ovalue[$i] != $lang["not_assigned"])
        {
    ?>
        <object width="300" height="225"><param name="movie" value="http://www.youtube.com/v/<?=$ovalue[$i]?>&amp;hl=en_US&amp;fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/<?=$ovalue[$i]?>&amp;hl=en_US&amp;fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="300" height="225"></embed></object>
    <?php
        }
    ?>
    </td>
</tr>
<?php
    }
?>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <td height="32" style="padding-left:20px; font-weight:bold; font-size:12px;"><?=$lang["current_manaul_setting"]?></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1" width="100%" bgcolor="#cccccc">
<col width="15%"><col width="35%"><col width="15%"><col width="35%">
<?php
    for($i = 1; $i <= $limit; $i++)
    {
?>
<tr>
    <td class="field" align="right" style="padding-right:5px;"><?=$lang["ooption"]." ".$i?></td>
    <td class="value" align="left">&nbsp;&nbsp;<?=$sku[$i].($sku[$i]==""?"":" - ").$name[$i]?></br>
    <?php
        if($value[$i] != $lang["not_assigned"])
        {
    ?>
        <object width="300" height="225"><param name="movie" value="http://www.youtube.com/v/<?=$value[$i]?>&amp;hl=en_US&amp;fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/<?=$value[$i]?>&amp;hl=en_US&amp;fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="300" height="225"></embed></object>
    <?php
        }
    ?>
    </td>
    <td class="field" align="right" style="padding-right:5px;"><?=$lang["noption"]." ".$i?></td>
    <td class="value" align="left">&nbsp;&nbsp;<input name="cat[<?=$i?>]" type="text" value="" id="cat<?=$i?>" READONLY>&nbsp;&nbsp;<span id="rid<?=$i?>"></span>
        <input name="sku[<?=$i?>]" type="hidden" value="" id="sku<?=$i?>" >
        <input name="language[<?=$i?>]" type="hidden" value="" id="language<?=$i?>" >
    </td>
</tr>
<?php
    }
?>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr class="header">
    <td width="65%" height="40" style="padding-left:20px;"><input type="button" value="<?=$lang["back"]?>" onClick="parent.document.location.href='<?=$_SESSION["LISTPAGE"]?>';" class="button"></td>
    <td align="left">&nbsp;&nbsp;<input type="button" value="<?=$lang["submit"]?>"onclick="this.form.submit();" class="button">&nbsp;&nbsp;<input type="reset" value="<?=$lang["reset"]?>" class="button" onClick="count = 1; resetForm();"></td>
</tr>
</table>
<input type="hidden" name="action" value="<?=$action?>">
<input type="hidden" name="posted" value="1">
</form>
</div>
<?=$notice["js"]?>
</body>
</html>