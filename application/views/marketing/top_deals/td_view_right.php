<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all">
    <script language="javascript"></script>
    <script language="javascript">
        <!--
        count = 1;

        function addRow(sku, name) {
            if (count > <?=$limit?>) {
                alert('<?=$lang["only_up_to_".$limit]?>');
            }

            for (var i = 1; i <= <?=$limit?>; i++) {
                c = "cat" + i;

                if (document.getElementById(c).value == sku) {
                    alert('<?=$lang["duplicates_not_allowed"]?>');
                    return false;
                }
            }

            var o = "cat" + count;
            var n = "nid" + count;
            document.getElementById(o).value = sku;
            document.getElementById(n).innerHTML = name;
            count++;
        }


        function resetForm() {
            for (var i = 1; i <=<?=$limit?>; i++) {
                o = "nid" + i;
                document.getElementById(o).innerHTML = "";
            }
        }
        -->
    </script>
</head>
<body topmargin="0" leftmargin="0" class="frame_left" style="width:auto;">
<div id="main" style="width:auto;">
    <?= $notice["img"] ?>
    <form name="fm" method="post" action="<?= $_SERVER["PHP_SELF"] . "/" . $catid ?>">
        </table>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="32"
                    style="padding-left:20px; font-weight:bold; font-size:12px;"><?= $lang["current_list"] ?></td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="1" width="100%" class="td_list">
            <col width="15%">
            <col width="35%">
            <col width="15%">
            <col width="35%">
            <tr class="page_header">
                <td width="50%" align="center" colspan="2"><b><?= $lang["current_auto_listing"] ?></b></td>
                <td width="50%" align="center" colspan="2"><b><?= $lang["current_overall_listing"] ?></b></td>
            </tr>
            <?php
            for ($i = 1; $i <= $limit; $i++) {
                ?>
                <tr height="20">
                    <td class="field" align="right" style="padding-right:5px;"><?= $lang["aoption"] . " " . $i ?></td>
                    <td class="value" align="left">
                        &nbsp;&nbsp;<?= $avalue[$i] . ($aname[$i] == "" ? "" : " - " . $aname[$i]) ?></td>
                    <td class="field" align="right"
                        style="padding-right:5px;"><?= $lang["over_option"] . " " . $i ?></td>
                    <td class="value" align="left">
                        &nbsp;&nbsp;<?= $ovalue[$i] . ($oname[$i] == "" ? "" : " - " . $oname[$i]) ?></td>
                </tr>
            <?php
            }
            ?>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="32"
                    style="padding-left:20px; font-weight:bold; font-size:12px;"><?= $lang["current_manaul_setting"] ?></td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="1" width="100%" bgcolor="#cccccc">
            <col width="15%">
            <col width="35%">
            <col width="15%">
            <col width="35%">
            <?php
            for ($i = 1; $i <= $limit; $i++) {
                ?>
                <tr>
                    <td class="field" align="right" style="padding-right:5px;"><?= $lang["ooption"] . " " . $i ?></td>
                    <td class="value" align="left">
                        &nbsp;&nbsp;<?= $value[$i] . ($name[$i] == "" ? "" : " - " . $name[$i]) ?></td>
                    <td class="field" align="right" style="padding-right:5px;"><?= $lang["noption"] . " " . $i ?></td>
                    <td class="value" align="left">&nbsp;&nbsp;<input name="cat[<?= $i ?>]" type="text" value=""
                                                                      id="cat<?= $i ?>" READONLY>&nbsp;&nbsp;<span
                            id="nid<?= $i ?>"></span></td>
                </tr>
            <?php
            }
            ?>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" bgcolor="#333333" width="100%">
            <tr>
                <td width="65%" height="40" style="padding-left:20px;"><input type="button" value="<?= $lang["back"] ?>"
                                                                              onClick="parent.document.location.href='<?= $_SESSION["LISTPAGE"] ?>';"
                                                                              class="button"></td>
                <td align="left">&nbsp;&nbsp;<input type="button" value="<?= $lang["submit"] ?>"
                                                    onclick="this.form.submit();" class="button">&nbsp;&nbsp;<input
                        type="reset" value="<?= $lang["reset"] ?>" class="button" onClick="count = 1; resetForm();">
                </td>
            </tr>
        </table>
        <input type="hidden" name="action" value="<?= $action ?>">
        <input type="hidden" name="posted" value="1">
    </form>
</div>
<?= $notice["js"] ?>
</body>
</html>