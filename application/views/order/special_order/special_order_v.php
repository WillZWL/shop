<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
<script type="text/javascript" src="<?=base_url()?>js/jquery-1.9.1.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" language="javascript" src="<?=base_url()?>js/lytebox.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/lytebox.css" type="text/css" media="screen" />
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["title"]?></td>
        <td width="650" align="right" class="title">
            <input type="button" value="<?=$lang["add_button"]?>" class="button" onClick="Redirect('<?= $current_path ?>')"> &nbsp;
            <input type="button" value="<?=$lang["on_hold_button"]?>" class="button" onClick="Redirect('<?= $current_path ?>/on_hold')"> &nbsp;
<?php if (check_app_feature_access_right($app_id, "ORD001101_aps_payment_order_page")) : ?>
        <input type="button" style="width:220px" value="<?=$lang["sale_aps_button"]?>" class="button" onClick="Redirect('<?= $current_path ?>/on_hold/aps_payment')"> &nbsp;
<?php endif; ?>
        <input type="button" value="<?=$lang["pending_button"]?>" class="button" onClick="Redirect('<?= $current_path ?>/pending')"></td>
    </tr>
    <tr>
        <td height="2" class="line"></td>
        <td height="2" class="line"></td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
    <col width='50%'></col><col width='20%'></col><col width='30%'></col>
    <tr>
        <td height="70" style="padding-left:8px">
            <b style="font-size:14px"><?=$lang["header"]?></b><br>
            <?=$lang["header_message".($platform_id?"":1)]?>
        </td>
        <td align="right" style="padding-right:8px">
            <?=$lang["selling_platform"]?>:
            <select style="width:100px" onChange="Redirect('<?= $current_path ?>/index/'+this.value);">
                <option></option>
                <?php
                if ($sp_type_list) :
                    $sp_selected[$platform_type] = " SELECTED";
                    foreach($sp_type_list as $key=>$val) :
                ?>
                    <option value="<?=$val?>"<?=$sp_selected[$val]?>><?=ucwords(strtolower($val));?></option>
                <?php
                    endforeach;
                endif;
                ?>
            </select>
        </td>
        <td align="right" style="padding-right:8px">
            <?=$lang["selling_platform"]?>:
            <select style="width:250px" <?=$platform_type?"":"disabled"?> onChange="Redirect('<?= $current_path ?>/index/<?=$platform_type?>/'+this.value)">
                <option></option>
                <?php
                    $sp_selected[$platform_id] = " SELECTED";
                    if ($sp_list) :
                        foreach($sp_list as $obj) :
                            $id = $obj->getSellingPlatformId();
                ?>
                    <option value="<?=$id?>"<?=$sp_selected[$id]?>><?=$id." - ".$obj->getName();?></option>
                <?php
                        endforeach;
                    endif;
                ?>
            </select>
        </td>
    </tr>
</table>
<?php if ($platform_id) : ?>
<form name="fm_checkout" method="post" onSubmit="return CheckSubmit(this)">
<table cellpadding="0" cellspacing="0" width="100%" class="bg_row">
    <tr>
        <td width="15">&nbsp;</td>
        <td>
            <br>
            <table cellpadding="2" cellspacing="0" width="80%" class="bg_row">
                <tr>
                    <td width="156">&nbsp;</td>
                    <td class="warn"><?=$lang["select_product_exists"]?></td>
                </tr>
                <?php for ($i=0; $i<10; $i++) : ?>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <input type="hidden" name="soi[<?=$i?>][sku]" value="<?=htmlspecialchars($_POST["soi"][$i]["sku"])?>">
                        <input name="soi[<?=$i?>][name]" value="<?=htmlspecialchars($_POST["soi"][$i]["name"])?>" style="width:60%" READONLY onKeyUp="calcTotal()">
                        <a href="<?= $current_path ?>/prod_list/<?=$i?>/<?=$platform_id?>" rel="lyteframe" rev="width: 1024px; height: 400px; scrolling: auto;" title="Select Product" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;">&nbsp; &nbsp; &nbsp;</a> x
                        <input name="soi[<?=$i?>][qty]" value="<?=htmlspecialchars($_POST["soi"][$i]["qty"])?>" class="int_input" onKeyUp="calcTotal()"> &nbsp;
                        <?=$lang["price"]?>: <input name="soi[<?=$i?>][price]" value="<?=htmlspecialchars($_POST["soi"][$i]["price"])?>" class="int_input" onKeyUp="calcTotal()">
                    </td>
                </tr>
                <?php endfor; ?>
            </table>
            <br>
            <table cellpadding="4" cellspacing="0" width="70%" class="bg_row">
            <tr>
                <tr>
                    <td width="156">&nbsp;</td>
                    <td align="right"><?=$lang["sub_total"]?>: <span id="sub_total"> <?=$default_curr?> 0.00</span></td>
                </tr>
                <tr>
                    <td width="156">&nbsp;</td>
                    <td align="right">
                        <?=$lang["delivery_charge"]?>: <span id="delivery_charge"> <?=$default_curr?>
                        <input type="text" name="delivery_charge" value="0.00" style="width:50px;" onKeyUp="calcTotal();">
                    <td>
                </tr>
                <tr>
                    <td width="156">&nbsp;</td>
                    <td align="right"><?=$lang["total"]?>: <span id="total"> <?=$default_curr?> 0.00</span></td>
                </tr>
            </tr>
            </table>
<br>
<br>
<table cellpadding="4" cellspacing="0" width="100%" class="bg_row">
    <tr>
        <td width="50%">
            <table cellpadding="4" cellspacing="0" width="100%" class="bg_row">
                <tr>
                    <td width="150"><span class="warn">*</span> Email address: </td>
                    <td>
                        <input name="client[email]" dname="Email Address" class="text" value="<?=htmlspecialchars($_POST["client"]["email"])?>" notEmpty validEmail>
                        <input type="button" value="Check Email" onClick="if (document.fm_checkout.elements['client[email]'].value != '') {document.getElementById('a_check').href='<?= $current_path ?>/check_email?email='+document.fm_checkout.elements['client[email]'].value;document.getElementById('a_check').onclick();}">
                        <a id="a_check" href="<?= $current_path ?>/check_email" rel="lyteframe" rev="width: 300px; height: 275px; scrolling: auto;" title="Check Email"></a>
                        Please Check Email First
                    </td>
                </tr>
                <tr>
                    <td><span class="warn">*</span> Location: </td>
                    <td>
                        <select disabled name="client[delivery_country_id]" class="text">
                            <?php
                                if ($country_list) :
                                    if ($_POST["client"]["delivery_country_id"]) :
                                        $c_selected[$_POST["client"]["delivery_country_id"]] = " SELECTED";
                                    else :
                                        $c_selected[$pbv_obj->getPlatformCountryId()] = " SELECTED";
                                    endif;
                                    foreach ($country_list as $id=>$name) :
                            ?>
                            <option value="<?=$id?>"<?=$c_selected[$id]?>><?=$name?>
                            <?php
                                    endforeach;
                                endif;
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td> Register Company Name: </td>
                    <td>
                        <input disabled name="client[delivery_company]" dname="Company" class="text2" value="<?=htmlspecialchars($_POST["client"]["delivery_company"])?>">
                    </td>
                </tr>
                <tr>
                    <td><span class="warn">*</span> Register Name: </td>
                    <td>
                        <?$t_selected[$_POST["client"]["title"]] = " SELECTED";?>
                        <select disabled name="client[title]">
                            <option value="Mr"<?=$t_selected["Mr"]?>>Mr
                            <option value="Mrs"<?=$t_selected["Mrs"]?>>Mrs
                            <option value="Miss"<?=$t_selected["Miss"]?>>Miss
                            <option value="Dr"<?=$t_selected["Dr"]?>>Dr
                        </select>
                        <input disabled name="client[delivery_name]" dname="First Name" class="text2" value="<?=htmlspecialchars($_POST["client"]["delivery_name"])?>" notEmpty>
                    </td>
                </tr>
                <tr>
                    <td><span class="warn">*</span> Delivery Address: </td>
                    <td>
                        <input disabled name="client[delivery_address_1]" dname="Address Line 1" class="text" value="<?=htmlspecialchars($_POST["client"]["delivery_address_1"])?>" notEmpty>
                        <input disabled name="client[delivery_address_2]" dname="Address Line 2" class="text" value="<?=htmlspecialchars($_POST["client"]["delivery_address_2"])?>">
                    </td>
                </tr>
                <tr>
                    <td><span class="warn">*</span> City/Town: </td>
                    <td>
                        <input disabled name="client[delivery_city]" dname="City" class="text" value="<?=htmlspecialchars($_POST["client"]["delivery_city"])?>" notEmpty>
                    </td>
                </tr>
                <tr>
                    <td>Postal Code: </td>
                    <td>
                        <input disabled name="client[delivery_postcode]" dname="Postcode" class="text" value="<?=htmlspecialchars($_POST["client"]["delivery_postcode"])?>">
                    </td>
                </tr>
                <tr>
                    <td>Telephone number: </td>
                    <td>
                        <input disabled name="client[tel_1]" dname="Telephone Country Code" size="3" value="<?=htmlspecialchars($_POST["client"]["tel_1"])?>"> -
                        <input disabled name="client[tel_2]" dname="Telephone Area Code"  size="3" value="<?=htmlspecialchars($_POST["client"]["tel_2"])?>"> -
                        <input disabled name="client[tel_3]" dname="Telephone" style="width:190px" value="<?=htmlspecialchars($_POST["client"]["tel_3"])?>">
                    </td>
                </tr>
                <tr>
                    <td><span class="warn">*</span> Reason For Order: </td>
                    <td>
                    <select name="so_extend[order_reason]" id="order_reason" dname="Reason For Order" notEmpty>
                        <option value=""> </option>
<?php
    $or_selected[$_POST["so_extend"]["order_reason"]] = " SELECTED";
    foreach($order_reason_list as $reason) :
        echo "<option value='" . $reason->getReasonId() . "' " . $or_selected[$reason->getReasonId()] . ">" . $reason->getReasonDisplayName() . "</option>";
    endforeach;
?>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>Additional Note: </td>
                    <td>
                        <input type="text" name="so_extend[notes]" class="input" value="<?=htmlspecialchars($_POST["so_extend"]["notes"])?>" maxLen="255" style="width:250px;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center"><input type="submit" value="Proceed"></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
            </table>
        </td>
        <td valign="top">
            <table cellpadding="4" cellspacing="0" width="100%" class="so_list">
                <tbody id="so_list">
                </tbody>
            </table>
        </td>
    </tr>
</table>
        </td>
    </tr>
</table>
    <input type="hidden" name="delivery" value="<?=$dc_default["courier"]?>">
    <input type="hidden" name="posted" value="1">
    <input type="hidden" name="client[id]" value="<?=$_POST["client"]["id"]?>">
    <input type="hidden" name="client[company]" value="<?=$_POST["client"]["bill_company"]?>">
    <input type="hidden" name="client[country_id]" value="<?=$_POST["client"]["bill_country_id"]?>">
    <input type="hidden" name="client[bill_name]" value="<?=$_POST["client"]["bill_name"]?>">
    <input type="hidden" name="client[bill_address]" value="<?=$_POST["client"]["bill_address"]?>">
    <input type="hidden" name="client[city]" value="<?=$_POST["client"]["bill_city"]?>">
    <input type="hidden" name="client[state]" value="<?=$_POST["client"]["bill_state"]?>">
    <input type="hidden" name="client[postcode]" value="<?=$_POST["client"]["bill_postcode"]?>">
    <input type="hidden" name="client[delivery_state]" value="<?=$_POST["client"]["delivery_state"]?>">
</form>

<script type="text/javascript">
addEventListener("message", receiveMessage, false);

function receiveMessage(event)
{
    populateSoData(event.data);
}

var ajax = createAjaxObject();
function populateSoData(client_id)
{
    var url = "<?= $current_path ?>/findAllSoByClientId/" + client_id;

    if (ajax) {
        ajax.open('GET', url, true);
        ajax.onreadystatechange = loadData;
        ajax.send();
    }
}

function loadData()
{
    if (ajax.readyState == 4) {
        if (ajax.status == 200) {
            var refund_status_name = new Array('<?=$lang["refund_status_name"][0]?>', '<?=$lang["refund_status_name"][1]?>', '<?=$lang["refund_status_name"][2]?>', '<?=$lang["refund_status_name"][3]?>','<?=$lang["refund_status_name"][4]?>');
            var content = "";
            var styleName = "";
            var jsonData = JSON.parse(ajax.responseText);
            var statusText = new Array("Inactive", "UnPaid", "Credit-Check", "Paid", "Unknown", "Packed", "Shipped");
            content += "<tr>";
            content += "<th><?=$lang["so_no"]?></th>";
            content += "<th><?=$lang["order_create_date"]?></th>";
            content += "<th><?=$lang["refund_status"]?></th>";
            content += "<th><?=$lang["currency"]?></th>";
            content += "<th><?=$lang["amount"]?></th>";
            content += "<th><?=$lang["status"]?></th>";
            content += "<th>Hold Desc</th>";
            content += "</tr>";
            for (i=0;i<jsonData.length;i++) {
                if (i%2 == 1) {
                    styleName = "class='odd'";
                } else {
                    styleName = "";
                }
                content += "<tr " + styleName + ">";
                content += "<td><a href='/cs/quick_search/view/" + jsonData[i].so_no + "' target='_blank'>" + jsonData[i].so_no + "</a><br>"+jsonData[i].split_level+"</td>";
                content += "<td>" + jsonData[i].order_create_date + "</td>";
                content += "<td>" + refund_status_name[jsonData[i].refund_status] + "</td>";
                content += "<td>" + jsonData[i].currency + "</td>";
                content += "<td>" + jsonData[i].amount + "</td>";
                content += "<td>" + statusText[jsonData[i].status] + "</td>";
                if ( jsonData[i].hold_status == 10 ) {
                content += "<td style='background-color: #B36464;'>Perm Hold</td>";
                } else if( jsonData[i].hold_status == 15 ) {
                content += "<td style='background-color: #B36464;'>split_parent</td>";
                } else {
                content += "<td><input type='radio' name='parent_so_no' value='" + jsonData[i].so_no + "' is_split_child='"+jsonData[i].is_split_child+"' so_amount='"+jsonData[i].amount+"'></td>";
                }
                content += "</tr>";
            }
            $("#so_list").html(content);
        }
    }
}

function checkvat()
{

    if(document.fm_checkout.vat_exempt.checked) {
        document.fm_checkout.elements["vat"].disabled == true;
    } else {
        document.fm_checkout.elements["vat"].disabled == false;
    }
    calcTotal();
}

function calcTotal()
{
    var vat_rate = <?=$pbv_obj->getVatPercent()?>;
    var declared_pcent = 100;
    var subtotal = 0;
    var total = 0;
    var vat = 0;
    var st = 0;
    for(var i=0; i<10;i++)
    {

        if(document.fm_checkout.elements["soi["+i+"][qty]"].value != "" && document.fm_checkout.elements["soi["+i+"][price]"].value != "")
        {
            var cur_price = document.fm_checkout.elements["soi["+i+"][price]"].value*1;
            var cur_qty = document.fm_checkout.elements["soi["+i+"][qty]"].value*1;
            var cur_subtotal = cur_qty * cur_price;
            subtotal += cur_subtotal;
            <?php
                if ($pbv_obj->getPlatformCountryId() == "AU")
                {
            ?>
                var declared = Math.min(cur_subtotal, 800);
            <?php
                }
                else
                {
            ?>
                var declared = cur_subtotal * declared_pcent / 100;
            <?php
                }
            ?>
            vat += declared * vat_rate / 100;
        }
    }

    st = subtotal;
    document.getElementById("sub_total").innerHTML = st.toFixed(2);
    delivery = document.fm_checkout.delivery_charge.value * 1;
    total = st + delivery;
    document.getElementById("total").innerHTML = total.toFixed(2);
}

function response(str)
{
    var fm = document.fm_checkout;
    client = fetch_params('?'+str);
    fm.elements['client[id]'].value = client["client_id"];
    fm.elements['client[title]'].value = client["title"];
    fm.elements['client[tel_1]'].value = client["tel_1"];
    fm.elements['client[tel_2]'].value = client["tel_2"];
    fm.elements['client[tel_3]'].value = client["tel_3"];

    fm.elements['client[country_id]'].value = client["bill_country_id"];
    fm.elements['client[company]'].value = client["bill_company"];
    fm.elements['client[bill_name]'].value = client["bill_name"];
    fm.elements['client[bill_address]'].value = client["bill_address"];
    fm.elements['client[city]'].value = client["bill_city"];
    fm.elements['client[postcode]'].value = client["bill_postcode"];
    fm.elements['client[state]'].value = client["bill_state"];

    fm.elements['client[delivery_country_id]'].value = client["delivery_country_id"];
    fm.elements['client[delivery_company]'].value = client["delivery_company"];
    fm.elements['client[delivery_name]'].value = client["delivery_name"];

    if (client["delivery_address_1"]) {
        var delivery_address = client["delivery_address_1"];
    } else {
        var delivery_address = client["delivery_address"];
    }

    fm.elements['client[delivery_address_1]'].value = delivery_address;
    fm.elements['client[delivery_address_2]'].value = client["delivery_address_2"];
    fm.elements['client[delivery_city]'].value = client["delivery_city"];
    fm.elements['client[delivery_postcode]'].value = client["delivery_postcode"];
    fm.elements['client[delivery_state]'].value = client["delivery_state"];

    fm.elements['client[email]'].focus();
}

function additem(str, line, curprice)
{
    var fm = document.fm_checkout;
    prod = fetch_params('?'+str);
    fm.elements["soi[" + line + "][sku]"].value = prod["sku"];
    fm.elements["soi[" + line + "][name]"].value = prod["name"];
    fm.elements["soi[" + line + "][price]"].value = curprice;
    if (fm.elements["soi[" + line + "][qty]"].value*1 == 0)
    {
        fm.elements["soi[" + line + "][qty]"].value = 1;
    }
    fm.elements["soi[" + line + "][price]"].focus();
}

function CheckSubmit(fm)
{
    var hasItem = 0;
    var so_no_list = $("input:radio[name=parent_so_no]:checked");
    for(var i=0; i<10;i++)
    {
        if(document.fm_checkout.elements["soi["+i+"][qty]"].value != "" && document.fm_checkout.elements["soi["+i+"][price]"].value != "")
        {
            hasItem += 1;
        }
    }

    if (hasItem == 0)
    {
        alert("Please input an item for product!");
        return false;
    }

    if (so_no_list.length <= 0)
    {
        alert("Please select an order!");
        return false;
    }
    else
    {
        var orderreason = document.getElementById("order_reason");
        var selected_orderreason = orderreason.options[orderreason.selectedIndex].value;

        // if order reason is Sales - APS, check to ensure total value not smaller than split child order's value
        if(selected_orderreason == 19 || selected_orderreason == 20 || selected_orderreason == 22)
        {
            for (var i = 0; i < so_no_list.length; i++)
            {
                if(so_no_list[i].checked)
                {
                    if(so_no_list[i].getAttribute('is_split_child') == 1)
                    {
                        var newtotal = document.getElementById("total").innerHTML;
                        newtotal = parseFloat(newtotal);
                        var so_amount = so_no_list[i].getAttribute('so_amount');
                        so_amount = parseFloat(so_amount);

                        if(so_amount > newtotal)
                        {
                            alert("Please re-check the order amount for this split order. It should be split order amount + additional order amount if any.");
                            return false;
                        }
                    }
                }
            }

        }
    }

    return CheckForm(fm);
}

calcTotal();
</script>
<?php endif; ?>
</div>
<?=$notice["js"]?>
</body>
</html>