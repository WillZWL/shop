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
    <?= $notice["img"] ?>
    <?php
    $ar_status = array($lang["inactive"], $lang["active"]);
    ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"><input type="button" value="<?= $lang["language_button"] ?>"
                                                               class="button"
                                                               onclick="Redirect('<?= site_url('marketing/product_spec/language') ?>')">
            </td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="1" width="100%" class="tb_list">
        <col width="20">
        <col width="120">
        <col width="200">
        <col>
        <col width="100">
        <col width="100">
        <col width="130">
        <tr class="add_header">
            <td height="20"></td>
            <td><?= $lang["id"] ?></td>
            <td><?= $lang["prod_spec_group"] ?></td>
            <td><?= $lang["prod_spec_name"] ?></td>
            <td><?= $lang["unit_type"] ?></td>
            <td><?= $lang["status"] ?></td>
            <td></td>
        </tr>
        <form name="fm_add" action="<?= base_url() ?>marketing/product_spec/edit/" method="post"
              onSubmit="return CheckForm(this)">
            <tr class="add_row">
                <td></td>
                <td><input name="code" dname="Product Specification Name" class="input" notEmpty></td>
                <td>
                    <select name="psg_id" dname="Product Specification Group" class="input" notEmpty>
                        <option value=""></option>
                        <?php
                        if ($psg_list) {
                            foreach ($psg_list as $psg_obj) {
                                ?>
                                <option value="<?= $psg_obj->get_id() ?>"><?= $psg_obj->get_name() ?></option>
                            <?php
                            }
                        }
                        ?>
                    </select>
                </td>
                <td><input name="name" dname="Product Specification Name" class="input" notEmpty maxLen=64></td>
                <td>
                    <select name="unit_type_id" class="input" onChange="ChangeUnit(this.value, this.form.unit_id)"
                            notEmpty>
                        <option value=""></option>
                        <?php
                        if ($unit_type_array) {
                            foreach ($unit_type_array as $ut_id => $ut_name) {
                                ?>
                                <option value="<?= $ut_id ?>"><?= $ut_name ?></option>
                            <?php
                            }
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select name="status" class="input">
                        <option value="1"><?= $lang["active"] ?>
                        <option value="0"><?= $lang["inactive"] ?>
                    </select>
                </td>
                <td align="center"><input type="submit" value="<?= $lang["add"] ?>"></td>
            </tr>
            <tr class="empty_row">
                <td colspan="7">
                    <hr></hr>
                </td>
            </tr>
            <input type="hidden" name="posted" value="1">
            <input type="hidden" name="cmd" value="add">
        </form>

        <table border="0" cellpadding="0" cellspacing="1" height="20" class="tb_list" width="100%">
            <col width="20">
            <col width="120">
            <col width="200">
            <col>
            <col width="100">
            <col width="100">
            <col width="130">
            <?php
            foreach ($psg_list AS $psg_obj) {
                ?>
                <tr class="header">
                    <td width="150" height="20" colspan="7">&nbsp;&nbsp;<?= $psg_obj->get_name() ?></td>
                </tr>
                <?php
                if ($prod_spec_list[$psg_obj->get_id()]) {
                    ?>
                    <tr class="add_header">
                        <td></td>
                        <td><?= $lang["id"] ?></td>
                        <td><?= $lang["prod_spec_group"] ?></td>
                        <td><?= $lang["prod_spec_name"] ?></td>
                        <td><?= $lang["unit_type"] ?></td>
                        <td><?= $lang["status"] ?></td>
                        <td>&nbsp;&nbsp;</td>
                    </tr>
                    <?php
                    $i = 0;
                    foreach ($prod_spec_list[$psg_obj->get_id()] AS $ps_obj) {
                        $is_edit = ($cmd == "edit" && $ps_id == $ps_obj->get_id());
                        ?>
                        <form name="fm_edit" action="<?= base_url() ?>marketing/product_spec/edit/" method="post"
                              onSubmit="return CheckForm(this)">
                            <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                                onMouseOut="RemoveClassName(this, 'highlight')" <?php if (!($is_edit)){
                            ?>onClick="Redirect('<?= site_url('marketing/product_spec/index/' . $ps_obj->get_id()) ?>/')"<?php
                            }?>>
                                <?php
                                if ($is_edit) {
                                    ?>
                                    <td height="20"></td>
                                    <td>&nbsp;&nbsp;<?= $ps_obj->get_code() ?></td>
                                    <td>&nbsp;&nbsp;<?= $psg_obj->get_name() ?></td>
                                    <td><input name="name" class="input" value="<?= $ps_obj->get_name() ?>" notEmpty
                                               maxLen=64></td>
                                    <td>&nbsp;&nbsp;<?= $unit_type_array[$ps_obj->get_unit_type_id()] ?></td>
                                    <td>
                                        <select name="status" class="input">
                                            <?php
                                            $selected_s[$ps_obj->get_status()] = "SELECTED";
                                            foreach ($ar_status AS $rskey => $rsvalue)
                                            {
                                            ?>
                                            <option value="<?= $rskey ?>" <?= $selected_s[$rskey] ?>><?= $rsvalue ?>
                                                <?php
                                                }
                                                ?>
                                        </select>
                                    </td>
                                    <td align="center"><input type="submit" value="<?= $lang["update"] ?>"></td>
                                <?php
                                } else {
                                    ?>
                                    <td height="20"></td>
                                    <td>&nbsp;&nbsp;<?= $ps_obj->get_code() ?></td>
                                    <td>&nbsp;&nbsp;<?= $psg_obj->get_name() ?></td>
                                    <td>&nbsp;&nbsp;<?= $ps_obj->get_name() ?></td>
                                    <td>&nbsp;&nbsp;<?= $unit_type_array[$ps_obj->get_unit_type_id()] ?></td>
                                    <td>&nbsp;&nbsp;<?= $ar_status[$ps_obj->get_status()] ?></td>
                                    <td>&nbsp;&nbsp;</td>
                                <?php
                                }
                                ?>
                            </tr>
                            <input type="hidden" name="posted" value="1">
                            <input type="hidden" name="cmd" value="update">
                            <input type="hidden" name="ps_id" value="<?= $ps_obj->get_id() ?>">
                        </form>
                        <?php
                        $i++;
                    }
                }
            }
            ?>

        </table>
        <script language="javascript">
        </script>
        <?= $notice["js"] ?>
</body>
</html>