<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <link rel="stylesheet" href="<?= base_url() ?>css/bootstrap.min.css" type="text/css" media="all" />
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <?php
    $ar_status = [$lang["inactive"], $lang["active"]];
    $ar_fcid = ["US_FC" => $lang["us_fc"], "UK_FC" => $lang["uk_fc"], "HK_FC" => $lang["hk_fc"]];
    $ar_allow_sell = [$lang["not_allow_sell"], $lang["allow_sell_to"]];

    ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" style="background:#286512"><input type="button" value="<?= $lang["list_button"] ?>"
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
            <tr class="header">
                <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                     onClick="Expand(document.getElementById('tr_search'));"></td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'id', '<?= @$xsort["id"] ?>')"><?= $lang["id"] ?> <?= @$sortimg["id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'id_3_digit', '<?= @$xsort["id_3_digit"] ?>')"><?= $lang["id_3_digit"] ?> <?= @$sortimg["id_3_digit"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'name', '<?= @$xsort["name"] ?>')"><?= $lang["name"] ?> <?= @$sortimg["name"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'status', '<?= @$xsort["status"] ?>')"><?= $lang["status"] ?> <?= @$sortimg["status"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'currency_id', '<?= @$xsort["currency_id"] ?>')"><?= $lang["currency_id"] ?> <?= @$sortimg["currency_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'language_id', '<?= @$xsort["language_id"] ?>')"><?= $lang["language_id"] ?> <?= @$sortimg["language_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'allow_sell', '<?= @$xsort["allow_sell"] ?>')"><?= $lang["allow_sell"] ?> <?= @$sortimg["allow_sell"] ?></a>
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
                    if ($this->input->get("status") !== FALSE) :
                        $selected[$this->input->get("status")] = " SELECTED";
                    endif;
                    ?>
                    <select name="status" class="input">
                        <option value="">
                        <option value="1"<?= !empty($selected[1]) ? $selected[1] : '' ?>><?= $lang["active"] ?>
                        <option value="0"<?= !empty($selected[0]) ? $selected[0] : '' ?>><?= $lang["inactive"] ?>
                    </select>
                </td>
                <td>
                    <select name="currency_id" class="input">
                        <option value=""></option>
                        <?php
                        foreach ($ar_currency as $key => $cur) :
                            ?>
                            <option value="<?= $key ?>"<?= $this->input->get("currency_id") == $key ? ' SELECTED' : '' ?>><?= $cur ?></option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </td>
                <td>
                    <select name="language_id" class="input">
                        <option value=""></option>
                        <?php
                        foreach ($ar_lang as $k => $l) {
                            ?>
                            <option value="<?= $k ?>"<?= $this->input->get("language_id") == $k ? ' SELECTED' : '' ?>><?= $l ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <?php
                    if ($this->input->get("allow_sell") !== FALSE) :
                        $selected[$this->input->get("allow_sell")] = " SELECTED";
                    endif;
                    ?>
                    <select name="allow_sell" class="input">
                        <option value="">
                        <option value="1"<?= !empty($selected[1]) ? $selected[1] : '' ?>><?= $lang["allow_sell_to"] ?>
                        <option value="0"<?= !empty($selected[0]) ? $selected[0] : '' ?>><?= $lang["not_allow_sell"] ?>
                    </select>
                </td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <?php
            $i = 0;
            if ($clist) :
                foreach ($clist as $cobj) :
                    ?>

                    <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')"
                        onClick="Redirect('<?= site_url('mastercfg/country/view/' . $cobj->getCountryId()) ?>')">
                        <td height="20"><img src="<?= base_url() ?>images/info.gif" title='<?= $lang["create_on"] ?>:<?= $cobj->getCreateOn() ?>&#13;<?= $lang["create_at"] ?>:<?= $cobj->getCreateAt() ?>&#13;<?= $lang["create_by"] ?>:<?= $cobj->getCreateBy() ?>&#13;<?= $lang["modify_on"] ?>:<?= $cobj->getModifyOn() ?>&#13;<?= $lang["modify_at"] ?>:<?= $cobj->getModifyAt() ?>&#13;<?= $lang["modify_by"] ?>:<?= $cobj->getModifyBy() ?>'>
                        </td>
                        <td><?= $cobj->getCountryId() ?></td>
                        <td><?= $cobj->getId3Digit() ?></td>
                        <td><?= $cobj->getName() ?></td>
                        <td><?= $ar_status[$cobj->getStatus()] ?></td>
                        <td><?= $ar_currency[$cobj->getCurrencyId()] ?></td>
                        <td><?= $ar_lang[$cobj->getLanguageId()] ?></td>
                        <td><?= $ar_allow_sell[$cobj->getAllowSell()] ?></td>
                        <td></td>
                    </tr>
                    <?php
                    $i++;
                endforeach;
            endif;
            ?>
        </table>
        <input type="hidden" name="showall" value='<?= $this->input->get("showall") ?>'>
        <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
        <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
    </form>
    <?= $links ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>