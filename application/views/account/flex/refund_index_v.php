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

    <form name="fm" action="<?= base_url() . "account/flex/refund" ?>" method="post" target="_self">
        <input type="hidden" name="is_query" value="1">
        <input type="hidden" name="search" value="1">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
            <tr height="70">
                <td align="left" style="padding-left:8px;"><b
                        style="font-size: 14px; color: rgb(0, 0, 0);"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?>
                </td>
                <td align="right">
                    <table border="0" cellpadding="2" cellspacing="0" width="980">
                        <col width="500">
                        <col width="100">
                        <col width="40">
                        <tr>
                            <td align='right'><b><?= $lang["start_date"] ?></b></td>
                            <td>
                                <input name="start_date" value='<?= htmlspecialchars($start_date) ?>' notEmpty><img
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
    <form name="fm_result" method="get">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="26">
            <col width="70">
            <col>
            <col width="26">
            <tr class="header">
                <td height="20"></td>
                <td><?= $lang["date"] ?></a></td>
                <td><?= $lang["filename"] ?></td>
                <td></td>
            </tr>
            <?php
            if ($result_array) {
                foreach ($result_array as $date => $arr) {
                    $script = "";
                    ?>

                    <tr class="row<?= $i % 2 ?>">
                        <td height="20"></td>
                        <td><?= $arr["date"] ?></td>
                        <td>
                            <?php
                            $script .= "<input style='width:170px' type='button' value='Generate Refund & Chargeback' class='button' onclick=\"Redirect(" . "'" . site_url('account/flex/genRefundAndChargebackInvoice/' . $date . '/' . $start_date . '/' . $end_date) . "'" . ")\">";
                            if (array_key_exists("zip", $arr)) {
                                $download_zip_link = "";
                                foreach ($arr["zip"] as $zip_file_name) {
                                    $download_zip_link .= "<a href='" . site_url('account/flex/download_file/' . $date . '/' . $status . '/' . $zip_file_name) . "'>" . $zip_file_name . "</a> | ";
                                }
                                $script = $download_zip_link . $script;
                            }
                            ?>
                            <?= $script ?>
                        </td>
                        <td></td>
                    </tr>
                <?php
                }
            }
            ?>
        </table>
    </form>
    <?= $this->sc['Pagination']->createLinksWithStyle() ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>