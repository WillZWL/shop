<html>
<head>
    <meta http-equiv="Content-Language" content="en-gb">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="keywords" content="">
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="imagetoolbar" content="no">
    <link rel="stylesheet" type="text/css" href="<?= base_url() . "css/style.css" ?>">
    <script language="javascript" src="<?= base_url() ?>js/common.js"></script>
    <link rel="stylesheet" href="/css/lytebox.css" type="text/css" media="screen"/>
    <script language="javascript">
        <!--
        function changeCellImage(a, b, c) {
            if (c != "") {
                document.getElementById(a).innerHTML = '<img src="' + b + c + '" border=0>';
            }
            else {
                document.getElementById(a).innerHTML = "";
            }
        }
        -->
    </script>
</head>

<body topmargin="0" leftmargin="0"
      onResize="SetFrameFullHeight(document.getElementById('plist'));SetFrameFullHeight(document.getElementById('pview'));">
<div id="main">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><b style="font-size:16px;color:#000000"><?= $lang["subtitle"] ?></b></td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["title"] ?></b><br><?= $lang["subheader"] ?></td>
        </tr>
    </table>
    <iframe name="clist" id="plist" src="<?= base_url() ?>/marketing/display_banner/get_list/" width="200" height="600"
            style="float:left;border-right:1px solid #000000;" noresize frameborder="0" marginwidth="0" marginheight="0"
            hspace=0 vspace=0 onLoad="SetFrameFullHeight('plist')"></iframe>
    <iframe name="cview" id="pview" src="" width="1059" height="600" style="float:left;border-left:1px solid #999999;"
            noresize frameborder="0" marginwidth="0" marginheight="0" hspace=0 vspace=0
            onLoad="SetFrameFullHeight('pview')"></iframe>
</div>
</body>
</html>