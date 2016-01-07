<?php
if ($soObj) {
    print "<div style=\"color:#00DD24;font-size:20px;\">" . $soObj->getSoNo() . " Created</div>";
} else {
    print "Fail to Create So";
}
?>