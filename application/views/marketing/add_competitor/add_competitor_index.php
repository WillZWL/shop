<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
</head>
<body>
<?= $notice["img"] ?>
<?php $status_arr = array("1" => $lang["active"], "0" => $lang["inactive"]); ?>
<div id="main">
    <table cellpadding="0" cellspacing="0" width="100%" border="0">
        <tr>
            <td style="width:65%;" align="left" class="title" height="30">
                <b style="font-size: 16px; color: rgb(0, 0, 0);"><?= $lang["title"] ?></b>
            </td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033"></td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
        <tr height="70">
            <td align="left" style="padding-left:5px;"><b
                    style="font-size: 14px; color: rgb(0, 0, 0);"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?>
            </td>
        </tr>
    </table>

    <table border="0" cellpadding="0" cellspacing="0" height="70" class="tb_list" width="100%">
        <col width="30">
        <col width="50px">
        <col width="350">
        <col width="100">
        <col width="300">
        <tr class="add_header">
            <td>&nbsp;</td>
            <td><?= $lang["country"] ?></td>
            <td><?= $lang["competitor_name"] ?></td>
            <td><?= $lang["status"] ?></td>
            <td>&nbsp;</td>
        </tr>

        <form name="fm_add" id="fm_add" method="post" onSubmit="return CheckForm(this)">
            <tr class="add_row">
                <td>&nbsp;</td>
                <td style="width:80px">
                    <select name="country" id="country" notEmpty>
                        <option></option>
                        <?php
                        if ($country_list) {
                            $selected[$country_id] = " SELECTED";
                            foreach ($country_list as $obj) {
                                $id = $obj->get_id();
                                ?>
                                <option
                                    value="<?= $id ?>" <?= $selected[$id] ?>><?= $id . " - " . $obj->get_name(); ?></option>
                            <?php
                            }
                        }
                        ?>
                    </select>
                </td>
                <td><input name="name" id="name" class="input" value="" maxlength="255" maxLen="255" notEmpty></td>
                <td><select name="status" class="input" notEmpty>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </td>
                <td align="left" style="padding: 0 0 0 10px">
                    <input type="hidden" name="posted" value="1">
                    <input type="hidden" name="action" value="add_comp">
                    <input type="submit" value="Add new competitor">
                </td>
            </tr>
        </form>

        <tr class="empty_row">
            <td colspan="6">
                <hr></hr>
            </td>
        </tr>

        <form name="fm" method="get">
            <tr class="header">
                <td height="20"><img src="<?= base_url() . '/images/expand.png' ?>" class="pointer"
                                     onClick="Expand(document.getElementById('tr_search'));"></td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'country_id', '<?= $xsort["country_id"] ?>')"><?= $lang["country"] ?></a> <?= $sortimg["country_id"] ?>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'competitor_name', '<?= $xsort["competitor_name"] ?>')"><?= $lang["competitor_name"] ?></a> <?= $sortimg["competitor_name"] ?>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'status', '<?= $xsort["status"] ?>')"><?= $lang["status"] ?></a> <?= $sortimg["status"] ?>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search">
                <td></td>
                <td style="width:80px">
                    <select name="country_id" id="country_id">
                        <option></option>
                        <?php
                        if ($country_list) {
                            $country_id = $this->input->get('country_id');
                            $selected[$country_id] = " SELECTED";
                            foreach ($country_list as $obj) {
                                $id = $obj->get_id();
                                ?>
                                <option
                                    value="<?= $id ?>" <?= $selected[$id] ?>><?= $id . " - " . $obj->get_name(); ?></option>
                            <?php
                            }
                        }
                        ?>
                    </select>
                </td>
                <td><input name="name" class="input" value="<?= $this->input->get("competitor_name") ?>"></td>
                <td><select name="status" class="input">
                        <option value=""></option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </td>
                <td align="left" style="padding: 0 0 0 10px"><input type="submit" name="searchsubmit" value=""
                                                                    class="search_button"
                                                                    style="background: url('<?= base_url() . "images/find.gif" ?>') no-repeat;">
                </td>
            </tr>
            <input type="hidden" name="sort" value="<?= $this->input->get("sort") ?>">
            <input type="hidden" name="order" value="<?= $this->input->get("order") ?>">
        </form>
        <?php
        $i = 0;
        if ($list) {
            foreach ($list as $comp_obj) {
                if ($comp_obj->get_id() != $this->input->get("edit")) {
                    ?>
                    <tr class="row<?= $i++ % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')"
                        onClick='Redirect("<?= base_url() . "marketing/add_competitor/?" . $_SERVER["QUERY_STRING"] . "&edit=" . $comp_obj->get_id() ?>");'>
                        <td><img src="<?= base_url() ?>images/info.gif"
                                 title='<?= $lang["create_on"] ?>:<?= $comp_obj->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $comp_obj->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $comp_obj->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $comp_obj->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $comp_obj->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $comp_obj->get_modify_by() ?>'>
                        </td>
                        <td><?= $comp_obj->get_country_id() ?></td>
                        <td><?= $comp_obj->get_competitor_name() ?></td>
                        <td><?= $status_arr[$comp_obj->get_status()] ?></td>
                        <td></td>
                    </tr>
                <?php
                } else {
                    ?>
                    <form name="fm_edit" method="post" onClick="checkForm(this)">
                        <tr class="row<?= $i++ % 2 ?>">
                            <td><img src="<?= base_url() ?>images/info.gif"
                                     title='<?= $lang["create_on"] ?>:<?= $comp_obj->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $comp_obj->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $comp_obj->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $comp_obj->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $comp_obj->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $comp_obj->get_modify_by() ?>'>
                            </td>
                            <td>
                                <select name="country_id" id="country_id">
                                    <?php
                                    if ($country_list) {
                                        $country_id = $comp_obj->get_country_id();
                                        $selected[$country_id] = " SELECTED";
                                        foreach ($country_list as $country_obj) {
                                            $id = $country_obj->get_id();
                                            ?>
                                            <option
                                                value="<?= $id ?>" <?= $selected[$id] ?>><?= $id . " - " . $country_obj->get_name(); ?></option>
                                        <?php
                                        }
                                    }
                                    ?>
                                </select></td>
                            <td><input name="name" type="text" value="<?= $comp_obj->get_competitor_name() ?>"
                                       class="input" notEmpty></td>
                            <td><select name="status" class="input">
                                    <?php
                                    foreach ($status_arr as $key => $value) {
                                        ?>
                                        <option
                                            value="<?= $key ?>" <?= $key == $comp_obj->get_status() ? "SELECTED" : "" ?>><?= $value ?></option>
                                    <?php
                                    }
                                    ?>
                                </select></td>
                            <td>
                                <input type="button" value="<?= $lang["update"] ?>"
                                       onClick="if(CheckForm(this.form)) document.fm_edit.submit();" class="button">&nbsp;&nbsp;
                                <input type="button" value="<?= $lang["back"] ?>"
                                       onClick='Redirect("<?= base_url() . "marketing/add_competitor/?" ?>");'
                                       class="button">
                            </td>
                            <input name="posted" type="hidden" value="1">
                            <input name="action" type="hidden" value="edit">
                            <input name="comp_id" type="hidden" value="<?= $comp_obj->get_id() ?>">
                            <input name="old_country_id" type="hidden" value="<?= $comp_obj->get_country_id() ?>">
                        </tr>
                    </form>
                <?php
                }
            }
        }
        ?>

    </table>
    <?= $this->pagination_service->create_links_with_style() ?>
</div>


<?= $notice["js"] ?>
<?php

if ($prompt_notice) {
    ?>
    <script language="javascript">alert('<?=$lang["update_notice"]?>')</script>
<?php
}
?>

</body>
</html>