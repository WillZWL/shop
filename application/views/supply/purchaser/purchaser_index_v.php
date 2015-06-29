<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/lytebox.css" type="text/css" media="screen" />
</head>
<body onResize="SetFrameFullHeight(document.getElementById('plist'));SetFrameFullHeight(document.getElementById('pview'));">
<div id="main">
<?$ar_status = array("0" => $lang["inactive"], "1" => $lang["active"]);?>
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["title"]?></td>
        <td width="400" align="right" class="title"></td>
    </tr>
    <tr>
        <td height="2" class="line"></td>
        <td height="2" class="line"></td>
    </tr>
</table>
<form name="fm" action="<?=base_url()?>supply/purchaser" method="get">
<table class="page_header" border="0" cellpadding="0" cellspacing="0" height="70" width="100%">
    <tr>
        <td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
        <td>
            <table align="right">
                <col width="80"><col width="160"><col width="40"><col width="160">
                <tr>
                    <td></td>
                    <td></td>
                    <td><b><?=$lang["sku"]?></b></td>
                    <td><input name="sku" class="input" value='<?=htmlspecialchars($this->input->get("sku"))?>'></td>
                    <td rowspan="2" align="center"><input type="submit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') #CCCCCC no-repeat center; width: 30px; height: 25px;"> &nbsp; </td>
                </tr>
                <tr>
                    <td><b><?=$lang["master_sku"]?></b></td>
                    <td><input name="master_sku" class="input" value='<?=htmlspecialchars($this->input->get("master_sku"))?>'></td>
                    <td><b><?=$lang["name"]?></b></td>
                    <td><input name="name" class="input" value='<?=htmlspecialchars($this->input->get("name"))?>'></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td height="2" class="line"></td>
        <td height="2" class="line"></td>
    </tr>
</table>
</form>
<iframe name="plist" id="plist" src="<?=base_url()?>supply/purchaser/index/left/?<?=$_SERVER['QUERY_STRING']?>" width="250" style="float:left;border-right:1px solid #000000;" noresize frameborder="0" marginwidth="0" marginheight="0" hspace=0 vspace=0 onLoad="SetFrameFullHeight(this)"></iframe>
<iframe name="pview" id="pview" src="" width="1009" style="float:left;border-left:1px solid #999999;" noresize frameborder="0" marginwidth="0" marginheight="0" hspace=0 vspace=0 onLoad="SetFrameFullHeight(this)"></iframe>
<?=$notice["js"]?>
</div>
<script>
    SetFrameFullHeight(document.getElementById('plist'));
    SetFrameFullHeight(document.getElementById('pview'));
    hash = document.location.hash.substr(1);
    top.frames["pview"].location.href='<?=base_url()?>supply/purchaser/view/' + hash;
</script>
</body>
</html>