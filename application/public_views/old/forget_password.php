<?php
$invalchars = array("{","}",":","]","[","!","?","&",")","(","?",";","#",);
$erray = array("",$lang_text['not_long_enough'],$lang_text['email_no_at'],$lang_text['email_no_com'],$lang_text['email_invalid_char'],$lang_text['email_not_exist'] . " " . $lang_text['email_not_exist2'] . "<a href='" . base_url() . "contact' target='_top'>" . $lang_text['email_not_exist3'] . "</a>" . $lang_text['email_not_exist4'],"T!");
$thisemail = $this->input->get("email");
$error=0;
$displayn = 2;
if(strlen($thisemail)==0){

	$error=6;
	$displayn = 1;
}else{
	$displayn=1;
	if(strlen($thisemail)>3){
		if(stripos($thisemail, "@")===false){
			$error = 2;
		}else{
			$thisafterat = substr($thisemail,stripos($thisemail, "@"));
			if(stripos($thisafterat, ".")===false){
				$error = 3;
			}else{
				foreach($invalchars as $charf){
					if(stripos($thisemail, $charf) && $error!=4){$error=4;};
				}
			}
		}
	}else{
		$error=1;
	}
	if($no_user==1 && $error<1){
		 $error=5;
	}

	if($error!=0){$displayn = 1;}
}
$from_checkout = (strpos($back, "checkout") !== FALSE);
$page_width = $from_checkout?"100%":1000;
?>
<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?=$lang_text['title']?></title>
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
<?php
/*
if(!$from_checkout)
{
	include VIEWPATH.'header.php';
}
*/
?>
<table width="<?=$page_width?>" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
	<td align="center" valign="top" style="padding-top:80px; padding-bottom:30px;">
	<!-- Form Start -->
	<?php if($error!=0 && $error!=6){ ?>
	<table style="text-align:left" cellspacing="0" cellpadding="1" bgcolor="#FF0000" width="500px">
	<tr>
		<td height="20px" colspan="3" style=" color:#FFFFFF; text-align:center; font-weight:bold; border-left:1px solid #990000; border-top:1px solid #990000;  border-bottom:1px solid #990000; border-right:1px solid #990000; padding:8px; ">
		  <?php
			 if($error!=0){ echo $erray[$error];}else{ if($no_user==1){ echo $lang_text['email_not_exist'];}}
		  ?>
		</td>
	</tr>
	</table>
	<?php }?>

	<form name="fm_login" method="GET" onSubmit="return CheckForm(this)" style="margin-top:5px">
	<table style="text-align:left" cellspacing="0" cellpadding="1" bgcolor="#FFA042" width="500" height="100">
		<tr>
			<td height="10px" colspan="3" style="text-align:center; font-weight:bold; border-left:1px solid #EF5F16; border-top:1px solid #EF5F16; padding:8px; ">
			 <?php if($error!=0 && $displayn==1){ ?><?=$lang_text['please_enter_email']?> <?php }else{ ?><?=$lang_text['email_submit']?><?php } ?>
			</td>
			<td style="border-right:1px solid #EF5F16; border-top:1px solid #EF5F16;">&nbsp;</td>
		</tr>
		<tr>
			<?php if($error!=0 && $displayn==1){ ?>
				<td width="60px" style="border-left:1px solid #EF5F16;">&nbsp;</td>
				<td><?=$lang_text['email']?>:</td>
				<td><input name="email"  value="<?=$this->input->get("page")?"":htmlspecialchars($this->input->get("email"))?>" notEmpty><input type="submit" value="<?=$lang_text['go']?>"></td>
			<?php }else{ ?>
				<td colspan="3" align="center" style="border-left:1px solid #EF5F16;">
					<?=$lang_text['email_send_shortly']?><br /><br />
					<?=$lang_text['email_send_check_junk']?>
				</td>
			<?php } ?>
			<td style="border-right:1px solid #EF5F16;">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4" style="border-left:1px solid #EF5F16; border-bottom:1px solid #EF5F16; padding:8px; border-right:1px solid  #EF5F16; ">&nbsp;</td>
		</tr>
	</table>
	<input name="back" type="hidden" value="<?=$back?>">
	</form>
	<!-- Form End -->
	</td>
</tr>
<?php
/*
if(!$from_checkout)
{
	include VIEWPATH.'footer_web.php';
}
*/
?>
</table>
</div>
</body>
</html>