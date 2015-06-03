<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="/css/style.css">
<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
<script src="/js/common.js" type="text/javascript"></script>
<title><?=$lang_text['confirm']?> - <?=$so_no?></title>
</head>

<body>
<?php require_once "order_confirm_tracking_pixel.php"; ?>
<div id="container">
<form name="fm_confirm" action="<?=str_replace('http://', 'https://', base_url())."checkout/response/paypal/".($debug?"1":"")?>" method="post">
<table border="0" cellpadding="5" cellspacing="0" width="940px" bgcolor="#FFFFFF" align="center">
	<tr>
		<td><img src="/images/value_basket_logo.png" border="0" /></td>
	</tr>
	<tr  height="1px" bgcolor="#FF9900"><td style="line-height:1px;padding:0px;" background="/images/line_blue.png" width="100%"></td></tr>
	<tr>
		<td>
			<table width="100%" cellpadding="10px" cellspacing="0" border="0">
			<tr>
				<td width="30%" valign="top">
					<b><?=$lang_text['confirm']?>:</b> <?=$so->get_so_no()?><br />
					<b><?=$lang_text['purchased_date']?>:</b> <?=date("Y-m-d",strtotime($so->get_order_create_date()))?>
				</td>
				<td width="35%" valign="top">
<?/*
					<b>Billing Information</b><br />
					<?=$so->get_bill_name()?><br /><br />
					<?=$so->get_bill_company()?><br />
					<?=str_replace("|", "<br />", $so->get_bill_address())?><br />
					<?=$so->get_bill_city()?><br />
					<?=$so->get_bill_postcode()?><br />
					<?=$bill_country->get_name()?><br />
*/?>
				</td>
				<td width="35%" valign="top">
					<b><?=$lang_text['delivery_information']?></b><br />
					<?=$so->get_delivery_name()?><br /><br />
					<?=$so->get_delivery_company()?><br />
					<?=str_replace("|", "<br />", $so->get_delivery_address())?><br />
					<?=$so->get_delivery_city()?><br />
					<?=$so->get_delivery_postcode()?><br />
					<?=$delivery_country->get_name()?><br />
				</td>
			</tr>
			</table>
		</td>
		<td>
		</td>
		<td>
		</td>
	</tr>
	<tr height="1px" bgcolor="#FF9900"><td style="line-height:1px;padding:0px;" background="/images/line_blue.png"></td></tr>
	<tr>
		<td>
			<p>
			<table width="100%" cellspacing="0" cellpadding="4">
				<tr style="font-weight:bold;background:#DDDDDD">
					<td width="50" style="border:1px solid #BBBBBB; border-width:1px 0px 1px 1px;">&nbsp;</td>
					<td align="left" style="border:1px solid #BBBBBB; border-width:1px 0px 1px 1px;"><?=$lang_text['product']?></td>
					<td align="right" width="40" style="border:1px solid #BBBBBB; border-width:1px 0px 1px 1px;"><?=$lang_text['quantity']?></td>
					<td align="right" width="80" style="border:1px solid #BBBBBB; border-width:1px 1px 1px 1px;"><?=$lang_text['total']?> (<?=$so->get_currency_id()?>)</td>
				</tr>
					<?php
						if ($so_items)
						{
							foreach ($so_items as $item)
							{
					?>
						<tr>
							<td align="center" style="border-left:1px solid #BBBBBB;"><img src="<?=get_image_file($item->get_main_img_ext(), 's', $item->get_prod_sku());?>"></td>
							<td align="left" style="border-left:1px solid #BBBBBB;"><?=$item->get_name()?></td>
							<td align="right" style="border-left:1px solid #BBBBBB;"><?=$item->get_qty()?></td>
							<td align="right" style="border-left:1px solid #BBBBBB;border-right:1px solid #BBBBBB;"><b><?=$item->get_amount()?></b></td>
						</tr>
					<?php
							}
						}
					?>
						<tr>
							<td colspan="5" style="border-top:1px solid #BBBBBB;">&nbsp;</td>
						</tr>
					</table>
			<p>

		</td>
	</tr>
	<tr>
		<td>
			<table width="100%">
			<?php
				if ($paypal_email != $client->get_email())
				{
			?>
				<tr>
					<td>
						<table>
							<tr valign="top">
								<td>
									<?=$lang_text['select_address_message']?>:
								</td>
								<td style="white-space:nowrap" nowrap>
									<input type="radio" name="pf_email" value="" CHECKED> <?=$client->get_email()?>
									<br>
									<input type="radio" name="pf_email" value="<?=$paypal_email?>"> <?=$paypal_email?>
									<br><br>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			<?php
				}
			?>
				<tr>
					<td><input type="button" value="<?=$lang_text['back_to_shopping']?>" onClick="Redirect('<?=str_replace('http://', 'https://', base_url())."checkout_onepage".($debug?"?debug=1":"");?>')"></td>
					<td align="right"><input type="submit" value="<?=$lang_text['confirm']?>"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<input type="hidden" name="so_no" value="<?=$so_no?>">
<input type="hidden" name="token" value="<?=$token?>">
<input type="hidden" name="PayerID" value="<?=$PayerID?>">
</form>
</div>
</body>
</html>