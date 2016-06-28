<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script language="javascript">

    </script>
    <style type="text/css">
        h1 {
            font-size:14px;
        }
    </style>
</head>
<body>
<div id="main">
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
        <tr height="100">
            <td align="left" style="padding-left:8px;"><b style="font-size: 14px; color: rgb(0, 0, 0);"><?= $lang["header"] ?></b>
            <br><span style="font-size: 12px; line-height:18px;"><?= $lang["header_message"] ?></span>
            </td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033" colspan="3"></td>
        </tr>
    </table>

    <table style="padding-top: 10px;">
        <form action="<?=base_url()?>order/credit_check/bulk_update" method="POST" onSubmit="return CheckForm(this);">
            <input type="hidden" name="post" value="1">
            <tr>
                <td>
                    <h1>List of orders:</h1><textarea cols="40" rows="20" name="order_list" class="input" notEmpty></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <h1>Note to add:</h1><input type="text" size="40" name="note" class="input">
                </td>
            </tr>
            <tr>
                <td>
                     <input type="checkbox" name="approve_if_paid" value="1">Change <b>paid</b> orders to <b>credit checked</b>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="Update orders"></input>
                </td>
            </tr>
        </form>
    </table>
    <div style="color:red;text-align:left;padding-top:10px;">
    <?php
        if($_SESSION['NOTICE']) {
            echo $_SESSION['NOTICE'];
        }
        unset($_SESSION['NOTICE']);
    ?>
    </div>
</div>
</body>
</html>