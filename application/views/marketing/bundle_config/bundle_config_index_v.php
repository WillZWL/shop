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
            <td width="400" align="right" class="title"><input type="button" value="<?= $lang["list_button"] ?>"
                                                               class="button"
                                                               onclick="Redirect('<?= base_url() ?>marketing/bundleConfig/') ?>')">
                &nbsp; <input type="button" value="<?= $lang["add_button"] ?>" class="button"
                              onclick="Redirect('<?= base_url() ?>marketing/bundleConfig/add/')"></td>
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
    <form name="fm" method="get" width="100%">
        <table border="0" cellpadding="0" cellspacing="0" bgcolor="#000000" width="100%" class="tb_list">
            <col width="20">
            <col width="100">
            <col width="100">
            <col width="100">
            <col width="100">
            <col width="26">
            <tr class="header">
                <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                     onClick="Expand(document.getElementById('tr_search'));"></td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'country_id', '<?= @$xsort["country_id"] ?>')"><?= $lang["country_id"] ?> <?= @$sortimg["country_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'discount_1_item', '<?= @$xsort["discount_1_item"] ?>')"><?= $lang["discount_1_item"] ?> <?= @$sortimg["discount_1_item"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'discount_2_item', '<?= @$xsort["discount_2_item"] ?>')"><?= $lang["discount_2_item"] ?> <?= @$sortimg["discount_2_item"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'discount_3_more_item', '<?= @$xsort["discount_3_more_item"] ?>')"><?= $lang["discount_3_more_item"] ?> <?= @$sortimg["discount_3_more_item"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td>
                    <select type="text" name="country_id" class="input" >
                        <?php
                        foreach ($country_list as $country_obj) :
                            ?>
                            <option value="<?= $country_obj->getCountryId() ?>" <?= ($this->input->get("country_id") == $country_obj->getCountryId() ? "SELECTED" : "") ?>>
                            <?= $country_obj->getCountryId() . " - " . $country_obj->getName() ?>
                            </option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </td>
                <td></td>
				<td></td>
                <td></td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <?php
            $i = 0;
            if ($bundleconfiglist) :
                foreach ($bundleconfiglist as $bundleconfig) :
                    ?>

                    <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')"
                        onClick="Redirect('<?= base_url() ?>marketing/bundleConfig/view/<?= $bundleconfig->getId() ?>')">
                        <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                             title='<?= $lang["create_on"] ?>:<?= $bundleconfig->getCreateOn() ?>&#13;<?= $lang["create_at"] ?>:<?= $bundleconfig->getCreateAt() ?>&#13;<?= $lang["create_by"] ?>:<?= $bundleconfig->getCreateBy() ?>&#13;<?= $lang["modify_on"] ?>:<?= $bundleconfig->getModifyOn() ?>&#13;<?= $lang["modify_at"] ?>:<?= $bundleconfig->getModifyAt() ?>&#13;<?= $lang["modify_by"] ?>:<?= $bundleconfig->getModifyBy() ?>'>
                        </td>
                        <td><?= $bundleconfig->getCountryId() ?></td>
                        <td><?= $bundleconfig->getDiscount1Item() ?></td>
                        <td><?= $bundleconfig->getDiscount2Item() ?></td>
                        <td><?= $bundleconfig->getDiscount3MoreItem() ?></td>

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