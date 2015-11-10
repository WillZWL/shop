<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?= _('Reset your password') ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
<style type="text/css">
body { width:auto;height:auto;background: #fff;font-family: "Open Sans Light", sans-serif;text-align: center;}
<!--
.bigger_font {font-size: 11px; line-height:20px; text-align: justify;}
-->
</style>
</head>
<body topmargin="0" leftmargin="0">
<div id="container">
<table cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
    <td align="center" valign="top" style="padding-top:80px; padding-bottom:30px;">
    <!-- Form Start -->
    <table style="text-align:left" cellspacing="0" cellpadding="1" bgcolor="#FF0000" width="500px">
    <tr>
        <?php if($error>0) { ?>
        <td height="20px" style="color:#FFFFFF; text-align:center; font-weight:bold; border-left:1px solid #990000; border-top:1px solid #990000;  border-bottom:1px solid #990000; border-right:1px solid #990000; padding:8px; ">
            <?=$notice?>
        </td>
        <?php } ?>
    </tr>
    </table>
    <form name="fm_login" method="GET" style="margin-top:5px">
    <input type='hidden' value='1' name='reset'>
    <table style="text-align:left" cellspacing="0" cellpadding="1" bgcolor="#549B17" width="500" height="100">
        <tr>
            <td style="text-align:center; font-weight:bold;">
             <?= _('Please enter your registered email address!') ?>
            </td>
        </tr>
        <tr>
            <?php
                if ($error !== 0) {
            ?>
                <td style="text-align:center; font-weight:bold;"><?= _("Email") ?>:
                    <input name="email" style="width:240px;font-size: 14px;" value="<?=$this->input->get("page")?"":htmlspecialchars($this->input->get("email"))?>" notEmpty>
                    <input type="submit" style="font-size: 16px;" value="<?= _('Go') ?>">
                </td>
            <?php
                }
                if ($error === 0) {
            ?>
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