<html>
<head>
    <title><?= $lang["header"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/mastercfg/warehouse/js_warehouse"></script>
    <script language="javascript">
        <!--

        function GetXmlHttpObject() {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                return new XMLHttpRequest();
            }
            if (window.ActiveXObject) {
                // code for IE6, IE5
                return new ActiveXObject("Microsoft.XMLHTTP");
            }
            return null;
        }


        function write_wh() {
            for (var i in warehouse) {
                selected = "";
                if (warehouse[i][0] == '<?=$wh?>') {
                    selected = "SELECTED";
                }
                document.write('<option value="' + warehouse[i][0] + '"  ' + selected + '>' + warehouse[i][1] + '</option>');
            }
        }

        function gotoPage(value) {
            if (value != "") {
                document.location.href = "<?=base_url()?>/order/supplier_order/confirm_shipment/" + value;
            }
        }

        function checkQty(index, index2) {
            var xmlhttp = GetXmlHttpObject();
            var sq = document.getElementById('sq[' + index + '][' + index2 + ']').value;
            var rq = document.getElementById('rqty[' + index + '][' + index2 + ']').value;

            if (rq > sq) {
                document.getElementById('rqty[' + index + '][' + index2 + ']').style.backgroundColor = '#cc99ff';
                document.getElementById('reason[' + index + '][' + index2 + ']').disabled = true;
                var pon = document.getElementById('pon[' + index + '][' + index2 + ']').value;
                var ln = document.getElementById('linen[' + index + '][' + index2 + ']').value;
                xmlhttp.onreadystatechange = function () {

                    var result = xmlhttp.responseText;
                    if (result == "1" && xmlhttp.readyState == 4) {
                        document.getElementById('rqty[' + index + '][' + index2 + ']').value = document.getElementById('sq[' + index + '][' + index2 + ']').value;
                        document.getElementById('rqty[' + index + '][' + index2 + ']').style.backgroundColor = '';
                        document.getElementById('reason[' + index + '][' + index2 + ']').disabled = true;
                        alert("<?=$lang['over_outstanding']?>");
                        return;
                    }
                }
                url = '<?=base_url()."order/supplier_order/check_outstanding/"?>?po_number=' + pon + '&line_number=' + ln + '&input=' + (rq - sq);
                xmlhttp.open("GET", url, true);
                xmlhttp.send(null);

            }
            else if (rq == sq) {
                document.getElementById('rqty[' + index + '][' + index2 + ']').style.backgroundColor = '';
                document.getElementById('reason[' + index + '][' + index2 + ']').disabled = true;
            }
            else {
                document.getElementById('rqty[' + index + '][' + index2 + ']').style.backgroundColor = '#ff99cc';
                document.getElementById('reason[' + index + '][' + index2 + ']').disabled = false;
            }
        }

        function submitTNT(tracking_no) {
            document.tnt.QUERY.value = tracking_no;
            document.tnt.submit();
        }

        function submitFEDEX(tracking_no) {
            document.fedex.tracknumbers.value = tracking_no;
            document.fedex.submit();
        }

        -->
    </script>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["subtitle"] ?></td>
            <td width="400" align="right" class="title"></td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033"></td>
            <td height="2" bgcolor="#000033"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["subheader"] ?>
                <br><?= $lang["select_wh"] ?><select onChange="gotoPage(this.value)">
                    <option value=""><?= $lang["please_select"] ?></option>
                    <script language="javascript">write_wh()</script>
                </select></td>
            <td width="200" valign="top" align="right" style="padding-right:8px"><br><?= $lang["shipment_found"] ?>
                <b><?= $total ?></b><br><br></td>
        </tr>
    </table>
    <?php if ($showcontent) { ?>
        <form name="fm" method="get">
            <table border="0" cellpadding="0" cellspacing="1" width="100%" class="tb_pad header">
                <col width="30">
                <col width="120">
                <col width="120">
                <col width="150">
                <col width="100">
                <col width="150">
                <col width="100">
                <col width="120">
                <col>
                <col width="100">
                <col width="40">
                <tr class="header">
                    <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                         onClick="Expand(document.getElementById('tr_search'));"></td>
                    <td><a href="#"
                           onClick="SortCol(document.fm, 'shipment_id', '<?= $xsort["shipment_id"] ?>')"><?= $lang["shipment_id"] ?> <?= $sortimg["shipment_id"] ?></a>
                    </td>
                    <td><?= $lang["sku"] ?></td>
                    <td><?= $lang["prod_name"] ?> <?= $sortimg["prod_name"] ?></td>
                    <td><?= $lang["qty"] ?></td>
                    <td><?= $lang["supplier_name"] ?></td>
                    <td><?= $lang["received_qty"] ?></td>
                    <td><?= $lang["reason"] ?></td>
                    <td><?= $lang["remarks"] ?></td>
                    <td><a href="#"
                           onClick="SortCol(document.fm, 'tracking_no', '<?= $xsort["tracking_no"] ?>')"><?= $lang["tracking_no"] ?> <?= $sortimg["tracking_no"] ?></a>
                    </td>
                    <td></td>
                </tr>
                <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                    <td width="20"></td>
                    <td><input name="shipment_id" class="input"
                               value="<?= htmlspecialchars($this->input->get("shipment_id")) ?>"></td>
                    <td><input name="sku" class="input" value="<?= htmlspecialchars($this->input->get("sku")) ?>"></td>
                    <td><input name="prod_name" class="input"
                               value="<?= htmlspecialchars($this->input->get("prod_name")) ?>"></td>
                    <td>&nbsp;</td>
                    <td><input name="supplier_name" class="input"
                               value="<?= htmlspecialchars($this->input->get("supplier_name")) ?>"></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><input name="tracking_no" class="input"
                               value="<?= htmlspecialchars($this->input->get("tracking_no")) ?>"></td>
                    <td align="left"><input type="submit" name="searchsubmit" value="" class="search_button"
                                            style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
                </tr>
            </table>
            <input type="hidden" name="showall" value='<?= $this->input->get("showall") ?>'>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
            <input type="hidden" name="searchsubmitted" value="1">
        </form>

        <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST" name="confirmForm">
            <table border="0" cellpadding="0" cellspacing="1" bgcolor="#BBBBFF" width="100%" class="tb_pad">
                <col width="30">
                <col width="120">
                <col width="120">
                <col width="150">
                <col width="100">
                <col width="150">
                <col width="100">
                <col width="120">
                <col>
                <col width="100">
                <col width="40">
                <?php
                $tracker_head = array(
                    "DHL" => "<a href='http://www.dhl.co.uk/publish/gb/en/eshipping/international_air.high.html?pageToInclude=RESULTS&type=fasttrack&AWB=[:tracking_no:]' target='DHL'>[:tracking_no:]",
                    "FEDEX" => "<a href='#' onClick='submitFEDEX(\"[:tracking_no:]\")');'>[:tracking_no:]",
                    "TNT" => "<a href='#' onClick='submitTNT(\"[:tracking_no:]\")');'>[:tracking_no:]",
                    "UPS" => "<a href='http://wwwapps.ups.com/WebTracking/track?HTMLVersion=5.0&loc=zh_HK&track.y=10&Requester=TRK_MOD&showMultipiece=N&trackNums=[:tracking_no:]' target='UPS'>[:tracking_no:]"
                );

                $tracker_tail = array("DHL" => "</a>",
                    "FEDEX" => "</a>",
                    "TNT" => "</a>",
                    "UPS" => "</a>"
                );
                $i = 0;
                if (!empty($result)) {
                    if ($total > 0) {
                        foreach ($result as $inv_obj) {
                            $tracking_no = $inv_obj->get_tracking_no();
                            $courier = $inv_obj->get_courier();
                            $link = $tracker[$courier] != "" && $tracking_no != "" ? $tracker[$courier] . $tracking_no : "";
                            $cur_color = $row_color[$i % 2];
                            $detail = $inv_obj->get_detail();
                            $detail_arr = explode("::", $detail);
                            $rc = count($detail_arr);
                            $step = 0;
                            foreach ($detail_arr as $val) {
                                $val_arr = explode("||", $val);
                                ?>
                                <tr class="row<?= $i % 2 ?>" onMouseOver="AddClassName(this, 'highlight')"
                                    onMouseOut="RemoveClassName(this, 'highlight')" class="pointer">
                                    <?php
                                    if (!$step) {
                                        ?>
                                        <td height="20" rowspan="<?= $rc ?>"></td>
                                        <td rowspan="<?= $rc ?>"><?= $inv_obj->get_shipment_id() ?><input
                                                name="shipment_id[<?= $i ?>]" id="shipment_id[<?= $i ?>]" type="hidden"
                                                value="<?= $inv_obj->get_shipment_id() ?>"></td>
                                    <?php
                                    }
                                    ?>
                                    <td><?= $val_arr[1] ?><input type="hidden" id="pon[<?= $i ?>][<?= $step ?>]"
                                                                 name="pon[<?= $i ?>][<?= $step ?>]"
                                                                 value="<?= $val_arr[0] ?>"></td>
                                    <td><?= $val_arr[2] ?><input type="hidden" id="linen[<?= $i ?>][<?= $step ?>]"
                                                                 name="linen[<?= $i ?>][<?= $step ?>]"
                                                                 value="<?= $val_arr[5] ?>"><input type="hidden"
                                                                                                   name="trans[<?= $i ?>][<?= $step ?>]"
                                                                                                   value="<?= $val_arr[6] ?>">
                                    </td>
                                    <td><?= $val_arr[3] ?><input name="sq[<?= $i ?>][<?= $step ?>]"
                                                                 id="sq[<?= $i ?>][<?= $step ?>]" type="hidden"
                                                                 value="<?= $val_arr[3] ?>"></td>
                                    <td><?= $val_arr[4] ?></td>
                                    <td><input class="input" type="text" name="rqty[<?= $i ?>][<?= $step ?>]"
                                               id="rqty[<?= $i ?>][<?= $step ?>]" value="<?= $val_arr[3] ?>"
                                               onKeyUp="checkQty('<?= $i ?>','<?= $step ?>')"></td>
                                    <td><select class="input" name="reason[<?= $i ?>][<?= $step ?>]"
                                                id="reason[<?= $i ?>][<?= $step ?>]" DISABLED>
                                            <option value="OTH">Others</option>
                                        </select></td>
                                    <?php
                                    if (!$step) {
                                        if ($tracking_no == "" || $courier == "") {
                                            $track = "";
                                        } else {
                                            $thead = str_replace("[:tracking_no:]", $tracking_no, $tracker_head[$courier]);
                                            $ttail = str_replace("[:tracking_no:]", $tracking_no, $tracker_tail[$courier]);
                                            $track = $thead . $ttail;
                                        }
                                        ?>
                                        <td rowspan="<?= $rc ?>"><input class="input" type="text"
                                                                        name="remarks[<?= $i ?>]"
                                                                        id="remarks[<?= $i ?>]" value=""></td>
                                        <td rowspan="<?= $rc ?>"><?= $courier . "<br>" . $track ?></td>
                                        <td rowspan="<?= $rc ?>"><input type="checkbox" name="checked[<?= $i ?>]"
                                                                        value="1"></td>
                                    <?php
                                    }
                                    ?>
                                </tr>
                                <?php
                                $step++;
                            }
                            $i++;
                        }
                    } else {
                        ?>
                        <tr class="row0">
                            <td colspan="11" align="center"><?= $lang["po_not_found"] ?></td>
                        </tr>
                    <?php
                    }
                }
                ?>
            </table>
            <input type="hidden" value="1" name="posted">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="tb_pad header">
                <tr>
                    <td align="right" style="padding-right:8px;">
                        <input type="button" value="Download CSV"
                               onClick="document.fm.action='<?= base_url() ?>/order/supplier_order/download_csv/<?= $wh ?>';document.fm.target='report';document.fm.submit();document.fm.action='';document.fm.target=''">
                        <input type="button" value="<?= $lang["confirm_quantity"] ?>"
                               onClick="if(CheckForm(this.form)) document.confirmForm.submit();">
                    </td>
                    <td width="10">&nbsp;</td>
                </tr>
            </table>
        </form>
        <?= $this->pagination_service->create_links_with_style() ?>
    <?php  } ?>
    <form name='fedex' target='fedex' method='post' action='http://fedex.com/Tracking'>
        <input type='hidden' name='tracknumbers' value=''>
        <input type='hidden' name='language' value='english'>
        <input type='hidden' name='action' value='track'>
        <input type='hidden' name='ascend_header' value='1'>
        <input type='hidden' name='mps' value='y'>
        <input type='hidden' name='cntry_code' value='gb'>
    </form>
    <form name='tnt' target='tnt' method='post' action='http://cgi.tnt.co.uk/trackntrace/ConEnquiry.asp' xmlns=''>
        <input type='hidden' name='QUERY' value=''>
        <input type='hidden' name='TrackChoice' value='consignment'>
    </form>
</div>
<?= $notice["js"] ?>
<iframe name="report" id="report" src="" width="1259" noresize frameborder="0" marginwidth="0" marginheight="0" hspace=0
        vspace=0 onLoad="SetFrameFullHeight(this)"></iframe>
<script>
    <?php
        if ($_SESSION["download_csv"])
        {
    ?>
    top.frames["report"].window.location.href = '<?=base_url()?>order/pick_list/get_export_csv/<?=$_SESSION["download_csv"]?>';
    <?php
        }
    ?>
</script>
</body>
</html>