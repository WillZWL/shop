<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
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
            <td align="left" class="title" height="30"><b
                    style="font-size: 16px; color: rgb(0, 0, 0);"><?= $lang["title"] ?></b></td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033"></td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
        <tr height="70">
            <td align="left" style="padding-left:8px;">
                    <input type="submit" value="<?= $lang["all_order"] ?>" onclick="Redirect('<?=site_url('report/freightManagementReport/index/all')?>')" class="button3">
            </td>
        </tr>
        <tr height="30">
            <td align="left" style="padding-left:8px;">
                    <input type="submit" value="<?= $lang["rec_ourier_order"] ?>" onclick="Redirect('<?=site_url('report/freightManagementReport/index/rec')?>')" class="button3">
            </td>
        </tr>
        <tr height="70">
            <td align="left" style="padding-left:8px;">
                    <input type="submit" value="<?= $lang["no_rec_ourier_order"] ?>" onclick="Redirect('<?=site_url('report/freightManagementReport/index/no_rec')?>')" class="button3">
            </td>
        </tr>
    </table>
</div>
</body>
</html>