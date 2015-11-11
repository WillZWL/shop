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
<body>
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="600" align="right" class="title">
            </td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>

    <form name="fm" action="<?= base_url() . "account/flex/pending_order_report" ?>" method="post" target="_self">
        <input type="hidden" name="is_query" value="1">
        <input type="hidden" name="search" value="1">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
            <tr height="70">
                <td align="left" style="padding-left:8px;"><b
                        style="font-size: 14px; color: rgb(0, 0, 0);"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?>
                </td>
                <td align="right">
                    <table border="0" cellpadding="2" cellspacing="0">
                        <tr align='right'>
                            <td><b><?= $lang["ship_date"] ?></b></td>
                            <td><input name="ship_date" value='<?= htmlspecialchars($ship_date) ?>' notEmpty><img
                                    src="/images/cal_icon.gif" class="pointer"
                                    onclick="showcalendar(event, document.fm.ship_date, false, false, false, '2010-01-01')"
                                    align="absmiddle"></td>
                            <td rowspan="2" align="right"><input type="submit" value="" class="search_button"
                                                                 style="background: url('<?= base_url() ?>/images/find.gif') #CCCCCC no-repeat center; width: 30px; height: 25px;">
                                &nbsp; </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="2" bgcolor="#000033" colspan="3"></td>
            </tr>
        </table>
    </form>
    <?= $this->sc['Pagination']->createLinksWithStyle() ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>