<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>supply/supplier_helper/js_supplist"></script>
    <style type="text/css">
        .field {
            padding-right: 8px;
            background-color: #ddddff;
            text-align: right;
            height: 22px;
        }

        .fieldwidth {
            width: 20%;
        }

        .value {
            padding-left: 8px;
            background-color: #eeeeff;
            text-align: left;
            height: 22px;
        }

        .valuewidth {
            width: 30%;
        }

        input {
            height: 18px;
        }

        #pst td {
            padding-left: 4px;
            padding-right: 4px;
        }

        #pst th {
            padding-left: 4px;
            padding-right: 4px;
            color: #ffffff;
            text-align: left;
        }

        .button {
            height: 24px;
        }

        .offield {
            padding-left: 8px;
            background-color: #ddddff;
            text-align: left;
            height: 22px;
        }

    </style>
    <script language="javascript">
        <!--
        var rowIndex = <?=count($po_item_list) + 1?>;

        var addok = <?=$po_obj->get_status() == "N"?1:0?>;

        function addRow(id, sku, name) {
            if (addok == 1) {
                var x = document.getElementById(id).insertRow(rowIndex);

                var a = x.insertCell(0);
                var b = x.insertCell(1);
                var c = x.insertCell(2);
                var d = x.insertCell(3);
                var e = x.insertCell(4);
                a.setAttribute("class", "value");
                b.setAttribute("class", "value");
                c.setAttribute("class", "value");
                d.setAttribute("class", "value");
                e.setAttribute("class", "value");
                a.innerHTML = sku + '<input type="hidden" name="sku[]" value="' + sku + '"><input type="hidden" name="line_number[]" value=""><input type="hidden" name="modify_on[]" value=""><input type="hidden" name="create_on[]" value=""><input type="hidden" name="create_at[]" value=""><input type="hidden" name="create_by[]" value="">';
                b.innerHTML = stripslashes(name);
                c.innerHTML = '<input type="text" name="qty[]" value="1" isInteger min=0 class="input" onKeyUp="calculatePrice();" isInteger min=1>';
                d.innerHTML = '<input type="text" name="price[]" value="0.00" isNumber min=0 class="input" onKeyUp="calculatePrice();" isNumber min=0>';
                e.innerHTML = '<input type=\'checkbox\' onClick=\'CalculatePrice()\' name="delete[]" value="1">';
                rowIndex++;
            }
            else {
                alert('<?=$lang["add_not_allowed"]?>');
            }
        }

        function calculatePrice() {
            if (document.getElementsByName('qty[]')) {
                var pos = '';
                var qty = document.getElementsByName('qty[]');
                var i = qty.length;
                var price = document.getElementsByName('price[]');
                var deleteItem = document.getElementsByName('delete[]');
                var total = 0.00;
                for (var j = 0; j < i; j++) {

                    if (!deleteItem[j].checked) {
                        total += qty[j].value * price[j].value;
                    }
                    pos = qty[j];

                    if (qty[j].value == 0) {
                        pos.style.backgroundColor = "#ffff00";
                    }
                    else {
                        pos.style.backgroundColor = "#ffffff";
                    }
                }
                document.getElementById('total').innerHTML = total.toFixed(2);
                document.getElementById('amount').value = total;
            }
        }


        function writeSupplier() {
            for (var i  in supplist) {
                if (i == <?=$po_obj->get_supplier_id()?>) {
                    selected = "SELECTED";
                }
                else {
                    selected = "";
                }
                document.write('<option value="' + i + '" ' + selected + '>' + supplist[i][0] + '</option>');
            }
        }

        function getCurrency(id) {
            if (id != "") {
                document.getElementById('scurr').innerHTML = supplist[id][1];
                document.getElementById('scurr2').innerHTML = " - " + supplist[id][1];
                document.getElementById('currency').value = supplist[id][1];
                document.getElementById('sourcing_region').value = supplist[id][2];
            }
        }

        function stripslashes(str) {
            return (str + '').replace(/\\(.?)/g, function (s, n1) {
                switch (n1) {
                    case '\\':
                        return '\\';
                    case '0':
                        return '\0';
                    case '':
                        return '';
                    default:
                        return n1;
                }
            });
        }
        -->
    </script>
</head>
<?php
$status_array = array("en" => array("N" => "New", "PS" => "Partially Delivered", "FS" => "Fully Delivered", "C" => "Completed", "CL" => "Cancelled"));
$dlvry_mode = array("en" => array("P" => "Self Pickup", "D" => "Supplier's Delivery"));
if ($syslang == "") {
    $syslang = "en";
}
?>
<body class="frame_left" style="width:auto;">
<div id="main" style="width:auto;">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["subtitle"] ?></td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" bgcolor="#BBBBFF" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["subheader"] ?></td>
            <td width="200" valign="top" align="right" style="padding-right:8px">&nbsp;</td>
        </tr>
    </table>
    <form name="fm" action="<?= base_url() ?>order/supplier_order/view/<?= $po_obj->get_po_number() ?>" method="POST"
          onSubmit="CheckForm(this);" target="_parent">
        <input type="hidden" name="posted" value="1">
        <input type="hidden" name="currency" id="currency" value="<?= $po_obj->get_currency(); ?>">
        <input type="hidden" name="amount" id="amount" value="<?= $po_obj->get_amount(); ?>">
        <input type="hidden" name="sourcing_region" id="sourcing_region" value="">
        <input type="hidden" name="po_number" value="<?= $po_obj->get_po_number() ?>">
        <table border="0" cellpadding="0" cellspacing="1" width="100%" bgcolor="#cccccc">
            <tr>
                <td class="field"><?= $lang["po_number"] ?></td>
                <td class="value" colspan="3"><?= $po_obj->get_po_number() ?></td>
            <tr>
                <td class="field fieldwidth"><?= $lang["supplier_invoice_number"] ?></td>
                <td class="value valuewidth">
                    <?php
                    if ($po_obj->get_status() == "N")
                    {
                    ?>
                    <input type="text" name="supplier_invoice_number"
                           value="<?= $po_obj->get_supplier_invoice_number() ?>" class="input"></td>
                <?php
                }
                else {
                    ?><?= $po_obj->get_supplier_invoice_number() ?><input type="hidden" name="supplier_invoice_number"
                                                                          value="<?= $po_obj->get_supplier_invoice_number() ?>"><?php
                }
                ?>


                <td class="field fieldwidth"><?= $lang["supplier"] ?></td>
                <td class="value valuewidth">
                    <?php
                    if ($po_obj->get_status() == "N") {
                        ?>
                        <select name="supplier" onChange="getCurrency(this.value);" class="input" notEmpty>
                            <option value=""><?= $lang["please_select"] ?></option>
                            <script language="javascript">writeSupplier();</script>
                        </select>
                    <?php
                    }
                    else
                    {
                    ?>
                        <script
                            language="javascript">document.write(supplist[<?=$po_obj->get_supplier_id()?>][0]);</script>
                    <input type="hidden" name="supplier" value="<?= $po_obj->get_supplier_id() ?>">
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="field fieldwidth"><?= $lang["delivery_mode"] ?></td>
                <td class="value valuewidth">
                    <?php
                    if ($po_obj->get_status() == "N") {
                        ?><select name="delivery_mode" class="input" notEmpty>
                        <option value="">-- Please Select --</option><?php
                        foreach ($dlvry_mode[$syslang] as $key => $value) {
                            ?>
                            <option
                            value="<?= $key ?>" <?= $key == $po_obj->get_delivery_mode() ? "SELECTED" : "" ?>><?= $value ?></option>
                        <?php
                        }
                        ?></select><?php
                    } else {
                        echo $dlvry_mode[$syslang][$po_obj->get_delivery_mode()];
                    }
                    ?></td>
                <td class="field fieldwidth"><?= $lang["status"] ?></td>
                <td class="value valuewidth"><?php
                    if ($po_obj->get_status() == "N") {
                        ?><select name="status" class="input">
                        <option value="N" SELECTED><?= $status_array[$syslang]["N"] ?></option>
                        <option value="CL"><?= $status_array[$syslang]["CL"] ?></option></select><?
                    } else {
                        echo $status_array[$syslang][$po_obj->get_status()];?><input type="hidden" name="status"
                                                                                     value="<?= $po_obj->get_status() ?>"><?php
                    }
                    ?></td>
            </tr>
            <tr>
                <td class="field fieldwidth"><?= $lang["create_on"] ?></td>
                <td class="value valuewidth"><?= $po_obj->get_create_on() ?></td>
                <td class="field fieldwidth"><?= $lang["modify_on"] ?></td>
                <td class="value valuewidth"><?= $po_obj->get_modify_on() ?></td>
            </tr>
            <tr>
                <td class="field fieldwidth"><?= $lang["create_by"] ?></td>
                <td class="value valuewidth"><?= $po_obj->get_create_by() ?></td>
                <td class="field fieldwidth"><?= $lang["modify_by"] ?></td>
                <td class="value valuewidth"><?= $po_obj->get_modify_by() ?></td>
            </tr>
            <tr>
                <td class="field fieldwidth"><?= $lang["current_total"] ?></td>
                <td class="value" colspan="3"><span id="scurr"><?= $po_obj->get_currency() ?></span>&nbsp;<span
                        id="total"><?= number_format($po_obj->get_amount(), 2); ?></span></td>
            </tr>
        </table>
        <table border="0" cellspacing="1" cellpadding="0" width="100%" bgcolor="#cccccc">
            <tr>
                <td height="25" bgcolor="#333333" style="padding-left:10px;"><font
                        style="color:#ffffff; font-weight:bold; font-size:12px;"><?= $lang["order_detail"] ?></font>
                </td>
            </tr>
            <table>
                <table border="0" cellspacing="1" cellpadding="0" width="100%" id="order_form" bgcolor="#cccccc">
                    <col width="100">
                    <col>
                    <col width="100">
                    <col width="100">
                    <col width="30">
                    <tr>
                        <td class="offield"><?= $lang["sku"] ?></td>
                        <td class="offield"><?= $lang["prodname"] ?></td>
                        <td class="offield"><?= $lang["order_qty"] ?></td>
                        <td class="offield"><?= $lang["unit_price"] ?>&nbsp;<span
                                id="scurr2"> - <?= $po_obj->get_currency() ?></span></td>
                        <td class="offield" width="40"><?= $lang["delete"] ?></td>
                    </tr>
                    <?php
                    foreach ($po_item_list as $obj) {
                        ?>
                        <tr>
                            <td class="value"><?= $obj->get_sku() ?><input type="hidden" name="sku[]"
                                                                           value="<?= $obj->get_sku() ?>"><input
                                    type="hidden" name="line_number[]" value="<?= $obj->get_line_number() ?>"><input
                                    type="hidden" name="modify_on[]" value="<?= $obj->get_modify_on() ?>"><input
                                    type="hidden" name="create_on[]" value="<?= $obj->get_create_on() ?>"><input
                                    type="hidden" name="create_at[]" value="<?= $obj->get_create_at() ?>"><input
                                    type="hidden" name="create_by[]" value="<?= $obj->get_create_by() ?>"></td>
                            <td class="value"><?= $obj->get_name() ?></td>
                            <td class="value"><input type="text" name="qty[]" value="<?= $obj->get_order_qty() ?>"
                                                     class="input" onKeyUp="calculatePrice();" isInteger
                                                     min=1 <?= $po_obj->get_status() == 'N' || $po_obj->get_status() == 'PS' ? "" : "READONLY" ?>>
                            </td>
                            <td class="value">
                                <?php
                                if ($po_obj->get_status() == "N")
                                {
                                    ?>  <input type="text" name="price[]" value="<?= $obj->get_unit_price() ?>"
                                               class="input" onKeyUp="calculatePrice()" isNumber min=0><?php
                                }
                                else
                                {
                                ?>  <?= $obj->get_unit_price() ?><input type="hidden" name="price[]"
                                                                        value="<?= $obj->get_unit_price() ?>"></td><?php
                            }
                            ?>
                            <td class="value"><input type="checkbox"
                                                     name="delete[]" <?= $po_obj->get_status() == "N" ? "" : "DISABLED" ?>
                                                     onClick="calculatePrice();" value="1"></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
                <table border="0" cellspacing="1" cellpadding="0" width="100%" bgcolor="#333333">
                    <tr>
                        <td align="left" style="padding-left:20px;"><input type="button" value="<?= $lang["back"] ?>"
                                                                           onClick="parent.location.href = '<?= base_url() . "order/supplier_order/index/" ?>';"
                                                                           class="button" target="_parent"></td>
                        <td align="right" height="30" style="padding-right:20px;"><input type="button"
                                                                                         value="<?= $lang["submit"] ?>"
                                                                                         onClick="if(CheckForm(this.form)) this.form.submit();"
                                                                                         class="button"></td>
                    </tr>
                </table>
    </form>
</div>
<script language="javascript">
    document.getElementById('sourcing_region').value = supplist[<?=$po_obj->get_supplier_id()?>][2];
</script>
<?= $notice["js"] ?>
</body>
</html>