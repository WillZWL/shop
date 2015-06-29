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
    $ar_fcid = array("US_FC" => $lang["us_fc"], "UK_FC" => $lang["uk_fc"], "HK_FC" => $lang["hk_fc"]);
    $ar_allow_sell = array($lang["not_allow_sell"], $lang["allow_sell_to"]);

    ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"><input type="button" value="<?= $lang["list_button"] ?>"
                                                               class="button"
                                                               onclick="Redirect('<?= site_url('mastercfg/country/') ?>')">
                &nbsp;</td>
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
    <form name="fm" method="get">
        <table border="0" cellpadding="0" cellspacing="0" bgcolor="#000000" width="100%" class="tb_list">
            <col width="20">
            <col width="50">
            <col width="60">
            <col>
            <col width="70">
            <col width="100">
            <col width="70">
            <col width="70">
            <col width="70">
            <col width="26">
            <tr class="header">
                <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                     onClick="Expand(document.getElementById('tr_search'));"></td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'id', '<?= $xsort["id"] ?>')"><?= $lang["id"] ?> <?= $sortimg["id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'id_3_digit', '<?= $xsort["id_3_digit"] ?>')"><?= $lang["id_3_digit"] ?> <?= $sortimg["id_3_digit"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'name', '<?= $xsort["name"] ?>')"><?= $lang["name"] ?> <?= $sortimg["name"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'status', '<?= $xsort["status"] ?>')"><?= $lang["status"] ?> <?= $sortimg["status"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'currency_id', '<?= $xsort["currency_id"] ?>')"><?= $lang["currency_id"] ?> <?= $sortimg["currency_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'language_id', '<?= $xsort["language_id"] ?>')"><?= $lang["language_id"] ?> <?= $sortimg["language_id"] ?></a>
                </td>
                <!--<td><a href="#" onClick="SortCol(document.fm, 'fc_id', '<?= $xsort["fc_id"] ?>')"><?= $lang["fc_id"] ?> <?= $sortimg["fc_id"] ?></a></td>-->
                <td><a href="#"
                       onClick="SortCol(document.fm, 'rma_fc', '<?= $xsort["fc_id"] ?>')"><?= $lang["rma_fc"] ?> <?= $sortimg["rma_fc"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'allow_sell', '<?= $xsort["allow_sell"] ?>')"><?= $lang["allow_sell"] ?> <?= $sortimg["allow_sell"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="id" class="input" value="<?= htmlspecialchars($this->input->get("id")) ?>"
                           maxlength="2"></td>
                <td><input name="id_3_digit" class="input"
                           value="<?= htmlspecialchars($this->input->get("id_3_digit")) ?>" maxlength="3"></td>
                <td><input name="name" class="input" value="<?= htmlspecialchars($this->input->get("name")) ?>"></td>
                <td>
                    <?php
                    if ($this->input->get("status") !== FALSE) {
                        $selected[$this->input->get("status")] = " SELECTED";
                    }
                    ?>
                    <select name="status" class="input">
                        <option value="">
                        <option value="1"<?= $selected[1] ?>><?= $lang["active"] ?>
                        <option value="0"<?= $selected[0] ?>><?= $lang["inactive"] ?>
                    </select>
                </td>
                <td>
                    <?php
                    if ($this->input->get("currency_id") !== FALSE) {
                        $selected[$this->input->get("currency_id")] = " SELECTED";
                    }
                    ?>
                    <select name="currency_id" class="input">
                        <option value=""></option>
                        <?php
                        foreach ($ar_currency as $key => $cur) {
                            ?>
                            <option value="<?= $key ?>"<?= $selected[$key] ?>><?= $cur ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <?php
                    if ($this->input->get("language_id") !== FALSE) {
                        $selected[$this->input->get("language_id")] = " SELECTED";
                    }
                    ?>
                    <select name="language_id" class="input">
                        <option value=""></option>
                        <?php
                        foreach ($ar_lang as $k => $l) {
                            ?>
                            <option value="<?= $k ?>"<?= $selected[$k] ?>><?= $l ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <!--
        <td>
            <?php
                if ($this->input->get("fc_id") !== FALSE) {
                    $selected[$this->input->get("fc_id")] = " SELECTED";
                }
                ?>
            <select name="fc_id" class="input">
                <option value=""></option>
            <?php
                foreach ($ar_fcid as $key => $fcname) {
                    ?>
                <option value="<?= $key ?>"<?= $selected[$key] ?>><?= $fcname ?></option>
            <?php
                }
                ?>
            </select>
        </td>
    -->
                <td>
                    <?php
                    unset($selected);
                    if ($this->input->get("rma_fc") !== FALSE) {
                        $selected[$this->input->get("rma_fc")] = " SELECTED";
                    }
                    ?>
                    <select name="rma_fc" class="input">
                        <option value=""></option>
                        <?php
                        foreach ($ar_fcid as $key => $fcname) {
                            ?>
                            <option value="<?= $key ?>"<?= $selected[$key] ?>><?= $fcname ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <?php
                    if ($this->input->get("allow_sell") !== FALSE) {
                        $selected[$this->input->get("allow_sell")] = " SELECTED";
                    }
                    ?>
                    <select name="allow_sell" class="input">
                        <option value="">
                        <option value="1"<?= $selected[1] ?>><?= $lang["allow_sell_to"] ?>
                        <option value="0"<?= $selected[0] ?>><?= $lang["not_allow_sell"] ?>
                    </select>
                </td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <?php
            $i = 0;
            if ($clist) {
                foreach ($clist as $cobj) {
                    ?>

                    <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')"
                        onClick="Redirect('<?= site_url('mastercfg/country/view/' . $cobj->get_id()) ?>')">
                        <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                             title='<?= $lang["create_on"] ?>:<?= $cobj->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $cobj->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $cobj->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $cobj->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $cobj->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $cobj->get_modify_by() ?>'>
                        </td>
                        <td><?= $cobj->get_id() ?></td>
                        <td><?= $cobj->get_id_3_digit() ?></td>
                        <td><?= $cobj->get_name() ?></td>
                        <td><?= $ar_status[$cobj->get_status()] ?></td>
                        <td><?= $ar_currency[$cobj->get_currency_id()] ?></td>
                        <td><?= $ar_lang[$cobj->get_language_id()] ?></td>
                        <!--<td><?= $ar_fcid[$cobj->get_fc_id()] ?></td>-->
                        <td><?= $ar_fcid[$cobj->get_rma_fc()] ?></td>
                        <td><?= $ar_allow_sell[$cobj->get_allow_sell()] ?></td>
                        <td></td>
                    </tr>
                    <?php
                    $i++;
                }
            }
            ?>
        </table>
        <input type="hidden" name="showall" value='<?= $this->input->get("showall") ?>'>
        <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
        <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
    </form>
    <?= $this->pagination_service->create_links_with_style() ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>