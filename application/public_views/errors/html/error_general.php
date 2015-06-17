<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	$lang_id = (function_exists("get_lang_id")) ? get_lang_id() : "en";
	$template_path = $lang_id . "/template/default.ini";
	$ini_array = parse_ini_file(APPPATH. "language/" . $template_path);
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Value Basket | Error</title>
	<link rel="SHORTCUT ICON" href="/resources/images/favicon.ico" />

	<script type="text/javascript" src="/resources/js/jquery.js"></script>
	<script type="text/javascript" src="/resources/js/jquery.selectbox.js"></script>
	<script type="text/javascript" src="/resources/js/jquery.fancybox-1.3.4.js"></script>
	<script type="text/javascript" src="/resources/js/default.js"></script>

	<link href="/resources/css/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css" media="screen" />

	<!-- load fonts -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin,cyrillic" rel="stylesheet" type="text/css" />
	<link href="http://fonts.googleapis.com/css?family=Rokkitt:400,700" rel="stylesheet" type="text/css" />

	<link href="/resources/css/reset.css" rel="stylesheet" type="text/css" media="screen, print" />
	<link href="/resources/css/selectbox.css" rel="stylesheet" type="text/css" media="screen, print" />
	<link href="/resources/css/style<?php print ($lang_id != 'en') ? ("_" . $lang_id) : "";?>.css" rel="stylesheet" type="text/css" media="screen, print" />
	<link href="/resources/css/print.css" rel="stylesheet" type="text/css" media="print" />

	<!--[if IE 6]>
		<link href="/resources/css/style_ie6.css" rel="stylesheet" type="text/css" media="screen, print" />
		<script type="text/javascript" src="/resources/js/pngfix8a-min.js"></script>
		<script type="text/javascript">DD_belatedPNG.fix('.fixpng');</script>
	<![endif]-->
</head>
<body lang="en">
	<div id="container" class="fixpng">
		<div class="wrapper">
			<div id="header">
				<ul id="head-nav">
					<li><a href="http://www.valuebasket.com/" title="Home"><?=$ini_array["default_menu_home"]; ?></a></li>
					<li><a href="http://www.valuebasket.com/myaccount/myaccount?x_sign_in=1" title="My account"><?=$ini_array["default_menu_my_account"]; ?></a></li>
					<li><a href="http://www.valuebasket.com/display/view/shipping" title="Shipping"><?=$ini_array["default_menu_shipping"]; ?></a></li>
					<li><a href="http://www.valuebasket.com/contact" title="Contact Us"><?=$ini_array["default_menu_contact_us"]; ?></a></li>
					<li><a href="http://www.valuebasket.com/display/view/faq" title="Help"><?=$ini_array["default_menu_help"]; ?></a></li>
				</ul>
				<div class="separator"><div></div></div>
				<h1><a href="http://www.valuebasket.com/" title="ValueBasket">ValueBasket</a></h1>
				<div id="search" class="fix box-shadow-2">
					<form action="" method="get">
						<input type="text" value="<?=$ini_array['default_text_find_your_product']?>" class="input" name="w" title="<?=$ini_array['default_text_find_your_product']?>" />
						<button type="submit" title="Send" class="button"><?=$ini_array["default_text_send"]; ?></button>
					</form>
				</div>

				<div class="clear"></div>

			</div>
				<div style="height:29px;">
				</div>
				<center>
					<h2 style="font-size:200%"><?=$heading; ?></h2>
					<?php echo $message; ?>
				</center>
			<div id="footer">
				<div id="footer_banners" style="height:290px;">
				</div>
				 <?php include_once(VIEWPATH . "template/menu/" . $lang_id . "/footer_menu_" . strtolower(PLATFORMID) . ".html"); ?>
				<div id="footer-bottom">
					<address><?=$ini_array["footer_text_all_rights_reserved"]; ?>/ <a href="https://www.valuebasket.com/display/view/about_us"><?=$ini_array["footer_text_about_us"]; ?></a> / <a href="/blog"><?=$ini_array["footer_text_blog"]; ?></a> / <a href="https://www.valuebasket.com/display/view/conditions_of_use"><?=$ini_array["footer_text_conditions_of_use"]; ?></a> / <a href="https://www.valuebasket.com/display/view/privacy_policy"><?=$ini_array["footer_text_privacy_policy"]; ?></a></address>
				</div>
			</div>
		</div>
	</div>
</body>
</html>