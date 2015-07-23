<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>

</head>
<body topmargin="0" leftmargin="0" style="width:auto;">
<div id="main" style="width:auto;">
    <?php
    if ($canedit == 1)
    {
    ?>
    <form action="<?= $_SERVER["PHP_SELF"] ?>?sscat=<?= $this->input->get('sscat') ?>" method="POST">
        <?php
        }
        ?>
        <table border="0" cellpadding="0" cellspacing="1" height="20" bgcolor="#000000" width="100%">
            <tr>
                <td width="180" height="20" bgcolor="#666666"><font color="#FFFFFF"><b>
                            &nbsp;&nbsp;<?= $lang["ra_info"] ?></b></font></td>
                <td height="20" bgcolor="#666666"><font color="#FFFFFF"><b>
                            &nbsp;&nbsp;<?= $lang["assoc_value"] ?></b></font></td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="1" bgcolor="#bbbbff" width="100%">
            <?php
            for ($i = 1; $i <= 8; $i++) {
                ?>
                <tr>
                    <td width="172" height="20" class="field" align="right"
                        style="padding-right:8px;"><?= $lang["ra"] . " " . $i ?></td>
                    <td height="20" align="left" class="value">&nbsp;&nbsp;<select name="sscat<?= $i ?>"
                                                                                   style="width:200px;">
                            <option value="" SELECTED>-- <?= $lang["not_defined"] ?> --</option><?php
                            foreach ($sscat_list as $sobj) {
                                $func = "get_rcm_ss_cat_id_" . $i;
                                ?>
                                <option
                                value="<?= $sobj->get_id() ?>" <?= ($sobj->get_id() == $ra_obj->$func() ? "SELECTED" : "") ?>><?= $sobj->get_name() ?></option><?php
                            }
                            ?></select></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td width="172" height="20" class="field" align="right"
                    style="padding-right:8px;"><?= $lang["warr_cat"] ?></td>
                <td height="20" align="left" class="value">&nbsp;&nbsp;<select name="warranty_cat" style="width:200px;">
                        <option value="" SELECTED>-- <?= $lang["not_defined"] ?> --</option><?php
                        foreach ($warr_cat_list as $warr_obj) {
                            ?>
                            <option
                            value="<?= $warr_obj->get_id() ?>" <?= ($warr_obj->get_id() == $ra_obj->get_warranty_cat() ? "SELECTED" : "") ?>><?= $warr_obj->get_name() ?></option><?php
                        }
                        ?></select></td>

            </tr>
            <tr>
                <td width="172" height="20" class="field" align="right"
                    style="padding-right:8px;"><?= $lang["status"] ?></td>
                <td height="20" align="left" class="value">&nbsp;&nbsp<select name="status" style="width:100px;">
                        <option
                            value="0" <?= ($ra_obj->get_status() == 0 ? "SELECTED" : "") ?>><?= $lang["status0"] ?></option>
                        <option
                            value="1" <?= ($ra_obj->get_status() == 1 ? "SELECTED" : "") ?>><?= $lang["status1"] ?></option>
                    </select></td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" height="40" class="page_header" width="100%">
            <tr>
                <td align="right" style="padding-right:8px"><input type="submit" name="submit"
                                                                   value="<?= $lang["update_record"] ?>"
                                                                   style="font-size:11px"></td>
            </tr>
        </table>
        <?php
        if ($canedit == 1)
        {
        ?>
        <input type="hidden" name="sscat" value="<?= $this->input->get("sscat") ?>">
        <input type="hidden" name="type" value="<?= $type ?>">
        <input type="hidden" name="posted" value="1">
    </form>
<?php
}
?>
</div>
</body>
</html>