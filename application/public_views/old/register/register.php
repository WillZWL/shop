<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>ValueBasket: Digital Cameras, Camcorders, IT products, Lens - online High Tech shop</title>
    <?php include VIEWPATH . "tags.php"; ?>

    <script language="javascript">
        <!--
        var win = null;
        function popWindow(mypage, myname, w, h, scroll) {
            LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
            TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
            settings =
                'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition + ',status=no,scrollbars=' + scroll + ''
            win = window.open(mypage, myname, settings)
            if (win.window.focus) {
                win.window.focus();
            }
        }
        //-->
    </script>

</head>

<body style="margin-top:0px;margin-left:0px;">
<center>
    <table border="0" cellpadding="0" cellspacing="0" width="982">
        <tr>
            <td width="1" valign="top" bgcolor="#999999"><img border="0" src="/images/spacer.gif" height="1" width="1">
            </td>
            <td width="980" valign="top" bgcolor="#FFFFFF"
                style="padding-left:20px;padding-right:17px;padding-bottom:20px" align="center">
                <?php include VIEWPATH . "header.php"; ?>

                <!-- Register Form Start -->
                <table width="800" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="12px" height="31px" background="/images/checkout/boxes/boxtl.gif">&nbsp;</td>
                        <td height="31px" align="left" background="/images/checkout/boxes/boxtp.gif"><img
                                src="/images/checkout/boxes/tx_myaccount_register.gif">&nbsp;</td>
                        <td width="12px" height="31px" background="/images/checkout/boxes/boxtr.gif">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="12px" height="31px" background="/images/checkout/boxes/boxl.gif">&nbsp;</td>
                        <td>

                            <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
                            <?php if (isset($notice)) { ?>
                                <table style="text-align:left" cellspacing="0" cellpadding="1" bgcolor="#FF0000"
                                       width="500px">
                                    <tr>
                                        <td height="20px" colspan="3"
                                            style=" color:#FFFFFF; text-align:center; font-weight:bold; border-left:1px solid #990000; border-top:1px solid #990000;  border-bottom:1px solid #990000; border-right:1px solid #990000; padding:8px; ">
                                            <?= $notice ?>      </td>
                                        <td width="60px" bgcolor="#FFFFFF">&nbsp;</td>
                                    </tr>
                                </table>
                            <?php } ?>
                            <?php include("register_form.php"); ?>
                        </td>
                        <td width="12px" background="/images/checkout/boxes/boxr.gif">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="12px" height="12px" background="/images/checkout/boxes/boxbl.gif">&nbsp;</td>
                        <td height="12px" background="/images/checkout/boxes/boxbm.gif">&nbsp;</td>
                        <td width="12px" height="12px" background="/images/checkout/boxes/boxbr.gif">&nbsp;</td>
                    </tr>
                </table>
                <!-- Register Form End -->

                <?php include VIEWPATH . "footer.php"; ?>
            </td>
            <td width="1" valign="top" bgcolor="#999999"><img border="0" src="/images/spacer.gif" height="1" width="1">
            </td>
        </tr>
    </table>
</center>
</body>


</html>