<!DOCTYPE html>
<head>
    <title>Split Order</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript">
        function changeCAgroup(mainprodrow, camain_uid) {
            // get all CAs linked to main product sku
            var ca_rows = document.getElementsByName('ca_row[' + camain_uid + '][]');
            if (ca_rows) {
                for (var i = 0; i < ca_rows.length; i++) {
                    // get the row number of current CA
                    var rowno = ca_rows[i].value;

                    var ca_grp = document.getElementById('group[' + rowno + '][grpno]');
                    var mainprod_grp = document.getElementById('group[' + mainprodrow + '][grpno]');
                    if (ca_grp) {
                        // assign it to same group as main product
                        ca_grp.value = mainprod_grp.value;
                    }

                }
                ;
            }
            return;
        }

        function remindPreview() {
            document.getElementById('remindtext').innerHTML = "<font color='red'><b>Preview data changes first.</b></font>";
            var process_button = document.getElementById('submit_process');
            process_button.setAttribute('disabled', 'disabled');
        }

        function enableProcess() {
            var process_button = document.getElementById('submit_process');
            process_button.removeAttribute("disabled");
        }

    </script>
</head>
<body>
<?php
if ($so_obj) :
    $so_no = $so_obj->getSoNo();
    $split_so_group = $so_obj->getSplitSoGroup();
    $splitgrouphtml = ($split_so_group) ? "<font style=\"color:red;\">(Split Group: $split_so_group)</font>" : "";
    $curr = $so_obj->getCurrencyId();
    $so_amount = $so_obj->getAmount();
    $delivery_country_id = $so_obj->getDeliveryCountryId();
    $promocode = $so_obj->getPromotionCode();
endif;
?>
<div id="splitprocessor"
     style="width:900px;margin:auto;overflow:auto;padding:0px 50px;background-color:#FCFCFC;box-shadow:0 5px 10px rgba(0, 0, 0, 0.5);">
    <div style="padding:10px 15px;text-align:left;">
        <br>

        <h2>SPLIT ORDER TURBO</h2>
    </div>
    <form name="splitorder" id="splitorder" method="post">
        <div>
            <fieldset style="border-width:2px;margin-left:6px;margin-right:6px;padding:0 8px ;">
                <legend><font style="color:red;"><h1><?= "[Order $so_no] SUMMARY" ?></h1></font></legend>
                <table width="100%" style="text-align=left;font-family: Lucida Sans Unicode,sans-serif;"
                       cellpadding="5">
                    <col width="15%">
                    <col width="40%">
                    <tr style="text-align:left">
                        <td>SO#</td>
                        <td><?= $so_no ?> <?= $splitgrouphtml ?> <input type="hidden" id="so_no" name="so_no"
                                                                        value="<?= $so_no ?>"></td>
                        <td colspan="2">
                    </tr>
                    <tr style="text-align:left">
                        <td>Total Order Value</td>
                        <td><?= "$curr $so_amount" ?></td>
                        <td colspan="2">
                    </tr>
                    <tr style="text-align:left">
                        <td>Destination Country</td>
                        <td><?= $delivery_country_id ?></td>
                        <td colspan="2">
                    </tr>
                    <tr style="text-align:left">
                        <td>Promo Code</td>
                        <td><?= $promocode ?></td>
                        <td colspan="2">
                    </tr>
                </table>
            </fieldset>
            <br>

            <div style="padding:10px 15px;text-align:left;">
                <br>Below shows the groups with the furthest split possible.
                <br>Items in the same group number will be assigned to the same split order number. Review the grouping
                and click 'Process' to finalize the split. <br>
            </div>
            <div>
                <fieldset style="border-width:2px;margin-left:6px;margin-right:6px;padding:0 5px">
                    <legend><font style="color:red;"><h1><?= "[Order $so_no] SPLIT GROUP" ?></h1></font></legend>
                    <table width="100%"
                           style="text-align=left;font-family: Lucida Sans Unicode,sans-serif;border-spacing:0px 1px"
                           cellpadding="1">
                        <col width="10%">
                        <col width="10%">
                        <col width="20%">
                        <col width="15%">
                        <col width="5%">
                        <col width="10%">
                        <col width="10%">
                        <col width="5%">
                        <col width="2%">
                        <?= $itemrow ?>
                        <tr style="background-color:#DCDCDC">
                            <td id="remindtext" colspan="9"></td>
                        </tr>
                        <tr>
                            <td colspan="9"></td>
                        </tr>
                    </table>
                </fieldset>
                <br><br>
            </div>
    </form>
</div>
</div>
<?= $notice["js"] ?>
</script>
</
body >
< / html >