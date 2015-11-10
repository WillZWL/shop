<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
</head>
<body class="frame_left" style="width:auto;">
<div id="main" style="width:auto;">
    <form name="fm" method="get" action="<?= base_url() ?>marketing/best_seller/view_left/">
        <input type="hidden" name="catid" value="<?= $catid; ?>">
        <input type="hidden" name="level" value="<?= $level; ?>">
        <input type="hidden" name="platform" value="<?= $platform; ?>">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <col width="5">
            <col width="40">
            <col width="120">
            <col width="35">
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" height="40" valign="middle" align="left"><b
                        style="font-size:14px; color:#000000;"><?= $lang["product_search"] ?></b></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td align="left"><?= $lang["sku"] ?></td>
                <td><input type="text" name="sku" value="<?= $this->input->get('sku') ?>" class="input"></td>
                <td rowspan="2"><input type="submit"
                                       style="background: rgb(204, 204, 204) url('<?= base_url() ?>/images/find.gif') no-repeat scroll center center; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; width: 30px; height: 25px;"
                                       class="search_button" value=""/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td align="left"><?= $lang["name"] ?></td>
                <td><input type="text" name="name" value="<?= $this->input->get('name') ?>" class="input"></td>
            </tr>
        </table>
        <?php
        if ($search) {
            ?>
            <script language="javascript">
                <!--
                function addToRight(sku, name) {
                    parent.frames['right'].addRow(sku, name);

                }
                -->
            </script>
            <hr>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
                <col width="50">
                <col>
                <tr class="header">
                    <td><a href="#"
                           onClick="SortCol(document.fm, 'sku', '<?= $xsort["sku"] ?>')"><?= $lang["sku"] ?> <?= $sortimg["sku"] ?></a>
                    </td>
                    <td><a href="#"
                           onClick="SortCol(document.fm, 'name', '<?= $xsort["name"] ?>')"><?= $lang["name"] ?> <?= $sortimg["name"] ?></a>
                    </td>
                </tr>
                <?php
                $i = 0;
                if ($objlist) {
                    foreach ($objlist as $obj) {
                        ?>
                        <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                            onMouseOut="RemoveClassName(this, 'highlight')"
                            onClick="addToRight('<?= $obj->get_sku() ?>','<?= htmlentities(addslashes($obj->get_name())) ?>')">
                            <td nowrap style="white-space:nowrap;"><?= $obj->get_sku() ?></td>
                            <td><?= $obj->get_name() ?></td>
                        </tr>
                        <?php
                        $i++;
                    }
                }
                ?>
            </table>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
            <?= $this->pagination_service->create_links_with_style() ?>
        <?php
        }
        ?>

    </form>
    <br><br>

    <div style="padding-top:10px; padding-left:5px; width:100%; text-align:left;">
        <?= $lang["notes"] ?>
    </div>
</div>
</body>
</html>
