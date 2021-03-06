<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <link rel="stylesheet" href="<?= base_url() ?>css/bootstrap.min.css" type="text/css" media="all" />
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script language="javascript">
        <!--
        function showView(sku) {
            top.window.location.hash = sku;
            var x = '<?=base_url()?>marketing/product_identifier/view/' + sku;
            parent.frames['pview'].document.location.href = x;
        }
        -->
    </script>
</head>
<body class="frame_left" style="width:auto;">
<div id="main" style="width:auto;">
    <form name="fm" method="get">
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
            if ($objlist) :
                foreach ($objlist as $obj) :
                    $prod_grp_cd = $version = $colour = $region_code = null;
                    if ($obj->getMasterSku()) :
                        list($prod_grp_cd, $version, $colour) = explode("-", $obj->getMasterSku());
                        if ($version) :
                            $region_code = "<span style='color:#0072E3'>(" . $version . ")</span> ";
                        endif;
                    endif;
                    ?>

                    <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')"
                        onClick="showView('<?= urlencode($obj->getSku()) ?>')">
                        <td nowrap style="white-space:nowrap;"><?= $obj->getSku() ?></td>
                        <td><?= $region_code . $obj->getName() ?></td>
                    </tr>
                    <?php
                    $i++;
                endforeach;
            endif;
            ?>
        </table>
        <input type="hidden" name="sku" value='<?= htmlspecialchars($this->input->get("sku")) ?>'>
        <input type="hidden" name="name" value='<?= htmlspecialchars($this->input->get("name")) ?>'>
        <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
        <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
    </form>
    <?= $links ?>
</div>
<?php
if ($i == 1) {
    ?>
    <script>
        top.window.location.hash = '<?=$obj->getSku()?>';
        parent.frames['pview'].document.location.href = '<?=base_url()?>marketing/product_identifier/view/<?=$obj->getSku()?>';
    </script>
<?php
}
?>
</body>
</html>