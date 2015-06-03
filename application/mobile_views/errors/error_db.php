<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Value Basket | Database Error</title>
	
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
	<link href="/resources/css/style.css" rel="stylesheet" type="text/css" media="screen, print" />
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
					<li><a href="http://www.valuebasket.com/" title="Home">Home</a></li>
					<li><a href="http://www.valuebasket.com/myaccount/myaccount?x_sign_in=1" title="My account">My account</a></li>
					<li><a href="http://www.valuebasket.com/display/view/shipping" title="Shipping">Shipping</a></li>
					<li><a href="http://www.valuebasket.com/contact" title="Contact Us">Contact Us</a></li>
					<li><a href="http://www.valuebasket.com/display/view/faq" title="Help">Help</a></li>
				</ul>
				<div class="separator"><div></div></div>
				<h1><a href="http://www.valuebasket.com/" title="ValueBasket">ValueBasket</a></h1>
				<div id="search" class="fix box-shadow-2">
					<form action="" method="get">
						<input type="text" value="Find your product" class="input" name="w" title="Find your product" />
						<button type="submit" title="Send" class="button">Send</button>
					</form>
				</div>

				<div class="clear"></div>
				<?php include_once(VIEWPATH . "/template/menu_en.html") ?>
			</div>
				<div style="height:59px;">
				</div>
				<center>
					<h2 style="font-size:200%"><?php echo $heading; ?></h2>
					<?php echo $message; ?>
				</center>
			<div id="footer">
				<div id="footer_banners" style="height:59px;">
				</div>
				<?php include_once(VIEWPATH . "/template/footer_menu_en.html") ?>
				<div id="footer-bottom">
					<address>2012 All Rights reserved / <a href="http://www.valuebasket.com/display/view/about_us">About Us</a> / <a href="http://www.valuebasket.com/display/view/terms">Conditions of Use</a> / <a href="http://www.valuebasket.com/display/view/privacy_policy">Privacy Policy</a></address>
				</div>
			</div>
		</div>
	</div>
</body>
</html>