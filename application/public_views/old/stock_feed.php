<?php
header('Content-type: application/xml');
$xml_content = '';

if (count($data_list) > 0) {
    $status = array("I" => "In Stock", "O" => "Out Of Stock", "A" => "Arriving", "P" => "Pre-order");

    $xml_content .= '<?xml version="1.0"?>' . "\n";
    $xml_content .= '<products>' . "\n";

    $prev_sku = "";
    foreach ($data_list as $sku => $result) {
        $xml_content .= '<product>' . "\n";
        $xml_content .= '<sku>' . $sku . '</sku>' . "\n";

        $xml_content .= '<platforms>' . "\n";
        foreach ($result as $country_id => $feed_obj) {
            if ($feed_obj->get_status() != 'I') {
                $quantity = 0;
            } else {
                $quantity = $feed_obj->get_qty();
            }

            $xml_content .= '<platform>' . "\n";
            $xml_content .= '<country_id>' . $country_id . '</country_id>' . "\n";
            $xml_content .= '<currency>' . $feed_obj->get_currency_id() . '</currency>' . "\n";
            $xml_content .= '<price>' . $feed_obj->get_price() . '</price>' . "\n";
            $xml_content .= '<quantity>' . $quantity . '</quantity>' . "\n";
            $xml_content .= '<stock_status>' . $status[$feed_obj->get_status()] . '</stock_status>' . "\n";
            $xml_content .= '</platform>' . "\n";
        }
        $xml_content .= '</platforms>' . "\n";
        $xml_content .= '</product>' . "\n";
    }
    $xml_content .= '</products>' . "\n";

} else {
    $xml_content .= '<?xml version="1.0"?>' . "\n";
    $xml_content .= '<Error>' . "\n";
    $xml_content .= '<Code>404</Code>' . "\n";
    $xml_content .= '<Message>No product information found.</Message>' . "\n";
    $xml_content .= '<RequestSKU>' . $_GET['skus'] . '</RequestSKU>' . "\n";
    $xml_content .= '</Error>' . "\n";
}
echo $xml_content;