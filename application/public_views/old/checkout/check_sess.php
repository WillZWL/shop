<?php
header("Content-type: text/javascript; charset: UTF-8");
header("Cache-Control: must-revalidate");
$offset = 60 * 60 * 24;
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
header($ExpStr);
?>
var cart_count = <?=$chk_cart["count"]?>;
var cart_total = <?=$chk_cart["total"]?>;
function w_cart_count(){document.write(cart_count);}
function w_cart_total(){document.write(cart_total);}
function w_platform_curr_format_cart_total(){document.write('<?=platform_curr_format($platform, $chk_cart["total"])?>');}