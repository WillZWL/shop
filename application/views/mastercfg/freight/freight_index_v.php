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
        <? include("freight_header_button_v.php"); ?>
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
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
        <col width="20">
        <col>
        <col width="100">
        <col width="100">
        <col width="120">
        <tr class="add_header">
            <td height="20"></td>
            <td><?= $lang["freight_cat"] ?></td>
            <td><?= $lang["weight"] ?></td>
            <td><?= $lang["declared_pcent"] ?></td>
            <td></td>
        </tr>
        <form name="fm_add" action="<?= base_url() ?>mastercfg/freight/add/?<?= $_SERVER['QUERY_STRING'] ?>"
              method="post" onSubmit="return CheckForm(this)">
            <tr class="add_row">
                <td>&nbsp;</td>
                <?php
                if ($cmd == "add") {
                    ?>
                    <td><input name="name" class="input" value="<?= $this->input->post("name") ?>" notEmpty maxLen=64>
                    </td>
                    <td><input name="weight" class="int_input" value="<?= $this->input->post("weight") ?>" notEmpty
                               isNumber min=0> kg
                    </td>
                    <td><input name="declared_pcent" class="int_input"
                               value="<?= $this->input->post("declared_pcent") ?>" isNumber min=0> %
                    </td>
                <?php
                } else {
                    ?>
                    <td><input name="name" class="input" notEmpty maxLen=64></td>
                    <td><input name="weight" class="int_input" notEmpty isNumber min=0> kg</td>
                    <td><input name="declared_pcent" class="int_input" isNumber min=0> %</td>
                <?php
                }
                ?>
                <td align="center"><input type="submit" value="<?= $lang["add"] ?>"></td>
            </tr>
            <tr class="empty_row">
                <td colspan="6">
                    <hr></hr>
                </td>
            </tr>
            <input type="hidden" name="posted" value="1">
            <input type="hidden" name="cmd" value="add">
            <input type="hidden" name="cat_type" value="<?= $cat_type ?>">
        </form>
        <form name="fm" method="get" onSubmit="return CheckForm(this)">
            <tr class="header">
                <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                     onClick="Expand(document.getElementById('tr_search'));"></td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'name', '<?= $xsort["name"] ?>')"><?= $lang["freight_cat"] ?> <?= $sortimg["name"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'weight', '<?= $xsort["weight"] ?>')"><?= $lang["weight"] ?> <?= $sortimg["weight"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'declared_pcent', '<?= $xsort["declared_pcent"] ?>')"><?= $lang["declared_pcent"] ?> <?= $sortimg["declared_pcent"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="name" class="input" value="<?= htmlspecialchars($this->input->get("name")) ?>"></td>
                <td><input name="weight" class="int_input" value="<?= htmlspecialchars($this->input->get("weight")) ?>"
                           isNumber> kg
                </td>
                <td><input name="declared_pcent" class="int_input"
                           value="<?= htmlspecialchars($this->input->get("declared_pcent")) ?>" isNumber> %
                </td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
            <input type="hidden" name="cat_type" value="<?= $cat_type ?>">
        </form>
        <?php
        $i = 0;
        if (!empty($objlist)) {
            foreach ($objlist as $obj) {
                $is_edit = ($cmd == "edit" && $cat_id == $obj->get_id());
                ?>

                <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                    onMouseOut="RemoveClassName(this, 'highlight')" <?if (!($is_edit)){
                ?>onClick="Redirect('<?= site_url('mastercfg/freight/index/freight/' . $obj->get_id()) ?>/?<?= $_SERVER['QUERY_STRING'] ?>')"<?
                }?>>
                    <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                         title='<?= $lang["create_on"] ?>:<?= $obj->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->get_modify_by() ?>'>
                    </td>
                    <?php
                    if ($is_edit) {
                        ?>
                        <form name="fm_edit"
                              action="<?= base_url() ?>mastercfg/freight/edit/<?= $obj->get_id() ?>/?<?= $_SERVER['QUERY_STRING'] ?>"
                              method="post" onSubmit="return CheckForm(this)">
                            <input type="hidden" name="posted" value="1">
                            <input type="hidden" name="cmd" value="edit">
                            <input type="hidden" name="id" value="<?= $obj->get_id() ?>">
                            <input type="hidden" name="cat_type" value="<?= $cat_type ?>">
                            <?php
                            if ($this->input->post("posted")) {
                                ?>
                                <td><input name="name" class="input" value="<?= $this->input->post("name") ?>" notEmpty
                                           maxLen=64></td>
                                <td><input name="weight" class="int_input" value="<?= $this->input->post("weight") ?>"
                                           notEmpty isNumber min=0> kg
                                </td>
                                <td><input name="declared_pcent" class="int_input"
                                           value="<?= $this->input->post("declared_pcent") ?>" isNumber min=0> %
                                </td>
                            <?php
                            } else {
                                ?>
                                <td><input name="name" class="input" value="<?= $obj->get_name() ?>" notEmpty maxLen=64>
                                </td>
                                <td><input name="weight" class="int_input" value="<?= $obj->get_weight() ?>" notEmpty
                                           isNumber min=0> kg
                                </td>
                                <td><input name="declared_pcent" class="int_input"
                                           value="<?= $obj->get_declared_pcent() ?>" isNumber min=0> %
                                </td>
                            <?php
                            }
                            ?>
                            <td align="center"><input type="submit" value="<?= $lang["update"] ?>"> &nbsp; <input
                                    type="button" value="<?= $lang["back"] ?>"
                                    onClick="Redirect('<?= site_url('mastercfg/freight/') ?>?<?= $_SERVER['QUERY_STRING'] ?>')">
                            </td>
                        </form>
                    <?php
                    } else {
                        ?>
                        <td><?= $obj->get_name() ?></td>
                        <td><?= $obj->get_weight() ?> kg</td>
                        <td><?= $obj->get_declared_pcent() ?> %</td>
                        <td>&nbsp;</td>
                    <?php
                    }
                    ?>
                </tr>
                <?php
                $i++;
            }
        }
        ?>
    </table>
    <?= $this->pagination_service->create_links_with_style() ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>