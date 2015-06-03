<?
$result["desc"] = array
					(
					"ps"=>array
							(
							"REFUSED"=>"Status REFUSED means the financial institution refused to authorise the payment. Possible reasons for refusal are: an exceeded credit limit, an incorrect expiry date, insufficient balance, or many other depending on the selected payment method. RBS WorldPay is not always informed of the refusal reason by the financial institutions. When RBS WorldPay does receive a reason for refusal, this is visible to you in the Merchant Interface.",
							"ERROR"=>"A payment that is not completely processed by the payment system obtains the status ERROR. There can be different (technical) reasons for this payment status.",
							"CANCELLED"=>"You can only cancel payments with status AUTHORISED that have not yet reached the status CAPTURED. Status CANCELLED means that the authorisation (your right to collect the money) is cancelled. You can choose to cancel for various reasons: because the goods or services are no longer available, or you suspect fraud, etc.<br /><br />Your cancel instructions can be submitted manually through the Merchant Interface or by sending an XML cancel instruction via an order modification message.",
							"AUTHORISED"=>"Status AUTHORISED means that the financial institution has approved the payment. For on-line payment methods, such as credit cards, this payment status is obtained directly after the relevant payment data are sent to the financial institution. Provided, of course, the payment is authorised. Payments performed through bank transfer or other off-line payment methods reach the status AUTHORISED in an off-line stage.<br /><br />In the case of a credit card payment, the amount is \" for you and will be deducted from the shopper's spending limit, but is not actually transferred yet. In the case of bank transfers and some other payment methods, the payment immediately obtains the status CAPTURED after being set to AUTHORISED.<br />RBS WorldPay informs your system of the authorisation so you can commence shipping the ordered goods. In case of credit card payments the authorisation remains valid up to a maximum of 28 days.",
							"DEFAULT"=>"",
							),
					"avs"=>array
							(
							"NOT_SUPPLIED_BY_SHOPPER"=>" This status is returned if we retrieve no address information from the merchant.",
							"NOT_SENT_TO_ACQUIRER"=>" This status is returned if the address details are not sent to the acquirer. This either means that an address is not complete or the acquirer does not support AVS.",
							"NO_RESPONSE_FROM_ACQUIRER"=>" This status is returned if the AVS data has been sent to the acquirer, but the acquirer does not respond to the AVS request.",
							"NOT_CHECKED_BY_ACQUIRER"=>" This status is returned if the acquirer responds with a ．not checked・ message. In most cases this means that the card issuer does not have address information.",
							"FAILED"=>" This status is returned if the acquirer responds that the address details do not match with the details at the issuer.",
							"PARTIAL_APPROVED"=>" This status is returned if the acquirer responds that only part of the address details match with the details at the issuer.",
							"APPROVED"=>" This status is returned if the acquirer responds that address matches the details at the issuer.",
							"UNKNOWN"=>" This status is returned if the acquirer returns an ．UNKNOWN・ status. This means that the acquirer could not verify the status.",
							),
					"cvc"=>array
							(
							"NOT_SUPPLIED_BY_SHOPPER"=>" This status is returned if Bibit retrieves no CVC information.",
							"NOT_SENT_TO_ACQUIRER"=>" This status is returned if the CVC details are not sent to the acquirer. This means that the acquirer does not support CVC.",
							"NO_RESPONSE_FROM_ACQUIRER"=>" This status is returned if the CVC data has been sent to the acquirer, but the acquirer does not respond to the CVC request.",
							"NOT_CHECKED_BY_ACQUIRER"=>" This status is returned if the acquirer responds with a ．not checked・ message.",
							"FAILED"=>" This status is returned if the acquirer responds that the CVC code does not match with the details at the issuer.",
							"APPROVED"=>" This status is returned if the acquirer responds that the CVC code matches the details at the issuer",
							"UNKNOWN"=>" This status is returned if the acquirer returns an 'UNKNOWN' status. This means that the acquirer could not verify the status.",
							),
					);

$result["display"] = array
					(
					"ps"=>array
							(
							"REFUSED"=>"We are sorry to inform you that your payment transaction has been refused by the financial institute. Possible reasons for refusal are: an exceeded credit limit, an incorrect expiry date, insufficient balance or an incorrect billing address or credit card details.  Please ensure that your billing address is the same as the billing address used to register your credit card.  You may wish to try to make a payment after checking all details or choose a different payment method.",
							"ERROR"=>"We are sorry to inform you that there is a technical error while processing your payment transaction.  You may wish to try to make a payment after checking all details or choose a different payment method.",
							"CANCELLED"=>"Your order has been cancelled.",
							"AUTHORISED"=>"Thank you for your order. Your payment has been accepted.",
							"DEFAULT"=>"The financial institute is unable to process your payment. Please try again later.",
							),
					"avs"=>array
							(
							"NOT_SUPPLIED_BY_SHOPPER"=>" This status is returned if we retrieve no address information from the merchant.",
							"NOT_SENT_TO_ACQUIRER"=>" This status is returned if the address details are not sent to the acquirer. This either means that an address is not complete or the acquirer does not support AVS.",
							"NO_RESPONSE_FROM_ACQUIRER"=>" This status is returned if the AVS data has been sent to the acquirer, but the acquirer does not respond to the AVS request.",
							"NOT_CHECKED_BY_ACQUIRER"=>" This status is returned if the acquirer responds with a ．not checked・ message. In most cases this means that the card issuer does not have address information.",
							"FAILED"=>" This status is returned if the acquirer responds that the address details do not match with the details at the issuer.",
							"PARTIAL_APPROVED"=>" This status is returned if the acquirer responds that only part of the address details match with the details at the issuer.",
							"APPROVED"=>" This status is returned if the acquirer responds that address matches the details at the issuer.",
							"UNKNOWN"=>" This status is returned if the acquirer returns an ．UNKNOWN・ status. This means that the acquirer could not verify the status.",
							),
					"cvc"=>array
							(
							"NOT_SUPPLIED_BY_SHOPPER"=>" This status is returned if Bibit retrieves no CVC information.",
							"NOT_SENT_TO_ACQUIRER"=>" This status is returned if the CVC details are not sent to the acquirer. This means that the acquirer does not support CVC.",
							"NO_RESPONSE_FROM_ACQUIRER"=>" This status is returned if the CVC data has been sent to the acquirer, but the acquirer does not respond to the CVC request.",
							"NOT_CHECKED_BY_ACQUIRER"=>" This status is returned if the acquirer responds with a ．not checked・ message.",
							"FAILED"=>" This status is returned if the acquirer responds that the CVC code does not match with the details at the issuer.",
							"APPROVED"=>" This status is returned if the acquirer responds that the CVC code matches the details at the issuer",
							"UNKNOWN"=>" This status is returned if the acquirer returns an 'UNKNOWN' status. This means that the acquirer could not verify the status.",
							),
					);

?>