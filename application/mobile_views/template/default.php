<!DOCTYPE html>
<html lang="en">
    <head>
		<?php print $_title . "\n"; ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
print $_meta;
?>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=1"/>
        <meta name="author" content="" />
        <meta name="googlebot" content="index, follow" />
        <meta name="robots" content="index, follow" />
		<script src="/resources/mobile/js/jquery-1.10.1.min.js" type="text/javascript"></script>
		<script src="/resources/mobile/js/jquery.fancybox.pack.js" type="text/javascript"></script>
		<script src="/resources/mobile/js/default.js" type="text/javascript"></script>

        <!-- Google web fonts -->
        <link href='<?php print (isset($_SERVER['HTTPS']) ? "https" : "http");?>://fonts.googleapis.com/css?family=Rokkitt:400,700' rel='stylesheet' type='text/css'>
		<link href='<?php print (isset($_SERVER['HTTPS']) ? "https" : "http");?>://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

		<!-- Styles -->
		<link href="/resources/mobile/css/jquery.fancybox.css" rel="stylesheet" type="text/css" media="screen" />
<!--		<link href="/resources/css/style.css" rel="stylesheet" type="text/css" /> -->
		<link href="/resources/mobile/css/style.css" rel="stylesheet" type="text/css" />
<?php
	print $_scripts;
	print "\n";
	print $_styles;
?>
    </head>
    <body>
<?php
if (PLATFORMCOUNTRYID == "RU")
{
	print "<script type=\"text/javascript\">var isAllowLatin=true;</script>";
}
?>
<?php print $_bodyscripts; ?>
<?php print $header ?>
<?php print $content ?>
<?php print $footer ?>
		<script src="/resources/mobile/js/sly.min.js" type="text/javascript"></script>
    </body>
</html>
