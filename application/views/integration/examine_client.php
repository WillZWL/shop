<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
</head>
<?php
$ar_status = array("N" => $lang["new"], "R" => $lang["ready_update_to_master"], "S" => $lang["success"], "F" => $lang["failed"], "I" => $lang["investigated"]);
$ar_color = array("N" => "#000000", "R" => "#0000CC", "S" => "#009900", "F" => "#CC0000", "I" => "#440088");
$ar_bool = array("0" => "No", "1" => "Yes");
?>
<body>
<div id="main" style="width:1024px;">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="1" width="100%" bgcolor="#cccccc">
        <?php
        if ($edit)
        {
        ?>

        <form name="fm" method="POST" onSubmit="return CheckForm(this);">
            <?php
            }
            ?>

            <col width="15%">
            <col width="35%">
            <col width="15%">
            <col width="35%">
            <tr bgcolor="#000033" height="20">
                <td colspan="4" class="value_text"><font
                        style="color:#ffffff; font-weight:bold;"><?= $lang["batch_info"] ?></font></td>
            </tr>
            <tr height="20">
                <td class="field field_text"><?= $lang["client_trans_id"] ?></td>
                <td class="value value_text"><?= $obj->get_trans_id() ?></td>
                <td class="field field_text"><?= $lang["client_status"] ?></td>
                <td class="value value_text"><?= ($obj->get_id() == "" ? $lang["new_client"] : $lang["existing_client_with_client_id"] . " " . $obj->get_id()) ?></td>
            </tr>
            <tr height="20">
                <td class="field field_text"><?= $lang["client_batch_status"] ?></td>
                <td class="value value_text"><?= $ar_status[$obj->get_batch_status()] ?></td>
                <td class="field field_text"><?= $lang["fail_reason"] ?></td>
                <td class="value value_text"><?= $obj->get_failed_reason() ?></td>
            </tr>
            <tr bgcolor="#000033" height="20">
                <td colspan="4" class="value_text"><font
                        style="color:#ffffff; font-weight:bold;"><?= $lang["edit_client_info_below"] ?></font></td>
            </tr>
            <tr height="20">
                <td class="field field_text"><?= $lang["client_email"] ?></td>
                <td class="value value_text"><input type="text" name="email" value="<?= $obj->get_email() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?> notEmpty></td>
                <td class="field field_text"><?= $lang["client_password"] ?></td>
                <td class="value value_text"><?= $this->encrypt->decode($obj->get_password()) ?></td>
            </tr>
            <tr height="20">
                <td class="field field_text"><?= $lang["client_forename"] ?></td>
                <td class="value value_text"><input type="text" name="forename" value="<?= $obj->get_forename() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?> notEmpty></td>
                <td class="field field_text"><?= $lang["client_surname"] ?></td>
                <td class="value value_text"><input type="text" name="surname" value="<?= $obj->get_surname() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?> ></td>
            </tr>
            <tr height="20">
                <td class="field field_text"><?= $lang["client_company_name"] ?></td>
                <td class="value value_text"><input type="text" name="companyname"
                                                    value="<?= $obj->get_companyname() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?>></td>
                <td class="field field_text"><?= $lang["client_address1"] ?></td>
                <td class="value value_text"><input type="text" name="address_1" value="<?= $obj->get_address_1() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?> notEmpty></td>
            </tr>
            <tr height="20">
                <td class="field field_text"><?= $lang["client_address2"] ?></td>
                <td class="value value_text"><input type="text" name="address_2" value="<?= $obj->get_address_2() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?>></td>
                <td class="field field_text"><?= $lang["client_address3"] ?></td>
                <td class="value value_text"><input type="text" name="address_3" value="<?= $obj->get_address_3() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?>></td>
            </tr>
            <tr height="20">
                <td class="field field_text"><?= $lang["client_city"] ?></td>
                <td class="value value_text"><input type="text" name="city" value="<?= $obj->get_city() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?> ></td>
                <td class="field field_text"><?= $lang["client_state"] ?></td>
                <td class="value value_text"><input type="text" name="state" value="<?= $obj->get_state() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?> ></td>
            </tr>
            <tr height="20">
                <td class="field field_text"><?= $lang["client_country_id"] ?></td>
                <td class="value value_text"><input type="text" name="country_id" value="<?= $obj->get_country_id() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?> notEmpty></td>
                <td class="field field_text"><?= $lang["client_postcode"] ?></td>
                <td class="value value_text"><input type="text" name="postcode" value="<?= $obj->get_postcode() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?> notEmpty></td>
            </tr>
            <tr>
                <td class="field field_text"><?= $lang["client_del_name"] ?></td>
                <td colspan="3" class="value value_text"><input type="text" name="country_id"
                                                                value="<?= $obj->get_del_name() ?>"
                                                                class="input" <?= $edit ? "" : "READONLY" ?> notEmpty>
                </td>
                </td>
            </tr>
            <tr height="20">
                <td class="field field_text"><?= $lang["client_del_company"] ?></td>
                <td class="value value_text"><input type="text" name="del_company"
                                                    value="<?= $obj->get_del_company() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?>></td>
                <td class="field field_text"><?= $lang["client_del_address1"] ?></td>
                <td class="value value_text"><input type="text" name="del_address_1"
                                                    value="<?= $obj->get_del_address_1() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?> notEmpty></td>
            </tr>
            <tr height="20">
                <td class="field field_text"><?= $lang["client_del_address2"] ?></td>
                <td class="value value_text"><input type="text" name="del_address_2"
                                                    value="<?= $obj->get_del_address_2() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?>></td>
                <td class="field field_text"><?= $lang["client_del_address3"] ?></td>
                <td class="value value_text"><input type="text" name="del_address_3"
                                                    value="<?= $obj->get_del_address_3() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?>></td>
            </tr>
            <tr height="20">
                <td class="field field_text"><?= $lang["client_del_city"] ?></td>
                <td class="value value_text"><input type="text" name="city" value="<?= $obj->get_del_city() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?> ></td>
                <td class="field field_text"><?= $lang["client_del_state"] ?></td>
                <td class="value value_text"><input type="text" name="state" value="<?= $obj->get_del_state() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?> ></td>
            </tr>
            <tr height="20">
                <td class="field field_text"><?= $lang["client_del_country_id"] ?></td>
                <td class="value value_text"><input type="text" name="country_id"
                                                    value="<?= $obj->get_del_country_id() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?> notEmpty></td>
                <td class="field field_text"><?= $lang["client_del_postcode"] ?></td>
                <td class="value value_text"><input type="text" name="postcode" value="<?= $obj->get_del_postcode() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?> notEmpty></td>
            </tr>
            <tr height="20">
                <td class="field field_text"><?= $lang["client_telephone"] ?></td>
                <td class="value value_text"><input type="text" name="tel" value="<?= $obj->get_tel_3() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?>></td>
                <td class="field field_text"><?= $lang["client_mobile"] ?></td>
                <td class="value value_text"><input type="text" name="mobile" value="<?= $obj->get_mobile() ?>"
                                                    class="input" <?= $edit ? "" : "READONLY" ?>></td>
            </tr>
            <tr height="20">
                <td class="field field_text"><?= $lang["client_subscriber"] ?></td>
                <td class="value value_text"><?= $ar_bool[$obj->get_subscriber()] ?></td>
                <td class="field field_text"><?= $lang["client_party_subscriber"] ?></td>
                <td class="value value_text"><?= $ar_bool[$obj->get_party_subscriber()] ?></td>
            </tr>

            <?php
            if ($edit)
            {
            ?>
            <tr bgcolor="#000033" height="20">
                <td colspan="4" align="right" style="padding-right:20px;"><input type="button"
                                                                                 value="<?= $lang["update"] ?>"
                                                                                 onClick="if(CheckForm(this.form)) this.form.submit();">
                </td>
            </tr>
            <input name="edit" value="<?= $edit ?>" type="hidden">
        </form>
    <?php
    }
    ?>
    </table>
</div>
<?= $notice["js"] ?>
</body>
</html>
