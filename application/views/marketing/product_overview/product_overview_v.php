
<html>
<head>
    <title> <?= $lang["title"] ?> </title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?= base_url() ?>css/bootstrap.min.css" type="text/css" media="all" />
    <!-- <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
    <script type="text/javascript" src="<?= base_url() ?>js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/colorbox.css" />
    <script type="text/javascript" src="<?= base_url() ?>js/jquery-colorbox.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>marketing/category/js_catlist"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/brand/js_brandlist"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        selectOptions();
        $('[data-toggle="tooltip"]').tooltip();
    });

    function selectOptions() {
        var query_string = window.location.search.substring(1);
        var query_array = query_string.split("&");
        for (var i = query_array.length - 1; i >= 0; i--) {
            var item = query_array[i].split("=");
            var select_dom = $("select[name=" + item[0] + "]");
            if (select_dom.length > 0) {
                select_dom.val(item[1]);
            }
        }
    }
    </script>
    <script type="text/javascript" src="<?= base_url() ?>supply/supplier_helper/js_supplist/1"></script>
    <script language="javascript" type="text/javascript">
        function switchSearch() {
            var multidiv = document.getElementById('multifilter');
            var bulklistdiv = document.getElementById('bulklist');
            var filtertype = document.getElementById("filtertype");
            var confirmclear = confirm("This will clear all search filters. Continue?");

            if (confirmclear == true) {
                document.getElementById('fm').reset();

                if (multidiv.style.display == 'block') {
                    multidiv.style.display = 'none';
                    bulklistdiv.style.display = 'block';
                    filtertype.value = 2;
                } else {
                    multidiv.style.display = 'block';
                    bulklistdiv.style.display = 'none';
                    filtertype.value = 1;
                }
            }
        }

        needToConfirm = false;
        window.onbeforeunload = askConfirm;

        function askConfirm(){
            if (needToConfirm) {
                return "<?=$lang["usaved_changes "]?>";
            }
        }

        function checkall_ele(platform, checkboxname, elementsName) {
            // name of main checkbox
            var main_adword_cb = document.getElementById(checkboxname);
            var checkedval = main_adword_cb.checked;
            if (platform) {
                var adwords_eles = document.getElementsByName(elementsName);
                for (var i = 0; i < adwords_eles.length; i++) {
                    if (adwords_eles[i].disabled != true) {
                        adwords_eles[i].checked = checkedval;
                    }
                }
            }
        }
    </script>
</head>

<body>
    <div id="main">
        <?= $notice["img"] ?>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td height="30" class="title"><?=$lang["title"]?></td>
                    <td width="600" align="right" class="title"><input type="button" value="<?=$lang["list_button"]?>" class="button" onClick="Redirect('<?=base_url('/marketing/ProductOverviewWebsite')?>')"></td>
                </tr>
                    <td height="2" class="line"></td>
                    <td height="2" class="line"></td>
                </tr>
            </table>

            <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
                <col width="150"><col width="420"><col width="170"><col width="420"><col>
                <tr>
                    <td></td>
                    <td>
                        <br>
                        <b>AFFILIATE'S SKU MANAGEMENT</b>
                        <br>affiliate_sku_status: 0 = auto / 1 = exclude / 2 = include
                        <br>All products not in the list have "auto" status. To add a status to a new product, simply insert new line and fill in the "new_affiliate_sku_status" column.
                        <form action="/marketing/productOverviewWebsite/exportAffiliateFeed" enctype="multipart/form-data" method="post">
                            <select name="platform_id">
                                <?php foreach ($clist as $cobj): ?>
                                    <option value="<?= $cobj->getSellingPlatformId() ?>"> <?= $cobj->getSellingPlatformId() ?> - <?= $cobj->getName() ?></option>
                                <?php endforeach ?>
                            </select>
                            <br>
                            <select name="afsku_status">
                                <option value="NA">ALL</option>
                                <option value="0">Auto</option>
                                <option value="1">Exclude</option>
                                <option value="2">Include</option>
                            </select>
                            <br>
                            <textarea rows="3" name="af_skulist" placeholder="Local SKU, separated by next line"></textarea>
                            <input type="submit" value="Export">
                        </form>
                    </td>
                    <td></td>
                    <td style="width:50%;text-align:left;">
                        <br>Import your updated affiliate SKU's management file here.
                        <br>Update/Insert will be based on these columns:
                        <br>sku, affiliate_id, platform_id, affiliate_sku_status, new_affiliate_sku_status
                        <form action="/marketing/ProductOverviewWebsite/upload_affiliate_feed" enctype="multipart/form-data" method="post">
                            <input type="file" name="datafile" size="40">
                            <input type="submit" value="Upload">
                        </form>
                    </td>
                </tr>

                <tr>
                    <td colspan="4"><br><hr></hr><br></td>
                </tr>
            </table>

            <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
                <col width="150"> <col width="420"> <col width="170"> <col width="420"> <col>
                <tr>
                    <td></td>
                    <td>
                        Export as CSV
                        <a href="/marketing/ProductOverviewWebsite?<?= $query_string ?>&csv=1">here</a>, after modification, upload the file below
                        <form action="/marketing/ProductOverviewWebsite/importSkuPrice" enctype="multipart/form-data" method="post">
                            <input type="file" name="datafile" size="40">
                            <input type="submit" value="Upload">
                        </form>
                    </td>
                    <td></td>
                    <td style="width:50%;text-align:left;">
                        Upload the clearance info
                        <form action="/marketing/ProductOverviewWebsite/uploadClearanceSku" enctype="multipart/form-data" method="post">
                            <input type="file" name="clearance_datafile" size="40">
                            <input type="submit" value="Upload">
                        </form>
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                </tr>
            </table>
            <form name="fm" id="fm" method="get" onSubmit="return CheckForm(this)">
                <?php
                    $multifilter_display = 'display:block';
                    $bulklist_display = 'display:none';
                    if ($filtertype == 2) {
                        $multifilter_display = 'display:none';
                        $bulklist_display = 'display:block';
                    }
                ?>
                <input type="hidden" name="filtertype" id="filtertype" value="<?= $this->input->get('filtertype') ?>">
                <div id="multifilter" style="<?= $multifilter_display ?>">
                    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
                        <col width="150"> <col width="420"> <col width="170"> <col width="420"> <col>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <input type="button" onclick="switchSearch()" value="Change Search Type">
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-right:8px" align="right"><b><?= $lang["platform_id"] ?></b>
                            </td>
                            <td>
                                <select name="platform_id">
                                    <?php foreach ($clist as $cobj): ?>
                                        <option value="<?= $cobj->getSellingPlatformId() ?>"> <?= $cobj->getSellingPlatformId() ?> - <?= $cobj->getName() ?></option>
                                    <?php endforeach ?>
                                </select>
                            </td>

                            <td style="padding-right:8px" align="right">
                                <b>Feed filter</b>
                            </td>
                            <td>
                                <select id="affeed" name="affeed" onchange="check_feed_selection(this)">
                                    <option value="">---</option>
                                    <?php foreach ($affiliate_feed_list as $key => $value): ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                    <?php endforeach ?>
                                </select>
                                <select id="feed_status" name="feed_status">
                                    <option value="">---</option>
                                    <?php foreach ($feed_status_list as $key => $value): ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                    <?php endforeach ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-right:8px" align="right">
                                <b><?= $lang["category"] ?></b>
                            </td>
                            <td>
                                <select name="catid" class="input" onChange="ChangeCat(this.value, this.form.scatid, this.form.sub_sub_cat_id)">
                                    <option value=""></option>
                                </select>
                            </td>
                            <td style="padding-right:8px" align="right">
                                <b><?= $lang["brand"] ?></b>
                            </td>
                            <td>
                                <select name="brand" class="input">
                                    <option value=""></option>
                                </select>
                            </td>
                            <td rowspan="3" align="center">
                                <input type="button" value="<?= $lang["cmd_search_button"] ?>" onClick="if (CheckForm(this.form)) this.form.submit();">
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-right:8px" align="right">
                                <b><?= $lang["sub_cat"] ?></b>
                            </td>
                            <td>
                                <select name="scatid" class="input">
                                    <option value=""></option>
                                </select>
                            </td>
                            <td style="padding-right:8px" align="right">
                                <b>Google Adwords</b>
                            </td>
                            <td>
                                <select name="adw" class="input">
                                    <option></option>
                                    <option value="Y">Enabled</option>
                                    <option value="N">Disabled</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-right:8px" align="right">
                                <b>PLA</b>
                            </td>
                            <td>
                                <select name="pla" class="input">
                                    <option></option>
                                    <option value="Y">Listed</option>
                                    <option value="N">Unlisted</option>
                                </select>
                            </td>
                            <td style="padding-right:8px" align="right">
                                <b>Adwords API Result</b>
                            </td>
                            <td>
                                <select name='adwapi' class='input'>
                                    <option></option>
                                    <option value='1'>Success only</option>
                                    <option value='0'>Fail only</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-right:8px" align="right">
                                <b>PLA API Result</b>
                            </td>
                            <td>
                                <select name='plaapi' class='input'>
                                    <option></option>
                                    <option value='IS'>Insert Success</option>
                                    <option value='IW'>Insert Warning</option>
                                    <option value='IF'>Insert Fail</option>
                                    <option value='DS'>Delete Success</option>
                                    <option value='DW'>Delete Warning</option>
                                    <option value='DF'>Delete Fail</option>
                                </select>
                            </td>
                            <td style="padding-right:8px" align="right">
                                <b>Results Per Page</b>
                            </td>
                            <td>
                                <select name="limit" class="input">
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-right:8px" align="right">
                                <b><?= $lang['price_type'] ?></b>
                            </td>
                            <td>
                                <select name="auto_price" class="input">
                                    <option value=""></option>
                                    <option value="N">Manual</option>
                                    <option value="Y">Auto</option>
                                </select>
                            </td>
                            <td style="padding-right:8px" align="right">
                                <b>Surplus Quantity</b>
                            </td>
                            <td>
                                <select id="surplusqty_prefix" name="surplusqty_prefix">
                                    <option value="2"> Smaller <= </option>
                                    <option value="1"> 0 and Smaller <= </option>
                                    <option value="3"> Greater >= </option>
                                </select>
                                <input name="surplusqty" class="input" value="10000" style="width:200px">
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-right:8px" align="right">
                            </td>
                            <td>
                            </td>
                            <td style="padding-right:8px" align="right">
                                <b>Margin</b>
                            </td>
                            <td>
                                <select id="margin_prefix" name="margin_prefix">
                                    <option value="2"> Smaller <= </option>
                                    <option value="1"> greater > 0 and Smaller <= </option>
                                    <option value="3"> Greater >= </option>
                                </select>
                                <input name="margin" class="input" value="" style="width:200px">%
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" style="padding-left:60px">
                                <br><b>* TIP: Refining your search may produce faster result
                                <br>* Results Per Page may give lesser results when used together with PLA/Adwords filters.</b>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                        </tr>
                    </table>
                </div>
                <div id="bulklist" style="<?= $bulklist_display ?>">
                    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
                        <col width="150"> <col width="420"> <col width="170"> <col width="420"> <col>
                            <td></td>
                            <td>
                                <input type="button" onclick="switchSearch()" value="Change Search Type">
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4"> &nbsp;</td>
                        </tr>
                        <tr>
                            <td style="padding-right:8px" align="right">
                                <b><?= $lang["platform_id"] ?></b>
                            </td>
                            <td>
                                <select name="platform_id2">
                                    <option value=""></option>
                                    <?php foreach ($clist as $cobj): ?>
                                        <option value="<?= $cobj->getSellingPlatformId() ?>"> <?= $cobj->getSellingPlatformId() ?> - <?= $cobj->getName() ?></option>
                                    <?php endforeach ?>
                                </select>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-right:8px" align="right">Filter by (only 1 will apply):</td>
                            <td>
                                <textarea rows="5" name="mskulist" placeholder="Master SKU, separated by next line"><?= ($this->input->get('mskulist')) ?: "" ?></textarea>
                            </td>
                            <td style="padding-right:8px" align="center">OR</td>
                            <td>
                                <textarea rows="5" name="skulist" placeholder="Local SKU, separated by next line"><?= ($this->input->get('skulist')) ?: "" ?></textarea>
                            </td>
                            <td rowspan="3" align="center">
                                <input type="button" value="<?= $lang["cmd_search_button"] ?>" onClick="if (CheckForm(this.form)) this.form.submit();">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                        </tr>
                    </table>
                </div>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data tb_list">
                    <thead>
                        <tr class="header">
                            <td height="20">
                                <img src="<?= base_url() ?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));">
                            </td>
                            <td title="<?= $lang["platform_id"] ?>">
                                <a href="#" onClick="SortCol(document.fm, 'platform_id', '<?= $xsort["platform_id"] ?>')">
                                    <?= $lang["plat_id"] ?> <?= $sortimg["platform_id"] ?>
                                </a>
                            </td>
                            <td title="<?= $lang["master_sku"] ?>">
                                <a href="#" onClick="SortCol(document.fm, 'master_sku', '<?= $xsort["master_sku"] ?>')">
                                    <?= $lang["master_sku"] ?> <?= $sortimg["master_sku"] ?>
                                </a>
                            </td>
                            <td title="<?= $lang['sku'] ?>">
                                <a href="#" onclick="SortCol(document.fm, 'sku', '<?= $xsort["sku"] ?>')">
                                    <?= $lang['sku'] ?><?= $sortimg['sku'] ?>
                                </a>
                            </td>
                            <td title="<?= $lang["product_name"] ?>">
                                <a href="#" onClick="SortCol(document.fm, 'prod', '<?= $xsort["prod_name"] ?>')">
                                    <?= $lang["product_name"] ?> <?= $sortimg["prod_name"] ?>
                                </a>
                            </td>
                            <td title="<?= $lang["clearance"] ?>">
                                <a href="#" onClick="SortCol(document.fm, 'clearance', '<?= $xsort["clearance"] ?>')">
                                    <?= $lang["clearance"] ?> <?= $sortimg["clearance"] ?>
                                </a>
                            </td>
                            <td title="<?= $lang["listing_status"] ?>">
                                <?= $lang["listing_status"] ?> <?= $sortimg["listing_status"] ?>
                            </td>
                            <td title="<?= $lang["website_quantity"] ?>">
                                <a href="#" onClick="SortCol(document.fm, 'website_quantity', '<?= $xsort["website_quantity"] ?>')">
                                    <?= $lang["ws_qty"] ?> <?= $sortimg["website_quantity"] ?>
                                </a>
                            </td>
                            <td title="<?= $lang["website_status"] ?>">
                                <a href="#" onClick="SortCol(document.fm, 'website_status', '<?= $xsort["website_status"] ?>')">
                                    <?= $lang["ws_status"] ?> <?= $sortimg["website_status"] ?>
                                </a>
                            </td>
                            <td title="Surplus Qty"><a href="#" onClick="SortCol(document.fm, 'surplus_quantity', '<?= $xsort["surplus_quantity"] ?>')">Surplus Qty<?= $sortimg["surplus_quantity"] ?></a>
                            </td>
                            <td title="<?= $lang["suppstatus"] ?>">
                                <a href="#" onClick="SortCol(document.fm, 'suppstatus', '<?= $xsort["suppstatus"] ?>')">
                                    <?= $lang["suppstatus"] ?> <?= $sortimg["suppstatus"] ?>
                                </a>
                            </td>
                            <td title="<?= $lang["purchaser_updated_date"] ?>">
                                <a href="#" onClick="SortCol(document.fm, 'purchaser_updated_date', '<?= $xsort["purchaser_updated_date"] ?>')">
                                    <?= $lang["updated"] ?> <?= $sortimg["purchaser_updated_date"] ?>
                                </a>
                            </td>
                            <td title="PLA" align="center">
                                PLA <br>
                                <input type="checkbox" id="chkpla" name="chkpla" onClick="checkall_ele('<?= $this->input->get("pfid") ?>','chkpla','is_advertised[<?= $platform_id ?>][]');">
                            </td>
                            <td title="pla result" align="center">
                                PLA Result
                            </td>
                            <td title="Adwords" align="center">
                                Adwords <br>
                                <input type="checkbox" id="chkadw" name="chkadw" onClick="checkall_ele('<?= $this->input->get("pfid") ?>','chkadw','google_adwords[<?= $platform_id ?>][]');">
                            </td>
                            <td title="<?= $lang['auto_restock'] ?>" align="center">
                                <?= $lang['auto_restock'] ?>
                            </td>
                            <td title="<?= $lang["total_cost"] ?>">
                                <?= $lang["cost"] ?>
                            </td>
                            <td title="<?= $lang["price_type"] ?>">
                                <?= $lang["price_type"] ?>
                            </td>
                            <td title="<?= $lang["vb_price"] ?>">
                                <a href="#" onClick="SortCol(document.fm, 'vb_price', '<?= $xsort["vb_price"] ?>')">
                                    <?= $lang["vb_price"] ?> <?= $sortimg["vb_price"] ?>
                                </a>
                                <br/><span class="special" style="font-size:7pt"></span>
                            </td>
                            <td title="<?= $lang["selling_price"] ?>">
                                <a href="#" onClick="SortCol(document.fm, 'price', '<?= $xsort["price"] ?>')">
                                    <?= $lang["price"] ?> <?= $sortimg["price"] ?>
                                </a>
                                <br/><span class="special" style="font-size:7pt"></span>
                            </td>
                            <td title="<?= $lang["profit"] ?>">
                                <a href="#" onClick="SortCol(document.fm, 'profit', '<?= $xsort["profit"] ?>')">
                                    <?= $lang["profit"] ?> <?= $sortimg["profit"] ?>
                                </a>
                            </td>
                            <td title="<?= $lang["profit_margin"] ?>">
                                <a href="#" onClick="SortCol(document.fm, 'margin', '<?= $xsort["margin"] ?>')">
                                    <?= $lang["margin"] ?> <?= $sortimg["margin"] ?>
                                </a>
                            </td>
                            <td title="<?= $lang["check_all"] ?>">
                                <input type="checkbox" name="chkall" value="1" onClick="checkall(document.fm_edit, this);">
                            </td>
                        </tr>
                        <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                                <input name="msku" class="input" value="<?= htmlspecialchars($this->input->get("msku")) ?>">
                            </td>
                            <td>
                                <input name="sku" class="input" value="<?= htmlspecialchars($this->input->get('sku')) ?>">
                            </td>
                            <td>
                                <input name="prod_name" class="input" value="<?= htmlspecialchars($this->input->get("prod_name")) ?>">
                            </td>
                            <td>
                                <select name="clear" class="input">
                                    <option value=""></option>
                                    <option value="0"><?= $lang["no"] ?></option>
                                    <option value="1"><?= $lang["yes"] ?></option>
                                </select>
                            </td>
                            <td>
                                <select name="liststatus" class="input">
                                    <option value=""></option>
                                    <option value="L"]><?= $lang["listed"] ?></option>
                                    <option value="N"]><?= $lang["not_listed"] ?></option>
                                </select>
                            </td>
                            <td>
                                <input name="wsqty" style="width:70px" value="<?= htmlspecialchars($this->input->get("wsqty")) ?>">
                            </td>
                            <td>
                                <select name="wsstatus" class="input">
                                    <option value=""></option>
                                    <option value="I"><?= $lang['instock'] ?></option>
                                    <option value="O"><?= $lang['outstock'] ?></option>
                                    <option value="P"><?= $lang['pre-order'] ?></option>
                                    <option value="A"><?= $lang['arriving'] ?></option>
                                </select>
                            </td>
                            <td>&nbsp;</td>
                            <td>
                                <select name="suppstatus" class="input">
                                    <option value=""></option>
                                    <option value="A"><?= $lang["available"] ?></option>
                                    <option value="C"><?= $lang["stock_constraint"] ?></option>
                                    <option value="O"><?= $lang["temp_out_of_stock"] ?></option>
                                    <option value="L"><?= $lang["last_lot"] ?></option>
                                    <option value="D"><?= $lang["discontinued"] ?></option>
                                </select>
                            </td>
                            <td>
                                <!-- <input name="purcupdate" class="input" value="<?= htmlspecialchars($this->input->get("purcupdate")) ?>"> -->
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <select name="auto_restock" class="input">
                                    <option value="">
                                    <option value="0"<?=$selected_aw["0"]?>>OFF</option>
                                    <option value="1"<?=$selected_aw["1"]?>>ON</option>
                                </select>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="center">
                                <input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?= base_url() ?>images/find.gif') no-repeat;">
                                <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
                                <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
                                <input type="hidden" name="search" value='1'>
                            </td>
                        </tr>
                    </thead>
            </form>
            <form name="fm_edit" method="post">
            <?php if ($product_list): ?>
                    <tbody>
                <?php
                    foreach ($product_list as $product):
                        $sku = $product->getSku();
                        $name = $product->getName();
                        $platform_id = $product->getPlatformId();
                        $clearance = $product->getClearance();
                        $listing_status = $product->getListingStatus();
                        $website_quantity = $product->getWebsiteQuantity();
                        $website_status = $product->getWebsiteStatus();
                        $auto_price = $product->getAutoPrice();
                        $is_advertised = $product->getIsAdvertised();
                        $gsc_comment = $product->getGscComment();
                        $enabled_pla_checkbox = $product->getEnabledPlaCheckbox();
                        $pla_status = $product->getGoogleStatus();
                        $pla_last_update_result = $product->getGoogleUpdateResult();
                        $auto_restock = $product->getAutoRestock();
                ?>
                        <tr onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')">
                            <td>&nbsp;</td>
                            <td><?= $platform_id ?></td>
                            <td>
                                <a href='<?= base_url()."marketing/pricing_tools/view/website/".$sku."?target=overview" ?>' target="_blank"><?= $product->getExtSku() ?></a>
                            </td>
                            <td>
                                <?= $sku ?>
                            </td>
                            <td><?= $name ?></td>
                            <td>
                                <select name='<?= "product[{$sku}][clearance]" ?>' title="Clearance Status" class="input">
                                    <option value="0" <?= ($clearance == '0') ? "selected" : '' ?>><?= $lang["no"] ?></option>
                                    <option value="1" <?= ($clearance == '1') ? "selected" : '' ?>><?= $lang["yes"] ?></option>
                                </select>
                            </td>
                            <td>
                                <select name='<?= "price[{$sku}][$platform_id][listing_status]" ?>' title="Listing Status" class="input">
                                    <option value="L" <?= ($listing_status == 'L') ? "selected" : '' ?>><?= $lang["listed"] ?></option>
                                    <option value="N" <?= ($listing_status == 'N') ? "selected" : '' ?>><?= $lang["not_listed"] ?></option>
                                </select>
                            </td>
                            <td>
                                <input name='<?= "product[{$sku}][website_quantity]" ?>' title="Website Quantity" style="width:30px" value='<?= $website_quantity ?>'>
                            </td>
                            <td>
                                <select name='<?= "product[{$sku}][website_status]" ?>' title="Website Status" class="input">
                                    <option value="I" <?= ($website_status == 'I') ? "selected" : '' ?>><?= $lang['instock'] ?></option>
                                    <option value="O" <?= ($website_status == 'O') ? "selected" : '' ?>><?= $lang['outstock'] ?></option>
                                    <option value="P" <?= ($website_status == 'P') ? "selected" : '' ?>><?= $lang['pre-order'] ?></option>
                                    <option value="A" <?= ($website_status == 'A') ? "selected" : '' ?>><?= $lang['arriving'] ?></option>
                                </select>
                            </td>
                            <td>
                                <?= $product->getSurplusQuantity() ?>
                            </td>
                            <td><?= $lang['supplier_status'][$product->getSupplierStatus()] ?></td>
                            <td><?= $product->getModifyOn() ?></td>
                            <td>
                                <input type="checkbox" <?= ($is_advertised === 'Y') ? 'checked' : '' ?> name='<?= "price[{$sku}][{$platform_id}][is_advertised]" ?>' <?= ($enabled_pla_checkbox) ?: 'disabled' ?> value="Y">
                                <?= $gsc_comment ?>
                            </td>
                            <td><a href="#" data-toggle="tooltip" data-placement="top" title="<?= $pla_last_update_result ?>"><?= $pla_status ?></a></td>
                            <td>
                                <!-- $adwords_input $adGroup_status -->
                            </td>
                            <td>
                                <select name='<?= "product[{$sku}][auto_restock]" ?>' title="Auto Restock" class="input" style="width: 50px;">
                                    <option value="0" <?= ($auto_restock == '0') ? "selected" : '' ?>><?= $lang["off"] ?></option>
                                    <option value="1" <?= ($auto_restock == '1') ? "selected" : '' ?>><?= $lang["on"] ?></option>
                                </select>
                            </td>
                            <td>
                                <?= $product->getPlatformCurrencyId() . ' ' . $product->getTotalCost() ?>
                            </td>
                            <td>
                                <select name='<?= "price[{$sku}][{$platform_id}][auto_price]" ?>' title="Price Type" class="input">
                                    <option value="N" <?= ($auto_price == 'N') ? "selected" : '' ?>>Manual</option>
                                    <option value="Y" <?= ($auto_price == 'Y') ? "selected" : '' ?>>Auto</option>
                                    <option value="C" <?= ($auto_price == 'C') ? "selected" : '' ?>>CompReprice</option>
                                </select>
                            </td>
                            <td><?= $product->getPlatformCurrencyId() . ' ' . $product->getVbPrice() ?></td>
                            <td>
                                <?= $product->getPlatformCurrencyId() . ' ' . $product->getPrice() ?>
                                <input type="text" name='<?= "price[{$sku}][{$platform_id}][price]" ?>' onkeyup="value=value.replace(/[^\d.]/g,'');rePrice(this.value, '<?= $platform_id ?>', <?= $sku ?>);">
                            </td>
                            <td><span id='<?= "{$platform_id}_{$sku}_profit" ?>' class="<?= ($product->getProfit() < 0) ? 'negative_margin' : '' ?>"><?= $product->getProfit() ?></span></td>
                            <td><span id='<?= "{$platform_id}_{$sku}_margin" ?>' class="<?= ($product->getMargin() < 0) ? 'negative_margin' : '' ?>"><?= $product->getMargin() ?>%</span></td>
                            <td><input type="checkbox" name='<?= "check[] ?>" ?>' value="<?= $sku.'||'.$platform_id ?>" onclick="Marked(this);"></td>
                        </tr>
                <?php endforeach ?>
                    </tbody>
            <?php endif ?>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-top:5px;">
                    <tr>
                        <td><?= $links ?></td>
                        <td align="right" style="padding-right:8px;">
                            <input type="submit" value="Update" class="button">
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="posted" value="1">
            </form>
            <?= $notice["js"] ?>
    </div>
    <?= $objlist["js"] ?>
    <script>
        InitBrand(document.fm.brand);
        // document.fm.brand.value = '<?=$this->input->get("brand")?>';
        // InitSupp(document.fm.supp);
        // document.fm.supp.value = '<?=$this->input->get("supp")?>';
        ChangeCat('0', document.fm.catid);
        // document.fm.catid.value = '<?=$this->input->get("catid")?>';
        ChangeCat('<?=$this->input->get("catid")?>', document.fm.scatid);
        // document.fm.scatid.value = '<?=$this->input->get("scatid")?>';

         function checklist(checkallboxid, checkname) {
             var parentcheckbox = document.getElementById(checkallboxid);
             var checkeles = document.getElementsByName(checkname);

             for (var i = 0; i < checkeles.length; i++) {
                 var isDisabled = checkeles[i].getAttribute('disabled');
                 if (isDisabled != false) {
                     checkeles[i].checked = parentcheckbox.checked;
                 }
             }
         }

        function rePrice(sp, platform, sku) {
            if (isNaN(sp)) {
                return false;
            }

            remote_url = window.location.protocol + '//' + window.location.hostname + '/marketing/pricing_tools/get_profit_margin_json/' + platform + '/' + sku +'/' + sp;
            $.ajax({
                type: 'POST',
                url: remote_url,
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                success: function(msg)
                {
                    profit = msg.get_profit;
                    margin = msg.get_margin;

                    $("span[id="+platform+"_"+sku+"_profit]").text(profit);
                    $("span[id="+platform+"_"+sku+"_margin]").text(margin+'%');

                    if (margin < 0) {
                        $("span[id="+platform+"_"+sku+"_profit]").addClass("negative_margin");
                        $("span[id="+platform+"_"+sku+"_margin]").addClass("negative_margin");
                    } else {
                        $("span[id="+platform+"_"+sku+"_profit]").removeClass("negative_margin");
                        $("span[id="+platform+"_"+sku+"_margin]").removeClass("negative_margin");
                    }
                },
                error: function(err) {
                }
            });
        }
    </script>
</body>
</html>