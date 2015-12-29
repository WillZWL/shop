<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>js/lytebox.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/lytebox.css" type="text/css" media="screen"/>
    <script type="text/javascript" src="<?= base_url() ?>js/calendar.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/calendar.css" type="text/css" media="all"/>
    <script language="javascript">
        function showBaddr() {
            var fm = document.fm_checkout;
            if (!fm.billaddr.checked) {
                document.getElementById('del_country_id').style.display = "none";
                document.getElementById('del_company').style.display = "none";
                document.getElementById('del_name').style.display = "none";
                document.getElementById('del_address').style.display = "none";
                document.getElementById('del_city').style.display = "none";
                document.getElementById('del_state').style.display = "none";
                document.getElementById('del_postcode').style.display = "none";
                document.getElementById('del_tel').style.display = "none";
                document.getElementById('del_mobile').style.display = "none";
                document.getElementById('del_forename').removeAttribute("notEmpty");
                document.getElementById('del_surname').removeAttribute("notEmpty");
                document.getElementById('del_address_1').removeAttribute("notEmpty");
                document.getElementById('del_city_town').removeAttribute("notEmpty");
                document.getElementById('del_postcode').removeAttribute("validPostal");
            } else {
                document.getElementById('del_country_id').style.display = "";
                document.getElementById('del_company').style.display = "";
                document.getElementById('del_name').style.display = "";
                document.getElementById('del_address').style.display = "";
                document.getElementById('del_city').style.display = "";
                document.getElementById('del_state').style.display = "";
                document.getElementById('del_postcode').style.display = "";
                document.getElementById('del_tel').style.display = "";
                document.getElementById('del_mobile').style.display = "";
                document.getElementById('del_forename').setAttribute("notEmpty", "");
                document.getElementById('del_surname').setAttribute("notEmpty", "");
                document.getElementById('del_address_1').setAttribute("notEmpty", "");
                document.getElementById('del_city_town').setAttribute("notEmpty", "");
                document.getElementById('del_postcode').setAttribute("validPostal", "");
            }
        }
    </script>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title">
                <input type="button" value="<?= $lang["add_button"] ?>" class="button"
                       onclick="Redirect('<?= site_url('order/manual_order') ?>')"> &nbsp;
                <?php
                if ($this->sc['Authorization']->checkAccessRights("ORD001701", "On Hold", 0)) :
                    ?>
                    <input type="button" value="<?= $lang["on_hold_button"] ?>" class="button"
                           onclick="Redirect('<?= site_url('order/manual_order/on_hold') ?>')"> &nbsp;
                <?php
                endif;
                if ($this->sc['Authorization']->checkAccessRights("ORD001702", "Pending", 0)) :
                    ?>
                    <input type="button" value="<?= $lang["pending_button"] ?>" class="button"
                           onclick="Redirect('<?= site_url('order/manual_order/pending') ?>')">
                <?php
                endif;
                ?>
            </td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <col width=50%></col>
        <col width=20%></col>
        <col width=30%></col>
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message" . ($platform_id ? "" : 1)] ?>
            </td>
            <td align="right" style="padding-right:8px">
                <?= $lang["selling_platform"] ?>:
                <select style="width:100px"
                        onChange="Redirect('<?= base_url() ?>order/manual_order/index/'+this.value);">
                    <option></option>
                    <?php
                    $sp_selected[$platform_type] = " SELECTED";
                    foreach ($sp_type_list as $val) :
                        ?>
                        <option value="<?= $val ?>"<?= $sp_selected[$val] ?>><?= ucwords(strtolower($val)); ?></option>
                    <?php
                    endforeach;
                    ?>
                </select>
            </td>
            <td align="right" style="padding-right:8px">
                <?= $lang["selling_platform"] ?>:
                <select style="width:250px" <?= $platform_type ? "" : "disabled" ?>
                        onChange="Redirect('<?= base_url() ?>order/manual_order/index/<?= $platform_type ?>/'+this.value)">
                    <option></option>
                    <?php
                    $sp_selected[$platform_id] = " SELECTED";
                    foreach ($sp_list as $obj) :
                        $id = $obj->getSellingPlatformId();
                        ?>
                        <option
                            value="<?= $id ?>"<?= $sp_selected[$id] ?>><?= $id . " - " . $obj->getName(); ?></option>
                    <?php
                    endforeach;
                    ?>

                </select>
            </td>
        </tr>
    </table>
    <?php
    if ($platform_id) :
        ?>
        <form name="fm_checkout" method="post" onSubmit="return CheckSubmit(this)">
            <table cellpadding="0" cellspacing="0" width="100%" class="bg_row">
                <tr>
                    <td width="15">&nbsp;</td>
                    <td>
                        <br>
                        <table cellpadding="2" cellspacing="0" width="80%" class="bg_row">
                            <tr>
                                <td width="156">&nbsp;</td>
                                <td class="warn"><?= $lang["select_product_exists"] ?></td>
                            </tr>
                            <?php for ($i = 0; $i < 10; $i++) : ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td><input type="hidden" name="soi[<?= $i ?>][sku]" value="<?= htmlspecialchars($_POST["soi"][$i]["sku"]) ?>"><input name="soi[<?= $i ?>][name]" value="<?= htmlspecialchars($_POST["soi"][$i]["name"]) ?>" style="width:60%" READONLY onKeyUp="calcTotal()"> <a href="<?= base_url() ?>order/manual_order/prod_list/<?= $i ?>/<?= $platform_id ?>" rel="lyteframe" rev="width: 1024px; height: 400px; scrolling: auto;" title="Select Product" class="search_button" style="background: url('<?= base_url() ?>images/find.gif') no-repeat;">&nbsp; &nbsp; &nbsp;</a> x <input name="soi[<?= $i ?>][qty]" value="<?= htmlspecialchars($_POST["soi"][$i]["qty"]) ?>" class="int_input" onKeyUp="calcTotal()"> &nbsp; <?= $lang["price"] ?>: <input name="soi[<?= $i ?>][price]" value="<?= htmlspecialchars($_POST["soi"][$i]["price"]) ?>" class="int_input" onKeyUp="calcTotal()">
                                </td>
                            </tr>
                            <?php endfor; ?>
                        </table>
                        <br>
                        <br>
                        <table cellpadding="4" cellspacing="0" width="70%" class="bg_row">
                            <tr>
                            <tr>
                                <td width="156">&nbsp;</td>
                                <td align="right"><?= $lang["sub_total"] ?>: <span id="sub_total"> <?= $default_curr ?> 0.00</span></td>
                            </tr>
                            <tr>
                                <td width="156">&nbsp;</td>
                                <td align="right">
                                    <?= $lang["vat_exempt"] ?>
                                    <input type="checkbox" name="vat_exempt" value="1" onClick="checkvat();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?= $lang["vat"] ?> :   <?= $default_curr ?> <input type="text" name="vat" value="0.00" readonly style="width:50px;">
                                </td>
                            </tr>
                            <tr>
                                <td width="156">&nbsp;</td>
                                <td align="right">
                                    <?= $lang["delivery_charge"] ?>: <span id="delivery_charge"> <?= $default_curr ?>
                                    <input type="text" name="delivery_charge" value="0.00" style="width:50px;" onKeyUp="calcTotal();">
                                <td>
                            </tr>
                            <tr>
                                <td width="156">&nbsp;</td>
                                <td align="right">
                                    <?= $lang["total"] ?>: <span id="total"> <?= $default_curr ?> 0.00</span>
                                </td>
                            </tr>
                            </tr>
                        </table>
                        <br>
                        <br>
                        <table cellpadding="4" cellspacing="0" width="60%" class="bg_row">
                            <tr>
                                <td width="150"><span class="warn">*</span> Email address:</td>
                                <td>
                                    <input name="client[email]" dname="Email Address" class="text"
                                               value="<?= htmlspecialchars($_POST["client"]["email"]) ?>" notEmpty validEmail>
                                    <input type="button" value="Check Email"
                                               onClick="if (document.fm_checkout.elements['client[email]'].value != '') {document.getElementById('a_check').href='<?= base_url() ?>/order/manual_order/check_email?email='+document.fm_checkout.elements['client[email]'].value;document.getElementById('a_check').onclick()}">
                                    <a id="a_check" href="<?= base_url() ?>/order/manual_order/check_email/"
                                       rel="lyteframe" rev="width: 300px; height: 275px; scrolling: auto;"
                                       title="Check Email"></a>
                                </td>
                            </tr>
                            <tr>
                                <td>Password: <br> (At least 5 characters)</td>
                                <td><input type="password" dname="Password" name="client[password]" class="text"></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="20px">
                                    <hr>
                                </td>
                            </tr>
                            <tr>
                                <td width="23" colspan="2">
                                    <input type="checkbox" name="billaddr" id="billaddr" onclick="showBaddr()" value="1"/>
                                    Click here if Billing Address and Cardholder Name is different from Delivery Detail
                                </td>
                            </tr>
                            <tr>
                                <td height="10px"></td>
                            </tr>
                            <tr>
                                <td><span class="warn">*</span> Location:</td>
                                <td>
                                    <select name="client[country_id]" class="text">
                                        <?php
                                        if ($country_list) :
                                            if ($_POST["client"]["country_id"]) :
                                                $c_selected[$_POST["client"]["country_id"]] = " SELECTED";
                                            else :
                                                $c_selected[$pbv_obj->getPlatformCountryId()] = " SELECTED";
                                            endif;
                                            foreach ($country_list as $id => $name) :
                                            ?>
                                            <option value="<?= $id ?>"<?= $c_selected[$id] ?>><?= $name ?>
                                        <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td> Register Company Name:</td>
                                <td><input name="client[companyname]" dname="Company" class="text2"
                                           value="<?= htmlspecialchars($_POST["client"]["companyname"]) ?>"></td>
                            </tr>
                            <tr>
                                <td><span class="warn">*</span> Register Name:</td>
                                <td>
                                    <?php $t_selected[$_POST["client"]["title"]] = " SELECTED";?>
                                    <select name="client[title]">
                                        <option value="Mr"<?= $t_selected["Mr"] ?>>Mr
                                        <option value="Mrs"<?= $t_selected["Mrs"] ?>>Mrs
                                        <option value="Miss"<?= $t_selected["Miss"] ?>>Miss
                                        <option value="Dr"<?= $t_selected["Dr"] ?>>Dr
                                    </select>
                                    <input name="client[forename]" dname="First Name" class="text2"
                                           value="<?= htmlspecialchars($_POST["client"]["forename"]) ?>" notEmpty>
                                    <input name="client[surname]" dname="Last Name" class="text2"
                                           value="<?= htmlspecialchars($_POST["client"]["surname"]) ?>" notEmpty>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="warn">*</span> Billing Address:</td>
                                <td>
                                    <input name="client[address_1]" dname="Address Line 1" class="text"
                                           value="<?= htmlspecialchars($_POST["client"]["address_1"]) ?>" notEmpty>
                                    <input name="client[address_2]" dname="Address Line 2" class="text"
                                           value="<?= htmlspecialchars($_POST["client"]["address_2"]) ?>">
                                </td>
                            </tr>
                            <tr>
                                <td><span class="warn">*</span> City/Town:</td>
                                <td>
                                    <input name="client[city]" dname="City" class="text"
                                           value="<?= htmlspecialchars($_POST["client"]["city"]) ?>" notEmpty>
                                </td>
                            </tr>
                            <tr>
                                <td> State:</td>
                                <td>
                                    <input name="client[state]" dname="State" class="text"
                                           value="<?= htmlspecialchars($_POST["client"]["state"]) ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>Postal Code:</td>
                                <td>
                                    <input name="client[postcode]" dname="Postcode" class="text"
                                           value="<?= htmlspecialchars($_POST["client"]["postcode"]) ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>Telephone number:</td>
                                <td>
                                    <input name="client[tel_1]" dname="Telephone Country Code" size="3"
                                           value="<?= htmlspecialchars($_POST["client"]["tel_1"]) ?>"> -
                                    <input name="client[tel_2]" dname="Telephone Area Code" size="3"
                                           value="<?= htmlspecialchars($_POST["client"]["tel_2"]) ?>"> -
                                    <input name="client[tel_3]" dname="Telephone" style="width:190px"
                                           value="<?= htmlspecialchars($_POST["client"]["tel_3"]) ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>Mobile number:</td>
                                <td>
                                    <input name="client[mtel_1]" dname="Mobile Country Code" size="3" value=""> -
                                    <input name="client[mtel_2]" dname="Mobile Area Code" size="3" value=""> -
                                    <input name="client[mtel_3]" dname="Mobile" style="width:190px"
                                           value="<?= htmlspecialchars($_POST["client"]["mtel_1"]) . htmlspecialchars($_POST["client"]["mtel_2"]) . htmlspecialchars($_POST["client"]["mtel_3"]) ?>">
                                </td>
                            </tr>
                            <tr>
                                <td height="10px"></td>
                            </tr>
                            <tr id='del_country_id' style="display:none">
                                <td><span class="warn">*</span> Delivery Location:</td>
                                <td>
                                    <select name="client[del_country_id]" class="text">
                                        <?php
                                        if ($country_list) :
                                            if ($_POST["client"]["del_country_id"]) :
                                                $c_selected[$_POST["client"]["del_country_id"]] = " SELECTED";
                                            else :
                                                $c_selected[$pbv_obj->getPlatformCountryId()] = " SELECTED";
                                            endif;
                                            foreach ($country_list as $id => $name) :
                                            ?>
                                            <option value="<?= $id ?>"<?= $c_selected[$id] ?>><?= $name ?>
                                        <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr id='del_company' style="display:none">
                                <td> Delivery Company Name:</td>
                                <td>
                                    <input name="client[del_company]" dname="Delivery Company" class="text2" value="<?= htmlspecialchars($_POST["client"]["del_company"]) ?>">
                                </td>
                            </tr>
                            <tr id='del_name' style="display:none">
                                <td><span class="warn">*</span> Delivery Name:</td>
                                <td>
                                    <?php $t_selected[$_POST["client"]["del_title"]] = " SELECTED";?>
                                    <select name="client[del_title]">
                                        <option value="Mr"<?= $t_selected["Mr"] ?>>Mr
                                        <option value="Mrs"<?= $t_selected["Mrs"] ?>>Mrs
                                        <option value="Miss"<?= $t_selected["Miss"] ?>>Miss
                                        <option value="Dr"<?= $t_selected["Dr"] ?>>Dr
                                    </select>
                                    <input id="del_forename" name="client[del_forename]" dname="Delivery First Name"
                                           class="text2"
                                           value="<?= htmlspecialchars($_POST["client"]["del_forename"]) ?>">
                                    <input id="del_surname" name="client[del_surname]" dname="Delivery Last Name"
                                           class="text2"
                                           value="<?= htmlspecialchars($_POST["client"]["del_surname"]) ?>">
                                </td>
                            </tr>
                            <tr id='del_address' style="display:none">
                                <td><span class="warn">*</span> Delivery Address:</td>
                                <td>
                                    <input id="del_address_1" name="client[del_address_1]"
                                           dname="Delivery Address Line 1" class="text"
                                           value="<?= htmlspecialchars($_POST["client"]["del_address_1"]) ?>">
                                    <input name="client[del_address_2]" dname="Delivery Address Line 2" class="text"
                                           value="<?= htmlspecialchars($_POST["client"]["del_address_2"]) ?>">
                                </td>
                            </tr>
                            <tr id='del_city' style="display:none">
                                <td><span class="warn">*</span> Delivery City/Town:</td>
                                <td>
                                    <input id="del_city_town" name="client[del_city]" dname="Delivery City" class="text"
                                           value="<?= htmlspecialchars($_POST["client"]["del_city"]) ?>">
                                </td>
                            </tr>
                            <tr id='del_state' style="display:none">
                                <td> Delivery State:</td>
                                <td>
                                    <input name="client[del_state]" dname="Delivery State" class="text"
                                           value="<?= htmlspecialchars($_POST["client"]["del_state"]) ?>">
                                </td>
                            </tr>
                            <tr id='del_postcode' style="display:none">
                                <td>Delivery Postal Code:</td>
                                <td>
                                    <input name="client[del_postcode]" dname="Delivery Postcode" class="text"
                                           value="<?= htmlspecialchars($_POST["client"]["del_postcode"]) ?>">
                                </td>
                            </tr>
                            <tr id='del_tel' style="display:none">
                                <td>Delivery Telephone number:</td>
                                <td>
                                    <input name="client[del_tel_1]" dname="Delivery Telephone Country Code" size="3"
                                           value="<?= htmlspecialchars($_POST["client"]["del_tel_1"]) ?>"> -
                                    <input name="client[del_tel_2]" dname="Delivery Telephone Area Code" size="3"
                                           value="<?= htmlspecialchars($_POST["client"]["del_tel_2"]) ?>"> -
                                    <input name="client[del_tel_3]" dname="Delivery Telephone" style="width:190px"
                                           value="<?= htmlspecialchars($_POST["client"]["del_tel_3"]) ?>">
                                </td>
                            </tr>
                            <tr id='del_mobile' style="display:none">
                                <td>Delivery Mobile number:</td>
                                <td>
                                    <input name="client[del_mtel_1]" dname="Delivery Mobile Country Code" size="3"
                                           value=""> -
                                    <input name="client[del_mtel_2]" dname="Delivery Mobile Area Code" size="3"
                                           value=""> -
                                    <input name="client[del_mtel_3]" dname="Delivery Mobile" style="width:190px"
                                           value="<?= htmlspecialchars($_POST["client"]["del_mtel_1"]) . htmlspecialchars($_POST["client"]["del_mtel_2"]) . htmlspecialchars($_POST["client"]["del_mtel_3"]) ?>">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" height="20px">
                                    <hr>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="warn">*</span> Reason For Order:</td>
                                <td>
                                    <select name="so_extend[order_reason]" dname="Reason For Order" notEmpty>
                                        <option value=""></option>
                                        <?php
                                        $or_selected[$_POST["so_extend"]["order_reason"]] = " SELECTED";
                                        foreach ($order_reason_list as $reason) :
                                            print "<option value='" . $reason->getReasonId() . "' " . $or_selected[$reason->getReasonId()] . ">" . $reason->getReasonDisplayName() . "</option>";
                                        endforeach;
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Additional Note:</td>
                                <td>
                                    <input type="text" name="so_extend[notes]" class="input"
                                           value="<?= htmlspecialchars($_POST["so_extend"]["notes"]) ?>" maxLen="255">
                                </td>
                            </tr>
                            <tr>
                                <td>Promotion Code:</td>
                                <td>
                                    <input type="text" name="promotion_code" value="" size="20" maxLen="20">
                                </td>
                            </tr>
                            <tr height="20px">
                            </tr>
                            <tr>
                                <td><span class="warn">*</span> Payment Date:</td>
                                <td>
                                    <input name="payment_date" dname="Payment Date"
                                           value="<?= htmlspecialchars($_POST['payment_date']) ?>" notEmpty>
                                    <img src="/images/cal_icon.gif" class="pointer"
                                         onclick="showcalendar(event, document.fm_checkout.payment_date, false, false, false, '2010-01-01', '<?= date("Y-m-d") ?>')"
                                         align="absmiddle">
                                </td>
                            </tr>
                            <?php if ($platform_type == 'AMAZON' || $platform_type == 'EBAY' || $platform_type == 'RAKUTEN') : ?>
                                <tr>
                                    <td><span class="warn">*</span> Platform Order Reference Number:</td>
                                    <td>
                                        <input name="platform_order_id" dname="Platform Order Reference Number"
                                               class="text"
                                               value="<?= htmlspecialchars($_POST['platform_order_id']) ?>" <?php if ($platform_type == 'AMAZON' || $platform_type == 'EBAY') {
                                            echo "notEmpty";
                                        }?>>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($platform_type == 'QOO10') : ?>
                                <tr>
                                    <td><span class="warn">*</span> Platform Order Number [Qoo10 packNo]:</td>
                                    <td>
                                        <input name="platform_order_id" dname="Platform Order Reference Number"
                                               class="text"
                                               value="<?= htmlspecialchars($_POST['platform_order_id']) ?>" <?php if ($platform_type == 'QOO10') {
                                            echo "notEmpty";
                                        }?>>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td><span class="warn">*</span> Payment Mode:</td>
                                <td>
                                    <select name="payment_gateway" dname="Payment Mode" class="text" notEmpty>
                                        <option></option>
                                        <?php
                                        if ($payment_gateway_list) :
                                            if ($_POST["payment_gateway"]) :
                                                $c_selected[$_POST["payment_gateway"]] = " SELECTED";
                                            endif;
                                            foreach ($payment_gateway_list as $obj) :
                                        ?>
                                            <option value="<?= $obj->getPaymentGatewayId() ?>"<?= $c_selected[$obj->getPaymentGatewayId()] ?>><?= $obj->getName() ?>
                                        <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="warn">*</span>
                                <?php
                                if ($platform_type == 'QOO10') :
                                    echo "Transaction Reference [Qoo10 orderNo]: ";
                                else :
                                    echo "Payment Transaction Reference: ";
                                endif;
                                ?>
                                </td>
                                <td>
                                    <input name="txn_id" dname="Payment Transaction Reference" class="text" value="<?= htmlspecialchars($_POST['txn_id']) ?>" notEmpty>
                                </td>
                            </tr>
                            <tr height="20px">
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:center"><input type="submit" value="Procced"></td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="delivery" value="<?= $dc_default["courier"] ?>">
            <input type="hidden" name="posted" value="1">
            <input type="hidden" name="client[id]" value="<?= $_POST["client"]["id"] ?>">
        </form>
        <script type="text/javascript">
            function checkvat() {

                if (document.fm_checkout.vat_exempt.checked) {
                    document.fm_checkout.elements["vat"].disabled = true;
                } else {
                    document.fm_checkout.elements["vat"].disabled = false;
                }
                calcTotal();
            }

            function calcTotal() {
                var vat_rate = <?=$pbv_obj->getVatPercent()?>;
                var declared_pcent = 100;
                var subtotal = 0;
                var total = 0;
                var vat = 0;
                var st = 0;
                for (var i = 0; i < 10; i++) {

                    if (document.fm_checkout.elements["soi[" + i + "][qty]"].value != "" && document.fm_checkout.elements["soi[" + i + "][price]"].value != "") {
                        var cur_price = document.fm_checkout.elements["soi[" + i + "][price]"].value * 1;
                        var cur_qty = document.fm_checkout.elements["soi[" + i + "][qty]"].value * 1;
                        var cur_subtotal = cur_qty * cur_price;
                        subtotal += cur_subtotal;
                        <?php
                            if ($pbv_obj->getPlatformCountryId() == "AU") {
                        ?>
                        var declared = Math.min(cur_subtotal, 800);
                        <?php
                            } else {
                        ?>
                        var declared = cur_subtotal * declared_pcent / 100;
                        <?php
                            }
                        ?>
                        vat += declared * vat_rate / 100;
                    }
                }

                st = subtotal - vat;
                document.getElementById("sub_total").innerHTML = st.toFixed(2);
                document.fm_checkout.vat.value = vat.toFixed(2);
                delivery = document.fm_checkout.delivery_charge.value * 1;
                total = st + delivery;
                if (!document.fm_checkout.vat_exempt.checked) {
                    total += vat;
                }
                document.getElementById("total").innerHTML = total.toFixed(2);
            }

            function response(str) {
                var fm = document.fm_checkout;
                client = fetch_params('?' + str);
                fm.elements['client[id]'].value = client["id"];
                fm.elements['client[country_id]'].value = client["country_id"];
                fm.elements['client[companyname]'].value = client['companyname'];
                fm.elements['client[title]'].value = client["title"];
                fm.elements['client[forename]'].value = client["forename"];
                fm.elements['client[surname]'].value = client["surname"];
                fm.elements['client[address_1]'].value = client["address_1"];
                fm.elements['client[address_2]'].value = client["address_2"];
                fm.elements['client[city]'].value = client["city"];
                fm.elements['client[state]'].value = client["state"];
                fm.elements['client[postcode]'].value = client["postcode"];
                fm.elements['client[tel_1]'].value = client["tel_1"];
                fm.elements['client[tel_2]'].value = client["tel_2"];
                fm.elements['client[tel_3]'].value = client["tel_3"];
                fm.elements['client[mtel_3]'].value = client["mobile"];
                fm.elements['client[del_country_id]'].value = client["del_country_id"];
                fm.elements['client[del_company]'].value = client["del_company"];
                fm.elements['client[del_address_1]'].value = client["del_address_1"];
                fm.elements['client[del_address_2]'].value = client["del_address_2"];
                fm.elements['client[del_city]'].value = client["del_city"];
                fm.elements['client[del_state]'].value = client["del_state"];
                fm.elements['client[del_postcode]'].value = client["del_postcode"];
                fm.elements['client[del_tel_1]'].value = client["del_tel_1"];
                fm.elements['client[del_tel_2]'].value = client["del_tel_2"];
                fm.elements['client[del_tel_3]'].value = client["del_tel_3"];
                fm.elements['client[del_mtel_3]'].value = client["del_mobile"];
                fm.elements['client[password]'].disabled = true;
                fm.elements['client[email]'].focus();

            }

            function additem(str, line, curprice) {
                var fm = document.fm_checkout;
                prod = fetch_params('?' + str);
                fm.elements["soi[" + line + "][sku]"].value = prod["sku"];
                fm.elements["soi[" + line + "][name]"].value = prod["name"];
                fm.elements["soi[" + line + "][price]"].value = curprice;
                if (fm.elements["soi[" + line + "][qty]"].value * 1 == 0) {
                    fm.elements["soi[" + line + "][qty]"].value = 1;
                }
                fm.elements["soi[" + line + "][price]"].focus();
            }

            function CheckSubmit(fm) {
                return CheckForm(fm);
            }

            calcTotal();
        </script>
    <?php
    endif;
    ?>
</div>
<?= $notice["js"] ?>
</body>
</html>