<html>
<head>
    <title>Bulk Update</title>
</head>
<body>
Please paste your list of orders below to update the notes and optionally,<br>
change order status from <b>paid</b>(2) to <b>credit checked</b>(3)<br>
Your orders should be separated by a new line. Empty lines will be ignored.<br><br>

<form action="/order/credit_check/bulk_update_post" method="POST">
    List of orders:<br><textarea cols="40" rows="20" name="order_list"></textarea><br>
    Note to add:<br><input type="text" size="40" name="note"><br>
    <input type="checkbox" name="approve_if_paid" value="1">Change <b>paid</b> orders to <b>credit checked</b><br>
    <input type="submit" value="Update orders"></input>
</form>
</body>
</html>