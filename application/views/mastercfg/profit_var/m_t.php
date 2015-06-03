<?php
// we connect to example.com and port 3307
$link = mysql_connect('127.0.0.1:4040', 'root', 'selectAdmin');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully';
mysql_close($link);
?> 
