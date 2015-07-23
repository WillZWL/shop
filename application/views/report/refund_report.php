<html>
<head>
    <title><?= $lang["title"] ?></title>
    <STYLE type="text/css">
        .button3 {
            WIDTH: 170px;
        }
    </STYLE>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/calendar.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/calendar.css" type="text/css" media="all"/>
</head>
<body onResize="SetFrameFullHeight(document.getElementById('report'));">
<div id="main">
    <?= $notice["img"] ?>
    <table cellpadding="0" cellspacing="0" width="100%" border="0">
        <tr>
            <td align="left" class="title" height="30"><b
                    style="font-size: 16px; color: rgb(0, 0, 0);"><?= $lang["title"] ?></b></td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033"></td>
        </tr>
    </table>
    <form name="fm" method="post" onSubmit="return CheckForm(this)">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
            <tr height="80">
                <td align="left" style="padding-left:8px;">
                    <b style="font-size: 14px; color: rgb(0, 0, 0);"><?= $lang["header"] ?></b><br><?= $lang["header_tip"] ?>

                </td>
                <td align="right">
                    <table border="0" cellpadding="0" cellspacing="0" style="line-height:8px;">
                        <col>
                        <col width="5%">
                        <col width="25%">
                        <col width="10%">
                        <col width="5%">
                        <col width="25%">
                        <col width="10px">
                        <tr>
                            <td><input id="check_order_create" type='checkbox' name="check[order_create]" value='1'><b>Order
                                    Create Date</b></td>
                            <td><b><?= $lang["start_date"] ?></b></td>
                            <td><input id="oc_start_date" name="start_date[order_create]"
                                       value='<?= htmlspecialchars($start_date) ?>'><img src="/images/cal_icon.gif"
                                                                                         class="pointer"
                                                                                         onclick="showcalendar(event, document.fm.oc_start_date, false, false, false, '2012-05-01')"
                                                                                         align="absmiddle"></td>
                            <td></td>
                            <td><b><?= $lang["end_date"] ?></b></td>
                            <td><input id="oc_end_date" name="end_date[order_create]"
                                       value='<?= htmlspecialchars($end_date) ?>'><img src="/images/cal_icon.gif"
                                                                                       class="pointer"
                                                                                       onclick="showcalendar(event, document.fm.oc_end_date, false, false, false, '2010-01-01','<?= $today ?>')"
                                                                                       align="absmiddle"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><input id="check_cs_request" type='checkbox' name="check[cs_request]" value='1'><b>CS
                                    Request Refund Date</b></td>
                            <td><b><?= $lang["start_date"] ?></b></td>
                            <td><input id="cs_start_date" name="start_date[cs_request]"
                                       value='<?= htmlspecialchars($start_date) ?>'><img src="/images/cal_icon.gif"
                                                                                         class="pointer"
                                                                                         onclick="showcalendar(event, document.fm.cs_start_date, false, false, false, '2012-05-01')"
                                                                                         align="absmiddle"></td>
                            <td></td>
                            <td><b><?= $lang["end_date"] ?></b></td>
                            <td><input id="cs_end_date" name="end_date[cs_request]"
                                       value='<?= htmlspecialchars($end_date) ?>'><img src="/images/cal_icon.gif"
                                                                                       class="pointer"
                                                                                       onclick="showcalendar(event, document.fm.cs_end_date, false, false, false, '2010-01-01','<?= $today ?>')"
                                                                                       align="absmiddle"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><input id="check_refund" type='checkbox' name="check[refund]" value='1' CHECKED><b>Refund
                                    Date</b></td>
                            <td><b><?= $lang["start_date"] ?></b></td>
                            <td><input id="rf_start_date" name="start_date[refund]"
                                       value='<?= htmlspecialchars($start_date) ?>'><img src="/images/cal_icon.gif"
                                                                                         class="pointer"
                                                                                         onclick="showcalendar(event, document.fm.rf_start_date, false, false, false, '2012-05-01')"
                                                                                         align="absmiddle"></td>
                            <td></td>
                            <td><b><?= $lang["end_date"] ?></b></td>
                            <td><input id="rf_end_date" name="end_date[refund]"
                                       value='<?= htmlspecialchars($end_date) ?>'><img src="/images/cal_icon.gif"
                                                                                       class="pointer"
                                                                                       onclick="showcalendar(event, document.fm.rf_end_date, false, false, false, '2010-01-01','<?= $today ?>')"
                                                                                       align="absmiddle"></td>
                            <td></td>
                        </tr>
                        <!--
    <tr>
        <td><b><?= $lang["end_date"] ?></b></td>
        <td><input name="end_date" value='<?= htmlspecialchars($end_date) ?>' notEmpty><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.fm.end_date, false, false, false, '2010-01-01')" align="absmiddle"></td>
    </tr>
    -->
                        <tr>
                            <td colspan="10" align="right" style="padding-top:5px; padding-right:8px;">
                                <b><font color="#ffffff" size="4" id="error_tip"></font> </b> <input type="button"
                                                                                                     value="<?= $lang["export_csv"] ?>"
                                                                                                     class="button3"
                                                                                                     onClick="refundReportVerify();">
                                <br/>
                            </td>
                        </tr>
                        <input type="hidden" name="is_query" value="1">
                    </table>
                </td>
            </tr>
            <tr>
                <td height="2" bgcolor="#000033" colspan="3"></td>
            </tr>
        </table>
    </form>
    <?= $notice["js"] ?>
</div>
<script>
    function refundReportVerify() {
        var number = 0;
        document.getElementById("error_tip").innerHTML = "";
        var check_order_create = document.getElementById("check_order_create").checked;
        var check_cs_request = document.getElementById("check_cs_request").checked;
        var check_refund = document.getElementById("check_refund").checked;

        var oc_start_date = document.getElementById("oc_start_date").value;
        var oc_end_date = document.getElementById("oc_end_date").value;

        var cs_start_date = document.getElementById("cs_start_date").value;
        var cs_end_date = document.getElementById("cs_end_date").value;

        var rf_start_date = document.getElementById("rf_start_date").value;
        var rf_end_date = document.getElementById("rf_end_date").value;

        if (!check_order_create && !check_cs_request && !check_refund) {
            document.getElementById("error_tip").innerHTML = "<?=$lang["error_0"]?>";
            return false;
        }

        if (check_order_create) {
            if (oc_start_date && oc_end_date) {
                var startD = new Date(Date.parse(oc_start_date.replace(/-/g, "/")));
                var endD = new Date(Date.parse(oc_end_date.replace(/-/g, "/")));
                var days = parseInt((endD.getTime() - startD.getTime()) / (1000 * 60 * 60 * 24));
                if (startD > endD) {
                    document.getElementById("error_tip").innerHTML = "<?=$lang["error_1"]?>";
                    return false;
                }
                if (days <= 30) {
                    number = number + 1;
                } else {
                    document.getElementById("error_tip").innerHTML = "<?=$lang["error_2"]?>";
                    return false;
                }
            } else {
                document.getElementById("error_tip").innerHTML = "<?=$lang["error_3"]?>";
                return false;
            }
        }

        if (check_cs_request) {
            if (cs_start_date && cs_end_date) {
                var startD = new Date(Date.parse(cs_start_date.replace(/-/g, "/")));
                var endD = new Date(Date.parse(cs_end_date.replace(/-/g, "/")));
                var days = parseInt((endD.getTime() - startD.getTime()) / (1000 * 60 * 60 * 24));
                if (startD > endD) {
                    document.getElementById("error_tip").innerHTML = "<?=$lang["error_1"]?>";
                    return false;
                }
                if (days <= 30) {
                    number = number + 1;
                } else {
                    document.getElementById("error_tip").innerHTML = "<?=$lang["error_2"]?>";
                    return false;
                }
            } else {
                document.getElementById("error_tip").innerHTML = "<?=$lang["error_3"]?>";
                return false;
            }
        }

        if (check_refund) {
            if (rf_start_date && rf_end_date) {
                var startD = new Date(Date.parse(rf_start_date.replace(/-/g, "/")));
                var endD = new Date(Date.parse(rf_end_date.replace(/-/g, "/")));
                if (startD > endD) {
                    document.getElementById("error_tip").innerHTML = "<?=$lang["error_1"]?>";
                    return false;
                }
                var days = parseInt((endD.getTime() - startD.getTime()) / (1000 * 60 * 60 * 24));
                if (days <= 30) {
                    number = number + 1;
                } else {
                    document.getElementById("error_tip").innerHTML = "<?=$lang["error_2"]?>";
                    return false;
                }
            } else {
                document.getElementById("error_tip").innerHTML = "<?=$lang["error_3"]?>";
                return false;
            }
        }
        document.getElementById("error_tip").innerHTML = "";
        if (number > 0 && CheckForm(document.fm)) {
            document.fm.submit();
        }

    }
</script>
</body>
</html>