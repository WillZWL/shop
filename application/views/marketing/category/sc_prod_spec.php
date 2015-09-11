<html>
<head>
    <meta http-equiv="Content-Language" content="en-gb">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="keywords" content="">
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="imagetoolbar" content="no">
    <link rel="stylesheet" type="text/css" href="<?= base_url() . "css/style.css" ?>">
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>marketing/category/js_unitlist"></script>
</head>
<body topmargin="0" leftmargin="0" style="width:100%;">
<div id="main" style="width:100%;">
    <?= $notice["img"] ?>
    <?php
    $ar_status = array($lang["inactive"], $lang["active"]);
    ?>
    <table border="0" cellpadding="0" cellspacing="0" height="50" class="page_header" width="100%">
        <tr>
            <td height="2" bgcolor="#000033"></td>
            <td height="2" bgcolor="#000033"></td>
        </tr>
        <tr>
            <td height="50" style="padding-left:8px"><b style="font-size:14px"><?= $lang["header"] . " " . $cat_obj->getName() ?></b>
        </tr>
    </table>
    <form name="fm_edit" action="<?= $_SERVER["PHP_SELF"] ?>" method="post" onSubmit="return CheckForm(this)">
        <table border="0" cellpadding="0" cellspacing="1" height="20" class="page_header" width="100%">
            <col>
            <col width="150">
            <col width="150">
            <col width="150">
            <col width="150">
            <?php
            if ($full_cps_list) :
                foreach ($full_cps_list AS $psg_name => $cps_array) :
                    ?>
                    <tr class="header">
                        <td height="20" colspan="5">&nbsp;&nbsp;<?= $psg_name ?></td>
                    </tr>
                    <?php
                    if ($cps_array) :
                        ?>
                        <tr class="field">
                            <td>&nbsp;&nbsp;<?= $lang["prod_spec_name"] ?></td>
                            <td>&nbsp;&nbsp;<?= $lang["unit_type"] ?></td>
                            <td>&nbsp;&nbsp;<?= $lang["unit_name"] ?></td>
                            <td>&nbsp;&nbsp;<?= $lang["priority"] ?></td>
                            <td>&nbsp;&nbsp;<?= $lang["status"] ?></td>
                        </tr>
                        <?php
                        $i = 0;
                        foreach ($cps_array AS $ps_name => $cps_obj) :
                            ?>
                            <tr class="value">
                                <td>&nbsp;&nbsp;<?= $ps_name ?></td>
                                <td>&nbsp;&nbsp;<?= $cps_obj->getUnitTypeName() ?></td>
                                <td>
                                    <?php
                                    if ($cps_obj->getUnitId()) :
                                        if ($cps_obj->getUnitId() == 'txt') :
                                            ?>
                                            &nbsp;&nbsp;Not suitable
                                        <?php
                                        else :
                                            ?>
                                            &nbsp;&nbsp;<?= $cps_obj->getUnitName() ?>
                                        <?php
                                        endif;
                                    else :
                                        if ($cps_obj->getUnitTypeId() == 'txt') :
                                            ?>
                                            &nbsp;&nbsp<input type="hidden"
                                                              name="cps_obj[<?= $cps_obj->getPsFuncId() ?>][unit_id]"
                                                              value="txt">Not suitable
                                        <?php
                                        else :
                                            ?>
                                            <select name="cps_obj[<?= $cps_obj->getPsFuncId() ?>][unit_id]" class="input">
                                                <?php
                                                $selected_s[$cps_obj->getUnitId()] = "SELECTED";
                                                foreach ($ut_array[$cps_obj->getUnitTypeId()] AS $obj) :
                                                ?>
                                                <option value="<?= $obj->getId() ?>" <?= @$selected_s[$cps_obj->getUnitId()] ?>><?= $obj->getUnitName() ?>
                                                <?php
                                                endforeach;
                                                ?>
                                            </select>
                                        <?php
                                        endif;
                                    endif;
                                    ?>
                                </td>

                                <td><input name="cps_obj[<?= $cps_obj->getPsFuncId() ?>][priority]" class="input"
                                           value="<?= $cps_obj->getPriority() ?>" notEmpty isNumber min=0></td>
                                <td>
                                    <select name="cps_obj[<?= $cps_obj->getPsFuncId() ?>][status]" class="input">
                                        <?php
                                        $selected_s = array();
                                        $selected_s[$cps_obj->getStatus()] = "SELECTED";
                                        foreach ($ar_status AS $rskey => $rsvalue) :
                                        ?>
                                        <option value="<?= $rskey ?>" <?= @$selected_s[$rskey] ?>><?= $rsvalue ?>
                                        <?php
                                        endforeach;
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <input type="hidden" name="posted" value="1">
                        <?php
                        endforeach;
                    endif;
                endforeach;
            endif;
            ?>
            <tr>
                <td colspan='6' align="right"><input type="submit" value="<?= $lang["update"] ?>"></td>
            </tr>

        </table>
    </form>
    <script language="javascript">
    </script>
    <?= $notice["js"] ?>
</body>
</html>