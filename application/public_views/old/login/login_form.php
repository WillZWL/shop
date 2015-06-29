<form name="fm_login" id="fm_login" method="post" onSubmit="return CheckForm(this)" style="margin-top:5px">
    <table width="258" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="15"></td>
        </tr>
        <tr>
            <td align="center">Sign in if you already have an account with us!</td>
        </tr>
        <tr>
            <td height="15"></td>
        </tr>
        <tr>
            <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>E-mail</td>
                        <td width="170"><input name="email" value="<?=$this->input->post("page")?"":htmlspecialchars($this->input->post("email"))?>" notEmpty dname="E-mail"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="10"></td>
        </tr>
        <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>Password</td>
                        <td width="170"><input type="password" name="password" notEmpty></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="10"></td>
        </tr>
        <tr>
            <td><table width="143" border="0" align="center" cellpadding="0" cellspacing="0" id="sign_cnt">
                    <tr>
                        <td height="39" align="center" background="/images/orderformbox_23.gif" style="cursor:pointer;" onClick="return SubmitLogin();"><font color="#ffffff"><strong>Sign in and continue</strong></font><a id="sign_in" href="<?=base_url()?>login/login_redirect" rel="lyteframe" rev="width: 550px; height:200px; scrolling: auto;padding: 40px;"></a></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="10"></td>
        </tr>
        <tr>
            <td align="center">
                <a id="a_check" href="<?=base_url()?>forget_password?back=checkout" rel="lyteframe" rev="width: 600px; height:400px; scrolling: auto;padding: 40px;">Forgotten password?</a>
            </td>
        </tr>
    </table>
<input type="hidden" name="posted" value="1">
</form>
<script>
function SubmitLogin()
{
<?php
    if ($ajax)
    {
?>
        if(CheckForm(document.fm_login) && xajax.call('_check_login', { parameters: [xajax.getFormValues('fm_login')], callback: x_CallBack.sign_cnt, mode: 'syncronous'}))
        {
            document.getElementById('sign_in').onclick();
            return false;
        }
<?php
    }
    else
    {
?>
        if(CheckForm(document.fm_login))
        {
            document.fm_login.submit();
            return true;
        }
<?php
    }
?>
}
</script>