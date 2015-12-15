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
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" style="background:#286512"><input type="button" value="<?= $lang["default_mapping"] ?>"
                                                               class="button"
                                                               onclick="Redirect('<?= site_url('mastercfg/custom_class/sub_cat/' . $country_id . '/') ?>')"><input
                    type="button" value="<?= $lang["edit_specific_sku"] ?>" class="button"
                    onclick="Redirect('<?= site_url('mastercfg/custom_class/sku/' . $country_id . '/') ?>')"><input
                    type="button" value="<?= $lang["list_button"] ?>" class="button"
                    onclick="Redirect('<?= site_url('mastercfg/custom_class/index/' . $country_id . '/') ?>')"></td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?><br>
                <?= $lang["country"] ?>:
                <select name="country_id"
                        onChange="Redirect('<?= base_url() ?>mastercfg/custom_class/sku/'+this.value)">
                    <option value="">
                        <?php
                        if ($countrylist) :
                        $selected[$country_id] = "SELECTED";
                        foreach ($countrylist as $country) :
                        ?>
                    <option
                        value="<?= $country->getCountryId() ?>" <?= @$selected[$country->getCountryId()] ?>><?= $country->getName() ?>
                        <?php
                        endforeach;
                        endif;
                        ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
    if ($country_id) :
    ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
        <col width="20">
        <col width="120">
        <col width="180">
        <col width="200">
        <col width="150">
        <col>
        <col width="60">
        <col width="100">
        <form name="fm" method="get" onSubmit="return CheckForm(this)">
            <tr class="header">
                <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                     onClick="Expand(document.getElementById('tr_search'));"></td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'sku', '<?= @$xsort["sku"] ?>')"><?= $lang["sku"] ?> <?= @$sortimg["sku"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'prod_name', '<?= @$xsort["prod_name"] ?>')"><?= $lang["prod_name"] ?> <?= @$sortimg["prod_name"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'sub_cat_name', '<?= @$xsort["sub_cat_name"] ?>')"><?= $lang["sub_cat_name"] ?> <?= @$sortimg["sub_cat_name"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'code', '<?= @$xsort["code"] ?>')"><?= $lang["code"] ?> <?= @$sortimg["code"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'description', '<?= @$xsort["description"] ?>')"><?= $lang["description"] ?> <?= @$sortimg["description"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'duty_pcent', '<?= @$xsort["duty_pcent"] ?>')"><?= $lang["duty_pcent"] ?> <?= @$sortimg["duty_pcent"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="sku" class="input" value="<?= htmlspecialchars($this->input->get("sku")) ?>"></td>
                <td><input name="prod_name" class="input"
                           value="<?= htmlspecialchars($this->input->get("prod_name")) ?>"></td>
                <td><input name="sub_cat_name" class="input"
                           value="<?= htmlspecialchars($this->input->get("sub_cat_name")) ?>"></td>
                <td><input name="code" class="input" value="<?= htmlspecialchars($this->input->get("code")) ?>"></td>
                <td><input name="description" class="input"
                           value="<?= htmlspecialchars($this->input->get("description")) ?>"></td>
                <td><input name="duty_pcent" class="input"
                           value="<?= htmlspecialchars($this->input->get("duty_pcent")) ?>"></td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
        </form>
        <?php
        $i = 0;
        if (!empty($pcclist)) {
            foreach ($pcclist as $pcc) :
                $is_edit = ($cmd == "edit" && $sku == $pcc->getSku());
                ?>

                <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')" <?=(!($is_edit)) ? "onClick=Redirect('". site_url('mastercfg/custom_class/sku/' . $country_id ."/".$pcc->getSku())."/?".$_SERVER['QUERY_STRING']."')" : ""?> onMouseOut="RemoveClassName(this, 'highlight')">
                    <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                         title='<?= $lang["create_on"] ?>:<?= $pcc->getCreateOn() ?>&#13;<?= $lang["create_at"] ?>:<?= $pcc->getCreateAt() ?>&#13;<?= $lang["create_by"] ?>:<?= $pcc->getCreateBy() ?>&#13;<?= $lang["modify_on"] ?>:<?= $pcc->getModifyOn() ?>&#13;<?= $lang["modify_at"] ?>:<?= $pcc->getModifyAt() ?>&#13;<?= $lang["modify_by"] ?>:<?= $pcc->getModifyBy() ?>'>
                    </td>
                    <td><?= $pcc->getSku() ?></td>
                    <td><?= $pcc->getProdName() ?></td>
                    <td><?= $pcc->getSubCatName() ?></td>
                    <?php
                    if ($is_edit) :
                        ?>
                        <form name="fm_edit"
                              action="<?= base_url() ?>mastercfg/custom_class/edit_sku/<?= $pcc->getSku() ?>/?<?= $_SERVER['QUERY_STRING'] ?>"
                              method="post" onSubmit="return CheckForm(this)">
                            <input type="hidden" name="posted" value="1">
                            <input type="hidden" name="cmd" value="edit">
                            <input type="hidden" name="sku" value="<?= $pcc->getSku() ?>">
                            <input type="hidden" name="country_id" value="<?= $country_id ?>">
                            <?php
                            if ($this->input->post("posted")) :
                                ?>
                                <td><input name="code" class="input" value="<?= $this->input->post("code") ?>" notEmpty
                                           maxLen=20></td>
                                <td><input name="description" class="input"
                                           value="<?= $this->input->post("description") ?>" maxLen=255></td>
                                <td><input name="duty_pcent" class="input"
                                           value="<?= $this->input->post("duty_pcent") ?>" notEmpty isNumber min=0></td>
                            <?php
                            else :
                                ?>
                                <td><input name="code" class="input" value="<?= $pcc->getCode() ?>" notEmpty maxLen=20>
                                </td>
                                <td><input name="description" class="input" value="<?= $pcc->getDescription() ?>"
                                           maxLen=255></td>
                                <td><input name="duty_pcent" class="input" value="<?= $pcc->getDutyPcent() ?>"
                                           notEmpty isNumber min=0></td>
                            <?php
                            endif;
                            ?>
                            <td align="center">
                                <input type="submit" value="<?= $lang["update"] ?>"> &nbsp;
                                <input type="button" value="<?= $lang["back"] ?>" onClick="Redirect('<?= site_url('mastercfg/custom_class/sku/' . $country_id) ?>?<?= $_SERVER['QUERY_STRING'] ?>')">
                            </td>
                        </form>
                    <?php
                    else :
                        ?>
                        <td><?= $pcc->getCode() ?></td>
                        <td><?= $pcc->getDescription() ?></td>
                        <td><?= $pcc->getDutyPcent() ?></td>
                        <td>&nbsp;</td>
                    <?php
                    endif;
                    ?>
                </tr>
                <?php
                $i++;
            endforeach;
        }
        ?>
        <?php
        endif;
        ?>
    </table>
    <?= $links ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>