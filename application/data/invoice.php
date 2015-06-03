<?php
	$header = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		   <html xmlns=\"http://www.w3.org/1999/xhtml\">
		   <head>
		   <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
		   <title>Sales Invoice</title>
		   <style type=\"text/css\">
		   .pb
 		   {
			page-break-after : always  ;
  		   }


		   body {
			margin:10;
		   }
		   * {
			font-family:Helvetica,verdana,arial,sans-serif;
			font-size:8pt;
		   }
		   </style>
		   </head>
		   <body topmargin=\"5\" leftmargin=\"5\" rightmargin=\"5\" bgcolor=\"#FFFFFF\" onLoad=\"if (parent.frames['printframe']){parent.frames['printframe'].focus();parent.frames['printframe'].print();}else{print();}\" style=\"overflow:none;\">";

	$footer = "</body></html>";

	$pagebreak = "
		   <p class=\"pb\"></p>
		     ";
?>
