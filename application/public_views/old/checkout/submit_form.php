<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Redirecting to Payment</title>
</head>
<body onload="redirect2url()">
<form name='form_url' action=<?= $form_action ?> method="post">
    <?php
    print $form_data;
    /*
    foreach ($form_data as $name => $value)
    {
        print "<input type='hidden' name='" . $name . "' value=\"" . $value . "\">\n";
    }
    */
    ?>
</form>
<script language="javascript">
    <!--
    function redirect2url() {
        document.form_url.submit();
    }
    // -->
</script>
</body>
</html>
