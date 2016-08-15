select '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n    <head>\r\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\r\n        <title>[:site_name:]: Your order has been delivered on time [:so_no:]</title>\r\n    </head>\r\n\r\n<body bgcolor=\"#eee\"><center>\r\n<table width=\"0\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#eee\">\r\n  <tr>\r\n    <td align=\"center\" style=\"padding:20px 20px;\">\r\n    <table width=\"700\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n  <tr>\r\n    <td height=\"650\">\r\n    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n      <tr>\r\n        <td width=\"19%\" style=\"padding:10px 0px;\"><img src=\"[:logo:]\" width=\"130\" height=\"60\" /></td>\r\n        <td width=\"81%\"></td>\r\n      </tr>\r\n    </table>\r\n\r\n    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#FFFFFF\">\r\n      <tr>\r\n        <td align=\"center\" style=\"padding:30px 30px;\"><font style=\"color:#666; font-weight: bold; font-family:Arial; line-height: 36px; font-size: 34px; \">Thank you.</font><br />\r\n<font style=\"color:#666; font-weight: bold; font-family:Arial; line-height: 36px; font-size: 22px; \">From all of us here at [:site_name:]!</font>\r\n</td></tr></table>\r\n    <img src=\"http://cdn.valuebasket.com/808AA1/vb/resources/images/Line_Org01.jpg\" width=\"100%\" height=\"3\" />\r\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#FFFFFF\">\r\n  <tr>\r\n        <td align=\"left\" style=\"padding:20px 20px 30px;\"><font style=\"color:#444; font-family:Arial; line-height: 23px; font-size: 12px; \">Dear [:forename:],<br /><br />\r\n          We\'re delighted to confirm that your order was delivered on [:last_update_time:]. We hope that you enjoy your purchase, and hope you visit us again soon! <br /> <br />\r\n          As part of our efforts to continuously deliver a superior customer experience, we’d like to invite you to share your experience with us. Your opinion is a key element in helping us understand how we can better service your future needs.\r\n\r\n</font>\r\n</td></tr></table>\r\n\r\n\r\n\r\n<img src=\"http://cdn.valuebasket.com/808AA1/vb/resources/images/Line_gry01.jpg\" width=\"100%\" height=\"14\" /><br/>\r\n\r\n<p><Font style=\"color:#444; font-family:Arial; line-height: 14px; font-size: 12px; padding:20px 20px;\">If we could be of further assistance regarding your order, feel free to contact us directly <a href=\"[:site_url:]/display/view/contact\" target=\"new\">here</a>.</Font></p>\r\n\r\n\r\n    </td>\r\n  </tr>\r\n</table></td>\r\n  </tr>\r\n</table>\r\n</body>\r\n</html>' into @message_html ;

select @message_html;


INSERT INTO `template` ( `type`, `tpl_id`, `tpl_name`, `platform_id`, `description`, `subject`, `bcc`, `cc`, `reply_to`, `from_name`, `from`, `tpl_file_name`, `tpl_alt_file_name`, `message_html`, `message_alt`, `status`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`)
VALUES
	( 1, 'aftership_thank_you_mail', 'Aftership Thank You Mail',
	 'WEBAU',
	 'Thank You email for Delivery on time - Aftership', '[:site_name:]: Your order has been delivered on time [:so_no:]', 'pantherbccemail@gmail.com', '', '',
	'Ahead Digital', 'support@aheaddigital.com.au',
	 '', '',
	 '', '', 1, now(), 0, 'feeling', now(), 3746177670, 'feeling'),
	( 1, 'aftership_thank_you_mail', 'Aftership Thank You Mail',
	 'WEBBE',
	 'Thank You email for Delivery on time - Aftership', '[:site_name:]: Your order has been delivered on time [:so_no:]', 'pantherbccemail@gmail.com', '', '',
	'Numeri Stock', 'support@numeristock.be',
	 '', '',
	 '', '', 1, now(), 0, 'feeling', now(), 3746177670, 'feeling'),
	( 1, 'aftership_thank_you_mail', 'Aftership Thank You Mail',
	 'WEBES',
	 'Thank You email for Delivery on time - Aftership', '[:site_name:]: Your order has been delivered on time [:so_no:]', 'pantherbccemail@gmail.com', '', '',
	'Buholoco', 'soporte@buholoco.es',
	 '', '',
	 '', '', 1, now(), 0, 'feeling', now(), 3746177670, 'feeling'),
	( 1, 'aftership_thank_you_mail', 'Aftership Thank You Mail',
	 'WEBFR',
	 'Thank You email for Delivery on time - Aftership', '[:site_name:]: Your order has been delivered on time [:so_no:]', 'pantherbccemail@gmail.com', '', '',
	'Numeri Stock', 'support@numeristock.fr',
	 '', '',
	 '', '', 1, now(), 0, 'feeling', now(), 3746177670, 'feeling'),
	( 1, 'aftership_thank_you_mail', 'Aftership Thank You Mail',
	 'WEBGB',
	 'Thank You email for Delivery on time - Aftership', '[:site_name:]: Your order has been delivered on time [:so_no:]', 'pantherbccemail@gmail.com', '', '',
	'DigitalDiscount', 'support@digitaldiscount.co.uk',
	 '', '',
	 '', '', 1, now(), 0, 'feeling', now(), 3746177670, 'feeling'),
	( 1, 'aftership_thank_you_mail', 'Aftership Thank You Mail',
	 'WEBIT',
	 'Thank You email for Delivery on time - Aftership', '[:site_name:]: Your order has been delivered on time [:so_no:]', 'pantherbccemail@gmail.com', '', '',
	'Nuovadigitale', 'assistenza@nuovadigitale.it',
	 '', '',
	 '', '', 1, now(), 0, 'feeling', now(), 3746177670, 'feeling'),
	( 1, 'aftership_thank_you_mail', 'Aftership Thank You Mail',
	 'WEBNL',
	 'Thank You email for Delivery on time - Aftership', '[:site_name:]: Your order has been delivered on time [:so_no:]', 'pantherbccemail@gmail.com', '', '',
	'9digital', 'support@9digital.nl',
	 '', '',
	 '', '', 1, now(), 0, 'feeling', now(), 3746177670, 'feeling'),
	( 1, 'aftership_thank_you_mail', 'Aftership Thank You Mail',
	 'WEBNZ',
	 'Thank You email for Delivery on time - Aftership', '[:site_name:]: Your order has been delivered on time [:so_no:]', 'pantherbccemail@gmail.com', '', '',
	'Ahead Digital', 'support@aheaddigital.co.nz',
	 '', '',
	 '', '', 1, now(), 0, 'feeling', now(), 3746177670, 'feeling'),
	( 1, 'aftership_thank_you_mail', 'Aftership Thank You Mail',
	 'WEBPL',
	 'Thank You email for Delivery on time - Aftership', '[:site_name:]: Your order has been delivered on time [:so_no:]', 'pantherbccemail@gmail.com', '', '',
	'Elektroraj', 'support@elektroraj.pl',
	 '', '',
	 '', '', 1, now(), 0, 'feeling', now(), 3746177670, 'feeling');


update template set `message_html` = @message_html where tpl_id = 'aftership_thank_you_mail';


select * from template where tpl_id = 'aftership_thank_you_mail';

