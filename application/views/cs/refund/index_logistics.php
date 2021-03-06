<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/profitVarHelper/jsPlatformlist"></script>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"></td>
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
        <col width="150">
        <col width="150">
        <col>
        <col width="150">
        <col width="120">
        <col width="120">
        <col width="20">
        <form name="fm" method="get" onSubmit="return CheckForm(this)">
            <tr class="header">
                <td height="20">
                    <img src="<?= base_url() ?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
                <td>
                    <a href="#" onClick="SortCol(document.fm, 'r.id', '<?= $xsort["r.id"] ?>')"><?= $lang["refund_id"] ?> <?= $sortimg["r.id"] ?></a>
                </td>
                <td>
                    <a href="#" onClick="SortCol(document.fm, 'r.so_no', '<?= $xsort["r.so_no"] ?>')"><?= $lang["so_no"] ?> <?= $sortimg["r.so_no"] ?></a>
                </td>
                <td>
                    <a href="#" onClick="SortCol(document.fm, 's.platform_order_id', '<?= $xsort["s.platform_order_id"] ?>')"><?= $lang["platform_order_id"] ?> <?= $sortimg["s.platform_order_id"] ?></a>
                </td>
                <td>
                    <a href="#" onClick="SortCol(document.fm, 's.platform_id', '<?= $xsort["s.platform_id"] ?>')"><?= $lang["platform_id"] ?> <?= $sortimg["s.platform_id"] ?></a>
                </td>
                <td>
                    <a href="#" onClick="SortCol(document.fm, 'r.total_refund_amount', '<?= $xsort["r.total_refund_amount"] ?>')"><?= $lang["amount"] ?> <?= $sortimg["r.total_refund_amount"] ?></a>
                </td>
                <td>
                    <a href="#" onClick="SortCol(document.fm, 'r.create_on', '<?= $xsort["r.create_on"] ?>')"><?= $lang["create_on"] ?> <?= $sortimg["r.create_on"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="rid" type="text" class="input" value="<?= $this->input->get("rid") ?>"></td>
                <td><input name="so" type="text" class="input" value="<?= $this->input->get("so") ?>"></td>
                <td><input name="platform_order_id" type="text" class="input"
                           value="<?= $this->input->get("platform_order_id") ?>"></td>
                <td><select name="platform_id" class="input">
                        <option value=""></option>
                    </select></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
        </form>
        <?php
        $i = 0;
    if (!empty($list)) :
        foreach ($list as $obj) :
        ?>

        <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
            onMouseOut="RemoveClassName(this, 'highlight')" <?php if (!($is_edit)):
        ?>onClick="Redirect('<?= site_url('cs/refund/logistics_view/' . $obj->getId()) ?>/?<?= $_SERVER['QUERY_STRING'] ?>')"<?php
        endif;?>>
            <td height="20"></td>
            <td><?= $obj->getId() ?></td>
            <td><?= $obj->getSoNo() ?></td>
            <td><?= $obj->getPlatformOrderId() ?></td>
            <td><?= $obj->getPlatformId() ?></td>
            <td><?= $obj->getCurrencyId() . " " . $obj->getTotalRefundAmount() ?></td>
            <td><?= $obj->getCreateOn() ?></td>
            <td>&nbsp;</td>
            <?php
            $i++;
            endforeach;
        endif;
            ?>
        <tr class="header">
            <td></td>
            <td colspan="7"><input type="button" onClick="Redirect('<?= base_url() ?>cs/refund/');"
                                   value="<?= $lang["back_to_main"] ?>"></td>
        </tr>
    </table>
    <?= $links ?>
    <?= $notice["js"] ?>
</div>
<script type="text/javascript">
    InitPlatform(document.fm.platform_id);
    document.fm.platform_id.value = '<?=$this->input->get("platform_id")?>';
</script>
</body>
</html>