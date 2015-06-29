<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script language="javascript">
<!--
function prepareSubmit()
{
    
}
-->
</script>
</head>
<body>
<div id="main">
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
    <td align="left" class="title" height="30"><b style="font-size: 16px; color: rgb(0, 0, 0);"><?=$lang["title"]?></b></td>
</tr>
<tr>
    <td height="2" bgcolor="#000033"></td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
<tr height="70">
    <td align="left" style="padding-left:8px;"><b style="font-size: 14px; color: rgb(0, 0, 0);"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
</tr>
<tr >
    <td align="left" style="padding-left:8px;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td width="25%" align="left" height="30"><?=$lang["by_prod_name"]?><input name="prod_name" type="text"></td>
        <td width="25%" align="left" height="30"><?=$lang["by_sku"]?><input name="sku" type="text"></td>
        <td width="50%" align="left" height="30"><input value="<?=$lang['search']?>" type="button" onClick="prepareSubmit();"></td>
    </tr>
    </table>
    </td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0">
<tr class="header">
    <td width="200"></td>
    <td width="99%"></td>
</tr>
</table>
</div>
</body>
</html>