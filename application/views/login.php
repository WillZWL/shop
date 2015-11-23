<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf8">
    <meta name="ROBOTS" content="noindex, nofollow">
    <script type="text/javascript">
        top_loc = top.document.location.href;
        doc_loc = document.location.href;
        if (top_loc != doc_loc) {
            top.window.location.reload();
        }
    </script>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body leftmargin="0" topmargin="100" onLoad="document.loginform.user_id.focus()" class="login">
<center>
    <form name="loginform" method="post"
          action="<?= site_url('auth/auth') ?><?= $this->input->get("back") ? "?back=" . urlencode($this->input->get("back")) : "" ?>">
        <table cellpadding="0" cellspacing="0" border="0">
            <tr style="background-color:#FFFFFF">
                <td height="100" colspan="3" background="//www.digitaldiscount.co.uk/images/logo/digitaldiscount.png">&nbsp;</td>
            </tr>
            <tr>
                <td width="1" valign="top" class="login"></td>
                <td width="198" valign="top" height="70" style="padding-top:10px" align="center" class="login">
                    <b class="login_large"></b><br><br>
                    <b class="login">Admin Centre</b><br><br>
                    <font class="msg">
                        <?php if (!empty($err_msg)): ?>
                            <?= $err_msg ?>
                        <?php else: ?>
                            <?= "Please login to access admin centre" ?>
                        <?php endif ?>
                    </font>
                </td>
                <td width="1" valign="top" class="login"></td>
            </tr>
            <tr>
                <td width="1" valign="top" class="login"></td>
                <td width="198" height="120" align="center" class="login">
                    User Name<br>
                    <input NAME="user_id" TYPE="TEXT" SIZE="20" VALUE=""
                           style="font-size:11px;text-align:center"><br><br>
                    Password<br>
                    <input NAME="password" TYPE="password" SIZE="20" style="font-size:11px;text-align:center">
                </td>
                <td width="1" valign="top" class="login"></td>
            </tr>
            <tr>
                <td height="26" colspan="3" background="login-button.gif" align="center"><input type="submit"
                                                                                                name="submit"
                                                                                                value="Login"
                                                                                                style="font-size:11px;height:24px;width:80px">
                </td>
            </tr>
        </table>
    </form>
    <br><br><br>
</center>
</body>
</html>

