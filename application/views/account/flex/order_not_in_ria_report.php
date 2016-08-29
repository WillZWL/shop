<html>
<head>
    <title><?= $lang["title"] ?></title>
    <style type="text/css">
        .button3 {
            width: 170px;
        }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/calendar.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/calendar.css" type="text/css" media="all"/>
</head>
<body>
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
    <form name="fm" action='<?= base_url() . "account/flex/orderNotInRiaReport"; ?>' method="post"
          onSubmit="return verfiy_date(this)">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
            <tr height="80">
                <td align='right'><?= $lang["payment_gateway"] ?></td>
                <td>
                    <select name='payment_gateway' id='payment_gateway'>
                        <option value='-1'>-- All gateway --</option>
                        <?php
                        foreach ($gateways as $gateway) {
                            print "<option value='" . $gateway->getId() . "'>" . $gateway->getName() . "</option>";
                        }
                        ?>
                    </select>
                </td>
                <td align='right'><?= $lang["currency"] ?></td>
                <td>
                    <select name='currency' id='currency'>
                        <option value='-1'>-- All currencies --</option>
                        <?php
                        foreach ($currencys as $currency) {
                            print "<option value='" . $currency->getCurrencyId() . "'>" . $currency->getCurrencyId() . "</option>";
                        }
                        ?>
                    </select>
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
                            <td></td>
                            <td><b>From</b></td>
                            <td><input id="oc_start_date" name="start_date"
                                       value='<?= htmlspecialchars($start_date) ?>'><img src="/images/cal_icon.gif"
                                                                                         class="pointer"
                                                                                         onclick="showcalendar(event, document.getElementById('oc_start_date'), false, false, false, '2010-01-01')"
                                                                                         align="absmiddle"></td>
                            <td></td>
                            <td><b>To</b></td>
                            <td><input id="oc_end_date" name="end_date" value='<?= htmlspecialchars($end_date) ?>'><img
                                    src="/images/cal_icon.gif" class="pointer"
                                    onclick="showcalendar(event, document.getElementById('oc_end_date'), false, false, false, '2010-01-01')"
                                    align="absmiddle"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="10" align="right" style="padding-top:5px; padding-right:8px;">
                                <input type="submit" value="Export CSV" class="button3">
                            </td>
                        </tr>
                        <input type="hidden" name="is_query" value="1">
                    </table>
                </td>
            </tr>
    </form>
    </table>
    <?= $notice["js"] ?>
</div>
<?php print $content; ?>

<script>
    function getDateInfo(fm_2) {
        //alert(fm_2.name);
        var start_date = document.getElementById("oc_start_date").value.trim();
        var end_date = document.getElementById("oc_end_date").value.trim();
        fm_2.expect_delivery_date_approve_start_date.value = start_date;
        fm_2.expect_delivery_date_approve_end_date.value = end_date;
        if (!start_date.match(/^\d{4}-\d{2}-\d{2}$/) || !end_date.match(/^\d{4}-\d{2}-\d{2}$/)) {
            alert("please input the correct date");
            return false;
        }
    }

    function verfiy_date() {
        var start_date = document.getElementById("oc_start_date").value.trim();
        var end_date = document.getElementById("oc_end_date").value.trim();
        if (!start_date.match(/^\d{4}-\d{2}-\d{2}$/) || !end_date.match(/^\d{4}-\d{2}-\d{2}$/)) {
            alert("please input the correct date");
            return false;
        }
    }

</script>
</body>
</html>