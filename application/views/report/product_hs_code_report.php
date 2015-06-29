<html>
<head>
    <title><?= $title ?></title>
</head>
<body>
<h1><?= $title ?></h1>
Please select the Main Category below to retrieve all it's item HS Code information.<br>

<form action="/report/product_hs_code_report/export_csv" method="POST">
    <?php
    if (count($catoption) > 0) {
        $option_html = <<<html
            <select name="cat_id" id="cat_id">

html;
        foreach ($catoption as $obj) {
            $id = $obj->get_id();
            $name = $obj->get_name();
            if ((strpos($name, "DO NOT") === false) && (strpos($name, "Do NOT") === false) && $name != "") {
                $option_html .= <<<html
                <option value="$id">$name</option>

html;
            }
        }
    }
    ?>
    <?= $option_html ?>
    </select><br>
    <input type="submit" value="Download CSV"></input>
</form>
</body>
</html>