<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>marketing/category/js_catlist"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/calendar.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/calendar.css" type="text/css" media="all"/>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <?php
    // construct all the HTML select options here
    $platform_option_html = $pmgw_option_html = $currency_option_html = $reason_option_html = $cb_reason_option_html = $cb_status_option_html = $cb_remark_option_html = "";

    if ($selling_platform) {
        foreach ($selling_platform as $key => $value) {
            $platform_id = $value->getSellingPlatformId();
            $platform_option_html .= <<<html
            <option value="$platform_id">$platform_id</option>
html;
        }
    }

    if ($pmgw_list) {
        foreach ($pmgw_list as $key => $value) {
            $pmgw_id = $value->getPaymentGatewayId();
            $pmgw_option_html .= <<<html
            <option value="$pmgw_id">$pmgw_id</option>
html;
        }
    }

    if ($currency_list) {
        foreach ($currency_list as $key => $value) {
            $currency_id = $value->getCurrencyId();
            $currency_option_html .= <<<html
            <option value="$currency_id">$currency_id</option>
html;
        }
    }

    if ($hold_reason_list) {
        foreach ($hold_reason_list as $key => $reason) {
            $reason_option_html .= <<<html
            <option value="$reason">$reason</option>
html;
        }
    }

    if ($chargeback_reason_list) {
        foreach ($chargeback_reason_list as $key => $value) {
            $cb_reason_option_html .= <<<html
            <option value="{$value->id}">{$value->name}</option>
html;
        }
    }

    if ($chargeback_status_list) {
        foreach ($chargeback_status_list as $key => $value) {
            $cb_status_option_html .= <<<html
            <option value="{$value->id}">{$value->name}</option>
html;
        }
    }

    if ($chargeback_remark_list) {
        foreach ($chargeback_remark_list as $key => $value) {
            $cb_remark_option_html .= <<<html
            <option value="{$value->id}">{$value->name}</option>
html;
        }
    }

    ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="600" align="right" class="title"></td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <form name="fm" method="post">
        <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
            <col width="10%">
            <col width="30%">
            <col width="10%">
            <col width="15%">
            <tr>
                <td height="30" style="padding-left:8px"><b><?= $lang["platform"] ?></b></td>
                <td>
                    <select name="platform" id="platform">
                        <option></option>
                        <?= $platform_option_html ? $platform_option_html : "<option></option>"; ?>
                    </select>
                </td>
                <td height="30" style=""><b><?= $lang["order_start"] ?></b></td>
                <td>
                    <input id="orderstart" name="orderstart" value='<?= htmlspecialchars($start_date) ?>'>
                    <img src="/images/cal_icon.gif" class="pointer"
                         onclick="showcalendar(event, document.getElementById('orderstart'), false, false, false, '2010-01-01')"
                         align="absmiddle">
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td height="30" style="padding-left:8px"><b><?= $lang["psp_gateway"] ?></b></td>
                <td>
                    <select name="pmgw" id="pmgw">
                        <option></option>
                        <?= $pmgw_option_html ? $pmgw_option_html : "<option></option>"; ?>
                    </select>
                </td>
                <td height="30" style=""><b><?= $lang["order_end"] ?></b></td>
                <td>
                    <input id="orderend" name="orderend" value='<?= htmlspecialchars($end_date) ?>'>
                    <img src="/images/cal_icon.gif" class="pointer"
                         onclick="showcalendar(event, document.getElementById('orderend'), false, false, false, '2010-01-01')"
                         align="absmiddle">
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td height="30" style="padding-left:8px"><b><?= $lang["hold_reason"] ?></b></td>
                <td>
                    <select name="rsn" id="rsn">
                        <option></option>
                        <?= $reason_option_html ? $reason_option_html : "<option></option>"; ?>
                    </select>
                </td>
                <td height="30" style="">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td height="30" style="padding-left:8px"><b><?= $lang["cb_reason"] ?></b></td>
                <td>
                    <select name="cbrsn" id="cbrsn">
                        <option></option>
                        <?= $cb_reason_option_html ? $cb_reason_option_html : "<option></option>"; ?>
                    </select>
                </td>
                <td height="30" style=""><b><?= $lang["cb_start"] ?></b></td>
                <td>
                    <input id="cbstart" name="cbstart" value='<?= htmlspecialchars($cb_start_date) ?>'>
                    <img src="/images/cal_icon.gif" class="pointer"
                         onclick="showcalendar(event, document.getElementById('cbstart'), false, false, false, '2010-01-01')"
                         align="absmiddle">
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td height="30" style="padding-left:8px"><b><?= $lang["cb_status"] ?></b></td>
                <td>
                    <select name="cbstatus" id="cbstatus">
                        <option></option>
                        <?= $cb_status_option_html ? $cb_status_option_html : "<option></option>"; ?>
                    </select>
                </td>
                <td height="30" style=""><b><?= $lang["cb_end"] ?></b></td>
                <td>
                    <input id="cbend" name="cbend" value='<?= htmlspecialchars($cb_start_date) ?>'>
                    <img src="/images/cal_icon.gif" class="pointer"
                         onclick="showcalendar(event, document.getElementById('cbend'), false, false, false, '2010-01-01')"
                         align="absmiddle">
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td height="30" style="padding-left:8px"><b><?= $lang["cb_remark"] ?></b></td>
                <td>
                    <select name="cbremark" id="cbremark">
                        <option></option>
                        <?= $cb_remark_option_html ? $cb_remark_option_html : "<option></option>"; ?>
                    </select>
                </td>
                <td height="30" style=""><b><?= $lang["order_no"] ?></b></td>
                <td>
                    <input type="text" id="so" name="so">
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td height="30" style="padding-left:8px"><b><?= $lang["currency"] ?></b></td>
                <td>
                    <select name="curr" id="curr">
                        <option></option>
                        <?= $currency_option_html ? $currency_option_html : "<option></option>"; ?>
                    </select>
                </td>
                <td height="30" style=""><b>&nbsp;</b></td>
                <td height="30" style=""><b>&nbsp;</b></td>
                <input type="hidden" name="search" value="1">
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td height="30" colspan="3" style=""><b>&nbsp;</b></td>
                <td height="30" style="text-align:right"><input type="submit" value="<?= $lang["generate"] ?>"></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td height="10" colspan="5"></td>
            </tr>
            <tr>
                <td height="2" class="line" colspan="5"></td>
            </tr>
        </table>
    </form>
    <?= $notice["js"] ?>
</div>
</body>
</html>