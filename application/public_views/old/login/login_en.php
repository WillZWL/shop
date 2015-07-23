<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="shortcut icon" href="<?= base_url() ?>images/favicon.ico" type="image/x-icon"/>
    <link href="<?= base_url() ?>css/style.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>js/jquery.js"></script>
    <title>Sign Up or Login to ValueBasket: Buy Skype Certified Headsets, Webcams, Phones here!</title>
    <? // include VIEWPATH."tags.php"; ?>

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
<div id="container">
    <?php
    include VIEWPATH . 'header_skype_' . get_lang_id() . '.php';
    if (!(strpos($back, "checkout") !== FALSE)) {
        include VIEWPATH . 'back_to_shopping_cart.php';
    }
    ?>
    <table width="1000" cellpadding="0" cellspacing="0" border="0" align="center">
        <tr>
            <td align="center" valign="top">
                <!-- Login Form Start -->
                <script type="text/javascript"
                        src="<?= base_url() ?>js/checkform.js?lang=<?= get_lang_id() ?>"></script>
                <?php
                /*
                if(strpos($back, "checkout") !== FALSE)
                {
                    if(isset($step) && $step =='2')
                    {
                        include VIEWPATH."checkout/checkoutprocessbar_".get_lang_id().".php";
                    }
                }*/
                ?>

                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td height="31px" width="100%">&nbsp;</td>
                    </tr>
                    <!--tr>
                        <td width="12px" height="31px" background="/images/checkout/boxes/boxtl.gif">&nbsp;</td>
                        <td height="31px" align="left" background="/images/checkout/boxes/boxtp.gif"><img src="/images/checkout/boxes/tx_myaccount_logre.gif">&nbsp;</td>
                        <td width="12px" height="31px" background="/images/checkout/boxes/boxtr.gif">&nbsp;</td>
                    </tr-->
                    <tr>
                        <!--td width="12px" height="31px" background="/images/checkout/boxes/boxl.gif">&nbsp;</td-->
                        <td align="center">
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

                            <?php include("login_form_" . get_lang_id() . ".php"); ?><br><br>
                            <?php //include(VIEWPATH."register/register_form.php");?>
                            Continue shopping at <a href="http://shop.skype.com" style="text-decoration:underline;">Skype
                                Shop</a>.
                        </td>
                        <!--td width="12px" background="/images/checkout/boxes/boxr.gif">&nbsp;</td-->
                    </tr>
                    <tr>
                        <td height="12" width="100%">&nbsp;</td>
                    </tr>
                    <!--tr>
                        <td width="12px" height="12px" background="/images/checkout/boxes/boxbl.gif">&nbsp;</td>
                        <td height="12px" background="/images/checkout/boxes/boxbm.gif">&nbsp;</td>
                        <td width="12px" height="12px" background="/images/checkout/boxes/boxbr.gif">&nbsp;</td>
                    </tr-->
                </table>
                <!-- Login Form End -->
            </td>
        </tr>
    </table>
    <?php
    if (!(strpos($back, "checkout") !== FALSE)) {
        include VIEWPATH . 'back_to_shopping_cart.php';
    }
    include VIEWPATH . "footer_" . get_lang_id() . ".php";
    ?>
</div>
</body>
</html>