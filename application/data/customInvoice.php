<?php

$header = "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<title>Custom Invoice</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<style type=\"text/css\">
.pb{page-break-after : always;}
body { margin:0 15px;}
* {font-family:arial;font-size:10px;}
</style>
</head>
<body marginwidth='0' marginheight='0' onLoad='print();'>
<div style='width:100%;'>";

$pagebreak = "
	   <p class=\"pb\"></p>
		 ";

$footer = "
</body>
</html>";
