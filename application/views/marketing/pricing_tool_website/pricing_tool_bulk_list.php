<html>
<head>
	<title></title>
</head>
<body>
Please paste your list of SKUs below to list it on all WEBxx platforms and mark them as auto-price<br>
Your SKUs should be separated by a new line. Empty lines will be ignored.<br>
CAUTION: If SKU was LISTED as NON Auto-price, it will change to auto-price<br>	
	<form action="/marketing/pricing_tool_website/bulk_list_post" method="POST">
		<textarea cols="40" rows="20" name="sku_list"></textarea><br>		
		<input type="submit" value="List and auto-price"></input>
	</form>
</body>
</html>