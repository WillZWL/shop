<form name="fm_login" method="post" onSubmit="return CheckForm(this)" style="margin-top:5px">
<table style="text-align:left" cellspacing="0" cellpadding="1" bgcolor="#87ceeb" width="500px" height="100">
    <tr>
        <td height="10px" colspan="3" style="text-align:center; font-weight:bold; border-left:1px solid #00AFF0; border-top:1px solid #00AFF0; border-right:1px solid #00AFF0; padding:8px; ">
          Sign in! if you already have an account with us.
        </td>
        <td width="60px" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr>
        <td width="60px" style="border-left:1px solid #00AFF0;">&nbsp;</td>
        <td>Email:</td>
        <td style="border-right:1px solid #00AFF0;"><input name="email" value="<?=$this->input->post("page")?"":htmlspecialchars($this->input->post("email"))?>" notEmpty></td>
        <td width="60px" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr>
        <td style="border-left:1px solid #00AFF0;">&nbsp;</td>
        <td>Password:</td>
        <td style="border-right:1px solid #00AFF0;"><input type="password" name="password"></td>
        <td width="60px" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr>
        <td style="border-left:1px solid #00AFF0;">&nbsp;</td>
        <td>&nbsp;</td>
        <td style="border-right:1px solid  #00AFF0;"><br>
            <input type="submit" value="Sign in and continue">
        </td>
        <td width="60px" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr>
        <td style="border-left:1px solid #00AFF0; border-bottom:1px solid #00AFF0;">&nbsp;</td>
        <td colspan="2" style="text-align:center; border-right:1px solid #00AFF0; border-bottom:1px solid #00AFF0; padding:8px;">
            <a href="<?=base_url()?>forget_password" style="color: #0000FF;text-decoration:underline">Forgotten password? </a>
        </td>
        <td width="60px" bgcolor="#FFFFFF" style="border-left:1px solid #FFFFFF;">&nbsp;</td>
    </tr>
</table>
<input type="hidden" name="posted" value="1">
</form>
