<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Checkout: Payment at ValueBasket: Buy Cameras, Camcorders, Music and Video players and Cool unique Gadgets
        and gifts here!</title>
    <?php include VIEWPATH . "tags.php"; ?>

    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>js/lytebox.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/lytebox.css" type="text/css" media="screen"/>

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

                            <form name="checkout_fm" method="post"
                                  action="<?= base_url() ?>checkout/process_checkout/<?= $payment_gateway ?>/<?= $debug ?>"
                                  onSubmit="return CheckForm(this)">
                                <table width="100%" border="0" cellspacing="0" cellpadding="3"
                                       style='font-size:12px;text-align:left'>
                                    <tr>
                                        <td></td>
                                        <td height='30' width='250'><b>&nbsp;&nbsp;Billing Address</b></td>
                                        <td><?php if ($_SESSION["client"]) { ?>
                                                <iframe src="<?= base_url() ?>myaccount/delivery_address"
                                                        frameborder="0" height="160"></iframe>
                                            <?php } ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td height='30' width='250'
                                            style=" background-color:#FFFFCC; border-left:1px solid #FFDD66; border-top:1px solid #FFDD66;">
                                            <b>&nbsp;&nbsp;Total Amount Payable</b></td>
                                        <td style=" background-color:#FFFFCC; border-top:1px solid #FFDD66; border-right:1px solid #FFDD66;  ">
                                            <?php
                                            foreach ($dc as $rskey => $rsvalue) {
                                                $courier[] = $rskey;
                                            }

                                            $cur_delivery = $cur_courier ? $cur_courier : $courier[0];
                                            echo PLATFORMCURRSIGN . ' ' . number_format($chk_cart[0]['total'] - $promo["disc_amount"] + $dc[$cur_delivery]["charge"], 2);
                                            ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td height='30' width='250'
                                            style=" background-color:#FFFFCC; border-left:1px solid #FFDD66; border-top:1px solid #FFDD66;">
                                            <b>&nbsp;&nbsp;Card Type</b></td>
                                        <td style=" background-color:#FFFFCC; border-top:1px solid #FFDD66; border-right:1px solid #FFDD66;  ">
                                            <select id='cardtype' name='cardtype' notEmpty>
                                                <option value="">- Select Your Card -</option>
                                                <option value='SOLO_GB-SSL'>SOLO</option>
                                                <option value='MAESTRO-SSL'>MAESTRO</option>
                                                <option value='VISA-SSL' SELECTED>VISA</option>
                                                <option value='ECMC-SSL'>MASTER</option>
                                                <option value='LASER-SSL'>LASER</option>
                                                <option value='SWITCH-SSL'>SWITCH</option>
                                            </select> <font color='red'>*</font></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td style=" background-color:#FFFFCC; border-left:1px solid #FFDD66;"><b>&nbsp;&nbsp;Card
                                                Holder's Name:</b></td>
                                        <td style=" background-color:#FFFFCC; border-right:1px solid #FFDD66;"
                                            width="200"><input type="text" name="holdername" size="25" maxlength="25"
                                                               value="" notEmpty><font color='red'>*</font></td>
                                        <td id='alertmsg_holdername'></td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td style=" background-color:#FFFFCC; border-left:1px solid #FFDD66;"><b>&nbsp;&nbsp;Card
                                                Number:</b></td>
                                        <td style=" background-color:#FFFFCC; border-right:1px solid #FFDD66;"><input
                                                type="text" name="cardnum" size="20" maxlength="19" value="" notEmpty
                                                isNumber><font color='red'>*</font></td>
                                        <td id='alertmsg_cardnum'></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style=" background-color:#FFFFCC; border-left:1px solid #FFDD66;"><b>&nbsp;&nbsp;Start
                                                From:(if applicable)</b></td>

                                        <td style=" background-color:#FFFFCC; border-right:1px solid #FFDD66;"><select
                                                id="start_month" name="start_month" class="account_info_start">
                                                <option value="">Month</option>
                                                <option value="1">01</option>
                                                <option value="2">02</option>
                                                <option value="3">03</option>
                                                <option value="4">04</option>
                                                <option value="5">05</option>
                                                <option value="6">06</option>
                                                <option value="7">07</option>

                                                <option value="8">08</option>
                                                <option value="9">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                            </select> / <select id="start_year" name="start_year"
                                                                class="account_info_start">
                                                <option value="">Year
                                                    <?php
                                                    $start_y = date("Y");
                                                    for ($y = $start_y;
                                                    $y > 1999;
                                                    $y--)
                                                    {
                                                    ?>
                                                <option value="<?= $y ?>"><?= $y ?>
                                                    <?php } ?>
                                            </select>
                                        </td>
                                        <td id='alertmsg_startdate'></td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td style=" background-color:#FFFFCC; border-left:1px solid #FFDD66;"><b>&nbsp;&nbsp;Expires
                                                End:</b></td>
                                        <td style=" background-color:#FFFFCC; border-right:1px solid #FFDD66;"><select
                                                id="exp_month" name="exp_month"
                                                class="account_info_exp" notEmpty>
                                                <option value="">Month</option>
                                                <option value="1">01</option>
                                                <option value="2">02</option>
                                                <option value="3">03</option>
                                                <option value="4">04</option>

                                                <option value="5">05</option>
                                                <option value="6">06</option>
                                                <option value="7">07</option>
                                                <option value="8">08</option>
                                                <option value="9">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                            </select> /

                                            <select id="exp_year" name="exp_year" class="account_info_exp" notEmpty>
                                                <option value="">Year
                                                    <?php
                                                    for ($y = $start_y;
                                                    $y < $start_y + 10;
                                                    $y++)
                                                    {
                                                    ?>
                                                <option value="<?= $y ?>"><?= $y ?>
                                                    <?php } ?>
                                            </select><font color='red'>*</font></td>
                                        <td id='alertmsg_expdate'></td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td style=" background-color:#FFFFCC; border-left:1px solid #FFDD66;"><b>&nbsp;&nbsp;Issue
                                                Number:(if applicable)</b></td>
                                        <td style=" background-color:#FFFFCC; border-right:1px solid #FFDD66;"><input
                                                id="inum" name="inum" value="" size="4" type="text" maxlength="4"></td>
                                        <td id='alertmsg_issuenum'></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style=" background-color:#FFFFCC; border-left:1px solid #FFDD66;"><b>&nbsp;&nbsp;Card
                                                Verification Code(CVC):</b></td>
                                        <td style=" background-color:#FFFFCC; border-right:1px solid #FFDD66;"
                                            width="100"><input id="cvc" name="cvc" value="" size="4" type="text"
                                                               maxlength="4" notEmpty isNumber><br><font
                                                color='red'>*</font> <a href="<?= base_url() ?>checkout/cvc"
                                                                        rel="lyteframe"
                                                                        rev="width: 400px; height: 200px; scrolling: auto;"
                                                                        title="Card Verification Code(CVC)">What's
                                                this?</a></td>
                                        <td id='alertmsg_cvcnum'></td>
                                    </tr>

                                    <tr height="50px" valign="middle">
                                        <td></td>
                                        <td style=" background-color:#FFFFCC; border-bottom:1px solid #FFDD66; border-left:1px solid #FFDD66;"></td>
                                        <td style=" background-color:#FFFFCC; border-bottom:1px solid #FFDD66; border-right:1px solid #FFDD66;"
                                            align="left">
                                            <button type="submit" style="cursor: pointer; cursor: hand;">Confirm
                                                Payment
                                            </button>
                                        </td>
                                        <td></td>
                                    </tr>
                                </table>
                                <br><br>
                                <input type="hidden" name="delivery" value="<?= $delivery ?>">
                                <input type="hidden" name="review" value="<?= $review ?>">
                            </form>


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