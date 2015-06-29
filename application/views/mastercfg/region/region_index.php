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
    $ar_status = array("C" => $lang["courier"], "S" => $lang["sourcing"]);
    ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"><input type="button" value="<?= $lang["add_button"] ?>"
                                                               class="button"
                                                               onclick="Redirect('<?= site_url('mastercfg/region/add/') ?>')">
            </td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033"></td>
            <td height="2" bgcolor="#000033"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?></td>
            <td width="200" valign="top" align="right" style="padding-right:8px"><br><?= $lang["region_found"] ?>
                <b><?= $total ?></b><br><br>
            <td width="22"></td>
        </tr>
    </table>
    <form name="fm" method="get">
        <table border="0" cellpadding="0" cellspacing="1" bgcolor="#000000" width="100%" class="tb_pad">
            <tr class="header">
                <td height="20" width="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                                onClick="Expand(document.getElementById('tr_search'));"></td>
                <td width="40"><a href="#"
                                  onClick="SortCol(document.fm, 'id', '<?= @$xsort["id"] ?>')"><?= $lang["id"] ?></a>
                </td>
                <td width="140"><a href="#"
                                   onClick="SortCol(document.fm, 'region_name', '<?= @$xsort["region_name"] ?>')"><?= $lang["region_name"] ?> <?= @$sortimg["region_name"] ?></a>
                </td>
                <td width="60"><a href="#"
                                  onClick="SortCol(document.fm, 'region_type', '<?= @$xsort["region_type"] ?>')"><?= $lang["region_type"] ?> <?= @$sortimg["region_type"] ?></a>
                </td>
                <td width="22"></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="id" class="input" value="<?= htmlspecialchars($this->input->get("id")) ?>"></td>
                <td><input name="region_name" class="input"
                           value="<?= htmlspecialchars($this->input->get("region_name")) ?>"></td>
                <td>
                    <select name="region_type" class="input">
                        <option value="">-- <?= $lang["please_select"] ?> --</option>
                        <option
                            value="C" <?= ($this->input->get("region_type") == "C" ? "SELECTED" : "") ?>><?= $lang["courier"] ?></option>
                        <option
                            value="S" <?= ($this->input->get("region_type") == "S" ? "SELECTED" : "") ?>><?= $lang["sourcing"] ?></option>
                    </select>
                </td>
                <td><input type="submit" name="searchsubmit" value="" class="search_button"
                           style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="1" bgcolor="#BBBBFF" width="100%" class="tb_pad">
            <?php
            $i = 0;
            foreach ($regionlist as $region) {
                $cur_color = $row_color[$i % 2];
                ?>

                <tr class="row<?= $i % 2 ?>" onMouseOver="AddClassName(this, 'highlight')"
                    onMouseOut="RemoveClassName(this, 'highlight')"
                    onclick="Redirect('<?= site_url('mastercfg/region/view/' . $region->get_id()) ?>');"
                    class="pointer">
                    <td height="20" width="20"><img src="<?= base_url() ?>images/info.gif"
                                                    title='<?= $lang["create_on"] ?>:<?= $region->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $region->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $region->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $region->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $region->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $region->get_modify_by() ?>'>
                    </td>
                    <td width="40"><?= $region->get_id() ?></td>
                    <td width="140"><?= $region->get_region_name() ?></td>
                    <td width="60"><?= $ar_status{$region->get_type()} ?></td>
                    <td width="22">&nbsp;</td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </table>
        <input type="hidden" name="showall" value='<?= $this->input->get("showall") ?>'>
        <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
        <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
    </form>
    <?= $this->pagination_service->create_links_with_style() ?>
</div>
</body>
</html>