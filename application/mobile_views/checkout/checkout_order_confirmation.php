<div id="content">
<h5 class="section-title Rokkitt"><?=$lang_text['confirmation_title']?></h5>
	<div id="payment_success">
		<div class="silver_box" id="payment_result">
			<p class="rokkit_12">
				<?=$lang_text['para_details1']?><br>
				<?=$lang_text['para_details2']?> <?=$so_no?>
				<br><br>
			</p>
			<p>
				<?=$lang_text['para_text1']?> <a href="mailto:<?=$client_email?>"><?=$client_email?></a>
				<?=$lang_text['para_text2']?>
			</p>
			<p>
			<?=$lang_text['order_review_text1']?><br><br>
			<p>
				<div style="float:left;width:400px;">
					<b><?=$lang_text['order_review_shipping_address']?></b><br><br>
					<?=$client_name?><br>
					<?=$address?><br>
					<?=$city?> <?=$postcode?> <br>
					<?=$country_name?><br><br>
					<b><?=$lang_text['telephone']?></b><br><br>
					<?=$telephone?><br><br>
				</div>
				<div style="float:left;">
					<b><?=$lang_text['order_review_items_ordered']?></b><br><br>
					<?=$item_detail?><br>
				</div>
				<div class="clear"></div>
			</p>
			<p>
			<?=$lang_text['payment_instruction1']?><br><br>
			<?=$lang_text['payment_instruction2']?><br><br>
			<?=$lang_text['payment_instruction3']?><br><br>
			<?=$lang_text['payment_instruction4']?><br><br>
			<?=$lang_text['payment_instruction5']?><br><br>
			<?=$lang_text['enquiry']?><br><br>
			<?=$lang_text['thank_you']?><br><br>
			</p>
		</div>
	</div>
</div>