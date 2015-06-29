<html>
<head>
    <title></title>
</head>
<body>
    Please paste your list of orders below to retrieve it's SKU information.<br>
    Your orders should be separated by a new line. Empty lines will be ignored.
    <form action="/report/aps_report/export_csv" method="POST">
        <textarea cols="40" rows="20" name="order_list"></textarea><br>     
        <input type="submit" value="Download CSV"></input>
    </form>
</body>
</html>