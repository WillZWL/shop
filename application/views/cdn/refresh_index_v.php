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
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
        <tr>
            <td class="field"><?=$lang["image_path"]?></td>
            <td class="value"><input name="path" class="input" value=""></td>
            <td class="value"><?=$lang["input_alert"]?> &nbsp;&nbsp;e.g http://cdn.valuebasket.com/808AA1/vb/salecycle/test.jpg</td>
        </tr>
        <tr>
            <td colspan="3" align="right" style="padding-right:8px;" height="40" class="tb_detail">
                <input type="submit" value="<?=$lang['refresh']?>">
            </td>
        </tr>
    </table>
    <input type="hidden" name="posted" value="1">
</form>
<?=$notice["js"]?>
</div>
</body>
</html>