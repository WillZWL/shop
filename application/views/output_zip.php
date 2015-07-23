<?php
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Type: application/zip");
header('Content-length: ' . filesize($file_path));
header('Content-disposition: attachment; filename="' . $filename . '"');
ob_clean();
flush();
readfile($file_path);