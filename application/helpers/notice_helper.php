<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function notice($lang = "", $enable = "")
{
    $notice["img"] = "";
    $notice["js"] = "";
    $background = $msg = "";
    if ($enable) {
        $ar_display_colour = array("success" => "#00FF00", "fail" => "#FF0000");
        $msg = isset($lang[$_SESSION["DISPLAY"][0]]) ? $lang[$_SESSION["DISPLAY"][0]] : str_replace("\n", "\\n", $_SESSION["DISPLAY"][0]);
        $background = $ar_display_colour[$_SESSION["DISPLAY"][1]];
        $notice["img"] = "<div align='right' style='background:$background; padding-right:6px;'>$msg</div>";

    }
    if (isset($_SESSION["NOTICE"])) {
        $alert_msg = isset($lang[$_SESSION["NOTICE"]]) ? $lang[$_SESSION["NOTICE"]] : str_replace("\n", "\\n", $_SESSION["NOTICE"]);
        if (!($background)) {
            $background = "#FFFFCC";
        }
        $notice["img"] = "<div align='right' style='background:$background; padding-right:6px;'>$msg<img src='/images/notice.png' class='pointer' onClick='shownotice();'></div>";
        $notice["js"] =
            "
		<script>
			function shownotice()
			{
				alert(\"" . $alert_msg . "\");
			}
			shownotice();
		</script>
		";
    }
    unset($_SESSION["NOTICE"]);
    unset($_SESSION["DISPLAY"]);
    return $notice;
}


