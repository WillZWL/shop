<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/calstyle.css">
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/datepicker.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/supply/supplier_helper/js_supplist"></script>
    <script language="javascript">
        <!--
        var rowIndex = 1;

        function addRow(id, sku, name) {
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
            a.innerHTML = sku + '<input type="hidden" name="sku[]" value="' + sku + '">';
            b.innerHTML = stripslashes(name);
            c.innerHTML = '<input type="text" name="qty[]" value="1" isInteger min=1 class="input" onKeyUp="calculatePrice();">';
            d.innerHTML = '<input type="text" name="price[]" value="0.00" isNumber min=0 class="input" onKeyUp="calculatePrice();">';
            e.innerHTML = '<input type=\'button\' value="x" onClick=\'deleteRow(this ' + ',' + '"order_form");\'>';
            rowIndex++;
        }

        function calculatePrice() {
            if (document.getElementsByName('qty[]')) {
                var qty = document.getElementsByName('qty[]');
                var i = qty.length;
                var price = document.getElementsByName('price[]');
                var total = 0.00;
                for (var j = 0; j < i; j++) {
                    total += qty[j].value * price[j].value;
                    if (qty[j].value == 0) {
                        qty[j].style.backgroundColor = "#ffff00";
                    }
                    else {
                        qty[j].style.backgroundColor = "#ffffff";
                    }
                }
                document.getElementById('total').innerHTML = total.toFixed(2);
                document.getElementById('amount').value = total;
            }
        }

        function deleteRow(r, id) {
            var i = r.parentNode.parentNode.rowIndex;
            document.getElementById(id).deleteRow(i);
            rowIndex--;
            calculatePrice();
        }

        function writeSupplier() {
            for (var i  in supplist) {
                document.write('<option value="' + i + '">' + supplist[i][0] + '</option>');
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
$status_array = $lang["status_name"];
$dlvry_mode = $lang["dlvry_mode"];
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
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["subheader"] ?></td>
            <td width="200" valign="top" align="right" style="padding-right:8px">&nbsp;</td>
        </tr>
    </table>
    <form name="fm" action="<?= base_url() ?>order/amend_supplier_order/add/" method="POST" onSubmit="CheckForm(this);"
          target="_parent">
        <input type="hidden" name="posted" value="1">
        <input type="hidden" name="currency" id="currency" value="">
        <input type="hidden" name="amount" id="amount" value="0">
        <input type="hidden" name="sourcing_region" id="sourcing_region" value="">
        <table border="0" cellpadding="0" cellspacing="1" width="100%" class="tb_list">
            <tr>
                <td class="field fieldwidth"><?= $lang["supplier_invoice_number"] ?></td>
                <td class="value valuewidth"><input type="text" name="supplier_invoice_number" value="" class="input">
                </td>
                <td class="field fieldwidth"><?= $lang["supplier"] ?></td>
                <td class="value valuewidth"><select name="supplier" onChange="getCurrency(this.value);" class="input"
                                                     notEmpty>
                        <option value=""><?= $lang["please_select"] ?></option>
                        <script language="javascript">writeSupplier();</script>
                    </select></td>
            </tr>
            <tr>
                <td class="field fieldwidth"><?= $lang["delivery_mode"] ?></td>
                <td class="value valuewidth"><select name="delivery_mode" class="input" notEmpty>
                        <option value="">-- Please Select --</option>
                        <?php

                        foreach ($dlvry_mode as $key => $value) {
                            ?>
                            <option value="<?= $key ?>"><?= $value ?></option>
                        <?php
                        }
                        ?></select></td>
                <td class="field fieldwidth"><?= $lang["status"] ?></td>
                <td class="value valuewidth"><?= $lang["new"] ?></td>
            </tr>
            <tr>
                <td class="field fieldwidth"><?= $lang["current_total"] ?></td>
                <td class="value"><span id="scurr"></span>&nbsp;<span id="total">0.00</span></td>
                <td class="field fieldwidth"><?= $lang["eta"] ?></td>
                <td class="value"><input name="eta" type="text" style="input" readonly notEmpty
                                         value="<?= date("d/m/Y") ?>">&nbsp;<input type='button' name='selectdate'
                                                                                   onclick="displayDatePicker('eta');"
                                                                                   value="<?= $lang["select_date"] ?>">
                </td>
            </tr>
        </table>
        <table border="0" cellspacing="1" cellpadding="0" width="100%" bgcolor="#cccccc">
            <tr>
                <td height="25" bgcolor="#333333" style="padding-left:10px;"><font
                        style="color:#ffffff; font-weight:bold; font-size:12px;"><?= $lang["order_detail"] ?></font>
                </td>
            </tr>
            <table>
                <table border="0" cellspacing="1" cellpadding="0" width="100%" id="order_form" bgcolor="#cccccc"
                       class="tb_list">
                    <col width="100">
                    <col>
                    <col width="100">
                    <col width="100">
                    <col width="30">
                    <tr class="header">
                        <td><?= $lang["sku"] ?></td>
                        <td><?= $lang["prodname"] ?></td>
                        <td><?= $lang["order_qty"] ?></td>
                        <td><?= $lang["unit_price"] ?>&nbsp;<span id="scurr2"></span></td>
                        <td width="30">&nbsp;</td>
                    </tr>
                </table>
                <table border="0" cellspacing="1" cellpadding="0" width="100%" bgcolor="#333333">
                    <tr>
                        <td align="left" style="padding-left:20px;"><input type="button" value="<?= $lang["back"] ?>"
                                                                           onClick="parent.location.href = '<?= $_SESSION["SOLISTPAGE"] ?>';"
                                                                           class="button"></td>
                        <td align="right" height="30" style="padding-right:20px;"><input type="button"
                                                                                         value="<?= $lang["submit"] ?>"
                                                                                         onClick="if(CheckForm(this.form)) this.form.submit();"
                                                                                         class="button"></td>
                    </tr>
                </table>
    </form>
</div>
<?= $notice["js"] ?>
</html>