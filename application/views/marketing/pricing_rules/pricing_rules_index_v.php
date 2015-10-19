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
                                                               onclick="Redirect('<?= base_url() ?>marketing/pricingRules/') ?>')">
                &nbsp; <input type="button" value="<?= $lang["add_button"] ?>" class="button"
                              onclick="Redirect('<?= base_url() ?>marketing/pricingRules/add/')"></td>
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
            <col width="70">
            <col width="70">			
            <col width="70">
            <col width="70">
            <col width="25">
            <col width="25">
            <col width="25">
            <col width="25">
            <col width="25">
            <col width="25">
            <col width="25">
            <col width="26">
            <tr class="header">
                <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                     onClick="Expand(document.getElementById('tr_search'));"></td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'country_id', '<?= @$xsort["country_id"] ?>')"><?= $lang["country_id"] ?> <?= @$sortimg["country_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'range_min', '<?= @$xsort["range_min"] ?>')"><?= $lang["range_min"] ?> <?= @$sortimg["range_min"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'range_max', '<?= @$xsort["range_max"] ?>')"><?= $lang["range_max"] ?> <?= @$sortimg["range_max"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'mark_up_value', '<?= @$xsort["mark_up_value"] ?>')"><?= $lang["mark_up_value"] ?> <?= @$sortimg["mark_up_value"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'mark_up_desc', '<?= @$xsort["mark_up_desc"] ?>')"><?= $lang["mark_up_desc"] ?> <?= @$sortimg["mark_up_desc"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'monday', '<?= @$xsort["monday"] ?>')"><?= $lang["monday"] ?> <?= @$sortimg["monday"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'tuesday', '<?= @$xsort["tuesday"] ?>')"><?= $lang["tuesday"] ?> <?= @$sortimg["tuesday"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'wednesday', '<?= @$xsort["wednesday"] ?>')"><?= $lang["wednesday"] ?> <?= @$sortimg["wednesday"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'thursday', '<?= @$xsort["thursday"] ?>')"><?= $lang["thursday"] ?> <?= @$sortimg["thursday"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'friday', '<?= @$xsort["friday"] ?>')"><?= $lang["friday"] ?> <?= @$sortimg["friday"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'saturday', '<?= @$xsort["saturday"] ?>')"><?= $lang["saturday"] ?> <?= @$sortimg["saturday"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'sunday', '<?= @$xsort["sunday"] ?>')"><?= $lang["sunday"] ?> <?= @$sortimg["sunday"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="country_id" class="input"
                           value="<?= htmlspecialchars($this->input->get("country_id")) ?>"></td>
                <td><input name="range_min" class="input"
                           value="<?= $this->input->get("range_min") ?>"></td>
				<td><input name="range_max" class="input"
                           value="<?= $this->input->get("range_max") ?>"></td>
                <td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <?php
            $i = 0;
            if ($pricingruleslist) :
                foreach ($pricingruleslist as $pricingrule) :
                    ?>

                    <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')"
                        onClick="Redirect('<?= base_url() ?>marketing/pricingRules/view/<?= $pricingrule->getId() ?>')">
                        <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                             title='<?= $lang["create_on"] ?>:<?= $pricingrule->getCreateOn() ?>&#13;<?= $lang["create_at"] ?>:<?= $pricingrule->getCreateAt() ?>&#13;<?= $lang["create_by"] ?>:<?= $pricingrule->getCreateBy() ?>&#13;<?= $lang["modify_on"] ?>:<?= $pricingrule->getModifyOn() ?>&#13;<?= $lang["modify_at"] ?>:<?= $pricingrule->getModifyAt() ?>&#13;<?= $lang["modify_by"] ?>:<?= $pricingrule->getModifyBy() ?>'>
                        </td>
                        <td><?= $pricingrule->getCountryId() ?></td>
                        <td><?= $pricingrule->getRangeMin() ?></td>
                        <td><?= $pricingrule->getRangeMax() ?></td>
                        <td><?= $pricingrule->getMarkUpValue() ?></td>
                        <td><?= $pricingrule->getMarkUpDesc() ?></td>						
                        <td><?= $pricingrule->getMonday() ?></td>
                        <td><?= $pricingrule->getTuesday() ?></td>
                        <td><?= $pricingrule->getWednesday() ?></td>
                        <td><?= $pricingrule->getThursday() ?></td>
                        <td><?= $pricingrule->getFriday() ?></td>
                        <td><?= $pricingrule->getSaturday() ?></td>
                        <td><?= $pricingrule->getSunday() ?></td>
						
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