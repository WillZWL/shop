<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
</head>
<body>
<div id="main">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="page_header">
        <tr height="30">
            <td style="padding-left:20px;"><input class="button" type="button"
                                                  onClick="Redirect('<?= base_url() ?>cs/compensation/create/')"
                                                  value="<?= $lang["create"] ?>"></td>
            <td style="padding-left:20px;"><?= $lang["create_desc"] ?></td>
        </tr>
        <?php
        if (check_app_feature_access_right($app_id, "CS000400_man_approve_btn")) {
            if (!$this->authorization_service->user_app_rights('CS000405')) {
                $disable_approve = "disabled";
            }
            ?>
            <tr height="30">
                <td style="padding-left:20px;"><input class="button" type="button"
                                                      onClick="Redirect('<?= base_url() ?>cs/compensation/manager_approval')"
                                                      value="<?= $lang["manager_approval"] ?>" <?= $disable_approve ?>>
                </td>
                <td style="padding-left:20px;"><?= $lang["manager_approval_desc"] ?></td>
            </tr>
        <?php
        }
        ?>
        <tr height="30">
            <td></td>
        </tr>
        <!--tr height="30">
    <td style="padding-left:20px;"><input class="button" type="button" onClick="Redirect('<?= base_url() ?>cs/compensation/report/')" value="<?= $lang["report"] ?>"></td>
    <td style="padding-left:20px;"><?= $lang["report_desc"] ?></td>
</tr-->
        <!--
<tr>
    <td colspan="2"><hr></td>
</tr>
<tr height="40">
    <td style="padding-left:20px;"><input class="button" type="button" onClick="Redirect('<?= base_url() ?>cs/compensation/reason/')" value="<?= $lang["reason"] ?>"></td>
    <td style="padding-left:20px;"><?= $lang["reason_desc"] ?></td>
</tr>
-->
    </table>
</div>
</body>
</html>