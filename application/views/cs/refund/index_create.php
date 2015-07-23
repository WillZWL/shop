<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/selling_platform/get_js"></script>
    <script language="javascript">
        <!--
        function drawList(value) {
            var selected = "";
            var output = "";
            for (var i in platform) {
                selected = platform[i][0] == value ? "SELECTED" : "";
                output = "<option value='" + platform[i][0] + "' " + selected + ">" + platform[i][0] + "</option>";
                document.write(output);
            }
        }
        -->
    </script>
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
        <col width="150">
        <col>
        <col width="120">
        <col width="120">
        <form name="fm" method="get" onSubmit="return CheckForm(this)">
            <tr class="header">
                <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                     onClick="Expand(document.getElementById('tr_search'));"></td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'so_no', '<?= $xsort["so_no"] ?>')"><?= $lang["so_no"] ?> <?= $sortimg["so_no"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'platform_order_id', '<?= $xsort["platform_order_id"] ?>')"><?= $lang["platform_order_id"] ?> <?= $sortimg["platform_order_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'platform_id', '<?= $xsort["platform_id"] ?>')"><?= $lang["platform_id"] ?> <?= $sortimg["platform_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'bill_name', '<?= $xsort["bill_name"] ?>')"><?= $lang["cname"] ?> <?= $sortimg["bill_name"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'amount', '<?= $xsort["amount"] ?>')"><?= $lang["amount"] ?> <?= $sortimg["amount"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'delivery_charge', '<?= $xsort["delivery_charge"] ?>')"><?= $lang["delivery_charge"] ?> <?= $sortimg["delivery_charge"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="so_no" type="text" class="input" value="<?= $this->input->get("so_no") ?>"></td>
                <td><input name="platform_order_id" type="text" class="input"
                           value="<?= $this->input->get("platform_order_id") ?>"></td>
                <td><select name="platform_id" class="input">
                        <option value=""></option>
                        <script language="javascript">drawList("<?=$this->input->get('platform_id')?>");</script>
                    </select></td>
                <td><input name="cname" type="text" class="input" value="<?= $this->input->get("cname") ?>"></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
            <input type="hidden" name="search" value="1">
        </form>
        <?php
        $i = 0;
        if (!empty($list))
        {
        foreach ($list as $obj)
        {
        ?>

        <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
            onMouseOut="RemoveClassName(this, 'highlight')" <?if (!($is_edit)){
        ?>onClick="Redirect('<?= site_url('cs/refund/create_view/' . $obj->get_so_no()) ?>/')"<?
        }?>>
            <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                 title='<?= $lang["create_on"] ?>:<?= $obj->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->get_modify_by() ?>'>
            </td>

            <td><?= $obj->get_so_no() ?></td>
            <td><?= $obj->get_platform_order_id() ?></td>
            <td><?= $obj->get_platform_id() ?></td>
            <td><?= $obj->get_bill_name() ?></td>
            <td><?= $obj->get_amount() ?></td>
            <td><?= $obj->get_delivery_charge() ?></td>
            <td>&nbsp;</td>
            <?php
            $i++;
            }
            }
            ?>
        <tr class="header">
            <td></td>
            <td colspan="7"><input type="button" onClick="Redirect('<?= base_url() ?>cs/refund/');"
                                   value="<?= $lang["back_to_main"] ?>"></td>
        </tr>
    </table>
    <?= $this->pagination_service->create_links_with_style() ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>