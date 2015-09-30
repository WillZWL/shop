<?php
$not_long_enough = _('This email is not long enough!');
$email_no_at = _('This email does not contain an @!');
$email_no_com = _('This Email must have a suffix! eg .com');
$email_invalid_char = _('There are invalid characters in this email!');
$email_not_exist = _('Email Address does not exist in our system. Please click');
$email_not_exist3 = _('here');
$email_not_exist4 = _('to find out how you can contact us');
$erray = array("", $not_long_enough, $email_no_at, $email_no_com, $email_invalid_char, $email_not_exist ."<a href='" . base_url() . "contact' target='_top'>" .$email_not_exist3. "</a>" . $email_not_exist4,"T!");
?>
<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?= _('Reset your password') ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="/js/jquery.js"></script>
<style type="text/css">
<!--
.bigger_font {font-size: 11px; line-height:20px; text-align: justify;}
-->
</style>
</head>
<body topmargin="0" leftmargin="0">
<div id="container">
<table width="<?=$page_width?>" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
    <td align="center" valign="top" style="padding-top:80px; padding-bottom:30px;">
    <!-- Form Start -->
    <?php if($error != 0 && $error != 6){ ?>
    <table style="text-align:left" cellspacing="0" cellpadding="1" bgcolor="#FF0000" width="500px">
    <tr>
        <td height="20px" style=" color:#FFFFFF; text-align:center; font-weight:bold; border-left:1px solid #990000; border-top:1px solid #990000;  border-bottom:1px solid #990000; border-right:1px solid #990000; padding:8px; ">
          <?
             if($error !=0 ){ echo $erray[$error];}else{ if($no_user==1){ echo $email_not_exist;}}
          ?>
        </td>
    </tr>
    </table>
    <?php }?>

    <form name="fm_login" method="GET" onSubmit="return CheckForm(this)" style="margin-top:5px">
    <table style="text-align:left" cellspacing="0" cellpadding="1" bgcolor="#FFA042" width="500" height="100">
        <tr>
            <td height="10px" colspan="2" style="text-align:center; font-weight:bold; border-left:1px solid #EF5F16; border-top:1px solid #EF5F16; padding:12px; ">
             <?php if($error !=0 && $displayn == 1){ ?><?= _('Please enter your registered email address!') ?> <?php }else{ ?><?= _('email_submit') ?><?php } ?>
            </td>
        </tr>
        <tr>
            <?php if($error != 0 && $displayn == 1){ ?>
                <td width="50px" align="right"><?= _("Email") ?>:</td>
                <td width="120px"><input name="email"  value="<?=$this->input->get("page")?"":htmlspecialchars($this->input->get("email"))?>" notEmpty><input type="submit" value="<?= _('Go') ?>"></td>
            <?php }else{ ?>
                <td align="center" style="border-left:1px solid #EF5F16;">
                    <?= _('An email containing a temporary password will be sent to your registered email shortly.') ?><br /><br />
                    <?= _('Kindly check your spam folder in the event our email is not received within a couple of minutes.') ?>
                </td>
            <?php } ?>
        </tr>
    </table>
    <input name="back" type="hidden" value="<?=$back?>">
    </form>
    </td>
</tr>
</table>
</div>
</body>
</html>