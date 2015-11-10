<?php

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"" . $output_filename . "\"");
header("Cache-Control: private");
if (readfile($filename)) {
    unset($_SESSION["metapack_file"]);
    unset($_SESSION["courier_file"]);
    unset($_SESSION["allocate_file"]);
}
?>