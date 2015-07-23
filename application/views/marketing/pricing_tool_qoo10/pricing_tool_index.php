<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script language="javascript">
        <!--
        function prepareSubmit() {
            var keyword = document.getElementById('prod_name').value;
            var sku = document.getElementById('psku').value;
            var plistframe = document.getElementById('plist').contentDocument;
            if (!plistframe) {
                plistframe = document.frames('plist');
            }
            plistframe.list.keyword.value = keyword;
            plistframe.list.sku.value = sku;
            plistframe.list.submit();
        }
        -->
    </script>
</head>
<body
    onResize="SetFrameFullHeight(document.getElementById('plist'));SetFrameFullHeight(document.getElementById('pview'));">
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
    <form name="fm" action="<?= base_url() . $this->tool_path ?>" method="get">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
            <tr height="70">
                <td align="left" style="padding-left:8px;"><b
                        style="font-size: 14px; color: rgb(0, 0, 0);"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?>
                </td>
                <td align="right">
                    <table border="0" cellpadding="0" cellspacing="0" style="text-align:right">
                        <col width="140">
                        <col width="160">
                        <col width="140">
                        <col width="160">
                        <col width="40">
                        <tr>
                            <td><b><?= $lang["by_master_sku"] ?></b></td>
                            <td><input name="master_sku" class="input"
                                       value='<?= htmlspecialchars($this->input->get("master_sku")) ?>'></td>
                            <td><b><?= $lang["by_sku"] ?></b></td>
                            <td><input name="sku" class="input"
                                       value='<?= htmlspecialchars($this->input->get("sku")) ?>'></td>
                            <td rowspan="2" align="center"><input type="submit" value="" class="search_button"
                                                                  style="background: url('<?= base_url() ?>/images/find.gif') #CCCCCC no-repeat center; width: 30px; height: 25px;">
                                &nbsp; </td>
                        </tr>
                        </tr>
                        <td></td>
                        <td></td>
                        <td><b><?= $lang["by_prod_name"] ?></b></td>
                        <td><input name="name" class="input" value='<?= htmlspecialchars($this->input->get("name")) ?>'>
                        </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="2" bgcolor="#000033" colspan="3"></td>
            </tr>
        </table>
    </form>
    <iframe name="plist" id="plist" src="<?= base_url() . $this->tool_path ?>/plist/?<?= $_SERVER['QUERY_STRING'] ?>"
            width="200" style="float:left;border-right:1px solid #000000;" noresize frameborder="0" marginwidth="0"
            marginheight="0" hspace=0 vspace=0 onLoad="SetFrameFullHeight(this)"></iframe>
    <iframe name="pview" id="pview" src="" width="1059" style="float:left;border-left:1px solid #999999;" noresize
            frameborder="0" marginwidth="0" marginheight="0" hspace=0 vspace=0
            onLoad="SetFrameFullHeight(this)"></iframe>
</div>
<script>
    SetFrameFullHeight(document.getElementById('plist'));
    SetFrameFullHeight(document.getElementById('pview'));
    hash = document.location.hash.substr(1);
    top.frames["pview"].location.href = '<?=base_url().$this->tool_path?>/view/' + hash;
</script>
</body>
</html>