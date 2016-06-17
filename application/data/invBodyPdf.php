<?php
	$body = "<table border='0' cellpadding='0' cellspacing='0' width='100%'>
		<tr>
			<td colspan='2' align='left'>
				<p><img border=0 src='/images/logo/".$logo."'></p>
			</td>
		</tr>

		<tr>
			<td colspan='2' align='left'>
				<font color='#FF6600'><b><i><p>".$lang['thank_you']." ". $site_url ."</p></i></b></font>
			</td>
		</tr>

		<tr>
			<td width='50%' valign='top'>
				<b>".$lang['order_no'].":</b>
				<p>".$order_no."</p>
				<p>&nbsp;</p>
			</td>
			<td width='50%' valign='top'>
				<b>".$lang['order_date'].":</b>
				<p>".$order_date."</p>
				<p>&nbsp;</p>
			</td>
		</tr>

		<tr>
			<td width='50%' valign='top'>
				<b>".$lang['ship_to'].":</b>
				<p>".$delivery_name."</p>
				<p>&nbsp;</p>
				<p>".$delivery_address."</p>
				<p>&nbsp;</p>
			</td>
			<td width='50%' valign='top'>
				<b>".$lang['bill_to'].":</b>
				<p>".$billing_name."</p>
				<p>&nbsp;</p>
				<p>".$billing_address."</p>
				<p>&nbsp;</p>
			</td>
		</tr>

		<tr>
			<td width='50%' valign='top'>
				<b>".$lang['coupon_code'].":</b>
				<p>".$promotion_code."</p>
				<p>&nbsp;</p>
			</td>
			<td width='50%' valign='top'>
				<b>".$lang['payment_method'].":</b>
				<p>".$payment_method."</p>
				<p>&nbsp;</p>
			</td>
		</tr>

		<tr>
			<td colspan='2'>
				<table border='1' width='100%' cellspacing='0' cellpadding='4'>
					<tr>
						<td colspan='5' valign=top bgcolor='#FFCC00' bordercolor='#666666' height='10px'>
							<b>".$lang['order_details']."</b>
						</td>
					</tr>

					<tr>
						<td width='521' colspan='2' valign'top' height='10px' bgcolor='#CCCCCC'><b>".$lang['description']."</b></td>
						<td width='117' valign'top' height='10px' bgcolor='#CCCCCC'><b>".$lang["qty"]."</b></td>
						<td width='117' valign'top' height='10px' bgcolor='#CCCCCC'><b>".$lang["price"]."</b></td>
						<td width='117' valign'top' height='10px' bgcolor='#CCCCCC'><b>".$lang["total"]."</b></td>
					</tr>

					".$item_information."

					<tr>
						<td colspan='2' rowspan='4' valign='top'>&nbsp;</td>
						<td colspan='2' valign='top' bgcolor='#CCCCCC'><b>".$lang["cost_of_item"]."</b></td>
						<td valign='top'><b>".platform_curr_format($sum_total)."</b></td>
					</tr>

					<tr>
						<td colspan='2' valign='top' bgcolor='#CCCCCC'><b>".$lang["standard_insured_delivery"]."</b></td>
						<td valign='top'><b>".platform_curr_format($sid)."</b></td>
					</tr>

					<tr>
						<td colspan='2' valign='top' bgcolor='#CCCCCC'><b>".$lang["currency"]."</b></td>
						<td valign='top'><b>".$currency."</b></td>
					</tr>

					<tr>
						<td colspan='2' valign='top' bgcolor='#CCCCCC'><b>".$lang["grand_total"]."</b></td>
						<td valign='top'><b>".platform_curr_format($total)."</b></td>
					</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td colspan='2'>
			<p>&nbsp;</p>

			<font size='-1'>
				<p><b>".$lang['our_return_policy']."</b></p>
				<p>".$lang['return_policy_part1']. "http://".$site_url."/display/view/contact, <a href='mailto:".$return_email."'>".$return_email."</a> ".$lang['return_policy_part2']."</p>
			</font>
		</td>
	</tr>

	<tr>
		<td colspan='2'>
			<font size='-1'>
				<b>".$lang["sales"]."</b> ".$sales_email."<br />
				<b>".$lang["customer_service"]."</b> ".$csemail."
			</font>
		</td>
	</tr>
</table>";
