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
    $ar_status = array("N" => $lang["new"], "P" => $lang["processing"], "C" => $lang["completed"], "CE" => $lang["completed_with_error"], "BE" => $lang["broke_with_error"], "RP" => $lang["reprocessing"]);
    $ar_color = array("N" => "#000000", "P" => "#0000CC", "C" => "#009900", "CE" => "#999900", "BE" => "#CC0000", "RP" => "#0000CC");
    ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"></td>
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
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="20">
            <col width="120">
            <col width="200">
            <col>
            <col width="150">
            <col width="120">
            <col width="120">
            <col width="100">
            <col width="26">
            <tr class="header">
                <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                     onClick="Expand(document.getElementById('tr_search'));"></td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'id', '<?= $xsort["id"] ?>')"><?= $lang["id"] ?> <?= $sortimg["id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'func_name', '<?= $xsort["func_name"] ?>')"><?= $lang["function_name"] ?> <?= $sortimg["func_name"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'remark', '<?= $xsort["remark"] ?>')"><?= $lang["remark"] ?> <?= $sortimg["remark"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'status', '<?= $xsort["status"] ?>')"><?= $lang["status"] ?> <?= $sortimg["status"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'create_on', '<?= $xsort["create_on"] ?>')"><?= $lang["start_time"] ?> <?= $sortimg["create_on"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'end_time', '<?= $xsort["end_time"] ?>')"><?= $lang["end_time"] ?> <?= $sortimg["end_time"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'duration', '<?= $xsort["duration"] ?>')"><?= $lang["duration"] ?> <?= $sortimg["duration"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="id" class="input" value="<?= htmlspecialchars($this->input->get("id")) ?>"></td>
                <td><input name="func_name" class="input"
                           value="<?= htmlspecialchars($this->input->get("func_name")) ?>"></td>
                <td><input name="remark" class="input" value="<?= htmlspecialchars($this->input->get("remark")) ?>">
                </td>
                <td>
                    <?php
                    if ($this->input->get("status") != "") {
                        $selected[$this->input->get("status")] = "SELECTED";
                    }
                    ?>
                    <select name="status" class="input">
                        <option value="">
                            <?php
                            foreach ($ar_status as $rskey => $rsvalue)
                            {
                            ?>
                        <option value="<?= $rskey ?>" <?= $selected[$rskey] ?>
                                style="color:<?= $ar_color[$rskey] ?>"><?= $rsvalue ?>
                            <?php
                            }
                            ?>
                    </select>
                </td>
                <td><input name="create_on" class="input"
                           value="<?= htmlspecialchars($this->input->get("create_on")) ?>"></td>
                <td><input name="end_time" class="input" value="<?= htmlspecialchars($this->input->get("end_time")) ?>">
                </td>
                <td><input name="duration" class="input" value="<?= htmlspecialchars($this->input->get("duration")) ?>">
                </td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <?php
            $i = 0;
            if (!empty($objlist)) {
                foreach ($objlist as $obj) {
                    ?>
                    <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')"
                        onClick="Redirect('<?= site_url("integration/integration/view/{$obj->get_func_name()}/{$obj->get_id()}") ?>')">
                        <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                             title='<?= $lang["create_on"] ?>:<?= $obj->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->get_modify_by() ?>'>
                        </td>
                        <td><?= $obj->get_id() ?></td>
                        <td><?= $obj->get_func_name() ?></td>
                        <td><?= $obj->get_remark() ?></td>
                        <td style="color:<?= $ar_color[$obj->get_status()] ?>"><?= $ar_status[$obj->get_status()] ?></td>
                        <td><?= $obj->get_create_on() ?></td>
                        <td><?= $obj->get_end_time() ?></td>
                        <td><?= $obj->get_duration() ?></td>
                        <td align="center">
                            <!--<input type="button" value="x" title="<?= $lang["not_listed"] ?>" class="x_button" onClick="event.cancelBubble=true;Redirect('<?= site_url('integration/integration/delete/' . $obj->get_id()) ?>')">--></td>
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