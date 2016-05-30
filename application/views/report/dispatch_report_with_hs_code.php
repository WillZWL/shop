<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/calendar.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/calendar.css" type="text/css" media="all"/>
</head>
<?php
$today = getdate();
?>
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
    <form name="fm" action="<?= base_url() . "report/DispatchWithHsCodeReport/query" ?>" method="post" target="report">
        <input type="hidden" name="is_query" value="1">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
            <tr height="70">
                <td align="left" style="padding-left:8px;"><b
                        style="font-size: 14px; color: rgb(0, 0, 0);"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?>
                </td>
                <td align="right">
                    <table border="0" cellpadding="2" cellspacing="0">
                        <tr>
                            <td align='right'><?= $lang["currency"] ?></td>
                            <td>
                                <select name='currency' id='currency'>
                                    <option value='-1'>-- Please select --</option>
                                    <?php
                                    foreach ($currencys as $currency) {
                                    ?>
                                        <option value="<?=$currency->getCurrencyId()?>"><?=$currency->getCurrencyId()?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td align='right'><b><?= $lang["start_date"] ?></b></td>
                            <td>
                                <input id="start_date" name="start_date"
                                       value='<?= htmlspecialchars($start_date) ?>'><img src="/images/cal_icon.gif"
                                                                                         class="pointer"
                                                                                         onclick="showcalendar(event, document.fm.start_date, false, false, false, '2012-05-01')"
                                                                                         align="absmiddle">
                            </td>
                            <td rowspan="2" align="center"><input type="submit" value="" class="search_button"
                                                                  style="background: url('<?= base_url() ?>/images/find.gif') #CCCCCC no-repeat center; width: 30px; height: 25px;">
                                &nbsp; </td>
                        </tr>
                        <tr>
                            <td align='right'><?= $lang["country"] ?></td>
                            <td>
                                <select name='country' id='country'>
                                    <option value='-1'>-- Please select --</option>
                                    <?php
                                    foreach ($countrys as $country) {
                                    ?>
                                        <option value="<?=$country->getId()?>"><?=$country->getName()?></option>;
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td align='right'><b><?= $lang["end_date"] ?></b></td>
                            <td>
                                <input id="end_date" name="end_date"
                                       value='<?= htmlspecialchars($end_date) ?>'><img src="/images/cal_icon.gif"
                                                                                         class="pointer"
                                                                                         onclick="showcalendar(event, document.fm.end_date, false, false, false, '2012-05-01')"
                                                                                         align="absmiddle">
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
</div>
</body>
</html>