<html>
<head>
    <title><?= $lang["title_split_orders"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/calendar.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/calendar.css" type="text/css" media="all"/>
    <script language="javascript">


    </script>
</head>
<?php
$today = getdate();
?>
<div id="main">
    <table cellpadding="0" cellspacing="0" width="100%" border="0">
        <tr>
            <td align="left" class="title" height="30" width="650"><b
                    style="font-size: 16px; color: rgb(0, 0, 0);"><?= $lang["title_split_orders"] ?></b></td>
            <td align="right" class="title">
                <input type="button" value="<?= $lang["title"] ?>" class="button"
                       onclick="Redirect('<?= base_url() . "report/salesReport/index" ?>')">&nbsp;
                <input type="button" value="<?= $lang["title_split_orders"] ?>" class="button"
                       onclick="Redirect('<?= base_url() . "report/salesReport/splitOrdersReport" ?>')">&nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="2" height="2" bgcolor="#000033"></td>

        </tr>
    </table>
    <form name="fm" action="<?= base_url() ?>report/salesReport/splitOrdersReport" method="post" target="report">
        <input type="hidden" name="is_query" value="1">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
            <tr height="70">
                <td align="left" style="padding-left:8px;"><b
                        style="font-size: 14px; color: rgb(0, 0, 0);"><?= $lang["header_split_orders"] ?></b><br><?= $lang["header_message"] ?>
                </td>
                <td align="left">
                    <table border="0" cellpadding="2" cellspacing="0">
                        <col width="100">
                        <col width="200">
                        <col width="100">
                        <tr>
                            <td align='right'><b><?= $lang["start_date"] ?></b></td>
                            <td><input name="start_date" value='<?= htmlspecialchars($start_date) ?>' notEmpty><img
                                    src="/images/cal_icon.gif" class="pointer"
                                    onclick="showcalendar(event, document.fm.start_date, false, false, false, '2012-05-01')"
                                    align="absmiddle">
                            </td>
                            <td rowspan="2" align="center"><input type="submit" value="" class="search_button"
                                                                  style="background: url('<?= base_url() ?>/images/find.gif') #CCCCCC no-repeat center; width: 30px; height: 25px;">
                                &nbsp; </td>
                        </tr>
                        <tr>
                            <td align='right'><b><?= $lang["end_date"] ?></b></td>
                            <td>
                                <input name="end_date" value='<?= htmlspecialchars($end_date) ?>' notEmpty><img
                                    src="/images/cal_icon.gif" class="pointer"
                                    onclick="showcalendar(event, document.fm.end_date, false, false, false, '2010-01-01','<?= $today ?>')"
                                    align="absmiddle">
                                <input type="hidden" name="display_type" value="">
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
<?= $notice["js"] ?>
<?php

if ($prompt_notice) {
    ?>
    <script language="javascript">alert('<?=$lang["update_notice"]?>')</script>
<?php
}
?>
</html>