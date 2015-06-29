<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Checkout: Payment at ValueBasket: Buy Cameras, Camcorders, Music and Video players and Cool unique Gadgets
        and gifts here!</title>
    <?php include VIEWPATH . "tags.php"; ?>

    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>

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
<?php require_once "order_confirm_tracking_pixel.php"; ?>
<center>
    <table border="0" cellpadding="0" cellspacing="0" width="982">
        <tr>
            <td width="1" valign="top" bgcolor="#999999"><img border="0" src="/images/spacer.gif" height="1" width="1">
            </td>
            <td width="980" valign="top" bgcolor="#FFFFFF"
                style="padding-left:20px;padding-right:17px;padding-bottom:20px" align="center">
                <?php include VIEWPATH . "header.php"; ?>
                <?php include VIEWPATH . "checkout/checkoutprocessbar.php"; ?>

                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="12px" height="31px" background="/images/checkout/boxes/boxtl.gif">&nbsp;</td>
                        <td height="31px" align="left" background="/images/checkout/boxes/boxtp.gif"><img
                                src="/images/checkout/boxes/tx_payment.gif">&nbsp;</td>
                        <td width="12px" height="31px" background="/images/checkout/boxes/boxtr.gif">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="12px" height="31px" background="/images/checkout/boxes/boxl.gif">&nbsp;</td>
                        <td>
                            <!-- Order Confirm Form Start -->

                            <div id="loading" style="position:absolute; left:50%"><img src="/images/pleasewait.gif">
                            </div>
                            <iframe name="redirect" style="position:relative"
                                    src="<?= $this->input->post("url_togoto") ?>" frameborder='0' scrolling='auto'
                                    width='100%' height='650'
                                    onLoad="document.getElementById('loading').style.display='none'"></iframe>

                            <!-- Order Confirm Form End -->
                        </td>
                        <td width="12px" background="/images/checkout/boxes/boxr.gif">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="12px" height="12px" background="/images/checkout/boxes/boxbl.gif">&nbsp;</td>
                        <td height="12px" background="/images/checkout/boxes/boxbm.gif">&nbsp;</td>
                        <td width="12px" height="12px" background="/images/checkout/boxes/boxbr.gif">&nbsp;</td>
                    </tr>
                </table>

                <?php include VIEWPATH . "footer.php"; ?>
            </td>
            <td width="1" valign="top" bgcolor="#999999"><img border="0" src="/images/spacer.gif" height="1" width="1">
            </td>
        </tr>
    </table>
</center>
</body>


</html>