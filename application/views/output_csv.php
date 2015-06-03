<?php
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: filename=$filename");
echo $output;
?>