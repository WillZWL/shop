<?php
if ($marketplace_list) {
    foreach ($marketplace_list as $key => $value) {
        $marketplace = strtoupper($value);
        $selected = ($selected_marketplace == $marketplace) ? " selected" : "";

        $marketplace_option .= <<<html
                <option value='$marketplace' $selected >$marketplace</option>
html;
    }
}

?>

<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>js/lytebox.js"></script>
    <script type="text/javascript">

        var error_message = '<?=$error_message?>';
        if (error_message) {
            alert(error_message);
        }

        function getPlatformId() {
            var e = document.getElementById("marketplace");
            var selected = e.options[e.selectedIndex].value;
            ;

            if (selected != "") {
                window.location = '<?=base_url()?>order/marketplace_order_import/index/' + selected;
            }
            else {
                window.location = '<?=base_url()?>order/marketplace_order_import/index/';
            }
        }

        function runRakuten(action) {
            var country = document.getElementById("country");
            var selected_country = country.options[country.selectedIndex].value;

            if (selected_country) {
                if (action == 'default') {
                    var numberDays = document.getElementById("numberDays").value;
                    if (numberDays.length == 0 || numberDays == 0)
                        numberDays = 7;

                    var nowdate = document.getElementById("nowDate").value;
                    if (!nowdate) {
                        alert("No date. Must be in format yyyy-mm-dd");
                        return;
                    }

                    var url = '<?=base_url()?>cron/platform_integration/rakuten_orders/import/' + selected_country + '?days=' + numberDays + '&nowtime=' + nowdate;
                }
                else if (action == "import_single") {
                    var orderNumber = document.getElementById("orderNumber").value;

                    if (orderNumber.length == 0 || orderNumber == 0) {
                        alert("No Order Number");
                        return;
                    }
                    else {
                        var url = '<?=base_url()?>cron/platform_integration/rakuten_orders/import_single_order/' + selected_country + '/' + orderNumber;

                    }
                }
                else if (action == 'get_orders_list') {
                    var numberDays = document.getElementById("info_numberDays").value;
                    if (numberDays.length == 0 || numberDays == 0)
                        numberDays = 7;
                    var nowdate = document.getElementById("info_nowDate").value;
                    if (!nowdate) {
                        alert("No date. Must be in format yyyy-mm-dd");
                        return;
                    }
                    var url = '<?=base_url()?>cron/platform_integration/rakuten_orders/get_orders_list/' + selected_country + '?days=' + numberDays + '&nowtime=' + nowdate;
                }
                else {
                    alert("undefined action");
                }

                if (url) {
                    console.log(url);
                    window.open(url);
                }

            }
            else {
                alert("No Country");
            }
            return;
        }

        function runQoo10(action) {
            var country = document.getElementById("country");
            var selected_country = country.options[country.selectedIndex].value;

            if (selected_country) {
                if (action == 'default') {

                    var url = '<?=base_url()?>cron/platform_integration/qoo10_orders/import/' + selected_country;
                }
                else {
                    alert("undefined action");
                }

                if (url) {
                    console.log(url);
                    window.open(url);
                }

            }
            else {
                alert("No Country");
            }
            return;
        }

        function runFNAC(action) {
            var country = document.getElementById("country");
            var selected_country = country.options[country.selectedIndex].value;

            if (selected_country) {
                if (action == 'default') {
                    var startDate = document.getElementById("startDate").value;
                    if (!startDate) {
                        alert("No start date. Must be in format yyyy-mm-dd");
                        return;
                    }

                    var endDate = document.getElementById("endDate").value;
                    if (!endDate) {
                        alert("No end date. Must be in format yyyy-mm-dd");
                        return;
                    }

                    var url = '<?=base_url()?>cron/cron_fnac/cron_retrieve_new_order/' + selected_country + '/' + startDate + '/' + endDate;
                }
                else if (action == 'acknowledge') {
                    var url = '<?=base_url()?>cron/cron_fnac/acknowledge_order/' + selected_country;
                }
                else {
                    alert("undefined action");
                }

                if (url) {
                    console.log(url);
                    window.open(url);
                }

            }
            else {
                alert("No Country");
            }
            return;
        }
    </script>
    <style type="text/css">

        tr.border_bottom td {
            border-bottom: 1pt solid black;
        }
    </style>
    <link rel="stylesheet" href="<?= base_url() ?>css/lytebox.css" type="text/css" media="screen"/>
</head>
<body>
<div id="template"
     style="width:770px;margin:auto;overflow:auto;padding:20px 50px;background-color:#FCFCFC;box-shadow:0 5px 10px rgba(0, 0, 0, 0.5);">
    <div style="padding:10px 15px;text-align:left;">
        <h2>Marketplace Order Import</h2>

        <div style="padding:10px;">
            <table width="100%" style="text-align=left;font-family: Lucida Sans Unicode,sans-serif;">
                <colgroup>
                    <col width="30%">
                    <col width="70%">
                </colgroup>
                <tr>
                    <td>Select Marketplace</td>
                    <td>
                        <select name="marketplace" id="marketplace" onchange="getPlatformId()">
                            <option></option>
                            <?= $marketplace_option ?>
                        </select>
                    </td>
                </tr>
                <?= ($platform_action_html) ? $platform_action_html : "" ?>
            </table>
        </div>
    </div>
</div>
<? $ar_status = array("0" => $lang["inactive"], "1" => $lang["active"]); ?>
<?= $notice["img"] ?>
<?= $notice["js"] ?>
</div>
</body>
</html>