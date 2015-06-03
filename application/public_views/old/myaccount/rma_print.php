<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?=$lang_text['meta_title']?></title>
<meta name="keywords" content="<?=$lang_text['meta_keyword']?>" />
<meta http-equiv="Content-Language" content="<?=get_lang_id()?>">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta name="copyright" content="Copyright 2001 - <?php echo date("Y"); ?> <?=$_SESSION["domain_platform"]["site_name"]?> All rights reserved.">
<meta name="author" content="webmaster@valuebasket.com">
<meta name="description" content="<?=$lang_text['meta_desc']?>" />
<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
<link rel="stylesheet" type="text/css" href="/css/style.css">
<script type="text/javascript" language="javascript"src="/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="/js/main.js"></script>
<script type="text/javascript" language="javascript" src="/js/image.js"></script>
<script language="javascript">
<!--
	var win = null;
	function popWindow(mypage,myname,w,h,scroll){
	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
	settings =
	'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',status=no,scrollbars='+scroll+''
	win = window.open(mypage,myname,settings)
	if(win.window.focus){win.window.focus();}
	}
//-->
</script>
<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
</head>
<body style="margin-top:0px;margin-left:0px;">
<div id="container">
<table width="800" cellpadding="0" cellspacing="0" border="0" align="center">
<tr style="height:30px"></tr>
<tr>
	<td width="706" align="left" valign="top"><a href="www.valuebasket.com"><img src="/images/valuebasket_logo.png" height="50" border="0" /></a></td>
	<td>&nbsp;</td>
	<td align="right"></td>
</tr>
<tr>
	<td width="100%" align="left" valign="top">
	<!--<img src="<?=base_url()?>images/myaccount/myaccount_rma_<?=get_lang_id()?>.gif" border="0"><br><br>-->
	<?//include_once "rma_form_".get_lang_id().".php"?>

<?php
	$cat_arr = array(0=>$lang_text['form_cat1'],1=>$lang_text['form_cat2'],2=>$lang_text['form_cat3']);
	$reason_arr = array(0=>$lang_text['form_reason1'],1=>$lang_text['form_reason2'],2=>$lang_text['form_reason3'],3=>$lang_text['form_reason4']);
	$action_arr = array(0=>$lang_text['user_action1'],1=>$lang_text['user_action2'],2=>$lang_text['user_action3']);

	if($order->get_split_so_group())
		$new_so_no = $order->get_split_so_group() . "/" . $rma_obj->get_so_no();
	else
		$new_so_no = $rma_obj->get_so_no();
?>
<table width="100%" border="0" cellpadding="3px" cellspacing="0" style="text-align:left">
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
	<td class="rma_title"><b><?=$lang_text['purchase_details']?></b></td>
	<td class="rma_field">
	<table border=0 cellpadding=0 cellspacing=0 width=99%>
	<col width="30%"><col width="30%"><col>
	<tr>
		<td><?=$lang_text['rma_number']?>&nbsp;:&nbsp;<?=htmlspecialchars($rma_obj->get_id())?></td>
		<td></td>
	</tr>
	<tr>
		<td><?=$lang_text['order_number']?>&nbsp;:&nbsp;<?=htmlspecialchars($rma_obj->get_client_id()?>-<?=$new_so_no?></td>
		<td></td>
	</tr>
	<tr>
		<td><?=$lang_text['purchase_date']?>&nbsp;:&nbsp;<?=date("Y-m-d",strtotime($order->get_order_create_date()))?></td>
		<td></td>
	</tr>
	</table>
	</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
	<td class="rma_title"><b><?=$lang_text['personal_details']?></b></td>
	<td class="rma_field">
	<table border=0 cellpadding=0 cellspacing=0 width=99%>
	<col width="33%"><col width="33%"><col>
	<tr>
		<td><b><?=$lang_text['first_name']?> :</b><br><?=htmlspecialchars($rma_obj->get_forename())?></td>
		<td><b><?=$lang_text['surname']?> :</b><br><?=htmlspecialchars($rma_obj->get_surname())?></td>
		<td></td>
	</tr>
	<tr height="10px"></tr>
	<tr>
		<td><b><?=$lang_text['country']?> :</b><br><?=htmlspecialchars($country_name)?></td>
		<td><b><?=$lang_text['city']?> :</b><br><?=htmlspecialchars($rma_obj->get_city())?></td>
		<td><b><?=$lang_text['state']?> :</b><br><?=htmlspecialchars($rma_obj->get_state())?></td>
	</tr>
	<tr height="10px"></tr>
	<tr>
		<td><b><?=$lang_text['address']?> :</b><br><?=htmlspecialchars($rma_obj->get_address_1())?><br><?=htmlspecialchars($rma_obj->get_address_2())?></td>
		<td><b><?=$lang_text['postcode']?> :</b><br><?=htmlspecialchars($rma_obj->get_postcode())?></td>
		<td></td>
	</tr>
	</table>
	</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
	<td class="rma_title"><b><?=$lang_text['product_details']?></b></td>
	<td class="rma_field">
	<table border=0 cellpadding=0 cellspacing=0 width=99%>
	<col width="33%"><col width="33%"><col>
	<tr>
		<td><b><?=$lang_text['product_returned']?> :</b><br><?=htmlspecialchars($rma_obj->get_product_returned())?></td>
		<td><b><?=$lang_text['serial_number']?> :</b><br><?=htmlspecialchars($rma_obj->get_serial_no())?></td>
		<td><b><?=$lang_text['categories']?> :</b><br><?= $cat_arr[$rma_obj->get_category()]; ?></td>
	</tr>
	</table>
	</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
	<td class="rma_title"><b><?=$lang_text['return_details']?></b></td>
	<td class="rma_field">
	<table border=0 cellpadding=0 cellspacing=0 width=99%>
	<col width="33%"><col width="33%"><col>
	<tr>
		<td><b><?=$lang_text['reason_for_returns']?> :</b><br><?= $reason_arr[$rma_obj->get_reason()];?></td>
		<td><b><?=$lang_text['action_required']?> :</b><br><?=$action_arr[$rma_obj->get_action_request()]; ?></td>
		<td></td>
	</tr>
	<tr height="10px"></tr>
	<tr>
		<td colspan="3"><b><?=$lang_text['detail_desc_of_product']?> :</b><br><?=htmlspecialchars($rma_obj->get_details())?></td>
	</tr>
	</table>
	</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
	<td class="rma_title"><b><?=$lang_text['declaration']?></b></td>
	<td class="rma_field">
		<p>
		<?=$lang_text['vb_return_policy']?><br />
		<ol>
			<li><?=$lang_text['policy1']?></li>
			<li><?=$lang_text['policy2']?></li>
			<li><?=$lang_text['policy3']?>
				<ol>
					<li style="list-style-type:lower-roman"><?=$lang_text['policy4']?></li>
					<li style="list-style-type:lower-roman"><?=$lang_text['policy5']?></li>
				</ol>
			</li>
			<li><?=$lang_text['policy6']?></li>
			<li><?=$lang_text['policy7']?></li>
			<li><?=$lang_text['policy8']?></li>
			<li><?=$lang_text['policy9']?></li>
			<li><?=$lang_text['policy10']?></li>
			<li><?=$lang_text['policy11']?></li>
		</ol>
	</p>
	<input type="checkbox" value="1" dname="Declaration" checked disabled>&nbsp;<?=$lang_text['agree_terms']?>
	</td>
</tr>
</table>
	<!-- RMA formform end-->
	</td>
</tr>
</table>
</body>
</html>
<script language="javascript">
//<!--
	window.print();
//-->
</script>