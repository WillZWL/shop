<div class="p10">
	<h2 class="section-title Rokkitt"><?php print $lang_text["header"]; ?></h2>
		<div id="payment_success">
			<div>
<?php
	if ($success == 1)
	{
?>
				<p>
				<?php print $lang_text["payment_success_content_1"]; ?> <?=$so->get_so_no();?>
				<?php print $lang_text["payment_success_content_2"]; ?> <?=$client->get_email();?>
				<?php print $lang_text["payment_success_content_3"]; ?>
				<br>
				</p>
				<ul>
					<li>
						<label><?=$lang_text['payment_success_table_header_1']?></label>
						<span>
							<?=$client->get_forename() . ' ' . $client->get_surname();?><br>
							<?=str_replace("|", " ",  $so->get_delivery_address());?>
							<?=$so->get_delivery_city();?><br>
							<?=$so->get_delivery_postcode();?><br>
							<?=($so->get_delivery_state()) ? $so->get_delivery_state() : ""?><br>
							<?=$country->get_name();?><br>
						</span>
					</li>
					<li>
						<label><?=$lang_text['payment_success_table_header_2']?></label>
						<span>
<?php
						$items = "";
						foreach ($so_items as $key => $row)
						{
							$qty = $row->get_qty();
							$items .= $qty . ' x ' . $row->get_name() . "<br>";
						}
						print $items;
?>
						</span>
					</li>
					<li>
						<label><?=$lang_text['payment_success_table_header_3']?></label>
						<span>
						<?php print ($client->get_tel_1()) ? $client->get_tel_1() : "" . ($client->get_tel_2()) ? $client->get_tel_2() : "" . ($client->get_tel_3()) ? $client->get_tel_3() : "" ?>
						</span>
					</li>
				</ul>
				<br>
				<?php print $lang_text["payment_success_content_4"]; ?>
				<a class="contact_link" href="<?=$base_url?>contact"><?php print $lang_text["payment_success_content_5"]; ?></a>
				<?php print $lang_text["payment_success_content_6"]; ?>
<?php
	}
	else
	{
?>
		<?php print $lang_text["payment_unsuccess_content_1"]; ?>
		<?=($display_cs_phone_no) ? $cs_phone_no:""?><br>
		<?php print $lang_text["payment_unsuccess_content_2"]; ?><br>
		<a class="contact_link" href="<?=$base_url?>contact"><?php print $lang_text["payment_unsuccess_content_3"]; ?></a>
		<?php print $lang_text["payment_unsuccess_content_4"]; ?>
		<?php print $lang_text["payment_unsuccess_content_5"]; ?>.<br><br>
<?php
	}
?>
			<div class="tac basket-action-buttons">
				<a class="dark-grey-gradient" title="" href="<?=$base_url;?>"><?=$lang_text["continue_shopping"]?></a>
			</div>
		</div>
	</div>
</div>
