<!DOCTYPE html>
<html lang="<?=get_lang_id()?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=1"/>
	<title><?=$lang_text['confirm']?> - <?=$so_no?></title>
	<script src="/resources/mobile/js/jquery-1.10.1.min.js" type="text/javascript"></script>
	<script src="/resources/mobile/js/default.js" type="text/javascript"></script>
	<link href="/resources/mobile/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
<form id="fm_confirm" name="fm_confirm" action="<?=str_replace('http://', 'https://', base_url())."checkout/response/paypal/".($debug?"1":"")?>" method="post">
<div class="p10">
	<div id="order_confirm">
		<img src="/images/value_basket_logo.png" border="0" />
		<div class="bordered">
			<ul>
				<li>
					<label><?=$lang_text['confirm']?></label>
					<span><?=$so->get_so_no()?></span>
				</li>
				<li>
					<label><?=$lang_text['purchased_date']?></label>
					<span><?=date("Y-m-d",strtotime($so->get_order_create_date()))?></span>
				</li>
				<li>
					<label><?=$lang_text['delivery_information']?></label>
					<span>
						<?=$so->get_delivery_name()?><br />
						<?=$so->get_delivery_company()?><br />
						<?=str_replace("|", " ", $so->get_delivery_address())?><br />
						<?=$so->get_delivery_city()?><br />
						<?=$so->get_delivery_postcode()?><br />
						<?=$delivery_country->get_name()?><br />
					</span>
				</li>
				<li>
<?php 
	if ($so_items)
	{
		foreach ($so_items as $item)
		{
?>
            <table border="0" class="basket-table">
                <thead>
                    <tr>
                        <th colspan="3" class="white-grey-gradient">
                            <div>
								<strong><?php print $item->get_name()?></strong>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="tac">
                    <tr>
                        <td><?php print $lang_text["unit_price"];?></td>
                        <td><?php print $lang_text["quantity"];?></td>
                        <td><?php print $lang_text["total"];?>(<?=$so->get_currency_id()?>)</td>
                    </tr>
                    <tr>
                        <td><strong class="orange"><?php print $item->get_unit_price();?></strong></td>
                        <td>
							<div style="width:80px;margin:auto;">
								<div style="padding-left:33px;float:left;">
									<?php print $item->get_qty();?>
								</div>
								<div style="clear:both;">
							</div>
						</td>
                        <td><strong class="orange"><?php print $item->get_amount();?></strong></td>
                    </tr>                
                </tbody>            
            </table>
<?php
		}
	}
?>
				</li>
<?php
	if ($paypal_email != $client->get_email())
	{
?>
				<li>
					<label><?=$lang_text['select_address_message']?></label>
					<span>
						<input type="radio" name="pf_email" value="" CHECKED> <?=$client->get_email()?>
						<br>
						<input type="radio" name="pf_email" value="<?=$paypal_email?>"> <?=$paypal_email?>
					</span>
				</li>
<?php
}
?>
				<li class="m0">
					<div class="tac">
						<a id="order_confirm_paypal_continue" href="javascript:;" onclick="document.getElementById('fm_confirm').submit();" title="" class="orange-gradient next-step"><?=$lang_text['confirm']?></a>
						<br><br><br>
						<div class="tac basket-action-buttons">
							<a class="dark-grey-gradient" title="" onclick="location.href='<?=str_replace('http://', 'https://', base_url())."checkout_onepage".($debug?"?debug=1":"");?>'" href="javascript:;"><?=$lang_text["back_to_shopping"]?></a>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>
<input type="hidden" name="so_no" value="<?=$so_no?>">
<input type="hidden" name="token" value="<?=$token?>">
<input type="hidden" name="PayerID" value="<?=$PayerID?>">
</form>
</div>
</body>
</html>