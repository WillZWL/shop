<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/selling_platform/get_js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/lytebox.css" type="text/css" media="screen"/>
</head>
<body>
<div id="main">
    <?php  $ar_status = ["0" => $lang["inactive"], "1" => $lang["active"]]; ?>
    <?= $notice["img"] ?>
    <script>
        function Proc(sov, tv) {
            var f = document.fm_proc;
            f.so_no.value = sov;
            f.type.value = tv;
            f.submit();
        }

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
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="650" align="right" class="title">
                <input type="button" value="<?= $lang["add_button"] ?>" class="button" onClick="Redirect('<?= site_url('order/special_order') ?>')">&nbsp;
                <input type="button" value="<?= $lang["on_hold_button"] ?>" class="button" onClick="Redirect('<?= site_url('order/special_order/on_hold') ?>')"> &nbsp;
                <?php  if (check_app_feature_access_right($app_id, "ORD001101_aps_payment_order_page")) : ?>
                    <input type="button" style="width:220px" value="<?= $lang["sale_aps_button"] ?>" class="button"
                           onClick="Redirect('<?= site_url('order/special_order/on_hold/aps_payment') ?>')"> &nbsp;
                <?php endif; ?>
                <input type="button" value="<?= $lang["pending_button"] ?>" class="button" onClick="Redirect('<?= site_url('order/special_order/pending') ?>')">
            </td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?></td>
        </tr>
    </table>

    <form name="fm" method="get">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="20">
            <col width="100">
            <col width="100">
            <col>
            <col>
            <col width="120">
            <col width="230">
            <col width="26">
            <tr class="header">
                <td height="20">
                    <img src="<?= base_url() ?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));">
                </td>
                <td style="white-space:nowrap">
                    <a href="#" onClick="SortCol(document.fm , 'platform_id', '<?= $xsort["platform_id"] ?>')"><?= $lang["platform"] ?> <?= $sortimg["platform_id"] ?></a>
                </td>
                <td style="white-space:nowrap">
                    <a href="#" onClick="SortCol(document.fm, 'so_no', '<?= $xsort["so_no"] ?>')"><?= $lang["order_id"] ?> <?= $sortimg["so_no"] ?></a>
                </td>
                <td style="white-space:nowrap">
                    <a href="#" onClick="SortCol(document.fm, 'email', '<?= $xsort["email"] ?>')"><?= $lang["client_email"] ?> <?= $sortimg["email"] ?></a>
                </td>
                <td style="white-space:nowrap"><?= $lang["order_detail"] ?></td>
                <td style="white-space:nowrap">
                    <a href="#" onClick="SortCol(document.fm, 'amount', '<?= $xsort["amount"] ?>')"><?= $lang["order_amount"] ?> <?= $sortimg["amount"] ?></a>
                </td>
                <td></td>
                <td title="<?= $lang["check_all"] ?>">
                    <?php if (check_app_feature_access_right($app_id, "ORD001102_print_invoice")) : ?>
                        <input type="checkbox" name="chkall" value="1" onClick="checkall(document.fm_edit, this, 1);">
                    <?php endif; ?>
                </td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td>
                    <select name="platform_id" class="input">
                        <option value=""></option>
                        <script language="javascript">drawList("<?=$this->input->get('platform_id')?>");</script>
                    </select>
                </td>
                <td><input name="so_no" class="input" value="<?= htmlspecialchars($this->input->get("so_no")) ?>"></td>
                <td><input name="email" class="input" value="<?= htmlspecialchars($this->input->get("email")) ?>"></td>
                <td></td>
                <td><input name="amount" class="input" value="<?= htmlspecialchars($this->input->get("amount")) ?>">
                </td>
                <td align="center" colspan="2">
                    <input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?= base_url() ?>images/find.gif') no-repeat;">
                </td>
            </tr>
            <input type="hidden" name="showall" value='<?= $this->input->get("showall") ?>'>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
    </form>
    <form name="fm_edit" method="post">
        <?php
        $i = 0;
        if ($objlist) :
            foreach ($objlist as $obj) :
                ?>

                <tr class="row<?= $i % 2 ?>">
                    <td height="20">
                        <img src="<?= base_url() ?>images/info.gif"
                                 title='<?= $lang["create_on"] ?>:<?= $obj->getCreateOn() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->getCreateAt() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->getCreateBy() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->getModifyOn() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->getModifyAt() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->getModifyBy() ?>'>
                    </td>
                    <td><?= $obj->getPlatformId() ?></td>
                    <td>
                        <a href="<?= base_url() . "cs/quick_search/view/" . $obj->getSoNo() ?>" target="_blank"><?= $obj->getSoNo() ?></a>
                    </td>
                    <td><?= $obj->getEmail() ?></td>
                    <td>
                        <?php
                        if ($obj->getReason() || $obj->getNote()) :
                            if ($obj->getReason()) :
                                echo $obj->getReason() . " : ";
                            endif;
                            if ($obj->getNote()) :
                                echo $obj->getNote();
                            endif;
                            echo "<br>";
                        endif;
                        if ($obj->getItems()) :
                            $items = explode("||", $obj->getItems());
                            foreach ($items as $item) :
                                list($sku, $name, $qty, $u_p, $amount) = @explode("::", $item);
                                ?>
                                <p class="normal_p">[<?= $sku ?>] <?= $name ?> x<?= $qty ?> @<?= $u_p ?>
                                    = <?= $amount ?></p>
                            <?php
                            endforeach;
                        endif;
                        ?>
                        <p class="normal_p"><?= "creator: " . $obj->getCreateBy() ?></p>
                    </td>
                    <td><?= $obj->getCurrencyId() ?> <?= $obj->getAmount() ?></td>
                    <td align='center'>
                        <input type="button" value="<?= $lang["previous"] ?>" onClick="if(confirm('<?= $lang["move_back_to_hold"] ?>'))Proc('<?= $obj->getSoNo() ?>', 'b');"> &nbsp; &nbsp;
                        <!--input type="button" value="<?= $lang["cc_hold"] ?>" onClick="Proc('<?= $obj->getSoNo() ?>', 'c');"-->
                        &nbsp; &nbsp;
                        <?php if (check_app_feature_access_right($app_id, "ORD001102_process_order")) : ?>
                            <input type="button" value="<?= $lang["process"] ?>" onClick="if(confirm('<?= $lang["approve_order"] ?>'))Proc('<?= $obj->getSoNo() ?>', 'p');">
                            <input type="button" value="<?= $lang["shipped"] ?>" onClick="if(confirm('<?= $lang["ship_order"] ?>'))Proc('<?= $obj->getSoNo() ?>', 's');">
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (check_app_feature_access_right($app_id, "ORD001102_print_invoice")) : ?>
                            <input type="checkbox" name="check[<?= $obj->getSoNo() ?>]" value="<?= $obj->getSoNo() ?>">
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
                $i++;
            endforeach;
        endif;
        ?>
        </table>
        <?php if (check_app_feature_access_right($app_id, "ORD001102_print_invoice")) : ?>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-top:5px;">
                <tr>
                    <td align="right" style="padding-right:8px;">
                        <input type="button" value="<?= $lang['print_custom_selected'] ?>"
                               onClick="this.form.action='<?= base_url() ?>order/order_fulfilment/custom_invoice';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';">
                        &nbsp;|&nbsp
                        <input type="button" value="<?= $lang['print_selected'] ?>"
                               onClick="this.form.action='<?= base_url() ?>order/order_fulfilment/invoice';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';">
                    </td>
                </tr>
            </table>
        <?php endif; ?>
        <input type="hidden" name="posted" value="1">
    </form>
    <form name="fm_proc" method="post">
        <input type="hidden" name="posted" value="1">
        <input type="hidden" name="so_no" value="">
        <input type="hidden" name="type" value="">
    </form>
    <?= $links ?>
</div>
<?= $notice["js"] ?>
</body>
</html>