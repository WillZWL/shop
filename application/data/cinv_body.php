<?php

$body="
<table width='100%' cellpadding='0' cellspacing='0' border='0'>
<tr>
	<td colspan='3' align='center'>$logo_place_holder<b style='font-size:16px;'>Custom Invoice</b><br/><br/></td>
</tr>
<tr>
	<td width='10%'>&nbsp;</td>
	<td width='80%'>
	<table width='100%' cellpadding='0' cellspacing='1' border='0' bgcolor='#000000'>
	<tr>
		<td class='field' width='20%'>Date of Invoice</td>
		<td class='value' width='30%'>".$date_of_invoice."</td>
		<td class='field' width='20%'>Order No.</td>
		<td class='value' width='30%'>".$client_id . '-' . $order_number."</td>
	</tr>
	<tr>
		<td class='field'>Shipper</td>
		<td class='value'>".$shipper_name."</td>
		<td class='field'>Ship to</td>
		<td class='value'>".$deliver_name."</td>
	</tr>
	<tr>
		<td class='field' rowspan='6' valign='top' style='padding-top:4px;'>Shipper Address</td>
		<td class='value'>".$saddr_1."</td>
		<td class='field' rowspan='6' valign='top' style='padding-top:4px;'>Ship to address</td>
		<td class='value'>".$daddr_1."</td>
	</tr>
	<tr>
		<td class='value'>".$saddr_2."</td>
		<td class='value'>".$daddr_2."</td>
	</tr>
	<tr>
		<td class='value'>".$saddr_3."</td>
		<td class='value'>".$daddr_3."</td>
	</tr>
	<tr>
		<td class='value'>".$saddr_4."</td>
		<td class='value'>".$daddr_4."</td>
	</tr>
	<tr>
		<td class='value'>".$saddr_5."</td>
		<td class='value'>".$daddr_5."</td>
	</tr>
	<tr>
		<td class='value'>".$saddr_6."</td>
		<td class='value'>".$daddr_6."</td>
	</tr>
	<tr>
		<td class='field'>Contact person</td>
		<td class='value'>".$shipper_contact."</td>
		<td class='field'>Contact person</td>
		<td class='value'>".$deliver_name."</td>
	</tr>
	<tr>
		<td class='field'>Contact no.</td>
		<td class='value'>".$shipper_phone."</td>
		<td class='field'>Contact no.</td>
		<td class='value'>".$deliver_phone."</td>
	</tr>
	</table>
	<br/><br/>
	<table border='0' cellpadding='0' cellspacing='0' width='100%'>
	<tr bgcolor='#000000'>
		<td colspan='11' height='1'></td>
	</tr>
	<tr>
		<td width='1' class='tborder'></td>
		<td class='field'>Product descriptions</td>
		<td width='1' class='tborder'></td>
		<td class='field'>Qty</td>
		<td width='1' class='tborder'></td>
		<td width='15%' class='field'>HS Code</td>
		<td width='1' class='tborder'></td>
		<td width='15%' class='field'>Unit Value(".$currency.")</td>
		<td width='1' class='tborder'></td>
		<td width='15%' class='field'>Total Value(".$currency.")</td>
		<td width='1' class='tborder'></td>
	</tr>
	<tr bgcolor='#000000'>
		<td colspan='11' height='1'></td>
	</tr>
	".$item_info."
	<tr>
		<td colspan='6' rowspan='8' class='value'></td>
		<td width='1' class='tborder'></td>
		<td class='field'>Cost of Item</td>
		<td width='1' class='tborder'></td>
		<td class='value'>".$total_cost."</td>
		<td width='1' class='tborder'></td>
	</tr>
	<tr bgcolor='#000000'>
		<td colspan='5' height='1'></td>
	</tr>
	</tr>
	<tr>
		<td width='1' class='tborder'></td>
		<td class='field'>Processing Fee</td>
		<td width='1' class='tborder'></td>
		<td class='value'>".$processing_fee."</td>
		<td width='1' class='tborder'></td>
	</tr>
	<tr bgcolor='#000000'>
		<td colspan='5' height='1'></td>
	</tr>

	<tr>
		<td width='1' class='tborder'></td>
		<td class='field'>Delivery</td>
		<td width='1' class='tborder'></td>
		<td class='value'>".$delivery."</td>
		<td width='1' class='tborder'></td>
	</tr>
	<tr bgcolor='#000000'>
		<td colspan='5' height='1'></td>
	</tr>

	<tr>
		<td width='1' class='tborder'></td>
		<td class='field'>Total value</td>
		<td width='1' class='tborder'></td>
		<td class='value'>".$total_value."</td>
		<td width='1' class='tborder'></td>
	</tr>
	<tr bgcolor='#000000'>
		<td colspan='5' height='1'></td>
	</tr>

	</table>
	<br/><br/>
	<div style='width:100%; text-align:left; font-size:12px; position:relative'>
	I declare that the above information is true and correct and to the best of our knowledge. The product(s) covered by this document are not subject to any export or import prohibitions & restrictions.<br/><br/>
	Signature&nbsp;:&nbsp;<img src='".base_url()."/images/vb_sign_cinv.png' style='border-bottom:1px solid #000000;'>
	<img src='".base_url()."order/integrated_order_fulfillment/get_barcode/$order_number' style='position:absolute;bottom:5px;right:0;'>
	</div>
	</td>
	<td width='10%'>&nbsp;</td>
</tr>
</table>
";
?>