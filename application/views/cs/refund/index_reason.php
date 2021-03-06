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
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
        <col width="20">
        <col width="200">
        <col>
        <col width="120">
        <tr class="add_header">
            <td height="20"></td>
            <td><?= $lang["reason_category"] ?></td>
            <td><?= $lang["reason_description"] ?></td>
            <td></td>
        </tr>
        <form name="fm_add" action="<?= base_url() ?>cs/refund/reason/?<?= $_SERVER['QUERY_STRING'] ?>" method="post"
              onSubmit="return CheckForm(this)">
            <tr class="add_row">
                <td>&nbsp;</td>
                <?php
                if ($action == "add") :
                    ?>
                    <td><select name="r_cat" class="input">
                            <?php
                            foreach ($lang["category"] as $key => $value) :
                                ?>
                                <option
                                    value="<?= $key ?>" <?= $this->input->post('r_cat') == $key ? "SELECTED" : "" ?>><?= $value ?></option>
                            <?php
                            endforeach;
                            ?>
                        </select></td>
                    <td><input name="r_desc" class="input" value="<?= $this->input->post("r_desc") ?>" notEmpty
                               maxLen=255></td>
                <?php
                else :
                    ?>
                    <td><select name="r_cat" class="input">
                            <?php
                            foreach ($lang["category"] as $key => $value) :
                                ?>
                                <option value="<?= $key ?>"><?= $value ?></option>
                            <?php
                            endforeach;
                            ?>
                        </select></td>
                    <td><input name="r_desc" class="input" notEmpty maxLen=255></td>
                <?php
                endif;
                ?>
                <td align="center"><input type="submit" value="<?= $lang["add"] ?>"></td>
            </tr>
            <tr class="empty_row">
                <td colspan="6">
                    <hr></hr>
                </td>
            </tr>
            <input type="hidden" name="posted" value="1">
            <input type="hidden" name="action" value="add">
        </form>
        <form name="fm" method="get" onSubmit="return CheckForm(this)">
            <tr class="header">
                <td height="20">
                    <img src="<?= base_url() ?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));">
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'reason_cat', '<?= $xsort["reason_cat"] ?>')"><?= $lang["reason_category"] ?> <?= $sortimg["reason_cat"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'description', '<?= $xsort["description"] ?>')"><?= $lang["reason_description"] ?> <?= $sortimg["description"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><select name="cat" class="input">
                        <option value=""></option>
                        <?php
                        foreach ($lang["category"] as $key => $value) :
                            ?>
                            <option
                                value="<?= $key ?>" <?= $this->input->get('cat') == $key ? "SELECTED" : "" ?>><?= $value ?></option>
                        <?php
                        endforeach;
                        ?>
                    </select></td>
                <td><input name="desc" class="input" value="<?= htmlspecialchars($this->input->get("desc")) ?>"></td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
        </form>
        <?php
        $i = 0;
        if (!empty($reason_list)) :
            foreach ($reason_list as $obj) :
                $is_edit = ($action == "edit" && $eid == $obj->getId());
                ?>

                <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                    onMouseOut="RemoveClassName(this, 'highlight')"
                <?php
                    if (!($is_edit)):
                ?>onClick="Redirect('<?= site_url('cs/refund/reason/' . $obj->getId()) ?>/?<?= $_SERVER['QUERY_STRING'] ?>')"
                <?php
                    endif;
                ?>>
                    <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                         title='<?= $lang["create_on"] ?>:<?= $obj->getCreateOn() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->getCreateAt() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->getCreateBy() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->getModifyOn() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->getModifyAt() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->getModifyBy() ?>'>
                    </td>
                    <?php
                    if ($is_edit) :
                        ?>
                        <form name="fm_edit" action="<?= base_url() ?>cs/refund/reason/<?= $offset ?>/?<?= $_SERVER['QUERY_STRING'] ?>"
                              method="post" onSubmit="return CheckForm(this)">
                            <input type="hidden" name="posted" value="1">
                            <input type="hidden" id="action" name="action" value="edit">
                            <input type="hidden" name="id" value="<?= $obj->getId() ?>">
                            <?php
                            if ($this->input->post("posted")) :
                                ?>
                                <td><select name="ecat" class="input">
                                        <?php
                                        foreach ($lang["category"] as $key => $value) :
                                            ?>
                                            <option
                                                value="<?= $key ?>" <?= $this->input->post('ecat') == $key ? "SELECTED" : "" ?>><?= $value ?></option>
                                        <?php
                                        endforeach;
                                        ?>
                                    </select></td>
                                <td><input name="edesc" class="input" value="<?= $this->input->post("edesc") ?>"
                                           notEmpty maxLen=255></td>
                            <?php
                            else :
                                ?>
                                <td><select name="ecat" class="input">
                                        <?php
                                        foreach ($lang["category"] as $key => $value) :
                                            ?>
                                            <option
                                                value="<?= $key ?>" <?= $this->input->post('ecat') == $key ? "SELECTED" : "" ?>><?= $value ?></option>
                                        <?php
                                        endforeach;
                                        ?>
                                    </select></td>
                                <td><input name="edesc" class="input" value="<?= $obj->getDescription() ?>" notEmpty
                                           maxLen=255></td>
                            <?php
                            endif;
                            if (!check_app_feature_access_right($app_id, "CS000201_delete_btn")) :
                                $disabled_button = "disabled";
                            endif;
                            ?>
                            <td align="center"><input type="submit"
                                                      value="<?= $lang["update"] ?>"><input <?= $disabled_button ?>
                                    onclick="document.getElementById('action').value = 'delete';" type="submit"
                                    value="<?= $lang["delete"] ?>"> &nbsp; <input type="button"
                                                                                  value="<?= $lang["back"] ?>"
                                                                                  onClick="Redirect('<?= site_url('cs/refund/reason/'.$offset.'/') ?>?<?= $_SERVER['QUERY_STRING'] ?>')">
                            </td>
                        </form>
                    <?php
                    else :
                        ?>
                        <td><?= $lang["category"][$obj->getReasonCat()] ?></td>
                        <td><?= $obj->getDescription() ?></td>
                        <td>&nbsp;</td>
                    <?php
                    endif;
                    ?>
                </tr>
                <?php
                $i++;
            endforeach;
        endif;
        ?>
        <tr class="header">
            <td></td>
            <td colspan="3"><input type="button" onClick="Redirect('<?= base_url() ?>cs/refund/');"
                                   value="<?= $lang["back_to_main"] ?>"></td>
        </tr>
    </table>
    <?= $links ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>