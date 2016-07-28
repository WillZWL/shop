<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <link rel="stylesheet" href="<?= base_url() ?>css/bootstrap.min.css" type="text/css" media="all" />
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/jquery.js"></script>

    <script>
        $(function () {
            $(".button_edit").click(function (e) {
                e.preventDefault();
                $("#flag_type").val("update");
                var this_email_id = $(this).attr("data");
                var this_email_box = $("#e_" + this_email_id);
                var this_form = $("#f_" + this_email_id);
                this_email_box.toggleClass("readonly_box");


                var this_original_email = this_email_box.attr("data");

                var readonly_attr = $(this).attr('readonly');

                if (this_email_box.hasClass("readonly_box")) {
                    this_email_box.attr("readonly", true);
                    $(this).text("Edit");
                    var edited_email = $("#e_" + this_email_id).val().trim();
                    if (edited_email !== this_original_email) {
                        this_form.submit();
                    }
                }
                else {
                    this_email_box.removeAttr("readonly");
                    $(this).text("Update");
                }
            });

            $(".button_delete").click(function (e) {
                e.preventDefault();
                var this_email_id = $(this).attr("data");
                $("#flag_type_" + this_email_id).val("delete");
                var this_form = $("#f_" + this_email_id);
                if (confirm('Do you want to remove this email?')) {
                    this_form.submit();
                }
                else {
                    return false;
                }

            });

            $("#add_email").click(function (e) {
                e.preventDefault();
                var new_email = $("#new_email").val().trim();
                if (new_email == '') {
                    return false;
                }
                else {
                    document.forms['fm'].submit();
                }
            });

            $("tr:not('#last_row', '#header_row')").mouseenter(function () {
                $(this).addClass('highlight');
                $(this).find('button').css({
                    visibility: 'visible'
                });
            }).mouseleave(function () {
                $(this).removeClass('highlight');
                $(this).find('button').css({
                    visibility: 'hidden'
                });
            });

        });
    </script>
    <link rel="stylesheet" href="<?= base_url() ?>css/calendar.css" type="text/css" media="all"/>
    <style type="text/css">
        .contentRow {
            height: 20px;
            background-color: #FFCCFF;
        }

        .tableField {
            background-color: #666666;
            color: #FFFFFF;
            font-size: 12px;
            font-weight: bold;
        }

        .readonly_box {
            border: none;
            background-color: transparent;
        }
    </style>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <table cellpadding="0" cellspacing="0" width="100%" border="0">
        <tr>
            <td align="left" class="title" height="30"><b
                    style="font-size: 16px; color: rgb(0, 0, 0);"><?= $lang["title"] ?></b></td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033"></td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
        <tr height="70" id='header_row'>
            <td align="left" style="padding-left:8px;">
                <b style="font-size: 14px; color: rgb(0, 0, 0);"><?= $lang["header"] ?></b><br>
            </td>
            <td align="right">
                <form name="fm" action="<?= base_url() . "order/email_referral_list_management/index" ?>" method="post">
                    <table border="0" cellpadding="3" cellspacing="0" width="400" style="line-height:8px;">
                        <col width="140">
                        <col width="160">
                        <col width="40">
                        <tr id='header_row'>
                            <td align='right'><b><?= $lang["add_new_email"] ?>: </b></td>
                            <td><input name="new_email" id="new_email" style="width:150px;"></td>
                            <td rowspan="2" align="center"><input type="submit" id='add_email' value="Add"
                                                                  style="width:50px;"><input type="hidden" name="add"
                                                                                             value="1"> &nbsp; </td>
                        </tr>
                    </table>
                </form>

            </td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033" colspan="3"></td>
        </tr>
    </table>
    <?= $notice["js"] ?>
    <table cellpadding='3' cellspacing='0' border='1' width="100%" style="border-collapse:collapse;" class="tb_list">
        <?php
        $total_number_of_records = "";
        //print heading
        print "<tr bgcolor='#000000'>";
        print "<td class='tableField'>" . $lang['client_id'] . "</td>";
        print "<td class='tableField'>" . $lang['Email'] . "</td>";
        print "<td class='tableField'>" . $lang['client_name'] . "</td>";
        print "<td class='tableField'>" . $lang['ip_address'] . "</td>";
        print "<td class='tableField'>" . $lang['address'] . "</td>";
        print "<td class='tableField'>" . $lang['postal_code'] . "</td>";
        print "<td class='tableField' align='center'> </td>";
        print "</tr>";
        //content
        $order_number = 1;
        $current_so = "";
        $rowcount = 0;

        //print heading
        foreach ($email_referral_list as $perEmailInfo) {
            $row_style = "row" . $rowcount % 2;
            ?>
            <form id="f_<?= $perEmailInfo->getId() ?>"
                  action="<?= base_url() . "order/email_referral_list_management/index/{$perEmailInfo->getId()}" ?>"
                  method="post">
                <tr class="<?= $row_style ?>" name="row<?= $rowcount ?>">
                    <td><?= $perEmailInfo->getClientId() ?></td>
                    <td><input name='email' class='readonly_box' id='<?= 'e_' . $perEmailInfo->getId() ?>'
                               type='text' value="<?= $perEmailInfo->getEmail() ?>" readonly style="width:200px"
                               data="<?= $perEmailInfo->getEmail() ?>"></td>
                    <td><?= $perEmailInfo->getSurname() . ' ' . $perEmailInfo->getForename() ?></td>
                    <td><?= $perEmailInfo->getCreateAt() ?></td>
                    <!-- <td><?= $perEmailInfo->getAddress1() . ',' . $perEmailInfo->getAddress2() . ',' . $perEmailInfo->getAddress3() ?></td> -->
                    <td><?= $perEmailInfo->getAddress() ?></td>
                    <td><?= $perEmailInfo->getPostcode() ?></td>
                    <td style='width:120px;text-align:center'>
                        <button class="button_edit" style='visibility:hidden' data='<?= $perEmailInfo->getId() ?>'>
                            Edit
                        </button>
                        <button class="button_delete" style='visibility:hidden' data='<?= $perEmailInfo->getId() ?>'>
                            Delete
                        </button>
                    </td>

                    <input id='flag_type_<?= $perEmailInfo->getId() ?>' type="hidden" name="post" value="update">
                </tr>
            </form>
            <?php
            $rowcount++;
        }
        ?>
        <tr id="last_row">
            <td colspan='8'>
                <form action="<?= base_url() . "order/email_referral_list_management/export_csv" ?>" method='POST'>
                    <button>Generate List</button>
                </form>
            </td>
        </tr>
    </table>

    <?= $links ?>
</div>
</div>

</body>
</html>
